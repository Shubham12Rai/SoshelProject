<?php

namespace App\Http\Controllers\Api;

use App\Models\VenueFlashMessage;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\UserLikeInsideVenues;
use App\Models\UsersInsideVenues;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProfileImage;
use App\Models\VenueFeedback;
use App\Models\FcmToken;
use App\Models\BlockedUser;
use App\Models\WeMet;
use App\Models\Status;
use App\Models\VenueNotification;
use App\Models\VenueDrinkRequest;
use Carbon\Carbon;


class VenueBasedController extends ApiHelper
{
    const VALIDATION_RULE = 'required|integer|min:1';
    /**
     * To fetch all rearby venues (Restaurant, Bar, University, Club) within 100 meters range
     * @param req :[place_id, interested_for, interested_in, incognito_mode, venue_name]
     * @return res : []
     */
    public function fetchNearByVenues()
    {
        $user = auth()->user();
        $latitude = $user->latitude;
        $longitude = $user->longitude;

        $data = $this->getAllNearByVenues($latitude, $longitude);

        $getData = [];
        $i = 0;
        $addedPlaceIds = [];

        if ($data) {
            foreach ($data as $value1) {

                $placeId = $value1['place_id'];

                // Check if this place_id has already exist
                if (!in_array($placeId, $addedPlaceIds)) {
                    $getData[$i]['name'] = $value1['place_name'];
                    $getData[$i]['place_id'] = $placeId;
                    $getData[$i]['type'] = $value1['name'];
                    $i++;
                    $addedPlaceIds[] = $placeId;
                }
            }
        }
        return $this->successRespond($getData, 'Success');
    }

