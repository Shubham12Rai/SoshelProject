<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\Like;
use App\Models\Ethnicity;
use App\Models\Sexuality;
use App\Models\DatingIntention;
use App\Models\JobRole;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\IncomeLevel;

class LikeDislikeController extends ApiHelper
{
    /**
     * Get liked users data based on new_like_status
     * @param req: ['new_like_status']
     * @return res: []
     */
    public function generalLike(Request $request)
    {
        $user = auth()->user();
        $earthRadius = config('constants.LOCATION.earthRadius');
        $radius = $request->radius ?? config('constants.LOCATION.radius');

        $commanValidation = [
            'nullable',
            'min:1'
        ];
        $commanValidation2 = [
            'nullable',
            'array',
            'min:0'
        ];
        $commanValidation3 = [
            'nullable',
            'min:0'
        ];

        $validator = Validator::make($request->all(), [
            "minimum_age" => 'nullable',
            "maximum_age" => 'nullable',
            "radius" => 'nullable|numeric',
            "max_heigth" => 'nullable',
            "min_heigth" => 'nullable',
            "ethnicity_id" => $commanValidation2,
            "ethnicity_id.*" => $commanValidation3,
            "family_plan_id" => $commanValidation,
            "zodiac_sign_id" => $commanValidation,
            "job_role_id" => $commanValidation2,
            "job_role_id.*" => $commanValidation3,
            "other_job_option" => 'nullable',
            "income_level_id" => 'nullable|min:1',
            "education_status_id" => $commanValidation3,
            "relationship_type_id" => $commanValidation,
            "covid_vaccine_id" => $commanValidation,
            'language_spoken' => 'nullable|array',
            'language_spoken.*' => $commanValidation,
            "religious_id" => $commanValidation,
            "politics_id" => $commanValidation3,
            "sexuality_id" => $commanValidation2,
            "sexuality_id.*" => $commanValidation3,
            "dating_intention_id" => $commanValidation2,
            "dating_intention_id.*" => $commanValidation3,
            "pronouns" => 'nullable|string'
            // "advance_filter_status" => "required|in:0,1"
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $locations = $this->distanceCalculation($earthRadius, $radius, $user->latitude, $user->longitude);

        $likedUsersData = User::join('likes', 'likes.user_id', '=', 'users.id')
            ->where('users.active_status', '=', 1)
            ->where('likes.liked_user_id', $user->id)
            ->where('likes.like_status', 1)
            ->whereIn('likes.is_read', [0, 1])
            ->select(
                'users.id',
                'users.full_name',
                'users.job_role_id',
                'users.other_job_option',
                'users.dob',
                'users.plan_id',
                'likes.is_read',
                'likes.created_at'
            )
            ->with(['plan', 'profileImage', 'job_role'])
            ->orderByDesc('likes.created_at');

        // filter option start
        if (($request->minimum_age != null) && ($request->maximum_age != null)) {
            $likedUsersData->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$request->input('minimum_age')])
                ->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$request->input('maximum_age')]);
        }

        if (($request->min_heigth != null) && ($request->max_heigth != null)) {
            $partsMin = explode("'", $request->min_heigth);
            $min_feet = (int) trim($partsMin[0]);
            $min_inches = (int) trim($partsMin[1]);

            $partsMax = explode("'", $request->max_heigth);
            $max_feet = (int) trim($partsMax[0]);
            $max_inches = (int) trim($partsMax[1]);

            $likedUsersData->where(function ($query) use ($min_feet, $min_inches, $max_feet, $max_inches) {
                $query->where(function ($query) use ($min_feet, $min_inches) {
                    $query->where('height_in_feet', '>', $min_feet);
                })->orWhere(function ($query) use ($min_feet, $min_inches) {
                    $query->where('height_in_feet', '=', $min_feet)
                        ->where('height_in_inch', '>=', $min_inches);
                });
            })->where(function ($query) use ($max_feet, $max_inches) {
                $query->where(function ($query) use ($max_feet, $max_inches) {
                    $query->where('height_in_feet', '<', $max_feet);
                })->orWhere(function ($query) use ($max_feet, $max_inches) {
                    $query->where('height_in_feet', '=', $max_feet)
                        ->where('height_in_inch', '<=', $max_inches);
                });
            });
        }

        if ($request->ethnicity_id != null) {
            $ethnicityCount = Ethnicity::count();
            $ethnicityIds = $request->input('ethnicity_id');
            if ($ethnicityCount - 1 === count($ethnicityIds)) {

            } else {
                $likedUsersData->whereIn('ethnicity_id', $ethnicityIds);
            }
        }

        if ($request->family_plan_id != null) {
            $likedUsersData->where('family_plan_id', '=', $request->input('family_plan_id'));
        }

        if ($request->zodiac_sign_id != null) {
            $zodiacIds = $request->input('zodiac_sign_id');
            if ($zodiacIds === 0) {

            } else {
                $likedUsersData->where('zodiac_sign_id', $zodiacIds);
            }
        }

        if ($request->job_role_id != null) {
            $jobRoleCount = JobRole::count();
            $jobRoleIds = $request->input('job_role_id');
            if ($jobRoleCount - 1 === count($jobRoleIds)) {

            } else {
                $likedUsersData->whereIn('job_role_id', $jobRoleIds);
            }
        }

        if ($request->other_job_option != null) {
            $likedUsersData->where('other_job_option', '=', $request->input('other_job_option'));
        }

        if ($request->income_level_id != null) {
            $getIncomeLevelId = IncomeLevel::select('id')->where('min_income', '=', $request->income_level_id)->first();
            $likedUsersData->where('income_level_id', '>=', $getIncomeLevelId->id);
        }

        if ($request->education_status_id != null) {
            if ($request->education_status_id === 0) {

            } else {
                if ($request->education_status_id === 1) {

                } elseif ($request->education_status_id === 2) {
                    $likedUsersData->where('education_status_id', '>=', 2);
                } else {
                    $likedUsersData->where('education_status_id', '=', $request->input('education_status_id'));
                }
            }
        }

        if ($request->language_spoken != null) {
            $likedUsersData->whereHas('user_language_spoken', function ($likedUsersData) use ($request) {
                $likedUsersData->whereIn('language_id', $request->input('language_spoken'));
            });
        }

        if ($request->relationship_type_id != null) {
            $likedUsersData->where('relationship_type_id', '=', $request->input('relationship_type_id'));
        }

        if ($request->covid_vaccine_id != null) {
            $likedUsersData->where('covid_vaccine_id', '=', $request->input('covid_vaccine_id'));
        }

        if ($request->religious_id != null) {
            $likedUsersData->where('religious_id', '=', $request->input('religious_id'));
        }

        if ($request->politics_id != null) {
            $politcsIds = $request->input('politics_id');
            if ($politcsIds === 0) {

            } else {
                $likedUsersData->where('politics_id', $politcsIds);
            }
        }

        if ($request->pronouns != null) {
            $likedUsersData->where('pronouns', '=', $request->input('pronouns'));
        }

        if ($request->sexuality_id != null) {
            $sexualityCount = Sexuality::count();
            $sexualityIds = $request->input('sexuality_id');
            if ($sexualityCount - 1 === count($sexualityIds)) {

            } else {
                $likedUsersData->whereIn('sexuality_id', $sexualityIds);
            }
        }

        if ($request->dating_intention_id != null) {
            $datingIntentionCount = DatingIntention::count();
            $datingIntentionIds = $request->input('dating_intention_id');
            if ($datingIntentionCount - 1 === count($datingIntentionIds)) {

            } else {
                $likedUsersData->whereIn('dating_intention_id', $datingIntentionIds);
            }
        }

        // filter option end

        $likedUsers = $likedUsersData->get();

        //checking disliked users
        $likes = Like::where('user_id', $user->id)
            ->where('like_status', 0)
            ->get();

        // Eliminate disliked users
        $likedUsers = $likedUsers->reject(function ($user) use ($likes) {
            return $likes->where('liked_user_id', $user->id)->isNotEmpty();
        });

        $recentlyActive = [];
        $allLikes = [];
        $newLikes = [];
        $newUserId = [];

        foreach ($likedUsers as &$userData) {
            $birthTime = new DateTime($userData->dob);
            $today = new DateTime();
            $age = $birthTime->diff($today)->y;
            $userData['age'] = $age;

            foreach ($locations as $location) {

                if ($location->id === $userData->id) {
                    $userData['distance'] = abs(number_format($location->distance, 2));
                    if ($userData->is_read == 0) {
                        $allLikes[] = $userData;
                    } elseif ($userData->is_read == 1) {
                        $newLikes[] = $userData;
                        $newUserId[] = $userData->id;
                    }
                }
            }

            // if auth user liked within the last 60 minutes
            $createdAt = Carbon::parse($userData->created_at);
            if ($createdAt >= Carbon::now()->subMinutes(60)) {
                $recentlyActive[] = $userData;
            }
        }

        $likeData = [
            'all_like_count' => count($allLikes),
            'new_like_count' => count($newLikes),
            'new_user_id' => $newUserId,
            'all_likes' => $allLikes,
            'new_likes' => $newLikes,
            'recently_active' => $recentlyActive
        ];

        return $this->successRespond($likeData, 'LikesData');
    }

    /**
     * To update is_read status for auth user like
     * @param req: ['liked_user_id : []']
     * @return res: []
     */
    public function likedUserIdUpdate(Request $request)
    {
        $user = auth()->user();
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'liked_user_id' => 'required|array',
            'liked_user_id.*' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $likedUserIds = $requestData['liked_user_id'];

        foreach ($likedUserIds as $userId) {
            Like::select("*")->where('user_id', $userId)->where('liked_user_id', $user->id)->update(['is_read' => 0]);

            $updatedData = Like::select("id", "user_id", "liked_user_id", "like_status", "is_read")
                ->where('user_id', $userId)->where('liked_user_id', $user->id)->orderByDesc('likes.created_at')->first();
            $result[] = $updatedData;
        }

        return $this->successRespond($result, 'Nearby');

    }
}