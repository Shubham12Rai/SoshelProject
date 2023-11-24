<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\DeleteAccountReason;
use App\Models\VenueFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SettingsController extends ApiHelper
{
	/**
     * To add authenticated user email id
     * @param req :[email]
     * @return res : []
     */
	public function addEmail(Request $request)
	{
		$user = Auth::user();
		$userId = $user->id;
		$validator = Validator::make($request->all(), [
			"email" => 'required|email|unique:users,email_id,' . $userId,
		]);

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}
		$user->refresh();
		if (trim($request->email) == trim($user->email_id)) {
			return $this->errorRespond(config('message.EmailIsAlreadyDefined'), config('constants.CODE.badRequest'));
		}

		$randomNumber = random_int(100000, 999999);
		$user = User::where('id', $userId)->update(
			[
				"email_id" => $request->email,
				"email_otp" => $randomNumber,
				'email_otp_verified_status' => '0',
				'email_otp_expire_time' => now()->addMinutes(config('constants.EMAIL_VERIFY.expiryTime'))
			]
		);
		$recipient = $request->input('email');
		$subject = "Subject of the Email";
		$message = "Your OTP for email verification is  $randomNumber";

		// Mail::send([], [], function ($email) use ($recipient, $subject, $message) {
		// 	$email->to($recipient)
		// 		->subject($subject)
		// 		->setBody($message, 'text/plain');
		// });

		$emailSent = $this->sendEmail($recipient, $subject, $message);

        if ($emailSent) {
            return $this->successRespond((Object) [], 'OtpSentOnEmail');
        } else {
            return $this->errorRespond("EmailNotSent", config('constants.CODE.badRequest'));
        }
	}

	/**
     * To verify Email otp
     * @param req :[otp]
     * @return res : []
     */
	public function verifyEmail(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"otp" => 'required|integer',
		]);

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}
		$user = Auth::user();
		$userId = $user->id;
		// check for email otp expire time
		// echo $user->email_otp_expire_time.'       now  '.now();die;
		if (now() > $user->email_otp_expire_time) {
			return $this->errorRespond(config('message.OtpTimeExpired'), config('constants.CODE.badRequest'));
		}

		$result = User::where(['id' => $userId, 'email_otp' => $request->otp])->first();
		if ($result) {
			$user = User::where(['id' => $userId])->update(['email_otp_verified_status' => 1, "email_otp" => null]);
			return $this->successRespond($user, 'EmailVerifiedSuccessfully');
		}
		return $this->errorRespond(config('message.WrongOTP'), config('constants.CODE.badRequest'));
	}

	/**
     * To toggle on/off notification_status and incognito_mode_status
     * @param req :[notification_status (1-on/0-off), incognito_mode_status(1-off/0-on)]
     * @return res : []
     */
	public function updateNotificationToggle(Request $request)
	{
		$user = Auth::user();
		$userId = $user->id;
		$validator = Validator::make($request->all(), [
			"notification_status" => 'nullable|integer|in:1,0',
			"incognito_mode_status" => 'nullable|integer|in:1,0'
		]);

		$notification = $request->notification_status === null ? $user->notification_status : $request->notification_status;
		// $incognitoModeStatus = $user->incognito_mode_status;
		// if ($user->gender != "Male") {
		$incognitoModeStatus = $request->incognito_mode_status === null ? $user->incognito_mode_status : $request->incognito_mode_status;
		// }

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}

		User::where('id', $userId)->update([
			'notification_status' => $notification,
			'incognito_mode_status' => $incognitoModeStatus
		]);

		$notificationAndIncognitoStatus = User::where('id', $userId)->first();

		$response['notification_status'] = $notificationAndIncognitoStatus->notification_status;
		$response['incognito_mode_status'] = $notificationAndIncognitoStatus->incognito_mode_status;
		return $this->successRespond($response, 'NotificationToggleUpdate');

	}

	/**
     * To get authenticated user setting screen data
     * @param req :[]
     * @return res : [
	 * 		"latitude",
	 *		"longitude",
	 *		"is_email_exist",
	 *		"incognito_status",
	 *		"notification_status",
	 *		"auth_gender"
	 *    ]
     */
	public function getSettingData()
	{
		$user = Auth::user();

		$response = [
			'latitude' => $user->latitude,
			'longitude' => $user->longitude,
			'is_email_exist' => $user->email_id ? 1 : 0,
			'incognito_status' => $user->incognito_mode_status,
			'notification_status' => $user->notification_status,
			'auth_gender' => $user->gender

		];

		return $this->successRespond($response, 'SettingData');
	}

	/**
     * To delete authenticated user account from Soshel with reason
     * @param req :[reason_id, other_reason]
     * @return res : []
     */
	public function deleteAccount(Request $request)
	{
		$user = Auth::user();
		$userId = $user->id;

		$validator = Validator::make($request->all(), [
			"reason_id" => 'required|integer|min:1',
			"other_reason" => 'nullable'
		]);

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}

		VenueFeedback::where('user_id', $userId)->update(['user_id' => 0]);

		$DeleteAccountReason = new DeleteAccountReason;
		$DeleteAccountReason->reason_id = $request->reason_id ? $request->reason_id : null;
		$DeleteAccountReason->other_reason = $request->other_reason != "" ? $request->other_reason : null;
		$DeleteAccountReason->save();

		$user->user_musics()->delete();
		$user->user_sports()->delete();
		$user->user_language_spoken()->delete();
		$user->user_pets()->delete();
		$user->user_going_out()->delete();
		$user->profileImage()->delete();
		$user->notification()->delete();
		$user->fcmToken()->delete();
		$user->reportedUsersFrom()->delete();
		$user->reportedUsersTo()->delete();
		$user->blockUsersFrom()->delete();
		$user->blockUsersTo()->delete();
		$user->chatSender()->delete();
		$user->chatReciever()->delete();
		$user->drinkRecieverUser()->delete();
		$user->drinkSenderUser()->delete();
		$user->likedFrom()->delete();
		$user->likedTo()->delete();
		$user->matchRecieverUser()->delete();
		$user->matchSenderUser()->delete();
		$user->UserLikeInsideVenuesFrom()->delete();
		$user->UserLikeInsideVenuesTo()->delete();
		$user->venueDrinkRecieverUser()->delete();
		$user->venueDrinkSenderUser()->delete();
		$user->venueFlashSender()->delete();
		$user->venueFlashReciever()->delete();
		$user->venueNotification()->delete();
		$user->checkInUserInsideVenue()->delete();

		$userData = User::where('id', $userId)->delete();

		return $this->successRespond($userData, 'AccountDeleted');
	}

	/**
     * To contact Soshel admin via Email id
     * @param req :[user_email_id, query]
     * @return res : []
     */
	public function contactUs(Request $request)
	{

		$validator = Validator::make($request->all(), [
			"user_email_id" => 'required|email',
			"query" => 'required'
		]);

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}
		$query = $request->input('query');
		$userEmailId = $request->input('user_email_id');
		$recipient = "knprai1999@gmail.com";
		$subject = "User query";
		$message = "User email id : ".$userEmailId."\n"."\n". "User query is : "."$query";

		Mail::send([], [], function ($email) use ($recipient, $subject, $message) {
			$email->to($recipient)
				->subject($subject)
				->setBody($message, 'text/plain');
		});

		return $this->successRespond((Object) [], 'QuerySent');

	}
}