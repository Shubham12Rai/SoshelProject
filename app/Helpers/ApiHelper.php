<?php

namespace App\Helpers;

use App\Models\FcmToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Mail;
use Twilio;
use Storage;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;
use App\Models\WeMet;
use App\Models\ProfileImage;
use App\Models\Notification;
use App\Models\VenueNotification;
use App\Models\ServiceGoogleDataSave;
use Intervention\Image\Facades\Image;
use SendGrid\Mail\Mail as SendGridMail;
use Illuminate\Encryption\Encrypter;


class ApiHelper
{
	public static function instance()
	{
		return new ApiHelper();
	}


	/**
	 * To add success response
	 * @param req :[]
	 * @param res : [success]
	 */
	public function successRespond($data, $message = null, $code = 200)
	{
		$message_string = 'message' . '.' . $message;
		$message = config($message_string);
		return $this->respond([
			'success' => true,
			'status_code' => $code,
			'message' => $message,
			// 'result'=> ( isset($data) && !empty($data) ) ? $data: $data
			'result' => $data
		], $code);
	}

	/**
	 * To add success response
	 * @param req :[]
	 * @param res : [success]
	 */
	public function success($data, $message = null, $code = 200)
	{
		$message_string = 'message' . '.' . $message;
		$message = config($message_string);
		return $this->respond([
			'success' => true,
			'status_code' => $code,
			'message' => $message,
			// 'result'=> ( isset($data) && !empty($data) ) ? $data: $data
			'data' => $data
		], $code);
	}

	public function alreadyLiked($message = null, $code = 200)
	{
		$message_string = 'message' . '.' . $message;
		$message = config($message_string);
		return $this->respond([
			'success' => true,
			'status_code' => $code,
			'message' => $message,
		], $code);
	}

	/**
	 * To add error response
	 * @param req :[]
	 * @param res : [error]
	 */
	public function errorRespond($message = null, $code = 400)
	{

		if (is_object($message)) {
			$data = json_decode(json_encode($message), true);
			$message = '';
			foreach ($data as $val) {
				$message .= $val[0];
			}
		} else {
			$message_string = 'message' . '.' . $message;
			$message_temp = $message;
			$message = config($message_string);
			if (empty($message) || $message == null) {
				$message = $message_temp;
			}
		}

		return $this->respond([
			'success' => false,
			'status_code' => $code,
			'message' => $message
		], $code);
	}

	public function respond($data, $code)
	{
		return response()->json($data, $code);
	}

	public function randomnumber($m, $n)
	{

		return random_int($m, $n);
	}


	public function validateUser(array $data)
	{
		$rules = [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|unique:users',
			'password' => 'required|string|min:8|confirmed',
			'phone_number' => ['required', 'integer', 'regex:/^[0-9]{10}$/'],

		];

		$messages = [
			'name.required' => 'The name field is required.',
			'email.required' => 'The email field is required.',
			'email.email' => 'The email must be a valid email address.',
			'email.unique' => 'The email has already been taken.',
			'password.required' => 'The password field is required.',
			'password.min' => 'The password must be at least 8 characters.',
			'password.confirmed' => 'The password confirmation does not match.',
			'phone_number.required' => 'The phone_number field is required.',
			'phone_number.integer' => 'The phone_number must be a numeric value.',
			'phone_number.regex' => 'The phone_number must be atleast 10 digits not more than 10',
		];

		return Validator::make($data, $rules, $messages);
	}

	public function getGoogleData($token)
	{
		try {
			$url = getenv('AUTH_GOOGLE_URL') . $token;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			$data = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($http_code === 200) {
				return json_decode($data, true);
			} else {
				throw new Exception('Failed to retrieve Google data');
			}
		} catch (Exception $e) {
			error_log($e->getMessage());
		}
	}

	public function getGooglePeople()
	{

		require_once 'vendor/autoload.php';

		$client = new Google_Client();
		$client->setClientId('YOUR_CLIENT_ID');
		$client->setClientSecret('YOUR_CLIENT_SECRET');
		$client->setRedirectUri('YOUR_REDIRECT_URI');
		$client->setAccessType('offline');

		// Set the access token received from Google
		$accessToken = 'YOUR_ACCESS_TOKEN';
		$client->setAccessToken($accessToken);

		// Check if the access token is expired
		if ($client->isAccessTokenExpired()) {
			$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			// Save the refreshed access token
			$newAccessToken = $client->getAccessToken();
		}

		$service = new Google_Service_People($client);

		// Get the user's profile information
		$person = $service->people->get('people/me', ['personFields' => 'phoneNumbers']);

		// Get the mobile number
		$phoneNumbers = $person->getPhoneNumbers();
		$mobileNumber = '';

		foreach ($phoneNumbers as $phoneNumber) {
			if ($phoneNumber->getType() === 'mobile') {
				$mobileNumber = $phoneNumber->getValue();
				break;
			}
		}
		foreach ($phoneNumbers as $phoneNumber) {
			if ($phoneNumber->getType() === 'mobile') {
				$mobileNumber = $phoneNumber->getValue();
				break;
			}
		}

		echo 'Mobile Number: ' . $mobileNumber;
	}

