<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\ProfileImage;
use App\Models\Matchs;
use App\Models\User;
use App\Models\Chat;
use App\Models\BlockedUser;
use App\Models\FcmToken;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MatchController extends ApiHelper
{
    const VALIDATION_RULES = 'required|integer|min:1';
    /**
     * To create match between the liked user and the auth user
     * @param req :["user_id"]
     * @param res : [Match user data]
     */
    public function createMatch(Request $request)
    {
        $user = auth()->user();
        $title = $user->full_name . " created a new match with you";
        $description = "Match description";
        $route = "matchScreen";

        $validator = Validator::make($request->all(), [
            "user_id" => self::VALIDATION_RULES
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $authUserId = $user->id;
        $reqUserId = $request->user_id;

        $data = User::where('id', $request->user_id)->first();
        if (empty($data)) {
            return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        }

        // check if match exist from either side
        $matchData = Matchs::where(function ($query) use ($authUserId, $reqUserId) {
            $query->where('sender_user_id', $authUserId)->where('receiver_user_id', $reqUserId)
                ->orWhere('sender_user_id', $reqUserId)->where('receiver_user_id', $authUserId);
        })->first();

        if ($matchData) {
            return $this->errorRespond('MatchExist', config('constants.CODE.badRequest'));
        }

        $matchData = new Matchs;
        $matchData->sender_user_id = $user->id;
        $matchData->receiver_user_id = $request->user_id;
        $matchData->save();

        $fcmTokeExist = FcmToken::where('user_id', $request->user_id)->first();

        if ($fcmTokeExist) {
            if ($data->notification_status === 1) {
                $this->sendFirebasePush($data->id, $title, $description, $route);
            }
            $this->saveNotification($data->id, $title, $description, $fcmTokeExist->device_type, $route);
        }

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
    }

    /**
     * To get the matched user data and recently likes count
     * @param req :[]
     * @param res : [Matched user data recently likes count]
     */
    public function getMatchAndRecentlyActive()
    {
        $user = auth()->user();

        $userMatchs = Matchs::where('sender_user_id', $user->id)
            ->orWhere('receiver_user_id', $user->id)
            ->get();
        $matchImg = [];
        $expireMatch = [];

        foreach ($userMatchs as $userMatch) {

            $otherUserId = ($userMatch->sender_user_id === $user->id)
                ? $userMatch->receiver_user_id
                : $userMatch->sender_user_id;

            $createdAt = Carbon::parse($userMatch->created_at);
            $timeDifference = $createdAt->diffInMinutes(Carbon::now());

            $userBlockExist = BlockedUser::where('from', $user->id)
                ->where('to', $otherUserId)
                ->whereIn('block_status', [1, 0])->first();

            $matchUserImage = ProfileImage::select('user_id', 'image_path')
                ->where('user_id', $otherUserId)
                ->orderBy('created_at')
                ->first();

            $checkChatInitiated = Chat::where(function ($query) use ($user, $otherUserId) {
                $query->where('sender_id', $user->id)
                    ->where('reciever_id', $otherUserId);
            })->orWhere(function ($query) use ($user, $otherUserId) {
                $query->where('sender_id', $otherUserId)
                    ->where('reciever_id', $user->id);
            })->exists();

            if ($timeDifference > config('constants.EXPIRE_TIME.matchExpireTime') && empty($checkChatInitiated)) {
                if (!$userBlockExist) {
                    $expireMatch[] = [
                        'user_id' => $otherUserId,
                        'image_path' => $matchUserImage->image_path,
                        'is_match_expire' => 1
                    ];
                }
            } else {
                if (!$userBlockExist && !$checkChatInitiated) {
                    $matchImg[] = [
                        'user_id' => $otherUserId,
                        'image_path' => $matchUserImage->image_path,
                        'is_match_expire' => 0
                    ];
                }
            }
        }

        // Get liked users
        $likedUsers = Like::where('liked_user_id', $user->id)
            ->where('like_status', 1)
            ->where('created_at', '>=', Carbon::now()->subMinutes(60))
            ->get();

        // Filter out users who have a corresponding dislike
        $likedUsers = $likedUsers->reject(function ($like) use ($user) {
            return Like::where('user_id', $user->id)
                ->where('liked_user_id', $like->user_id)
                ->where('like_status', 0)
                ->exists();
        });

        $response = [
            'match_user' => array_merge($matchImg, $expireMatch),
            'recent_like_count' => count($likedUsers)
        ];
        return $this->successRespond($response, 'MatchedData');
    }

    /**
     * To get the matched user profile data
     * @param req :["user_id"]
     * @param res : [Matched user data]
     */
    public function getMatchUserProfileData(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULES
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

        $userData = User::where('id', $request->user_id)
            ->selectRaw('*, TIMESTAMPDIFF(YEAR, dob, CURDATE()) as age')
            ->with($eagerLoadedRelationships)
            ->find($request->user_id);

        if (empty($userData)) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        $earthRadius = config('constants.LOCATION.earthRadius');
        $locations = $this->userDistanceCalculation($earthRadius, $user->latitude, $user->longitude)
            ->firstWhere('id', $userData->id);

        if ($locations) {
            $userData->distance = number_format(floatval($locations->distance), 2);
        }

        $senderUserId = $user->id;
        $receiverUserId = $userData->id;
        $hasMatch = Matchs::where(function ($query) use ($senderUserId, $receiverUserId) {
            $query->where(function ($subQuery) use ($senderUserId, $receiverUserId) {
                $subQuery->where('sender_user_id', $senderUserId)->where('receiver_user_id', $receiverUserId);
            })->orWhere(function ($subQuery) use ($senderUserId, $receiverUserId) {
                $subQuery->where('sender_user_id', $receiverUserId)->where('receiver_user_id', $senderUserId);
            });
        })->first();

        if (!$hasMatch) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        $createdAt = Carbon::parse($hasMatch->created_at);
        $time = $createdAt > Carbon::now()->subMinutes(2880);
        $matchExpire = $createdAt > $time;

        $matchExpireStatus = false;

        if ($matchExpire == 1) {
            $checkChatInitiated = Chat::where(function ($query) use ($user, $request) {
                $query->where('sender_id', $user->id)
                    ->where('reciever_id', $request->user_id);
            })->orWhere(function ($query) use ($user, $request) {
                $query->where('sender_id', $request->user_id)
                    ->where('reciever_id', $user->id);
            })->exists();

            if (empty($checkChatInitiated)) {
                $matchExpireStatus = true;
            }
        }

        $userData->is_match_expire = $matchExpireStatus;

        return $this->successRespond($userData, 'MatchedData');
    }

    /**
     * To Remove/Rematch matched user
     * @param req :["user_id", "is_rematch"]
     * @param res : []
     */
    public function removeRematch(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULES,
            'is_rematch' => 'required|integer|in:1,0'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $userExist = User::where('id', $request->user_id)->first();

        if (empty($userExist)) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        $title = $userExist->full_name . " sent you a rematch request";
        $description = "Match description";
        $route = "matchScreen";

        $senderUserId = $user->id;
        $receiverUserId = $request->user_id;

        // Delete the match
        Matchs::where(function ($query) use ($senderUserId, $receiverUserId) {
            $query->where(function ($q) use ($senderUserId, $receiverUserId) {
                $q->where('sender_user_id', $senderUserId)
                    ->where('receiver_user_id', $receiverUserId);
            })->orWhere(function ($q) use ($senderUserId, $receiverUserId) {
                $q->where('sender_user_id', $receiverUserId)
                    ->where('receiver_user_id', $senderUserId);
            });
        })->delete();

        if ($request->is_rematch === 1) {
            $createdData = Like::create([
                'user_id' => $user->id,
                'liked_user_id' => $request->user_id,
                'like_status' => 1
            ]);

            $fcmTokeExist = FcmToken::where('user_id', $request->user_id)->first();

            if ($fcmTokeExist) {
                if ($userExist->notification_status === 1) {
                    $this->sendFirebasePush($userExist->id, $title, $description, $route);
                }
                $this->saveNotification($userExist->id, $title, $description, $fcmTokeExist->device_type, $route);
            }

            return $this->successRespond($createdData, 'Rematch');
        }
        // Remove the related chat messages
        Chat::where(function ($query) use ($senderUserId, $receiverUserId) {
            $query->where(function ($q) use ($senderUserId, $receiverUserId) {
                $q->where('sender_id', $senderUserId)
                    ->where('reciever_id', $receiverUserId);
            })->orWhere(function ($q) use ($senderUserId, $receiverUserId) {
                $q->where('sender_id', $receiverUserId)
                    ->where('reciever_id', $senderUserId);
            });
        })->delete();

        return $this->successRespond((object) [], 'RemoveMatch');
    }
}