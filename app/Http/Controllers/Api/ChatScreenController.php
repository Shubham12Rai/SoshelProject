<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\ProfileImage;
use Illuminate\Http\Request;
use App\Models\WeMet;
use App\Models\User;
use App\Models\Chat;
use App\Models\Like;
use App\Models\Report;
use App\Models\Matchs;
use App\Models\FcmToken;
use App\Models\BlockedUser;
use App\Models\ReportedUser;
use App\Models\DrinkRequest;
use App\Models\Status;
use Illuminate\Support\Facades\Validator;

class ChatScreenController extends ApiHelper
{
    const VALIDATION_RULES = 'required|integer|min:1';
    /**
     * To create we met data with the matching user
     * @param req :[user_id]
     * @return res : []
     */
    public function weMet(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULES,
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
        //         'to_user' => $request->user_id
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
     * To block any matching user
     * @param req :[user_id]
     * @return res : []
     */
    public function userBlock(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULES,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $userExist = User::find($request->user_id);

        if (!$userExist) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        $this->deleteChat($user, $userExist);

        $blockedUserData = BlockedUser::updateOrCreate([
            'from' => $user->id,
            'to' => $request->user_id,
            'block_status' => 1
        ]);

        return $this->successRespond($blockedUserData, 'Blocked');
    }

    /**
     * To report any matching user
     * @param req :[reported_to_id, reasons_id, other_reason]
     * @return res : []
     */
    public function report(Request $request)
    {
        $user = auth()->user();
        $credentials = [];

        $validator = Validator::make($request->all(), [
            'reported_to_id' => self::VALIDATION_RULES,
            'reasons_id' => 'required|integer|min:1|max:6',
            'other_reason' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $reportedUserExist = User::where('id', $request->reported_to_id)->first();

        if (!$reportedUserExist) {
            return $this->errorRespond('InValidUserId', config('constants.CODE.badRequest'));
        }

        $reportedUserData = ReportedUser::where('from', $user->id)->where('to', $request->reported_to_id)->exists();

        if ($reportedUserData) {
            return $this->errorRespond('AlreadyReported', config('constants.CODE.badRequest'));
        }

        $credentials['from'] = $user->id;
        $credentials['to'] = $request->reported_to_id;

        $checkOtherOption = Report::where('id', $request->reasons_id)->first();

        if ($checkOtherOption !== null) {
            if ($checkOtherOption->option === "Other reasons") {
                if (isset($request->reasons_id)) {
                    $credentials['other_reason'] = $request->other_reason;
                    $credentials['reasons_id'] = null;
                }
            } else {
                $credentials['reasons_id'] = $request->reasons_id;
                $credentials['other_reason'] = null;
            }
        }

        $reportedUser = $request->reported_to_id;

        $this->deleteChat($user, $reportedUser);

        // block reported user
        BlockedUser::updateOrCreate([
            'from' => $user->id,
            'to' => $request->reported_to_id,
            'block_status' => 0
        ]);

        ReportedUser::create($credentials);

        $totalReportCount = ReportedUser::where('to', $request->reported_to_id)->count();
        $status = Status::where('name', 'Block')->first();
        // update status for users who have been reported more than once
        if ($totalReportCount > 1) {
            $reportedUserExist->active_status = $status->id;
            $reportedUserExist->save();
        }

        return $this->successRespond((object) [], 'UserReport');
    }

    // Comman function to delete chat
    private function deleteChat($user, $reportedUser)
    {
        $chats = Chat::where(function ($query) use ($user, $reportedUser) {
            $query->where('sender_id', $user->id)->where('reciever_id', $reportedUser)
                ->orWhere('sender_id', $reportedUser)->where('reciever_id', $user->id);
        })->get();

        foreach ($chats as $chat) {
            $chat->delete();
        }
    }

    /**
     * To send drink request
     * @param req :[receiver_user_id]
     * @return res : []
     */
    public function sendDrinkRequest(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'receiver_user_id' => self::VALIDATION_RULES,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $requestExist = DrinkRequest::where('sender_user_id', $user->id)
            ->where('receiver_user_id', $request->receiver_user_id)
            ->exists();

        if ($requestExist) {
            return $this->errorRespond('DrinkAlreadySent', config('constants.CODE.badRequest'));
        }

        $drinkRequsetData = new DrinkRequest();
        $drinkRequsetData->sender_user_id = $user->id;
        $drinkRequsetData->receiver_user_id = $request->receiver_user_id;
        $drinkRequsetData->save();

        return $this->successRespond($drinkRequsetData, 'SentDrink');

    }

    /**
     * To get authenticated user drink requests
     * @param req :[]
     * @return res : []
     */
    public function getDrinkRequest()
    {
        $user = auth()->user();

        $drinkRequestData = DrinkRequest::where('receiver_user_id', $user->id)->get();

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
     * To accept/decline drink request
     * @param req :[user_id, is_accept (1-accept/0-decline)]
     * @return res : []
     */
    public function acceptAndDeclineDrinkRequest(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $likedUserId = $request->user_id;
        $title = $user->full_name . " created a new match with you";
        $description = "Match description";
        $route = "chatScreen";

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULES,
            'is_accept' => 'required|integer|in:0,1'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $data = $this->getUserData($request->user_id);

        $authUserId = $user->id;
        $reqUserId = $request->user_id;

        if ($request->is_accept === 1) {
            
            $existingMatch = $this->checkExistingMatch($authUserId, $reqUserId);

            $userChatData = $this->getUserDrinkData($reqUserId, $authUserId);
            $userDrinkData = $this->getUserChatData($reqUserId, $authUserId);

            if ($existingMatch >= 2) {

                $this->deleteUserData($userChatData);
                $this->deleteUserData($userDrinkData);

                return $this->errorRespond('MatchExist', config('constants.CODE.badRequest'));
            }

            $matchData = new Matchs;
            $matchData->sender_user_id = $user->id;
            $matchData->receiver_user_id = $request->user_id;
            $matchData->save();

            $this->deleteLike($userId, $likedUserId);

            $this->deleteUserData($userChatData);
            $this->deleteUserData($userDrinkData);

            $fcmTokeExist = FcmToken::where('user_id', $request->user_id)->first();

            if ($fcmTokeExist) {
                if ($data->notification_status === 1) {
                    $this->sendFirebasePush($data->id, $title, $description, $route);
                }
                $this->saveNotification($data->id, $title, $description, $fcmTokeExist->device_type, $route);
            }
            return $this->successRespond($matchData, 'DrinkRequestAccept');

        } else {

            $drinkExist = DrinkRequest::where('sender_user_id', $reqUserId)
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
     * To send flash message request
     * @param req :[reciever_id, message, is_match_and_flash (flash-before creating match/match - after creating match)]
     * @return res : []
     */
    public function saveMessage(Request $request)
    {
        $user = auth()->user();
        $values = $request->only('reciever_id', 'message');
        $values['sender_id'] = $user->id;
        $recieverId = $request->reciever_id;
        $validator = Validator::make($request->all(), [
            'reciever_id' => self::VALIDATION_RULES,
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
        if ($request->is_match_and_flash === "flash") {
            $values["message_from"] = $request->is_match_and_flash;
            Chat::create($values);

            // Count the total number of messages sent by the authenticated user
            $userMessageCount = Chat::where('sender_id', $user->id)->count();

            $messageCount = Chat::where('sender_id', $user->id)
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
                "full_name" => $senderData ? $senderData->full_name : '',
                "profile_image" => $senderData ? $senderData->image_path : '',
                "data" => $messageCount
            ];
            return $this->successRespond($response, 'MessageSaved');
        } else {
            $checkChatInitiated = Chat::where(function ($query) use ($user, $recieverId) {
                $query->where('sender_id', $user->id)
                    ->where('reciever_id', $recieverId);
            })->orWhere(function ($query) use ($user, $recieverId) {
                $query->where('sender_id', $recieverId)
                    ->where('reciever_id', $user->id);
            })
                ->where('message_from', "match")->exists();

            if (empty($checkChatInitiated)) {
                $values["message_from"] = $request->is_match_and_flash;
                Chat::create($values);
            }

            return $this->successRespond((object) [], 'MessageSaved');
        }
    }

    /**
     * To get auth user flash message data list
     * @param req :[]
     * @return res : [flash message data list including message count and messages]
     */
    public function getMessage()
    {
        $userId = auth()->user()->id;

        $messages = Chat::join('users', 'chats.sender_id', '=', 'users.id')
            ->where('chats.reciever_id', $userId)
            ->where('chats.message_from', 'flash')
            ->select('chats.*', 'users.full_name')
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
        $totalMessageCount = Chat::where('sender_id', $userId)->count();

        $response = [
            "total_message_count" => $totalMessageCount,
            "data" => $data,
        ];

        return $this->successRespond($response, 'YourMessageData');
    }

    /**
     * To get flash message data based on sender_id
     * @param req :['sender_id']
     * @return res : [All messages with respect to sender_id]
     */
    public function flashMessageData(Request $request)
    {
        $userId = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'sender_id' => self::VALIDATION_RULES,
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $userExists = User::where('id', $request->sender_id)->exists();

        if ($userExists === false) {
            return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        }

        $messages = Chat::where('reciever_id', $userId)
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
     * To accept/decline flash message request
     * @param req :[user_id, is_accept (1-accept/0-decline)]
     * @return res : []
     */
    public function acceptAndDeclineMessageRequest(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $likedUserId = $request->user_id;
        $title = $user->full_name . " created a new match with you";
        $description = "Match description";
        $route = "chatScreen";

        $validator = Validator::make($request->all(), [
            'user_id' => self::VALIDATION_RULES,
            'is_accept' => 'required|integer|in:0,1'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $data = $this->getUserData($request->user_id);

        $authUserId = $user->id;
        $reqUserId = $request->user_id;

        if ($request->is_accept === 1) {

            $existingMatch = $this->checkExistingMatch($authUserId, $reqUserId);

            $userChatData = $this->getUserDrinkData($reqUserId, $authUserId);
            $userDrinkData = $this->getUserChatData($reqUserId, $authUserId);

            if ($existingMatch >= 2) {

                $this->deleteUserData($userChatData);
                $this->deleteUserData($userDrinkData);

                return $this->errorRespond('MatchExist', config('constants.CODE.badRequest'));
            }

            $matchData = new Matchs;
            $matchData->sender_user_id = $user->id;
            $matchData->receiver_user_id = $request->user_id;
            $matchData->save();

            //removing like/disliked users
            $this->deleteLike($userId, $likedUserId);

            $this->deleteUserData($userChatData);
            $this->deleteUserData($userDrinkData);

            $fcmTokeExist = FcmToken::where('user_id', $request->user_id)->first();

            if ($fcmTokeExist) {
                if ($data->notification_status === 1) {
                    $this->sendFirebasePush($data->id, $title, $description, $route);
                }
                $this->saveNotification($data->id, $title, $description, $fcmTokeExist->device_type, $route);
            }
            return $this->successRespond($matchData, 'DrinkRequestAccept');

        } else {

            $messageExist = Chat::where('sender_id', $reqUserId)
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
    private function getUserData($userId)
    {
        $data = User::where('id', $userId)->first();
        if (empty($data)) {
            return $this->errorRespond('UserNotExist', config('constants.CODE.badRequest'));
        }
        return $data;
    }

    // Comman function to check auth user match
    private function checkExistingMatch($authUserId, $reqUserId)
    {
        return Matchs::where(function ($query) use ($authUserId, $reqUserId) {
            $query->where('sender_user_id', $authUserId)->where('receiver_user_id', $reqUserId);
        })->orWhere(function ($query) use ($authUserId, $reqUserId) {
            $query->where('receiver_user_id', $authUserId)->where('sender_user_id', $reqUserId);
        })->count();
    }

    // Comman function to get auth user drink request
    private function getUserDrinkData($reqUserId, $authUserId)
    {
        return DrinkRequest::where('sender_user_id', $reqUserId)->where('receiver_user_id', $authUserId)->get();
    }

    // Comman function to get auth user flash message request
    private function getUserChatData($reqUserId, $authUserId)
    {
        return Chat::where('sender_id', $reqUserId)->where('reciever_id', $authUserId)->get();
    }

    // Comman function to delete auth user flash message and drink request
    private function deleteUserData($userData)
    {
        $userData->each->delete();
    }

    // Comman function to delete auth user like
    private function deleteLike($userId, $likedUserId)
    {
        Like::where(function ($query) use ($userId, $likedUserId) {
            $query->where(function ($subQuery) use ($userId, $likedUserId) {
                $subQuery->where('user_id', $userId)->where('liked_user_id', $likedUserId);
            })->orWhere(function ($subQuery) use ($userId, $likedUserId) {
                $subQuery->where('liked_user_id', $userId)->where('user_id', $likedUserId);
            });
        })->delete();
    }
}