	public function getAppleData($token)
	{
		try {
			$token_parts = explode(".", $token);
			$token_payload = base64_decode($token_parts[1]);
			$jwt_payload = json_decode($token_payload);

			if (empty($jwt_payload)) {
				throw new Exception("Invalid Token!");
			}
			$expiration = $jwt_payload->exp;
			$is_token_expired = ($expiration - time()) < 0;

			if ($is_token_expired) {
				throw new Exception("Invalid Token!");
			}

			return $jwt_payload;
		} catch (Exception $e) {
			error_log($e->getMessage());
			throw $e;
		}
	}

	public function getInstagramData($access_token)
	{
		try {

			$apiUrl = getenv('AUTH_INSTA_URL');
			$apiUrl .= '&access_token=' . $access_token;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $apiUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$data = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($http_code === 200) {
				return json_decode($data, true);
			} else {
				throw new Exception('Failed to retrieve Google data');
			}
		} catch (Exception $e) {
			error_log($e->getMessage());
		}
	}

	/**
	 * To generate otp for Twilio implementation
	 * @param req :[]
	 * @param res : []
	 */
	public function generateOTP($phoneNumber)
	{
		$user = User::where('mobile_number', $phoneNumber)->first();

		$now = now();

		if ($user) {
			if ($now->isBefore($user->otp_expire_time)) {
				return $user;
			}
			return $user;
		}

		return User::create([
			'mobile_number' => $phoneNumber,
			'otp' => random_int(1234, 9999),
			'otp_expire_time' => $now->addMinutes(1),
		]);
	}

	/**
	 * To upload user profile photos to s3 bucket
	 * @param req :[]
	 * @param res : []
	 */
	public function uploadFile($file, $path)
	{
		if (strpos($path, 'manager') == false) {

			$path = 'manager/' . $path;
		}
		$timestamp = time();
		$name = $timestamp . '_' . $file->getClientOriginalName();

		$file_path = $path . $name;

		Storage::disk('s3')->put($file_path, file_get_contents($file), 'public');

		return $name;
	}

	/**
	 * To get users data within given radius
	 * @param req :[]
	 * @param res : []
	 */
	public function distanceCalculation($earthRadius, $radius, $latitude, $longitude)
	{
		return User::select("*")
			->selectRaw("{$earthRadius} * 2 * ASIN(SQRT(POWER(SIN(({$latitude} - ABS(latitude)) * pi()/180 / 2), 2) + COS({$latitude} * pi()/180) * COS(ABS(latitude) * pi()/180) * POWER(SIN(({$longitude} - longitude) * pi()/180 / 2), 2))) AS distance")
			->havingRaw("distance <= {$radius}")
			->orderBy('distance')
			->get();
	}

	public function referCodeGeneration()
	{
		$referCode = User::select('refer_code')->where('refer_code', '<>', '')->orderByDesc('id')->first();
		if (!$referCode) {
			return config('constants.REFER.REFER_CODE');
		}
		$referCode = $referCode->refer_code;
		$referCode = (int) ltrim($referCode, 'R');
		$referCode = $referCode + 1;
		if (strlen($referCode) == 1) {
			$referCode = 'R0000' . $referCode;
		} elseif (strlen($referCode) == 2) {
			$referCode = 'R000' . $referCode;
		} elseif (strlen($referCode) == 3) {
			$referCode = 'R00' . $referCode;
		} elseif (strlen($referCode) == 4) {
			$referCode = 'R0' . $referCode;
		} else {
			$referCode = 'R' . $referCode;
		}
		return $referCode;
	}

	/**
	 * calculate user distance
	 * @param req :[]
	 * @param res : []
	 */
	public function userDistanceCalculation($earthRadius, $latitude, $longitude)
	{
		return User::select("*")
			->selectRaw("{$earthRadius} * 2 * ASIN(SQRT(POWER(SIN(({$latitude} - ABS(latitude)) * pi()/180 / 2), 2) + COS({$latitude} * pi()/180) * COS(ABS(latitude) * pi()/180) * POWER(SIN(({$longitude} - longitude) * pi()/180 / 2), 2))) AS distance")
			// ->havingRaw("distance <= ?", [$radius])
			->orderBy('distance')
			->get();
	}

