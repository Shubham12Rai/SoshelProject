<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\FcmToken;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class NotificationController extends ApiHelper
{
    /**
     * To save or update device fcm token
     * @param req :[fcm_token, device_type]
     * @return res : []
     */
    public function updateToken(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
            'device_type' => 'nullable|in:android,ios'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $userExist = User::find($user->id);

        if (!empty($userExist)) {
            $response = FcmToken::create([
                'user_id' => $userExist->id,
                'token' => $request->fcm_token,
                'device_type' => $request->device_type ?? null
            ]);
        }
        
        return $this->successRespond($response, 'TokenSaved');
    }

    /**
     * To get authenticated user notifications
     * @param req :[Bearer Token]
     * @return res : [Auth user notification data]
     */
    public function getNotificationData()
    {
        $user = auth()->user();

        $notificationData = Notification::select("id", "user_id", "title", "description", "route_name", "created_at", "updated_at")->where('user_id', $user->id)->orderByDesc('created_at')->get();

        return $this->successRespond($notificationData, 'NotificationData');
    }

    /**
     * To delete authenticated user notification
     * @param req :[notification_id : []]
     * @return res : []
     */
    public function deleteNotification(Request $request)
    {
        $notificationIds = $request->all();
        $validator = Validator::make($notificationIds, [
            'notification_id' => 'required|array',
            'notification_id.*' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        foreach ($notificationIds as $notificationId) {
            $notificationExist = Notification::where('id', $notificationId)->first();

           if (!$notificationExist) {
                return $this->errorRespond('InvalidNotificationId', config('constants.CODE.badRequest'));
           }

           $notificationExist->destroy($notificationId);
        }

        return $this->successRespond( (object) [] ,'DeleteNotification');

    }

    /**
     * To mark a notification as read
     * @param req :[notification_id]
     * @return res : []
     */
    public function isReadNotification(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $notificationExist = Notification::where('id', $request->notification_id)->where('user_id', $user->id)->first();

        if (!$notificationExist)
        {
            return $this->errorRespond('InvalidNotificationId', config('constants.CODE.badRequest'));
        }

        $notificationExist->is_read = 1;
        $notificationExist->update();

        return $this->successRespond($notificationExist, 'NotificationIsReadUpdate');
    }
}