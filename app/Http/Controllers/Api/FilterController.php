<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\Like;
use App\Models\Chat;
use App\Models\DrinkRequest;
use App\Models\Matchs;
use App\Models\Ethnicity;
use App\Models\Sexuality;
use App\Models\DatingIntention;
use App\Models\JobRole;
use App\Models\IncomeLevel;
use App\Models\ProfileImage;
use Illuminate\Support\Facades\Validator;

class FilterController extends ApiHelper
{

    /**
     * To get user data according to applied filters
     * @param req :["minimum_age", "maximum_age", "radius", "heigth_feet", "heigth_inch", "ethnicity_id",
     * "family_plan_id","zodiac_sign_id", "job_role", "income_level", "education_status_id", "school_college_name",
     * "relationship_type_id", 'language_spoken[]', "religious_id", "politics_id", "sexuality_id", "dating_intention_id"
     * "advance_filter_status"]
     * @param res : [data according to filter applied in the given radius]
     */
    public function filter(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            "minimum_age" => 'nullable',
            "maximum_age" => 'nullable',
            "radius" => 'nullable|numeric',
            "max_heigth" => 'nullable',
            "min_heigth" => 'nullable',
            "ethnicity_id" => 'nullable|array|min:0',
            "ethnicity_id.*" => 'nullable|min:0',
            "family_plan_id" => 'nullable|min:1',
            "zodiac_sign_id" => 'nullable|min:1',
            "job_role_id" => 'nullable|array|min:0',
            "job_role_id.*" => 'nullable|min:0',
            "other_job_option" => 'nullable',
            "income_level_id" => 'nullable|min:1',
            "education_status_id" => 'nullable|min:0',
            "relationship_type_id" => 'nullable|min:1',
            "covid_vaccine_id" => 'nullable|min:1',
            'language_spoken' => 'nullable|array',
            'language_spoken.*' => 'nullable|min:1',
            "religious_id" => 'nullable|min:1',
            "politics_id" => 'nullable|min:0',
            "sexuality_id" => 'nullable|array|min:0',
            "sexuality_id.*" => 'nullable|min:0',
            "dating_intention_id" => 'nullable|array|min:0',
            "dating_intention_id.*" => 'nullable|min:0',
            "pronouns" => 'nullable|string'
            // "advance_filter_status" => "required|in:0,1"

        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

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

        $query = User::query();

        if (($request->minimum_age != null) && ($request->maximum_age != null)) {
            $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$request->input('minimum_age')])
                ->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$request->input('maximum_age')]);
        }

        if ($request->radius != null || $request->radius == null) {

            $earthRadius = config('constants.LOCATION.earthRadius');
            $radius = $request->radius ?? config('constants.LOCATION.radius');

            $likedUserIds = Like::where('user_id', $user->id)->pluck('liked_user_id')->toArray();

            // checking disliked user_id
            $likedUser = Like::Where('liked_user_id', $user->id)->where('like_status', '!=', 1)->pluck('user_id')->toArray();

            $locations = $this->distanceCalculation($earthRadius, $radius, $user->latitude, $user->longitude);

            $matches = Matchs::where('sender_user_id', $user->id)
                ->orWhere('receiver_user_id', $user->id)
                ->get();

            $usersToEliminate = [];
            foreach ($matches as $match) {
                if ($match->sender_user_id == $user->id) {
                    $usersToEliminate[] = $match->receiver_user_id;
                } elseif ($match->receiver_user_id == $user->id) {
                    $usersToEliminate[] = $match->sender_user_id;
                }
            }

            $usersToRemove = array_merge($likedUserIds, $likedUser);

            $actualData = $locations->whereNotIn('id', $usersToRemove);

            $filteredLocations = $actualData->reject(function ($location) use ($user) {
                return $location->id === $user->id;
            });

            $userIds = $filteredLocations->pluck('id')->toArray();

            $userDistances = $filteredLocations->pluck('distance')->toArray();

            $userDistancesMap = array_combine($userIds, $userDistances);

            $query->whereIn('id', $userIds)->where('active_status', '!=', 4)
                ->selectRaw('*, TIMESTAMPDIFF(YEAR, dob, CURDATE()) as age');
        }

        if (($request->min_heigth != null) && ($request->max_heigth != null)) {
            $partsMin = explode("'", $request->min_heigth);
            $min_feet = (int) trim($partsMin[0]);
            $min_inches = (int) trim($partsMin[1]);

            $partsMax = explode("'", $request->max_heigth);
            $max_feet = (int) trim($partsMax[0]);
            $max_inches = (int) trim($partsMax[1]);

            $query->where(function ($query) use ($min_feet, $min_inches, $max_feet, $max_inches) {
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
                $query->whereIn('ethnicity_id', $ethnicityIds);
            }
        }

        if ($request->family_plan_id != null) {
            $query->where('family_plan_id', '=', $request->input('family_plan_id'));
        }

        if ($request->zodiac_sign_id != null) {
            $zodiacIds = $request->input('zodiac_sign_id');
            if ($zodiacIds === 0) {

            } else {
                $query->where('zodiac_sign_id', $zodiacIds);
            }
        }

        if ($request->job_role_id != null) {
            $jobRoleCount = JobRole::count();
            $jobRoleIds = $request->input('job_role_id');
            if ($jobRoleCount - 1 === count($jobRoleIds)) {

            } else {
                $query->whereIn('job_role_id', $request->input('job_role_id'));
            }
        }

        if ($request->other_job_option != null) {
            $query->where('other_job_option', '=', $request->input('other_job_option'));
        }

        if ($request->income_level_id != null) {
            $getIncomeLevelId = IncomeLevel::select('id')->where('min_income', '=', $request->income_level_id)->first();
            $query->where('income_level_id', '>=', $getIncomeLevelId->id);
        }

        if ($request->education_status_id != null) {
            if ($request->education_status_id === 0) {

            } else {
                if ($request->education_status_id === 1) {

                } elseif ($request->education_status_id === 2) {
                    $query->where('education_status_id', '>=', 2);
                } else {
                    $query->where('education_status_id', '=', $request->input('education_status_id'));
                }
            }
        }

        if ($request->language_spoken != null) {
            $query->whereHas('user_language_spoken', function ($query) use ($request) {
                $query->whereIn('language_id', $request->input('language_spoken'));
            });
        }

        if ($request->relationship_type_id != null) {
            $query->where('relationship_type_id', '=', $request->input('relationship_type_id'));
        }

        if ($request->covid_vaccine_id != null) {
            $query->where('covid_vaccine_id', '=', $request->input('covid_vaccine_id'));
        }

        if ($request->religious_id != null) {
            $query->where('religious_id', '=', $request->input('religious_id'));
        }

        if ($request->politics_id != null) {
            $politcsIds = $request->input('politics_id');
            if ($politcsIds === 0) {

            } else {
                $query->where('politics_id', $politcsIds);
            }
        }

        if ($request->pronouns != null) {
            $query->where('pronouns', '=', $request->input('pronouns'));
        }

        if ($request->sexuality_id != null) {
            $sexualityCount = Sexuality::count();
            $sexualityIds = $request->input('sexuality_id');
            if ($sexualityCount - 1 === count($sexualityIds)) {

            } else {
                $query->whereIn('sexuality_id', $sexualityIds);
            }
        }

        if ($request->dating_intention_id != null) {
            $datingIntentionCount = DatingIntention::count();
            $datingIntentionIds = $request->input('dating_intention_id');
            if ($datingIntentionCount - 1 === count($datingIntentionIds)) {

            } else {
                $query->whereIn('dating_intention_id', $datingIntentionIds);
            }
        }

        // Apply the condition based on auth user 'gender' and 'interested_for'
        $gender = $user->gender;
        $interestedFor = $user->interested_for;

        $query->where(function ($query) use ($gender, $interestedFor) {
            if ($gender === 'Male' && $interestedFor === 'Female') {
                $query->where('gender', 'Female')
                    ->where(function ($query) {
                        $query->where('interested_for', 'Male')
                            ->orWhere('interested_for', 'Both');
                    });
            } elseif ($gender === 'Female' && $interestedFor === 'Male') {
                $query->where('gender', 'Male')
                    ->where(function ($query) {
                        $query->where('interested_for', 'Female')
                            ->orWhere('interested_for', 'Both');
                    });
            } elseif ($gender === 'Non-Binary' && $interestedFor === 'Both') {
                $query->where('gender', 'Non-Binary')
                    ->where('interested_for', 'Both');
            } elseif ($gender === 'Male' && $interestedFor === 'Male') {
                $query->where(function ($query) {
                    $query->where('gender', 'Male')
                        ->orWhere('gender', 'Female');
                })
                    ->where(function ($query) {
                        $query->where('interested_for', 'Male')
                            ->orWhere('interested_for', 'Both');
                    });
            } elseif ($gender === 'Female' && $interestedFor === 'Female') {
                $query->where(function ($query) {
                    $query->where('gender', 'Male')
                        ->orWhere('gender', 'Female');
                })
                    ->where(function ($query) {
                        $query->where('interested_for', 'Female')
                            ->orWhere('interested_for', 'Both');
                    });
            } elseif ($gender === 'Non-Binary' && $interestedFor === 'Male') {
                $query->where('gender', 'Non-Binary')
                    ->where(function ($query) {
                        $query->where('interested_for', 'Male')
                            ->orWhere('interested_for', 'Both');
                    });
            } elseif ($gender === 'Non-Binary' && $interestedFor === 'Female') {
                $query->where('gender', 'Non-Binary')
                    ->where(function ($query) {
                        $query->where('interested_for', 'Female')
                            ->orWhere('interested_for', 'Both');
                    });
            } elseif ($gender === 'Male' && $interestedFor === 'Both') {
                $query->where(function ($query) {
                    $query->where('gender', 'Male')
                        ->orWhere('gender', 'Female');
                })
                    ->where(function ($query) {
                        $query->where('interested_for', 'Male')
                            ->orWhere('interested_for', 'Female')
                            ->orWhere('interested_for', 'Both');
                    });
            } elseif ($gender === 'Female' && $interestedFor === 'Both') {
                $query->where(function ($query) {
                    $query->where('gender', 'Male')
                        ->orWhere('gender', 'Female');
                })
                    ->where(function ($query) {
                        $query->where('interested_for', 'Male')
                            ->orWhere('interested_for', 'Both')
                            ->orWhere('interested_for', 'Female');
                    });
            }
        });

        $drinkData = DrinkRequest::where('sender_user_id', $user->id)->pluck('receiver_user_id'); // get receiver_user_id data
        $flashData = Chat::where('sender_id', $user->id)->pluck('reciever_id'); //get reciever_id data

        $filterData = $query->with($eagerLoadedRelationships)
            ->where('incognito_mode_status', 1)
            ->whereNotIn('id', $usersToEliminate) // eliminate user IDs
            ->whereNotIn('id', $drinkData->toArray()) // Exclude users whose id is in $drinkData
            ->whereNotIn('id', $flashData->toArray()) // Exclude users whose id is in $flashData
            ->get();

        $authUserProfile = ProfileImage::select('image_path')->where('user_id', $user->id)->first();

        $response = [
            'auth_user_profile' => $authUserProfile->image_path ?? null,
            'exist_user' => $user->exist_status,
            'data' => $filterData
        ];

        if (isset($userDistancesMap)) {
            foreach ($filterData as &$user) {
                $userId = $user['id'];
                if (isset($userDistancesMap[$userId])) {
                    $user['distance'] = abs(number_format($userDistancesMap[$userId], 2));
                } else {
                    $user['distance'] = null;
                }
            }
        }
        return $this->successRespond($response, 'FilteredData');
    }
}