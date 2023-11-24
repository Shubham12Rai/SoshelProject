<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ProfileImage;
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
use App\Helpers\ApiHelper;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class UserProfileController extends ApiHelper
{
    const VALIDATION_RULES = 'required|integer|min:1';
    const VALIDATION_NULLABLE = 'nullable|array';
    /**
     * To setup user account details based on login user token
     * @param req :[mobile_number, full_name, birth_date, gender, interested_in, interested_for]
     * @return res : [user data from User table and profile_photos from profile_image table]
     */
    public function userProfile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'birth_date' => 'required',
            'gender' => 'required|string',
            'interested_in' => 'required',
            'interested_for' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $birthTime = new DateTime($request->birth_date);
        $today = new DateTime();
        $age = $birthTime->diff($today)->y;

        if ($age < 18 || $age > 80) {
            return $this->errorRespond('AgeError', config('constants.CODE.badRequest'));
        }

        $user->full_name = $request->full_name;
        $user->gender = $request->gender;
        $user->dob = date('Y-m-d', strtotime($request->birth_date));
        $user->interested_in = $request->interested_in;
        $user->interested_for = $request->interested_for;
        $user->save();

        $userExist = User::where('id', $user->id)->with([
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
            'currentSubcription.plan',
        ])->first();

        return $this->successRespond($userExist, 'ProfileDataSave');
    }


    /**
     * To setup user account details based on login user token
     * @param req :[mobile_number, height, ethnicity, sexuality, dating_intention, education_status, job_role, income_level
     *             'school_college_name', 'relationship_type', 'vaccine_status', 'language_spoken', 'religious', 'family_plan',
     *             'zodiac_sign', 'politics', 'pronouns', 'sports', 'music', ,going_out', 'pets', 'bio']
     * @return res : [user details from User table and profile_photos from profile_image table]
     */
    public function profileDetail(Request $request)
    {
        $user = auth()->user();

        $credentials = $request->only(
            'height_in_inch',
            'height_in_feet',
            'ethnicity_id',
            'sexuality_id',
            'dating_intention_id',
            'education_status_id',
            'income_level',
            'school_college_name',
            'relationship_type_id',
            'covid_vaccine_id',
            'religious_id',
            'family_plan_id',
            'zodiac_sign_id',
            'politics_id',
            'pronouns',
            'bio',
            'income_level_id',

        );

        $validatedData = $request->all();
        $validator = Validator::make($request->all(), [
            'height_in_inch' => 'required|integer|min:0',
            'height_in_feet' => 'required|integer|min:2',
            'ethnicity_id' => self::VALIDATION_RULES,
            'sexuality_id' => self::VALIDATION_RULES,
            'dating_intention_id' => self::VALIDATION_RULES,
            'education_status_id' => self::VALIDATION_RULES,
            'income_level_id' => 'nullable|integer|min:1',
            'income_level' => 'nullable|integer|min:1',
            'language_spoken' => self::VALIDATION_NULLABLE,
            'sport' => self::VALIDATION_NULLABLE,
            'music' => self::VALIDATION_NULLABLE,
            'pet' => self::VALIDATION_NULLABLE,
            'going_out' => self::VALIDATION_NULLABLE,
            'bio' => 'required',
            'job_role_id' => 'nullable|integer|min:1|max:52',
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        if (isset($validatedData['language_spoken'])) {
            $lengthCount = count($validatedData['language_spoken']);
            if ($lengthCount > 5) {
                return $this->errorRespond('Languagelength', config('constants.CODE.badRequest'));
            }

            UserLanguageSpoken::where('user_id', $user->id)->delete();

            foreach ($validatedData['language_spoken'] as $language) {
                $languageData = Language::find($language);
                if (empty($languageData)) {
                    return $this->errorRespond('LanguageNotValid', config('constants.CODE.badRequest'));
                }
                UserLanguageSpoken::updateOrCreate(['user_id' => $user->id, 'language_id' => $language]);
            }
        }

        if (isset($validatedData['sport'])) {
            UserSport::where('user_id', $user->id)->delete();

            foreach ($validatedData['sport'] as $sport) {
                $sportData = Sport::find($sport);
                if (empty($sportData)) {
                    return $this->errorRespond('SportNotValid', config('constants.CODE.badRequest'));
                }
                UserSport::updateOrCreate(['user_id' => $user->id, 'sport_id' => $sport]);
            }
        }

        if (isset($validatedData['music'])) {
            UserMusic::where('user_id', $user->id)->delete();

            foreach ($validatedData['music'] as $music) {
                $musicData = Music::find($music);
                if (empty($musicData)) {
                    return $this->errorRespond('MusicNotValid', config('constants.CODE.badRequest'));
                }
                UserMusic::updateOrCreate(['user_id' => $user->id, 'music_id' => $music]);
            }
        }

        if (isset($validatedData['pet'])) {
            UserPet::where('user_id', $user->id)->delete();

            foreach ($validatedData['pet'] as $pet) {
                $petData = Pet::find($pet);
                if (empty($petData)) {
                    return $this->errorRespond('PetNotValid', config('constants.CODE.badRequest'));
                }
                UserPet::updateOrCreate(['user_id' => $user->id, 'pet_id' => $pet]);
            }
        }

        if (isset($validatedData['going_out'])) {
            UserGoingOut::where('user_id', $user->id)->delete();

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
                if (isset($request->other_job)) {
                    $credentials['other_job_option'] = $request->other_job;
                    $credentials['specific_work_area'] = null;
                    $credentials['job_role_id'] = null;
                }
            } else {
                $credentials['job_role_id'] = $request->job_role_id;
                $credentials['specific_work_area'] = $request->specific_work_area;
                $credentials['other_job_option'] = null;
            }
        }



        $userExist = User::where('id', $user->id)->first();

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


    /**
     * To delete uploaded images
     * @param req :[profile_photo_id]
     * @return res : []
     */
    public function imageDelete(Request $request)
    {
        $arr = array();
        $validator = Validator::make($request->all(), [
            'profile_photo_id' => self::VALIDATION_RULES,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $checkData = ProfileImage::find($request->profile_photo_id);

        if (!empty($checkData) && isset($checkData->image_path)) {
            Storage::delete($checkData->image_path);
            $checkData->delete();
        } else {
            return $this->errorRespond('InvalidId', config('constants.CODE.badRequest'));
        }

        return $this->successRespond($arr, 'ProfilePhotoDelete');
    }

    /**
     * To upload user profile photos
     * @param req :[profile_photo]
     * @return res : [User data]
     */
    public function photoUpload(Request $request)
    {
        $allImage = array();
        $configPath = config('constants.IMAGE_PATH.profile_photo');
        foreach ($request->file('photo') as $image) {
            $path = $this->uploadFile($image, $configPath);
            array_push($allImage, Storage::disk('s3')->url('manager/' . $configPath . $path));
        }
        return $this->successRespond($allImage, 'PhotoUpload');
    }



    /**
     * To upload url instagram images
     * @param req :[profile_photo_url]
     * @return res : [Auth user data with uploaded images]
     */
    public function profilePhotoUrl(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'profile_photo_url' => 'required|array'
        ]);
        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $configPath = config('constants.IMAGE_PATH.profile_photo');

        // Remove the existing profile images for the user
        ProfileImage::where('user_id', $user->id)->delete();

        foreach ($request->profile_photo_url as $imageUrl) {

            // Download the image from the URL using Intervention Image
            $image = Image::make($imageUrl);

            // Generate a unique filename
            $filename = 'uploaded_image_' . uniqid() . '.jpg';

            // Save the images to the storage
            $path = 'images/' . $filename;
            Storage::put($path, (string) $image->encode('jpg'));
            $s3Path = 'manager/' . $configPath . $filename;

            // Retriv the images uploaded from the s3 bucket
            Storage::disk('s3')->put($s3Path, file_get_contents(storage_path('app/' . $path)));
            $s3ImageUrl = Storage::disk('s3')->url($s3Path);

            ProfileImage::create([
                'user_id' => $user->id,
                'image_path' => $s3ImageUrl
            ]);

            // Delete the locally saved image after uploading to S3
            unlink(storage_path('app/' . $path));
        }

        $imageData = ProfileImage::select("id", "image_path")->where('user_id', $user->id)
            ->latest()->get();
        $mergedArray = array_merge($user->toArray(), ['profile_photo' => $imageData]);
        return $this->successRespond($mergedArray, 'ProfilePhotoSave');
    }

    /**
     * To get required field status
     * @param req :[Bearer_Token]
     * @return res : [
     *           "is_profile_data_exists": true,
     *           "is_profile_image_exists": true,
     *           "is_profile_detail_exists": true,
     *           "is_term_and_condition_accepted": true,
     *           "is_privacy_policy_accepted": false
     * ]
     */
    public function requiredFieldStatus()
    {
        $user = auth()->user();

        $userExist = User::with('profileImage')->find($user->id);

        if (!$userExist) {
            return $this->errorRespond('UserdataNotFound', config('constants.CODE.notFound'));
        }

        $response = [
            'is_profile_data_exists' => !empty($userExist->full_name) && !empty($userExist->dob) && !empty($userExist->gender) && !empty($userExist->interested_in) && !empty($userExist->interested_for),
            'is_profile_image_exists' => !empty($userExist->profileImage[0]->image_path),
            'is_profile_detail_exists' => (!empty($userExist->height_in_feet) || !empty($userExist->height_in_inch)) && !empty($userExist->ethnicity_id) && !empty($userExist->sexuality_id) && !empty($userExist->dating_intention_id) && !empty($userExist->education_status_id) && !empty($userExist->bio),
            'is_term_and_condition_accepted' => !empty($userExist->terms_condition),
            'is_privacy_policy_accepted' => !empty($userExist->privacy_policy)
        ];

        return $this->successRespond($response, 'RequireFieldStatus');
    }
}