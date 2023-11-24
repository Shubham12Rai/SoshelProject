<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\Like;
use App\Models\ProfileImage;
use App\Models\FcmToken;
use App\Models\Matchs;
use App\Models\Chat;
use App\Models\DrinkRequest;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends ApiHelper
{
    /**
     * To setup user account details based on login user token
     * @param req :[]
     * @param res : [Nearest user data from auth user]
     */
    public function getNearestLocations()
    {
        $user = auth()->user();

        $earthRadius = config('constants.LOCATION.earthRadius');
        $radius = config('constants.LOCATION.radius');
        $result = [];

        $likedUserIds = Like::where('user_id', $user->id)->pluck('liked_user_id')->toArray();

        $locations = $this->distanceCalculation($earthRadius, $radius, $user->latitude, $user->longitude);

        $filteredLocations = $locations->whereNotIn('id', $likedUserIds);

        foreach ($filteredLocations as $location) {
            if (
                ($location->id !== $user->id) &&
                (
                    ($user->gender === 'Male' && $user->interested_for === 'Female' && $location->gender === 'Female' &&
                        $location->interested_for === 'Male')
                    || ($user->gender === 'Female' && $user->interested_for === 'Male' && $location->gender === 'Male' &&
                        $location->interested_for === 'Female')
                    || ($user->gender === 'Non-Binary' && $user->interested_for === 'Both' &&
                        $location->gender === 'Non-Binary' && $location->interested_for === 'Both')
                )
            ) {
                $userDetails = User::where('id', $location->id)->with([
                    // 'income_level',
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
                    'job_role'
                ])->first()->toArray();

                $birthTime = new DateTime($location->dob);
                $today = new DateTime();
                $age = $birthTime->diff($today)->y;

                $userDetails['distance'] = abs(number_format($location->distance, 2));
                $userDetails['age'] = $age;
                $result[] = $userDetails;
            }
        }

        $authUserProfile = ProfileImage::select('image_path')->where('user_id', $user->id)->first();

        $response = [
            'auth_user_profile' => $authUserProfile->image_path ?? null,
            'exist_user' => $user->exist_status,
            'data' => $result
        ];
        return $this->successRespond($response, 'LocationList');
    }

    /**
     * To like/dislike and create match
     * @param req :[]
     * @param res : []
     */
    public function likeAndDislike(Request $request)
    {
        $user = auth()->user();
        $title = $user->full_name . " liked you";
        $description = "Notification description";
        $route = "likeScreen";

        $validator = Validator::make($request->all(), [
            'liked_user_id' => 'required|integer|min:1',
            'status' => 'required|in:0,1'
        ], [
            'status.in' => 'The status field must be 1 or 0.',
        ]);
        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $data = User::where('id', $request->liked_user_id)->first();
        if (empty($data)) {
            return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        }

        $likeData = Like::where('liked_user_id', $data->id)->where('user_id', $user->id)->first();

        if ($likeData) {
            return $this->alreadyLiked('AlreadyLiked');
        }

        $likeDataExist = Like::where('liked_user_id', $user->id)->where('user_id', $data->id)->first();

        $fcmTokeExist = FcmToken::where('user_id', $request->liked_user_id)->first();

        if ($likeDataExist) {

            if ($request->status != 1) {
                $createdData = Like::create([
                    'user_id' => $user->id,
                    'liked_user_id' => $data->id,
                    'like_status' => $request->status
                ]);

                return $this->successRespond($createdData, 'UserDisliked');
            }

            $title = $user->full_name . " created a new match with you";
            $description = "Match description";
            $route = "chatScreen";

            $authUserId = $user->id;
            $reqUserId = $request->liked_user_id;

            $matchData = Matchs::where(function ($query) use ($authUserId, $reqUserId) {
                $query->where('sender_user_id', $authUserId)->where('receiver_user_id', $reqUserId)
                    ->orWhere('sender_user_id', $reqUserId)->where('receiver_user_id', $authUserId);
            })->first();

            if ($matchData) {
                return $this->errorRespond('MatchExist', config('constants.CODE.badRequest'));
            }

            $matchData = new Matchs;
            $matchData->sender_user_id = $user->id;
            $matchData->receiver_user_id = $request->liked_user_id;
            $matchData->save();

            if (($request->status === 1) && $fcmTokeExist) {
                if ($data->notification_status === 1) {
                    $this->sendFirebasePush($data->id, $title, $description, $route);
                }
                $this->saveNotification($data->id, $title, $description, $fcmTokeExist->device_type, $route);
            }

            Chat::where(function ($query) use ($authUserId, $reqUserId) {
                $query->where('sender_id', $reqUserId)->where('reciever_id', $authUserId)
                    ->orWhere('sender_id', $authUserId)->where('reciever_id', $reqUserId);
            })->delete();

            DrinkRequest::where(function ($query) use ($authUserId, $reqUserId) {
                $query->where('sender_user_id', $reqUserId)->where('receiver_user_id', $authUserId)
                    ->orWhere('sender_user_id', $authUserId)->where('receiver_user_id', $reqUserId);
            })->delete();

            // delete like record from both side after successful matching
            Like::where(function ($query) use ($authUserId, $reqUserId) {
                $query->where('user_id', $reqUserId)->where('liked_user_id', $authUserId)
                    ->orWhere('user_id', $authUserId)->where('liked_user_id', $reqUserId);
            })->delete();

            $response = [
                'is_like' => true,
                'data' => $matchData
            ];

            return $this->successRespond($response, 'MatchData');

        } else {
            $createdData = Like::create([
                'user_id' => $user->id,
                'liked_user_id' => $data->id,
                'like_status' => $request->status
            ]);

            // Send push notification and save notification data
            if (($request->status === 1) && $fcmTokeExist) {
                if ($data->notification_status === 1) {
                    $this->sendFirebasePush($data->id, $title, $description, $route);
                }
                $this->saveNotification($data->id, $title, $description, $fcmTokeExist->device_type, $route);
            }

            return $this->successRespond($createdData, 'LikeAdded');
        }

    }

    /**
     * get a user profile data according to given user id
     * @param req :["user_id"]
     * @param res : [user profile data]
     */
    public function aboutProfile(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $earthRadius = config('constants.LOCATION.earthRadius');
        $locations = $this->userDistanceCalculation($earthRadius, $user->latitude, $user->longitude);
        $result = [];

        $matchingLocation = $locations->where('id', $request->user_id)->first();
        if (!empty($matchingLocation)) {
            $userDetails = User::where('id', $matchingLocation->id)->with([
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
            ])->first()->toArray();

            $userDetails['is_like'] = true;

            $birthTime = new DateTime($matchingLocation->dob);
            $today = new DateTime();
            $age = $birthTime->diff($today)->y;

            $distance = floatval(str_replace(',', '', $matchingLocation->distance));

            $userDetails['distance'] = number_format($distance, 2);
            $userDetails['age'] = $age;
            $result[] = $userDetails;
        } else {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        $authUserProfile = ProfileImage::select('image_path')->where('user_id', $user->id)->first();
        $response = [
            'auth_user_profile' => $authUserProfile->image_path ?? null,
            'exist_user' => $user->exist_status,
            'data' => $result
        ];

        return $this->successRespond($response, 'LikedUserDetail');

    }

    /**
     * To mark a new user as visited user
     * @param req :["exist_status"]
     * @param res : [
     *   "user_id",
     *   "exist_status",
     *   "active_status"
     * ]
     */
    public function userAlreadyExist(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'exist_status' => 'nullable|integer|in:1',
        ], [
            'exist_status.in' => 'You can only send 1 as request value.'
        ]);
        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        if (isset($request->exist_status)) {
            $user->exist_status = $request->exist_status;
            $user->save();

            $data = [
                'user_id' => $user->id,
                'exist_status' => $user->exist_status,
                'active_status' => $user->active_status,
            ];
            return $this->successRespond($data, 'ExistStatus');
        }

        $data = [
            'user_id' => $user->id,
            'exist_status' => $user->exist_status,
            'active_status' => $user->active_status,
        ];
        return $this->successRespond($data, 'ExistRetrive');

    }

    /**
     * check if user already visited or not
     * @param req :[]
     * @param res : [
     *   "exist_status"
     * ]
     */
    public function getUserExistStatus()
    {
        $user = auth()->user();

        $userData = User::select('exist_status')->where('id', $user->id)->first();

        return $this->successRespond($userData, 'UserExistStatus');
    }
}