    /**
     * To save required details for Venue base check in 
     * @param req :[place_id, interested_for, interested_in, incognito_mode, venue_name]
     * @return res : []
     */
    public function saveFilters(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        $title = "You have successfully checked in inside venue base swiping";
        $description = "Venue base check-in description";
        $route = "likeScreen";
        $validator = Validator::make($request->all(), [
            "place_id" => 'required',
            "interested_in" => [
                'required',
                Rule::in(['Making Friends', 'Dating'])
            ],
            "interested_for" => [
                'required',
                Rule::in(['Men', 'Women', 'Both'])
            ],
            "incognito_mode" => 'required|int|between:0,1',
            "venue_name" => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $userExist = UsersInsideVenues::where('user_id', $userId)->where('status', 1)->first();
        $date = date("Y-m-d H:i:s");

        if ($userExist) {
            $this->deleteRecordsForUser($userId);
            $status = Status::where('name', 'Delete')->first();
            UsersInsideVenues::where('user_id', $userId)->update(['status' => $status->id]);
        }

        // If no record with the same user_id exists, create a new one.
        UsersInsideVenues::create([
            'user_id' => $userId,
            'name' => $request->name,
            'place_id' => $request->place_id,
            'created_at' => $date,
            'updated_at' => $date,
            "interested_in" => $request->interested_in,
            "interested_for" => $request->interested_for,
            "incognito_mode" => $request->incognito_mode,
            'venue_name' => $request->venue_name,
        ]);

        $fcmTokeExist = FcmToken::where('user_id', $userId)->first();

        if ($fcmTokeExist) {
            if ($user->notification_status === 1) {
                $this->sendFirebasePush($userId, $title, $description, $route);
            }
            $this->saveVenueBaseNotification($userId, $title, $description, $route);
        }

        $venueBaseCheckInStatus = User::where('id', $userId)->update(['venue_based_checkin_status' => 1]);
        $response = [
            'venue_based_swiping' => $venueBaseCheckInStatus
        ];
        return $this->successRespond($response, 'Success');
    }

    /**
     * To get all potential cards 
     * @param req :[Bearer Token]
     * @return res : [Potential users card]
     */
    public function getCards()
    {
        $user = auth()->user();
        $userId = $user->id;
        $type = UsersInsideVenues::where('user_id', $userId)->where('status', 1)->first();
        if (!$type) {
            return $this->errorRespond('NoNearByVenueFound', config('constants.CODE.notFound'));
        }
        $venue_based_swiping_status = $type ? 1 : 0;
        $userData = [];

        $getfilters = UsersInsideVenues::where('user_id', $userId)->where('status', 1)->first();

        if (!$getfilters) {
            return $this->errorRespond('NoNearByVenueFound', config('constants.CODE.notFound'));
        }

        $interestedIn = $getfilters->interested_in;
        $interestedFor = $getfilters->interested_for;
        $placeId = $getfilters->place_id;
        $likedUserIds = UserLikeInsideVenues::where('from', $userId)->pluck('to');

        $venueDrinkData = VenueDrinkRequest::where('sender_user_id', $userId)->pluck('receiver_user_id'); // get receiver_user_id data
        $venueFlashData = VenueFlashMessage::where('sender_id', $userId)->pluck('reciever_id'); //get reciever_usre_id data

        $getUsers = User::select('users.*', 'users_inside_venues.place_id')
            ->selectRaw('users.dob, TIMESTAMPDIFF(YEAR, dob, CURDATE()) as age')
            ->join('users_inside_venues', 'users_inside_venues.user_id', '=', 'users.id')
            ->where('users.id', '!=', $userId)
            ->where('users.active_status', '=', 1)
            ->where('users_inside_venues.interested_in', $interestedIn)
            ->where('users_inside_venues.status', 1) //
            ->where(function ($query) use ($interestedFor) {
                if ($interestedFor === 'Men') {
                    $query->whereIn('users_inside_venues.interested_for', ['Women', 'Both']);
                } elseif ($interestedFor === 'Women') {
                    $query->whereIn('users_inside_venues.interested_for', ['Men', 'Both']);
                } elseif ($interestedFor === 'Both') {
                    $query->whereIn('users_inside_venues.interested_for', ['Men', 'Women', 'Both']);
                }
            })
            ->where('users_inside_venues.place_id', $placeId)
            ->where('users_inside_venues.incognito_mode', 0)
            ->whereNotIn('users.id', $likedUserIds)
            ->whereNotIn('users.id', $venueDrinkData->toArray()) // Exclude users whose id is in $venueDrinkData
            ->whereNotIn('users.id', $venueFlashData->toArray()) // Exclude users whose id is in $venueFlashData
            ->with([
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
            ])
            ->get();
        $userData = $getUsers;

        $authUserProfile = ProfileImage::select('image_path')->where('user_id', $user->id)->first();

        $response = [
            'auth_user_profile' => $authUserProfile->image_path ?? null,
            'exist_user' => $user->exist_status,
            'venue_based_swiping' => $venue_based_swiping_status,
            'data' => $userData
        ];
        return $this->successRespond($response, 'Success');
    }

    /**
     * To like/dislike and create match  
     * @param req :[liked_user_id, status - 1(Like)/0(Dislike)]
     * @return res : []
     */
    public function likeUser(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $toUserId = $request->user_id;

        $title_like = $user->full_name . " has liked you inside this venue";
        $description_like = "Venue based like description";
        $route_like = "likeScreen";

        $title_match = $user->full_name . " has created match with you inside this venue";
        $description_match = "Venue based match description";
        $route_match = "chatScreen";

        $validator = Validator::make($request->all(), [
            'user_id' => [
                'required',
                'int',
                'exists:users,id'
            ],
        ]);
        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }
        if (trim($request->user_id) == trim($userId)) {
            return $this->errorRespond(config('message.CannotLikeUserSelf'), config('constants.CODE.badRequest'));
        }
        $user = auth()->user();
        $userId = $user->id;
        $type = UsersInsideVenues::where('user_id', $userId)->first();
        if (!$type) {
            return $this->errorRespond('NoNearByVenueFound', config('constants.CODE.notFound'));
        }

        $existingLike = UserLikeInsideVenues::where(['from' => $userId, 'to' => $toUserId])->first();

        // Determine title, description, and route based on whether the like already exists
        $isLikeExist = UserLikeInsideVenues::where('from', $toUserId)->where('to', $userId)->first();
        $title = $isLikeExist ? $title_match : $title_like;
        $description = $isLikeExist ? $description_match : $description_like;
        $route = $isLikeExist ? $route_match : $route_like;

        $toUserData = User::where('id', $request->user_id)->first();

        if (!$existingLike) {
            $userLike = new UserLikeInsideVenues();
            $userLike->from = $userId;
            $userLike->to = $toUserId;
            $userLike->status = $request->like_status;
            $userLike->save();

            $ifMatchExist = UserLikeInsideVenues::where(['from' => $toUserId, 'to' => $userId])->first();

            if ($ifMatchExist) {
                VenueFlashMessage::where(function ($query) use ($toUserId, $userId) {
                    $query->where(function ($subQuery) use ($toUserId, $userId) {
                        $subQuery->where('sender_id', $toUserId)->where('reciever_id', $userId);
                    })->orWhere(function ($subQuery) use ($toUserId, $userId) {
                        $subQuery->where('reciever_id', $toUserId)->where('sender_id', $userId);
                    });
                })->delete();

                VenueDrinkRequest::where(function ($query) use ($toUserId, $userId) {
                    $query->where(function ($subQuery) use ($toUserId, $userId) {
                        $subQuery->where('sender_user_id', $toUserId)->where('receiver_user_id', $userId);
                    })->orWhere(function ($subQuery) use ($toUserId, $userId) {
                        $subQuery->where('receiver_user_id', $toUserId)->where('sender_user_id', $userId);
                    });
                })->delete();
            }

            $fcmTokeExist = FcmToken::where('user_id', $toUserId)->first();

            if ($fcmTokeExist && ($request->like_status === 1)) {
                if ($toUserData->notification_status === 1) {
                    $this->sendFirebasePush($toUserId, $title, $description, $route);
                }
                $this->saveVenueBaseNotification($toUserId, $title, $description, $route);
            }

            return $ifMatchExist ? $this->successRespond($userLike, 'MatchData') : $this->successRespond($userLike, 'LikeAdded');
        }
        return $this->alreadyLiked('AlreadyLiked');
    }

    /**
     * To get authenticated user matches
     * @param req :[Bearer Token]
     * @return res : []
     */
    public function getVenueMatchData()
    {
        $userId = auth()->user()->id;

        // Get the list of blocked users for the current user
        $blockedUserIds = BlockedUser::where('from', $userId)
            ->pluck('to')
            ->toArray();

        $matches = UserLikeInsideVenues::select('from', 'to')
            ->where('status', 1)
            ->where(function ($query) use ($userId) {
                $query->where('from', $userId)
                    ->orWhere('to', $userId);
            })
            ->get();
        $matchingUsers = [];

        foreach ($matches as $match) {
            $from = $match->from;
            $to = $match->to;

            $reverseMatch = $matches->where('from', $to)->where('to', $from)->first();

            if ($reverseMatch) {
                $matchingUsers[] = $reverseMatch->from == $userId ? $reverseMatch->to : $reverseMatch->from;
            }
        }

        // Filter out users who are in the blocked list
        $matchingUsers = array_diff($matchingUsers, $blockedUserIds);
        $matchingUsers = array_unique($matchingUsers);
        $matchImg = [];
        foreach ($matchingUsers as $matchUserId) {
            $matchUserImage = ProfileImage::join('users', 'users.id', 'profile_images.user_id')->select('profile_images.user_id', 'profile_images.image_path')
                ->where('profile_images.user_id', $matchUserId) // checking matched user active status
                ->where('users.active_status', 1)
                ->orderBy('profile_images.created_at')
                ->first();

            // checking if met exist
            $weMetExist = WeMet::where(function ($query) use ($userId, $matchUserId) {
                $query->where(function ($subQuery) use ($userId, $matchUserId) {
                    $subQuery->where('from_user', $userId)->where('to_user', $matchUserId);
                })->orWhere(function ($subQuery) use ($userId, $matchUserId) {
                    $subQuery->where('from_user', $matchUserId)->where('to_user', $userId);
                });
            })->first();

            $existingMatch = VenueFlashMessage::where(function ($query) use ($userId, $matchUserId) {
                $query->where('sender_id', $userId)->where('reciever_id', $matchUserId);
            })->orWhere(function ($query) use ($userId, $matchUserId) {
                $query->where('sender_id', $matchUserId)->where('reciever_id', $userId);
            })->exists();

            if ($matchUserImage && !$existingMatch) {
                $matchImg[] = [
                    'user_id' => $matchUserId,
                    'image_path' => $matchUserImage->image_path,
                    'is_we_met_exist' => $weMetExist ? 1 : 0,
                ];
            }
        }

        $response = [
            'match_user' => $matchImg,
        ];
        return $this->successRespond($response, 'Success');
    }

    /**
     * To authenticated user likes data
     * @param req :[Bearer Token]
     * @return res : [
     *   "venue_based_swiping": 1,
     *   "all_like_count": 0,
     *   "new_like_count": 0,
     *   "new_user_id": [],
     *   "all_likes": [],
     *   "new_likes": [],
     *   "recently_active": []
     * ]
     */
    public function venueBasedUserLike()
    {
        $user = auth()->user();
        $userId = $user->id;

        $userExist = UsersInsideVenues::select('*')->where('user_id', $user->id)->first();
        if (!$userExist) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        $userLikeData = $userExist::join('user_likes_inside_venues', 'user_likes_inside_venues.to', 'users_inside_venues.user_id')
            ->where('user_likes_inside_venues.to', $userExist->user_id)
            ->where('user_likes_inside_venues.status', 1)
            ->select('user_likes_inside_venues.from', 'user_likes_inside_venues.created_at', 'user_likes_inside_venues.is_read')
            ->distinct() // Adding this line to retrieve distinct records
            ->get();

        $matches = UserLikeInsideVenues::select('from', 'to')
            ->whereIn('status', [1, 0])
            ->where(function ($query) use ($userId) {
                $query->where('from', $userId)
                    ->orWhere('to', $userId);
            })
            ->get();

        $matchingUsers = [];

        foreach ($matches as $match) {
            $from = $match->from;
            $to = $match->to;

            $reverseMatch = $matches->where('from', $to)->where('to', $from)->first();

            if ($reverseMatch) {
                $matchingUsers[] = $reverseMatch->from == $userId ? $reverseMatch->to : $reverseMatch->from;
            }
        }

        $recentlyActive = [];
        $allLikes = [];
        $newLikes = [];
        $newUserId = [];

        foreach ($userLikeData as $userData) {

            // Check if the user_id is not in the matchingUsers array
            if (!in_array($userData->from, $matchingUsers)) {
                $userDob = User::where('id', $userData->from)
                    ->where('active_status', '=', 1)
                    ->select('id', 'full_name', 'job_role_id', 'other_job_option', 'dob', 'plan_id')
                    ->selectRaw('dob, TIMESTAMPDIFF(YEAR, dob, CURDATE()) as age')
                    ->with(['plan', 'profileImage', 'job_role'])->first();

                if ($userDob) {
                    if ($userData->is_read == 0) {
                        $allLikes[] = $userDob;
                    } elseif ($userData->is_read == 1) {
                        $newLikes[] = $userDob;
                        $newUserId[] = $userDob->id;
                    }

                    // if auth user liked within the last 60 minutes
                    $createdAt = Carbon::parse($userData->created_at);
                    if ($createdAt >= Carbon::now()->subMinutes(60)) {
                        $recentlyActive[] = $userDob;
                    }
                }
            }
        }

        $likeData = [
            'venue_based_swiping' => $userExist ? 1 : 0,
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
     * To mark a like as read
     * @param req :[liked_user_id : []]
     * @return res : [If the like has been read then it will move to the all likes section]
     */
    public function venueBasedUserLikeIsReadUpdate(Request $request)
    {
        $user = auth()->user();
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'liked_user_id' => 'required|array',
            'liked_user_id.*' => self::VALIDATION_RULE
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $likedUserIds = $requestData['liked_user_id'];

        foreach ($likedUserIds as $userId) {
            UserLikeInsideVenues::select("*")->where('from', $userId)->where('to', $user->id)->update(['is_read' => 0]);

            $updatedData = UserLikeInsideVenues::select("id", "from", "to", "is_read")
                ->where('from', $userId)->where('to', $user->id)->orderByDesc('created_at')->first();
            $result[] = $updatedData;
        }

        return $this->successRespond($result, 'LikeStatusUpdated');

    }

    /**
     * To check out venue base
     * @param req :[Bearer Token]
     * @return res : []
     */
    public function venueBasedMoveOut()
    {
        $user = auth()->user()->id;

        $userExist = UsersInsideVenues::select('*')->where('user_id', $user)->where('status', 1)->first();
        if (!$userExist) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        User::where('id', $user)->update(['venue_based_checkin_status' => 0]);
        $this->deleteRecordsForUser($user);
        UsersInsideVenues::select('*')->where('user_id', $user)->update(['status' => 3]);

        $response = [
            'venue_based_checkin_status' => 0
        ];

        return $this->successRespond($response, 'MovedOutFromVenueBased');
    }

    public function deleteRecordsForUser($userId)
    {

        // Delete records from UserLikeInsideVenues table
        UserLikeInsideVenues::where('from', $userId)->orWhere('to', $userId)->delete();

        // Delete records from VenueNotification table
        VenueNotification::where('user_id', $userId)->delete();

        // Delete records from VenueDrinkRequest table
        VenueDrinkRequest::where('sender_user_id', $userId)->orWhere('receiver_user_id', $userId)->delete();

        // Delete records from VenueFlashMessage table
        VenueFlashMessage::where('sender_id', $userId)->orWhere('reciever_id', $userId)->delete();

    }

    /**
     * To get authenticated user venue base check in status
     * @param req :[Bearer Token]
     * @return res : [
     *       "venue_based_checkin_status",
     *       "venue_base_match_exist",
     *        "venue_base_feedback_exist",
     *        "auth_user_gender"
     * ]
     */
    public function getVenueBasedCheckinStatus()
    {
        $userId = auth()->user();

        $userMatchExist = UserLikeInsideVenues::where(function ($query) use ($userId) {
            $query->where(function ($subQuery) use ($userId) {
                $subQuery->where('status', 1)->where('from', $userId->id)->where('to', '!=', $userId->id);
            })->orWhere(function ($subQuery) use ($userId) {
                $subQuery->where('status', 1)->where('from', '!=', $userId->id)->where('to', $userId->id);
            });
        })->get();

        $matchingUsers = 0;
        foreach ($userMatchExist as $match) {
            $from = $match->from;
            $to = $match->to;

            $reverseMatch = $userMatchExist->where('from', $to)->where('to', $from)->first();

            if ($reverseMatch) {
                $matchingUsers = 1;
            }
        }

        $userFeedbackExist = VenueFeedback::where('user_id', $userId->id)
            ->whereIn('status', [1, 2])
            ->first();

        $feedbackExist = 0;
        if ($userFeedbackExist) {
            $feedbackExist = 1;
        }
        $response = [
            'venue_based_checkin_status' => $userId->venue_based_checkin_status,
            'venue_base_match_exist' => $matchingUsers,
            'venue_base_feedback_exist' => $feedbackExist,
            'auth_user_gender' => $userId->gender
        ];
        return $this->successRespond($response, 'UserCheckInStatus');
    }

    /**
     * To unmatch an existing match
     * @param req :[user_id]
     * @return res : []
     */
    public function venueBaseUnmatch(Request $request)
    {
        $userId = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULE,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $unmatchUserId = $request->user_id;
        $matchExist = UserLikeInsideVenues::where(function ($query) use ($userId, $unmatchUserId) {
            $query->where(function ($subQuery) use ($userId, $unmatchUserId) {
                $subQuery->where('from', $userId)->where('to', $unmatchUserId);
            })->orWhere(function ($subQuery) use ($userId, $unmatchUserId) {
                $subQuery->where('from', $unmatchUserId)->where('to', $userId);
            });
        })->first();

        if ($matchExist) {
            UserLikeInsideVenues::where(function ($query) use ($userId, $unmatchUserId) {
                $query->where(function ($subQuery) use ($userId, $unmatchUserId) {
                    $subQuery->where('from', $userId)->where('to', $unmatchUserId);
                })->orWhere(function ($subQuery) use ($userId, $unmatchUserId) {
                    $subQuery->where('from', $unmatchUserId)->where('to', $userId);
                });
            })->delete();
            return $this->successRespond((object) [], 'VenueMatchRemove');

        }
        return $this->errorRespond('NoMatchFound', config('constants.CODE.badRequest'));

    }

    /**
     * To save venue base feedback with dislike reason
     * @param req :[like_status - 1(Like)/2(Dislike), dislike_reason]
     * @return res : []
     */
    public function venueBaseFeedback(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'like_status' => 'required',
            'dislike_reason' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $userVenueData = UsersInsideVenues::where('user_id', $user->id)->first();

        if (!$userVenueData) {
            return $this->errorRespond('UserOutsideVenue', config('constants.CODE.badRequest'));
        }

        VenueFeedback::create([
            'place_id' => $userVenueData->place_id,
            'user_id' => $user->id,
            'status' => $request->like_status,
            'feedback_reason' => $request->dislike_reason ?? '',
            'place_name' => $userVenueData->venue_name,
            'address' => ""
        ]);

        return $this->successRespond((object) [], 'VenueFeedback');
    }

    /**
     * To create a we met data between authenticated and given user
     * @param req :[user_id]
     * @return res : []
     */
    public function venueBaseWeMet(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULE,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }
        // $fromUser = $user->id;
        // $toUser = $request->user_id;
        // $metExist = WeMet::where(function ($query) use ($fromUser, $toUser) {
        //     $query->where(function ($subQuery) use ($fromUser, $toUser) {
        //         $subQuery->where('from_user', $fromUser)->where('to_user', $toUser);
        //     })->orWhere(function ($subQuery) use ($fromUser, $toUser) {
        //         $subQuery->where('from_user', $toUser)->where('to_user', $fromUser);
        //     });
        // })->first();

        // $isExist = false;
        // if ($metExist) {
        //     $isExist = true;
        // } else {
        //     $newMet = WeMet::create([
        //         'from_user' => $user->id,
        //         'to_user' => $request->user_id,
        //         'venue_base_we_met_status' => 1
        //     ]);
        // }

        // $response = [
        //     'is_exist' => $isExist,
        //     'data' => $metExist ? (object) [] : $newMet
        // ];

        $response = $this->handleWeMet($user, $request->user_id);

        return $this->successRespond($response, 'WeMet');
    }

    /**
     * To get authenticated user match data inside venue
     * @param req :[Bearer Token]
     * @return res : [Match user data if exists]
     */
    public function getVenueMatchUserData(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULE
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
            'user_income_level'
        ];

        $userData = UsersInsideVenues::select('user_id')->where('user_id', $request->user_id)->first();
        if (!$userData) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }
        $matchUserId = $userData->user_id;
        $matches = UserLikeInsideVenues::select('from', 'to')
            ->where('status', 1)
            ->where(function ($query) use ($userId, $matchUserId) {
                $query->where('from', $userId)->where('to', $matchUserId)
                    ->orWhere('to', $userId)->where('from', $matchUserId);
            })
            ->get();

        $matchingUsers = [];
        foreach ($matches as $match) {
            $from = $match->from;
            $to = $match->to;

            $reverseMatch = $matches->where('from', $to)->where('to', $from)->first();

            if ($reverseMatch) {
                $matchingUsers[] = $reverseMatch->from == $userId ? $reverseMatch->to : $reverseMatch->from;
            }
        }
        $matchingUsers = array_unique($matchingUsers);

        if (!$matchingUsers) {
            return $this->errorRespond('NoMatchExist', config('constants.CODE.badRequest'));
        }

        $requestedUserData = User::where('id', $matchingUsers[0])
            ->selectRaw('*, TIMESTAMPDIFF(YEAR, dob, CURDATE()) as age')
            ->with($eagerLoadedRelationships)
            ->first();

        return $this->successRespond($requestedUserData, 'MatchUserProfileData');
    }

