<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLanguageSpoken;
use App\Models\Language;
use App\Models\Music;
use App\Models\UserMusic;
use App\Models\Sport;
use App\Models\UserSport;
use App\Models\Pet;
use App\Models\UserPet;
use App\Models\GoingOut;
use App\Models\UserGoingOut;
use App\Models\JobRole;
use App\Models\ProfileImage;
use Illuminate\Support\Facades\Validator;

class ProfileScreenController extends ApiHelper
{
    /**
     * To get authenticated user profile data
     * @param req :[]
     * @return res : [Auth user profile data]
     */
    public function getProfileData()
    {
        $user = auth()->user();
        $eagerLoadedRelationships = [
            'user_musics',
            'user_sports',
            'user_language_spoken',
            'user_pets',
            'user_going_out',
            'ethnicity',
            'sexuality',
            'datingIntention',
            'educationStatus',
            'plan',
            'relationshipType',
            'covidVaccine',
            'familyPlan',
            'zodiacSign',
            'politics',
            'religious',
            'profileImage',
            'job_role',
            'user_income_level',
        ];

        $userExist = User::where('id', $user->id)
            ->with($eagerLoadedRelationships)
            ->first();

        $userProfileImage = ProfileImage::where('user_id', $user->id)->first();

        $notNullFields = 0;
        if ($userProfileImage->image_path) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->mobile_number) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->email_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->gender) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->height_in_feet) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->ethnicity_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->sexuality_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->dating_intention_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->education_status_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->dob) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->school_college_name) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->relationship_type_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->covid_vaccine_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->family_plan_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->zodiac_sign_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->politics_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->pronouns) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->bio) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->religious_id) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->interested_in) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->interested_for) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->job_role_id || $userExist->other_job_option) {
            $notNullFields = $notNullFields + 1;
        }

        if ($userExist->income_level_id) {
            $notNullFields = $notNullFields + 1;
        }

        $userLanguageSpoken = json_decode($userExist->user_language_spoken);

        if (!empty($userLanguageSpoken)) {
            $notNullFields = $notNullFields + 1;
        }

        $userMusics = json_decode($userExist->user_musics);
        $userSports = json_decode($userExist->user_sports);
        $userPets = json_decode($userExist->user_pets);
        $userGoingOut = json_decode($userExist->user_going_out);

        if (!empty($userMusics) || !empty($userSports) || !empty($userPets) || !empty($userGoingOut)) {
            $notNullFields = $notNullFields + 1;
        }

        $percentage = ($notNullFields / 25) * 100;
        $respone = [
            'profile_percentage' => round($percentage),
            'auth_user_profile' => $userProfileImage->image_path ?? null,
            'full_name' => $user->full_name,
            'data' => $userExist
        ];

        return $this->successRespond($respone, 'AuthProfileData');
    }

    /**
     * To update or upload profile images
     * @param req :[user_id, image_path]
     * @return res : [Auth user updated data]
     */
    public function updateOrUpload(Request $request)
    {
        $userId = auth()->user()->id;

        if (isset($request->update_images_url)) {
            ProfileImage::where('user_id', $userId)->delete();
        }

        foreach ($request->update_images_url as $imageUrl) {
            // Download the image from the URL using Intervention Image (Helper function)
            $imageToSave = $this->profileImageUrl($imageUrl);

            ProfileImage::create([
                'user_id' => $userId,
                'image_path' => $imageToSave
            ]);
        }

        return $this->successRespond((object) [], 'ImageUploadOrUpdate');
    }

    /**
     * To update authenticated user interest and details
     * @param req :[sport, music, going_out, pet, height_in_inch, height_in_feet, ethnicity_id, sexuality_id, dating_intention_id, education_status_id, income_level_id, relationship_type_id, covid_vaccine_id, language_spoken, religious_id, family_plan_id, zodiac_sign_id, politics_id]
     * @return res : [Auth user updated data]
     */
    public function updateInterestAndDetail(Request $request)
    {
        $user = auth()->user();

        $validationRule = 'nullable|integer|min:1';
        $arrayValidation = 'nullable|array';
        $validatedData = $request->all();
        $validator = Validator::make($request->all(), [
            'sport' => $arrayValidation,
            'sport.*' => $validationRule,
            'music' => $arrayValidation,
            'music.*' => $validationRule,
            'going_out' => $arrayValidation,
            'going_out.*' => $validationRule,
            'pet.*' => $validationRule,
            'height_in_inch' => 'nullable|integer|min:0|max:11',
            'height_in_feet' => 'nullable|integer|min:3|max:8',
            'ethnicity_id' => $validationRule,
            'sexuality_id' => $validationRule,
            'dating_intention_id' => $validationRule,
            'education_status_id' => $validationRule,
            'income_level_id' => $validationRule,
            'relationship_type_id' => $validationRule,
            'covid_vaccine_id' => $validationRule,
            'language_spoken' => $arrayValidation,
            'language_spoken.*' => $validationRule,
            'religious_id' => $validationRule,
            'family_plan_id' => $validationRule,
            'zodiac_sign_id' => $validationRule,
            'politics_id' => $validationRule,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $credentials = [];

        $nullableFields = [
            'bio',
            'height_in_inch',
            'height_in_feet',
            'ethnicity_id',
            'sexuality_id',
            'dating_intention_id',
            'education_status_id',
            'relationship_type_id',
            'covid_vaccine_id',
            'religious_id',
            'family_plan_id',
            'zodiac_sign_id',
            'politics_id',
            'income_level_id'
        ];

        foreach ($nullableFields as $field) {
            if (isset($request->$field)) {
                $credentials[$field] = $request->$field;
            }
        }

        $credentials['school_college_name'] = $request->school_college_name;
        $credentials['pronouns'] = $request->pronouns;

        UserSport::where('user_id', $user->id)->delete();
        UserMusic::where('user_id', $user->id)->delete();
        UserPet::where('user_id', $user->id)->delete();
        UserGoingOut::where('user_id', $user->id)->delete();
        UserLanguageSpoken::where('user_id', $user->id)->delete();

        if (isset($validatedData['language_spoken'])) {
            $lengthCount = count($validatedData['language_spoken']);
            if ($lengthCount > 5) {
                return $this->errorRespond('Languagelength', config('constants.CODE.badRequest'));
            }
            foreach ($validatedData['language_spoken'] as $language) {
                $languageData = Language::find($language);
                if (empty($languageData)) {
                    return $this->errorRespond('LanguageNotValid', config('constants.CODE.badRequest'));
                }
                UserLanguageSpoken::updateOrCreate(['user_id' => $user->id, 'language_id' => $language]);
            }
        }

        if (isset($validatedData['sport'])) {
            foreach ($validatedData['sport'] as $sport) {
                $sportData = Sport::find($sport);
                if (empty($sportData)) {
                    return $this->errorRespond('SportNotValid', config('constants.CODE.badRequest'));
                }
                UserSport::updateOrCreate(['user_id' => $user->id, 'sport_id' => $sport]);
            }
        }

        if (isset($validatedData['music'])) {
            foreach ($validatedData['music'] as $music) {
                $musicData = Music::find($music);
                if (empty($musicData)) {
                    return $this->errorRespond('MusicNotValid', config('constants.CODE.badRequest'));
                }
                UserMusic::updateOrCreate(['user_id' => $user->id, 'music_id' => $music]);
            }
        }

        if (isset($validatedData['pet'])) {
            foreach ($validatedData['pet'] as $pet) {
                $petData = Pet::find($pet);
                if (empty($petData)) {
                    return $this->errorRespond('PetNotValid', config('constants.CODE.badRequest'));
                }
                UserPet::updateOrCreate(['user_id' => $user->id, 'pet_id' => $pet]);
            }
        }

        if (isset($validatedData['going_out'])) {
            foreach ($validatedData['going_out'] as $going_out) {
                $goingOutData = GoingOut::find($going_out);
                if (empty($goingOutData)) {
                    return $this->errorRespond('GoingOutNotValid', config('constants.CODE.badRequest'));
                }
                UserGoingOut::updateOrCreate(['user_id' => $user->id, 'going_out_id' => $going_out]);
            }
        }

        $checkOtherOption = JobRole::where('id', $request->job_role_id)->first();
        if ($checkOtherOption !== null) {
            if ($checkOtherOption->name === "Others") {
                // if (isset($request->other_job)) {
                $credentials['other_job_option'] = $request->other_job;
                $credentials['specific_work_area'] = null;
                $credentials['job_role_id'] = null;
                // }
            } else {
                $credentials['job_role_id'] = $request->job_role_id;
                $credentials['specific_work_area'] = $request->specific_work_area;
                $credentials['other_job_option'] = null;
            }
        }

        $userExist = User::where('id', $user->id)->first();

        if (empty($userExist)) {
            return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        }

        if ($userExist) {
            $userExist->update($credentials);

            $userDetails = User::where('id', $user->id)->with([
                'job_role',
                'user_musics',
                'user_sports',
                'user_language_spoken',
                'user_pets',
                'user_going_out',
                'ethnicity',
                'sexuality',
                'datingIntention',
                'educationStatus',
                'plan',
                'relationshipType',
                'covidVaccine',
                'familyPlan',
                'zodiacSign',
                'politics',
                'religious',
                'profileImage',
                'user_income_level',
                'currentSubcription.plan'
            ])->first();

            return $this->successRespond($userDetails, 'ProfileDetailSave');

        } else {
            return $this->errorRespond('UserNotFound', config('constants.CODE.unauthorized'));
        }
    }
}