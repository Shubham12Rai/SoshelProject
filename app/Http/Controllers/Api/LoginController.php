<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\Subscription;
use DateTime;
use App\Models\User;
use App\Models\ProfileImage;
use App\Models\FcmToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class LoginController extends ApiHelper
{
	public function __construct()
	{
		//Not in use
	}


	/**
	 * To login customer with req
	 * @param req :[email, password]
	 * @return res : [customer data object with token]
	 */
	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'phone_number' => ['required'],
		]);
		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		} else {

			$user = User::where('mobile_number', '=', $request->phone_number)->first();
			$otp = 1234;
			$details = array();
			// OTP send start
			// $new_user = $this->generateOTP($request->phone_number);
			// $new_user->sendSMS($request->phone_number);
			// return $this->successRespond($new_user, 'OTPsent');
			// end

			if (!empty($user)) {
				User::where("id", $user->id)->update(['otp' => $otp, 'otp_expire_time' => now()]);
				$details['user_exist'] = 1;
				return $this->successRespond($details, 'OtpSent');
			} else {

				if (!empty($request->google_token || ($request->apple_token))) {
					if (!empty($request->google_token)) {
						$googleData = $this->getGoogleData($request->google_token);

						if (!empty($googleData)) {
							$user_data = User::create([
								'email_id' => $googleData['email'],
								'google_id' => $googleData['sub'],
								'full_name' => $googleData['given_name'],
								'mobile_number' => $request->phone_number,
								'otp' => $otp,
								'otp_expire_time' => now()
							]);
							// refer
							$update['refer_code'] = $this->referCodeGeneration();
							if ($request->has('refer_code')) {
								$update['referred_by'] = $request->refer_code;
								$referred_user = User::where('refer_code', $request->refer_code)->first();
								$referred_user_update['refer_count'] = $referred_user->refer_count + 1;
								if (($referred_user->refer_count + 1) % 5 == 0) {
									$referred_user_update['subscription_earned'] = $referred_user->subscription_earned + 1;
									$this->subscriptionEarned($referred_user->id);
								}
								User::where('id', $referred_user->id)->update($referred_user_update);
							}
							User::where('id', $user_data->id)->update($update);
						} else {
							return $this->errorRespond('NotValidUser', config('constants.CODE.badRequest'));
						}
					}
					if (!empty($request->apple_token)) {
						$appleData = $this->getAppleData($request->apple_token);
						if (!empty($appleData)) {
							$user_data = User::create([
								'email_id' => $appleData->email,
								'apple_id' => $appleData->sub,
								'mobile_number' => $request->phone_number,
								'otp' => $otp,
								'otp_expire_time' => now()
							]);
							// refer
							$update['refer_code'] = $this->referCodeGeneration();
							if ($request->has('refer_code')) {
								$update['referred_by'] = $request->refer_code;
								$referred_user = User::where('refer_code', $request->refer_code)->first();
								$referred_user_update['refer_count'] = $referred_user->refer_count + 1;
								if (($referred_user->refer_count + 1) % 5 == 0) {
									$referred_user_update['subscription_earned'] = $referred_user->subscription_earned + 1;
									$this->subscriptionEarned($referred_user->id);
								}
								User::where('id', $referred_user->id)->update($referred_user_update);
							}
							User::where('id', $user_data->id)->update($update);
						} else {
							return $this->errorRespond('NotValidUser', config('constants.CODE.badRequest'));
						}
					}
				} else {
					$user_data = User::create([
						'mobile_number' => $request->phone_number,
						'otp' => $otp,
						'otp_expire_time' => now()
					]);
					// refer
					$update['refer_code'] = $this->referCodeGeneration();
					if ($request->has('refer_code')) {
						$update['referred_by'] = $request->refer_code;
						$referred_user = User::where('refer_code', $request->refer_code)->first();
						$referred_user_update['refer_count'] = $referred_user->refer_count + 1;
						if (($referred_user->refer_count + 1) % 5 == 0) {
							$referred_user_update['subscription_earned'] = $referred_user->subscription_earned + 1;
							$this->subscriptionEarned($referred_user->id);
						}
						User::where('id', $referred_user->id)->update($referred_user_update);
					}
					User::where('id', $user_data->id)->update($update);
				}
				$details['user_exist'] = 0;
				return $this->successRespond($details, 'SuccessRegistration');
			}
		}
	}
	/**
	 * To verify otp with req
	 * @param req :[verification_code,mobile]
	 * @return res : [customer data with token]
	 */
	public function verifyOtp(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'phone_number' => ['required'],
			'verification_code' => 'required|numeric|digits:4',
		]);
		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}
		$user = User::where('mobile_number', '=', $request->phone_number)->first();
		if (!empty($user)) {
			$date = new DateTime($user->otp_expire_time);
			$now = new DateTime();
			$differTime = date_diff($date, $now);
			if ($differTime->i < getenv('OTP_EXPIRE_TIME')) {
				if ($user->otp == $request->input('verification_code')) {
					// User::where("id", $user->id)->update(['otp_verified_status' => 1]);
					$data = User::where('mobile_number', '=', $request->phone_number)->first();
					$data->token = auth('api')->tokenById($user->id);

					$userImg = ProfileImage::where('user_id', $user->id)->exists();
					$data->is_profile_exist = $userImg ? 1 : 0;

					return $this->successRespond($data, 'OtpVerified');
				} else {
					return $this->errorRespond('OtpNotMatch', config('constants.CODE.badRequest'));
				}
			} else {
				return $this->errorRespond('OtpTimeExpired', config('constants.CODE.badRequest'));
			}
		} else {
			return $this->errorRespond('NotValidMobile', config('constants.CODE.badRequest'));
		}
	}
	/**
	 * To send otp to customer on forgot/resend/
	 * @param req :[mobile]
	 * @return res : [username data]
	 */
	public function resendOtp(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'phone_number' => ['required'],
		]);
		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}
		$otp = 1234;
		$user = User::where('mobile_number', '=', $request->phone_number)->first();
		if (!empty($user)) {
			$currentTime = new DateTime();
			if ($user) {
				// OTP send start
				// $new_user = $this->generateOTP($request->phone_number);
				// $new_user->sendSMS($request->phone_number);
				// return $this->successRespond($new_user, 'OTPsent');
				// end
				User::where("id", $user->id)->update(['otp' => $otp, 'otp_expire_time' => $currentTime]);
				$data = User::where('mobile_number', '=', $request->phone_number)->first();
				return $this->successRespond($data, 'OtpSent');
			} else {
				return $this->errorRespond('NotValidMobile', config('constants.CODE.badRequest'));
			}
		}
	}

	/**
	 * To add email for account recovery 
	 * @param req :[email_id]
	 * @return res : []
	 */
	public function emailRecovery(Request $request)
	{
		$user = auth('api')->user();
		if ($user) {
			$validator = Validator::make($request->all(), [
				'email_id' => 'required|email'
			]);
			if ($validator->fails()) {
				return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
			}
			$emailExist = User::where('email_id', '=', $request->email_id)->first();
			if (!empty($emailExist)) {
				return $this->errorRespond('userExists', config('constants.CODE.badRequest'));
			} else {
				$otp = 1234;
				User::where("id", $user->id)->update(['email_id' => $request->email_id, 'otp' => $otp, 'otp_expire_time' => now()]);
				$responseData = User::where('id', $user->id)->first();
				return $this->successRespond($responseData, 'SuccessRegistration');
			}
		} else {
			return $this->errorRespond('tokenInvalid', config('constants.CODE.unauthorized'));
		}
	}

	/**
	 * To accept terms&condition
	 * @param req :[email_id, phone_number]
	 * @return res : []
	 */
	public function termsAndCondition(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email_id' => ['nullable', 'email']
		]);
		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}
		if (!empty($request->phone_number)) {
			$userExist = User::where('mobile_number', '=', $request->phone_number)->first();
		} elseif (!empty($request->email_id)) {
			$userExist = User::where('email_id', '=', $request->email_id)->first();
		} else {
			return $this->errorRespond('PhoneAndEmailRequired', config('constants.CODE.badRequest'));
		}
		if (!empty($userExist)) {
			User::where("id", $userExist->id)->update(['terms_condition' => 1]);
			$responseData = User::select('id', 'terms_condition')->where('id', '=', $userExist->id)->first();
			return $this->successRespond($responseData, 'SuccessTerms');
		} else {
			return $this->errorRespond('NotValidUser', config('constants.CODE.badRequest'));
		}
	}

	/**
	 * To accept privacy policy
	 * @param req :[email_id, phone_number]
	 * @return res : []
	 */
	public function privacyPolicy(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email_id' => ['nullable', 'email']
		]);
		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}
		if (!empty($request->phone_number)) {
			$userExist = User::where('mobile_number', '=', $request->phone_number)->first();
		} elseif (!empty($request->email_id)) {
			$userExist = User::where('email_id', '=', $request->email_id)->first();
		} else {
			return $this->errorRespond('PhoneAndEmailRequired', config('constants.CODE.badRequest'));
		}
		if (!empty($userExist)) {
			User::where("id", $userExist->id)->update(['privacy_policy' => 1]);
			$responseData = User::select('id', 'privacy_policy')->where('id', '=', $userExist->id)->first();
			return $this->successRespond($responseData, 'SuccessPrivacy');
		} else {
			return $this->errorRespond('NotValidUser', config('constants.CODE.badRequest'));
		}
	}

	private function subscriptionEarned($user_id)
	{
		$date = date('Y-m-d H:i:s');
		$today = Carbon::now();
		$oneMonthLater = $today->clone()->addMonth();
		$Subscription = new Subscription();
		$Subscription->user_id = $user_id;
		$Subscription->plan_id = config('constants.PLAN_TYPE.PREMIUM');
		$Subscription->start_date = $today;
		$Subscription->end_date = $oneMonthLater;
		$Subscription->status = '1';
		$Subscription->created_at = $date;
		$Subscription->updated_at = $date;
		$Subscription->created_by = $user_id;
		$Subscription->updated_by = $user_id;
		$Subscription->save();
	}

	/**
	 * To log out user
	 * @param req :[]
	 * @return res : []
	 */
	public function logout(Request $request)
	{
		$user = auth()->user();

		$token = $request->bearerToken();
		FcmToken::where('user_id', $user->id)->delete();
		FacadesJWTAuth::setToken($token)->invalidate();
		return $this->successRespond((Object) [],'LogOutSuccessfully');
	}
}