    /**
     * To get authenticated user notifications 
     * @param req :[Bearer Token]
     * @return res : []
     */
    public function getVenueNotificationData()
    {
        $user = auth()->user();

        $notificationData = VenueNotification::select("id", "user_id", "title", "description", "is_read", "route_name", "created_at", "updated_at")
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return $this->successRespond($notificationData, 'NotificationData');
    }

    /**
     * To mark a notification as read
     * @param req :[notification_id]
     * @return res : []
     */
    public function isReadVenueNotification(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'notification_id' => self::VALIDATION_RULE,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $notificationExist = VenueNotification::where('id', $request->notification_id)->where('user_id', $user->id)->first();

        if (!$notificationExist) {
            return $this->errorRespond('InvalidNotificationId', config('constants.CODE.badRequest'));
        }

        $notificationExist->is_read = 1;
        $notificationExist->update();

        return $this->successRespond($notificationExist, 'NotificationIsReadUpdate');
    }

    /**
     * To delete authenticated user Venue notification
     * @param req :[notification_id]
     * @return res : []
     */
    public function deleteVenueNotification(Request $request)
    {
        $notificationIds = $request->all();
        $validator = Validator::make($notificationIds, [
            'notification_id' => 'required|array',
            'notification_id.*' => self::VALIDATION_RULE
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        foreach ($notificationIds as $notificationId) {
            $notificationExist = VenueNotification::where('id', $notificationId)->first();

            if (!$notificationExist) {
                return $this->errorRespond('InvalidNotificationId', config('constants.CODE.badRequest'));
            }

            $notificationExist->destroy($notificationId);
        }

        return $this->successRespond((object) [], 'DeleteNotification');

    }

    /**
     * To check if we met exists between given user and authenticated user
     * @param req :[user_id]
     * @return res : [is_we_met_exist - 1(exists)/0(not-exists)]
     */
    public function venueBaseGetWeMet(Request $request)
    {
        $user = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULE,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $toUser = $request->user_id;
        $weMetExist = WeMet::where(function ($query) use ($user, $toUser) {
            $query->where(function ($subQuery) use ($user, $toUser) {
                $subQuery->where('from_user', $user)->where('to_user', $toUser);
            })->orWhere(function ($subQuery) use ($user, $toUser) {
                $subQuery->where('from_user', $toUser)->where('to_user', $user);
            });
        })->exists();

        $response = [
            "is_we_met_exist" => $weMetExist ? 1 : 0,
        ];

        return $this->successRespond($response, 'IsWeMetExist');
    }

    /**
     * To send drink request inside venue
     * @param req :[receiver_user_id]
     * @return res : [get sent drink request data]
     */
    public function venueBaseSendDrinkRequest(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'receiver_user_id' => self::VALIDATION_RULE,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $reportedUserExist = User::where('id', $request->receiver_user_id)->first();

        if (!$reportedUserExist) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        $requestExist = VenueDrinkRequest::where('sender_user_id', $user->id)
            ->where('receiver_user_id', $request->receiver_user_id)
            ->exists();

        if ($requestExist) {
            return $this->errorRespond('DrinkAlreadySent', config('constants.CODE.badRequest'));
        }

        $drinkRequsetData = new VenueDrinkRequest();
        $drinkRequsetData->sender_user_id = $user->id;
        $drinkRequsetData->receiver_user_id = $request->receiver_user_id;
        $drinkRequsetData->save();

        return $this->successRespond($drinkRequsetData, 'SentDrink');
    }

    /**
     * Get all drink request of an authenticated user inside venue
     * @param req :[Bearer Token]
     * @return res : [get all received drink request data]
     */
    public function getVenueDrinkRequest()
    {
        $user = auth()->user();

        $drinkRequestData = VenueDrinkRequest::where('receiver_user_id', $user->id)->get();

        // $responseData = array();
        // foreach ($drinkRequestData as $data) {

        //     $requestedUserData = User::join('profile_images', 'profile_images.user_id', 'users.id')
        //         ->where('profile_images.user_id', $data->sender_user_id)
        //         ->select('profile_images.image_path', 'users.id', 'users.full_name')
        //         ->first();

        //     if ($requestedUserData) {
        //         $responseData[] = [
        //             'id' => $data->id,
        //             'request_user_id' => $requestedUserData->id,
        //             'full_name' => $requestedUserData->full_name,
        //             'image_path' => $requestedUserData->image_path,
        //             'created_at' => $data->created_at,
        //             'updated_at' => $data->updated_at
        //         ];
        //     }
        // }

        $responseData = $this->handleGetdrinkRequest($drinkRequestData);


        return $this->successRespond($responseData, 'DrinkRequestData');
    }

    /**
     * To accept or decline drink request inside venue
     * @param req :[user_id, is_accept (1-accept/0-decline)]
     * @return res : [If accept then create a match otherwise drink request will be deleted]
     */
    public function acceptAndDeclineVenueDrinkRequest(Request $request)
    {
        $user = auth()->user();
        $title = $user->full_name . " created a new match with you inside this venue";
        $description = "Match description";
        $route = "chatScreen";

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULE,
            'is_accept' => 'required|integer|in:0,1'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        // $data = User::where('id', $request->user_id)->first();
        // if (empty($data)) {
        //     return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        // }

        $data = $this->getUserVenueData($request->user_id);

        $authUserId = $user->id;
        $reqUserId = $request->user_id;

        if ($request->is_accept === 1) {
            // $existingMatch = UserLikeInsideVenues::where(function ($query) use ($authUserId, $reqUserId) {
            //     $query->where('from', $authUserId)->where('to', $reqUserId);
            // })->orWhere(function ($query) use ($authUserId, $reqUserId) {
            //     $query->where('from', $reqUserId)->where('to', $authUserId);
            // })->count();

            $existingMatch = $this->checkExistingMatchInsideVenue($authUserId, $reqUserId);

            // $venueDrinkData = VenueDrinkRequest::where('sender_user_id', $reqUserId)->where('receiver_user_id', $authUserId)->get();
            // $venueFlashData = VenueFlashMessage::where('sender_id', $reqUserId)->where('reciever_id', $authUserId)->get();

            $venueFlashData = $this->checkUserVenueFlashData($reqUserId, $authUserId);
            $venueDrinkData = $this->getUserVenueDrinkData($reqUserId, $authUserId);

            if ($existingMatch >= 2) {
                // $venueDrinkData->each->delete();
                // $venueFlashData->each->delete();

                $this->deleteUserData($venueFlashData);
                $this->deleteUserData($venueDrinkData);

                return $this->errorRespond('MatchExist', config('constants.CODE.badRequest'));
            }

            // deleting like/dislike
            // UserLikeInsideVenues::where(function ($query) use ($authUserId, $reqUserId) {
            //     $query->where('from', $authUserId)->where('to', $reqUserId);
            // })->orWhere(function ($query) use ($authUserId, $reqUserId) {
            //     $query->where('from', $reqUserId)->where('to', $authUserId);
            // })->delete();

            $this->deleteLikeInsideVenue($authUserId, $reqUserId);



            $matchData = new UserLikeInsideVenues;
            $matchData->from = $user->id;
            $matchData->to = $request->user_id;
            $matchData->save();

            $matchData = new UserLikeInsideVenues;
            $matchData->from = $request->user_id;
            $matchData->to = $user->id;
            $matchData->save();

            // $venueDrinkData->each->delete();
            // $venueFlashData->each->delete();

            $this->deleteUserData($venueFlashData);
            $this->deleteUserData($venueDrinkData);

            $fcmTokeExist = FcmToken::where('user_id', $request->user_id)->first();

            if ($fcmTokeExist) {
                if ($data->notification_status === 1) {
                    $this->sendFirebasePush($data->id, $title, $description, $route);
                }
                $this->saveNotification($data->id, $title, $description, $fcmTokeExist->device_type, $route);
            }
            return $this->successRespond($matchData, 'DrinkRequestAccept');

        } else {

            $drinkExist = VenueDrinkRequest::where('sender_user_id', $reqUserId)
                ->where('receiver_user_id', $authUserId)
                ->get();
            if (!$drinkExist) {
                return $this->errorRespond('UserRequestNotExist', config('constants.CODE.badRequest'));
            }

            foreach ($drinkExist as $drink) {
                $drink->delete();
            }

            return $this->successRespond((object) [], 'DeleteDrinkRequest');
        }
    }

    /**
     * To send flash message inside venue
     * @param req :[reciever_id, message]
     * @return res : [Total count and flash messages data sent by authenticated user]
     */
    public function venueBaseSendFlashMessage(Request $request)
    {
        $user = auth()->user();
        $values = $request->only('reciever_id', 'message', 'is_match_and_flash');
        $values['sender_id'] = $user->id;
        $validator = Validator::make($request->all(), [
            'reciever_id' => self::VALIDATION_RULE,
            'message' => 'required',
            'is_match_and_flash' => 'required|in:flash,match'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $userExists = User::where('id', $request->reciever_id)->exists();

        if ($userExists === false) {
            return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        }
        $values['message_from'] = $request->is_match_and_flash;
        VenueFlashMessage::create($values);

        // Count the total number of messages sent by the authenticated user
        $userMessageCount = VenueFlashMessage::where('sender_id', $user->id)->count();

        $messageCount = VenueFlashMessage::where('sender_id', $user->id)
            ->where('reciever_id', $request->reciever_id)
            ->orderBy('created_at', 'asc')
            ->select('sender_id', 'reciever_id', 'message', 'created_at')
            ->get();

        $senderData = User::join('profile_images', 'profile_images.user_id', '=', 'users.id')
            ->where('users.id', $user->id)
            ->where('profile_images.user_id', $user->id)
            ->orderBy('profile_images.created_at', 'asc')
            ->select('users.full_name', 'profile_images.image_path')
            ->first();

        $count = count($messageCount);
        $response = [
            "total_message_count" => $userMessageCount,
            "message_count" => $count,
            "full_name" => $senderData->full_name,
            "profile_image" => $senderData->image_path,
            "data" => $messageCount
        ];
        return $this->successRespond($response, 'MessageSaved');
    }

    /**
     * To get list of auth user flash message data inside venue
     * @param req :[Bearer Token]
     * @return res : [flash message data list including message count and messages]
     */
    public function getVenueFlashMessage()
    {
        $userId = auth()->user()->id;

        $messages = VenueFlashMessage::join('users', 'venue_flash_message.sender_id', '=', 'users.id')
            ->where('venue_flash_message.reciever_id', $userId)
            ->where('venue_flash_message.message_from', 'flash')
            ->select('venue_flash_message.*', 'users.full_name')
            ->get();

        // $result = [];

        // foreach ($messages as $message) {

        //     $senderImage = ProfileImage::select('image_path')
        //         ->where('user_id', $message->sender_id)
        //         ->orderBy('created_at', 'asc')
        //         ->first();

        //     $senderId = $message->sender_id;

        //     $result[$senderId]["sender_id"] = $senderId;
        //     $result[$senderId]["full_name"] = $message->full_name;
        //     $result[$senderId]["reviever_id"] = $userId;
        //     $result[$senderId]["image_path"] = $senderImage->image_path;
        //     $result[$senderId]["message"][] = $message->message;
        //     $result[$senderId]["message_count"] = count($result[$senderId]["message"]);
        //     $result[$senderId]["created_at"] = $message->created_at;
        // }

        // $data = array_values($result);

        $data = $this->handleGetMessages($messages, $userId);
        $totalMessageCount = VenueFlashMessage::where('sender_id', $userId)->count();

        $response = [
            "total_message_count" => $totalMessageCount,
            "data" => $data,
        ];

        return $this->successRespond($response, 'YourMessageData');
    }

    /**
     * To get flash message data based on sender_id inside venue
     * @param req :['sender_id']
     * @return res : [All messages with respect to sender_id]
     */
    public function getVenueflashMessageData(Request $request)
    {
        $userId = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'sender_id' => self::VALIDATION_RULE,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $userExists = User::where('id', $request->sender_id)->exists();

        if ($userExists === false) {
            return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        }

        $flashMessageExist = VenueFlashMessage::where('sender_id', $request->sender_id)->exists();
        if ($flashMessageExist === false) {
            return $this->errorRespond('FlashMessageNotExist', config('constants.CODE.badRequest'));
        }

        $messages = VenueFlashMessage::where('reciever_id', $userId)
            ->where('sender_id', $request->sender_id)
            ->select('sender_id', 'reciever_id', 'message', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $userProfile = ProfileImage::select('image_path')
            ->where('user_id', $request->sender_id)
            ->orderBy('created_at', 'asc')
            ->first();

        $response = [
            "profile_image" => $userProfile->image_path,
            "body" => $messages
        ];

        return $this->successRespond($response, 'YourMessageData');

    }

    /**
     * To accept or decline flash messsage request inside venue
     * @param req :[user_id, is_accept]
     * @return res : [If accept then create a match otherwise flash message will be deleted]
     */
    public function acceptAndDeclineVenueMessageRequest(Request $request)
    {
        $user = auth()->user();
        $title = $user->full_name . " created a new match with you inside this venue";
        $description = "Match description";
        $route = "chatScreen";

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULE,
            'is_accept' => 'required|integer|in:0,1'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        // $data = User::where('id', $request->user_id)->first();
        // if (empty($data)) {
        //     return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        // }

        $data = $this->getUserVenueData($request->user_id);

        $flashMessageExist = VenueFlashMessage::where('sender_id', $request->user_id)->exists();
        if ($flashMessageExist === false) {
            return $this->errorRespond('FlashMessageNotExist', config('constants.CODE.badRequest'));
        }

        $authUserId = $user->id;
        $reqUserId = $request->user_id;

        if ($request->is_accept === 1) {
            // $existingMatch = UserLikeInsideVenues::where(function ($query) use ($authUserId, $reqUserId) {
            //     $query->where('from', $authUserId)->where('to', $reqUserId);
            // })->orWhere(function ($query) use ($authUserId, $reqUserId) {
            //     $query->where('from', $reqUserId)->where('to', $authUserId);
            // })->count();

            $existingMatch = $this->checkExistingMatchInsideVenue($authUserId, $reqUserId);

            // $venueFlashData = VenueFlashMessage::where('sender_id', $reqUserId)->where('reciever_id', $authUserId)->get();
            // $venueDrinkData = VenueDrinkRequest::where('sender_user_id', $reqUserId)->where('receiver_user_id', $authUserId)->get();

            $venueFlashData = $this->checkUserVenueFlashData($reqUserId, $authUserId);
            $venueDrinkData = $this->getUserVenueDrinkData($reqUserId, $authUserId);

            if ($existingMatch >= 2) {
                // $venueFlashData->each->delete();
                // $venueDrinkData->each->delete();

                $this->deleteUserData($venueFlashData);
                $this->deleteUserData($venueDrinkData);

                return $this->errorRespond('MatchExist', config('constants.CODE.badRequest'));
            }

            // deleting like/dislike
            // $existingMatch = UserLikeInsideVenues::where(function ($query) use ($authUserId, $reqUserId) {
            //     $query->where('from', $authUserId)->where('to', $reqUserId);
            // })->orWhere(function ($query) use ($authUserId, $reqUserId) {
            //     $query->where('from', $reqUserId)->where('to', $authUserId);
            // })->delete();

            $this->deleteLikeInsideVenue($authUserId, $reqUserId);

            $matchData = new UserLikeInsideVenues;
            $matchData->from = $user->id;
            $matchData->to = $request->user_id;
            $matchData->save();

            $matchData2 = new UserLikeInsideVenues;
            $matchData2->from = $reqUserId;
            $matchData2->to = $authUserId;
            $matchData2->save();

            // $venueFlashData->each->delete();
            // $venueDrinkData->each->delete();

            $this->deleteUserData($venueFlashData);
            $this->deleteUserData($venueDrinkData);

            $fcmTokeExist = FcmToken::where('user_id', $request->user_id)->first();

            if ($fcmTokeExist) {
                if ($data->notification_status === 1) {
                    $this->sendFirebasePush($data->id, $title, $description, $route);
                }
                $this->saveNotification($data->id, $title, $description, $fcmTokeExist->device_type, $route);
            }
            return $this->successRespond($matchData, 'DrinkRequestAccept');

        } else {

            $messageExist = VenueFlashMessage::where('sender_id', $reqUserId)
                ->where('reciever_id', $authUserId)
                ->get();
            if (!$messageExist) {
                return $this->errorRespond('UserRequestNotExist', config('constants.CODE.badRequest'));
            }

            foreach ($messageExist as $message) {
                $message->delete();
            }

            return $this->successRespond((object) [], 'DeleteDrinkRequest');
        }
    }

    // Comman function to check auth user
    private function getUserVenueData($userId)
    {
        $data = User::where('id', $userId)->first();
        if (empty($data)) {
            return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        }
        return $data;
    }

    // Comman function to check auth user match
    private function checkExistingMatchInsideVenue($authUserId, $reqUserId)
    {
        return UserLikeInsideVenues::where(function ($query) use ($authUserId, $reqUserId) {
            $query->where('from', $authUserId)->where('to', $reqUserId);
        })->orWhere(function ($query) use ($authUserId, $reqUserId) {
            $query->where('from', $reqUserId)->where('to', $authUserId);
        })->count();
    }

    // Comman function to get auth user drink request
    private function getUserVenueDrinkData($reqUserId, $authUserId)
    {
        return VenueDrinkRequest::where('sender_user_id', $reqUserId)->where('receiver_user_id', $authUserId)->get();
    }

    // Comman function to get auth user flash message request
    private function checkUserVenueFlashData($reqUserId, $authUserId)
    {
        return VenueFlashMessage::where('sender_id', $reqUserId)->where('reciever_id', $authUserId)->get();
    }

    // Comman function to delete auth user flash message and drink request
    private function deleteUserData($userData)
    {
        $userData->each->delete();
    }

    // Comman function to delete auth user like
    private function deleteLikeInsideVenue($authUserId, $reqUserId)
    {
        return UserLikeInsideVenues::where(function ($query) use ($authUserId, $reqUserId) {
            $query->where('from', $authUserId)->where('to', $reqUserId);
        })->orWhere(function ($query) use ($authUserId, $reqUserId) {
            $query->where('from', $reqUserId)->where('to', $authUserId);
        })->delete();
    }
}