	/**
	 * send push notification
	 * @param req :[]
	 * @param res : []
	 */
	public function sendFirebasePush($userId, $title, $description, $route)
	{
		$tokens = FcmToken::where('user_id', $userId)->pluck('token')->toArray();
		foreach ($tokens as $token) {
			if (!$token) {
				return false;
			}
		}

		//decrypting server_key
		$serverKey = $this->decryptString(config('constants.PUSH_NOTIFICATION.server_key'));
		$notifyData = [
			'title' => $title,
			'body' => $description
		];
		$registrationIds = $tokens;
		if (count($tokens) > 1) {
			$fields = array(
				'registration_ids' => $registrationIds,
				// for multiple users
				'notification' => $notifyData,
				'data' => [
					'route' => $route
				],
				'priority' => 'high'
			);
		} else {
			$fields = array(
				'to' => $registrationIds[0],
				// for single user
				'notification' => $notifyData,
				'data' => [
					'route' => $route
				],
				'priority' => 'high'
			);
		}
		$headers = [
			'Authorization: key=' . $serverKey,
			'Content-Type: application/json',
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, config('constants.PUSH_NOTIFICATION.fcm_url'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);

		if ($result === FALSE) {
			$error = curl_error($ch);
			return $this->errorRespond('FCM Send Error: ' . $error, config('constants.CODE.badRequest'));
		}
		curl_close($ch);
		return $this->success($result, 'Push notification sent successfully');
	}

	/**
	 * To save notification (out side venue) data in db
	 * @param req :[]
	 * @param res : []
	 */
	public function saveNotification($userId, $title, $description, $type, $route)
	{
		$notification = Notification::create([
			'user_id' => $userId,
			'title' => $title,
			'description' => $description,
			'type' => $type,
			'route_name' => $route
		]);

		$notification->save();
	}

	/**
	 * To save venue notification data in db
	 * @param req :[]
	 * @param res : []
	 */
	public function saveVenueBaseNotification($userId, $title, $description, $route)
	{
		$notification = VenueNotification::create([
			'user_id' => $userId,
			'title' => $title,
			'description' => $description,
			'route_name' => $route
		]);

		$notification->save();
	}

	/**
	 * get all nearby venues for venue base swiping 
	 * @param req :[]
	 * @param res : []
	 */
	public function getAllNearByVenues($latitude, $longitude)
	{
		try {

			$maxRadius = config('constants.LOCATION.maxRadius');
			$earthRadius = config('constants.LOCATION.earthRadius');

			$haversine = "({$earthRadius} * acos(cos(radians(" . $latitude . ")) 
				* cos(radians(`latitude`)) 
				* cos(radians(`longitude`) 
				- radians(" . $longitude . ")) 
				+ sin(radians(" . $latitude . ")) 
				* sin(radians(`latitude`))))";

			$serviceGoogleData = ServiceGoogleDataSave::join('service_type', 'service_google_data_save.service_type_id', '=', 'service_type.id')
				->select('place_id', 'place_name', 'service_type.name')
				->whereRaw("{$haversine} < ?", [$maxRadius])
				->get();

			return $serviceGoogleData;

		} catch (Exception $e) {
			error_log($e->getMessage());
		}
	}

	/**
	 * get all nearby venue for polar map 
	 * @param req :[]
	 * @param res : []
	 */
	public function getAllNearByVenuesForPolorMap($latitude, $longitude, $radius)
	{
		try {
			$getData = [];

			$earthRadius = config('constants.LOCATION.earthRadius');

			$haversine = "({$earthRadius} * acos(cos(radians(" . $latitude . ")) 
				* cos(radians(`latitude`)) 
				* cos(radians(`longitude`) 
				- radians(" . $longitude . ")) 
				+ sin(radians(" . $latitude . ")) 
				* sin(radians(`latitude`))))";

			$serviceGoogleData = ServiceGoogleDataSave::join('service_type', 'service_google_data_save.service_type_id', '=', 'service_type.id')
				->select('place_id', 'place_name', 'vicinity', 'latitude', 'longitude', 'service_type.name')
				->whereRaw("{$haversine} < ?", [$radius])
				->get();

			return $serviceGoogleData;

		} catch (Exception $e) {
			error_log($e->getMessage());
		}
	}

	// public function curlFunction($url)
	// {
	// 	$ch = curl_init();
	// 	curl_setopt($ch, CURLOPT_URL, $url);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	// 	$data = curl_exec($ch);
	// 	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	// 	curl_close($ch);
	// 	$results = json_decode($data, true)['results'] ?? [];
	// 	return $results;
	// }

	/**
	 * To download and Retriv the images uploaded from the s3 bucket
	 * @param req :[]
	 * @param res : []
	 */
	public function profileImageUrl($imageUrl)
	{
		$configPath = config('constants.IMAGE_PATH.profile_photo');

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

		unlink(storage_path('app/' . $path));

		return Storage::disk('s3')->url($s3Path);

	}

	/**
	 * To send email using sendgrid
	 * @param req :[]
	 * @param res : []
	 */
	public function sendEmail($recipient, $subject, $message)
	{
		$email = new SendGridMail();
		$email->setFrom("sender@example.com", "Sender Name");
		$email->setSubject($subject);
		$email->addTo($recipient);
		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(config('services.sendgrid.api_key'));
		try {
			$response = $sendgrid->send($email);
			return true; // Email sent successfully
		} catch (Exception $e) {
			error_log($e->getMessage());
			return false; // Email not sent
		}
	}

	/**
	 * curl call for get all data
	 * @param req :[]
	 * @param res : []
	 */
	public function curlFunctionForCron($url)
	{
		$url = trim($url);
		\Log::info("google data $url");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		$data = curl_exec($ch);
		curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		\Log::info("curl data");
		\Log::info($data);
		return json_decode($data, true);
	}

	/**
	 * handling weMet inside and outside venue
	 * @param req :[]
	 * @param res : []
	 */
	public function handleWeMet($user, $toUser)
	{
		$fromUser = $user->id;
		$metExist = WeMet::where(function ($query) use ($fromUser, $toUser) {
			$query->where(function ($subQuery) use ($fromUser, $toUser) {
				$subQuery->where('from_user', $fromUser)->where('to_user', $toUser);
			})->orWhere(function ($subQuery) use ($fromUser, $toUser) {
				$subQuery->where('from_user', $toUser)->where('to_user', $fromUser);
			});
		})->first();
		$isExist = false;
		if ($metExist) {
			$isExist = true;
		} else {
			$newMet = WeMet::create([
				'from_user' => $fromUser,
				'to_user' => $toUser
			]);
		}

		return [
			'is_exist' => $isExist,
			'data' => $metExist ? (object) [] : $newMet
		];
	}

	/**
	 * handling get drink request inside and outside venue
	 * @param req :[]
	 * @param res : []
	 */
	public function handleGetdrinkRequest($drinkRequestData)
	{
		$responseData = array();
        foreach ($drinkRequestData as $data) {

            $requestedUserData = User::join('profile_images', 'profile_images.user_id', 'users.id')
                ->where('profile_images.user_id', $data->sender_user_id)
                ->select('profile_images.image_path', 'users.id', 'users.full_name')
                ->first();

            if ($requestedUserData) {
                $responseData[] = [
                    'id' => $data->id,
                    'request_user_id' => $requestedUserData->id,
                    'full_name' => $requestedUserData->full_name,
                    'image_path' => $requestedUserData->image_path,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at
                ];
            }
        }

		return $responseData;
	}

	/**
	 * handling get flash message request inside and outside venue
	 * @param req :[]
	 * @param res : []
	 */
	public function handleGetMessages($messages, $userId)
	{
		$result = [];

        foreach ($messages as $message) {
            $senderImage = ProfileImage::select('image_path')
                ->where('user_id', $message->sender_id)
                ->orderBy('created_at', 'asc')
                ->first();

            $senderId = $message->sender_id;

            $result[$senderId]["sender_id"] = $senderId;
            $result[$senderId]["full_name"] = $message->full_name;
            $result[$senderId]["receiver_id"] = $userId;
            $result[$senderId]["image_path"] = $senderImage->image_path;
            $result[$senderId]["message"][] = $message->message;
            $result[$senderId]["message_count"] = count($result[$senderId]["message"]);
            $result[$senderId]["created_at"] = $message->created_at;
        }

        $data = array_values($result);

        return $data;
	}

	/**
     * Custom encryption function using aes-128-cbc.
     *
     * @param  string  $value
     * @return string
     */
    public function encryptString($data) {
        $cipher = config('constants.ENCRYPT_DECRYPT_KEY.cipher');
        $options = 0;
        $key = config('constants.ENCRYPT_DECRYPT_KEY.key');
        $iv = config('constants.ENCRYPT_DECRYPT_KEY.iv');
        // $data=$request->input('data');
    
        $encrypted = openssl_encrypt($data, $cipher, $key, $options, $iv);
    
        if ($encrypted === false) {
            die('Encryption failed: ' . openssl_error_string());
        }
    
        return $encrypted;
    }

	/**
     * Custom decryption function using aes-128-cbc.
     *
     * @param  string  $value
     * @return string
     */
	public function decryptString($data) {
        $cipher = config('constants.ENCRYPT_DECRYPT_KEY.cipher');
        $options = 0;
        $key = config('constants.ENCRYPT_DECRYPT_KEY.key');
        $iv = config('constants.ENCRYPT_DECRYPT_KEY.iv');
        // $data=$request->input('data');
        $decrypted = openssl_decrypt($data, $cipher, $key, $options, $iv);
    
        if ($decrypted === false) {
            die('Decryption failed: ' . openssl_error_string());
        }
    
        return $decrypted;
    }


}