<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\GetData;
use App\Helpers\ApiHelper;
use DB;

class SocialLoginController extends ApiHelper
{

    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        if ($request->input('type') == config('constants.LOGIN_TYPE.google')) {
            return $this->googleLogin($request);

        } else if ($request->input('type') == config('constants.LOGIN_TYPE.instagram')) {

            return $this->instagramLogin($request);

        } else if ($request->input('type') == config('constants.LOGIN_TYPE.apple')) {

            return $this->appleLogin($request);

        } else {
            return $this->errorRespond('invalidLogin', config('constants.CODE.badRequest'));
        }
    }

    public function googleLogin(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'social_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $googleData = $this->getGoogleData($request->social_id);

        if (!empty($googleData)) {
            $oldUser = DB::table('users')
                ->where('google_id', $googleData['sub'])
                ->orWhere('email_id', $googleData['email'])
                ->first();

            $return_data = (object) [
                'user_exist' => 0,
                'type' => 1,
                'user_data' => (object) []
            ];

            if ($oldUser) {

                $oldUser->token = auth('api')->tokenById($oldUser->id);
                $return_data->type = 1;
                $return_data->user_data = $oldUser;
                $return_data->user_exist = 1;

            }

            return $this->successRespond($return_data, 'GoogleSucessLogin');
        } else {
            return $this->errorRespond('TokenInvalid', config('constants.CODE.badRequest'));
        }
    }

    public function appleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $appleData = $this->getAppleData($request->social_id);
        
        if (!empty($appleData)) {

            if (empty($appleData->email)) {

                return $this->errorRespond('AppleEmailNotFound', config('constants.CODE.badRequest'));

            } else {

                $oldUser = DB::table('users')
                    ->where('apple_id', $appleData->sub)
                    ->orWhere('email_id', $appleData->email)
                    ->first();

                $return_data = (object) [
                    'user_exist' => 0,
                    'type' => 3,
                    'user_data' => (object) []
                ];

                if ($oldUser) {

                    $oldUser->token = auth('api')->tokenById($oldUser->id);
                    $return_data->user_data = $oldUser;
                    $return_data->type = 3;
                    $return_data->user_exist = 1;

                }
                return $this->successRespond($return_data, 'AppleSucessLogin');
            }
        } else {
            return $this->errorRespond('NotValidUser', config('constants.CODE.badRequest'));
        }
    }

    public function instagramLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }
        $instagramData = $this->getInstagramData($request->social_id);

        if (!empty($instagramData)) {

            if (empty($instagramData['id'])) {

                return $this->errorRespond('InstagramDataNotFound', config('constants.CODE.badRequest'));

            } else {

                $oldUser = User::where('instagram_id', $instagramData['id'])->first();
                if ($oldUser) {
                    $oldUser->token = auth('api')->tokenById($oldUser->id);
                    $oldUser->user_exist = 1;

                    return $this->successRespond($oldUser, 'InstagramSucessLogin');

                } else {
                    $checkUser = User::where('full_name', $instagramData['username'])->first();

                    if ($checkUser) {

                        User::where('id', $checkUser->id)->update(['instagram_id' => $instagramData['id']]);
                        $updatedUser = User::where('full_name', $instagramData['username'])
                            ->first();
                        $updatedUser->token = auth('api')->tokenById($updatedUser->id);
                        $updatedUser->user_exist = 1;

                        return $this->successRespond($updatedUser, 'InstagramSucessLogin');

                    } else {
                        $newUser = User::create([
                            'full_name' => $instagramData['username'],
                            'instagram_id' => $instagramData['id']
                        ]);

                        $newUser->token = auth('api')->tokenById($newUser->id);
                        $newUser->user_exist = 0;

                        return $this->successRespond($newUser, 'InstagramSucessSignup');
                    }
                }
            }
        } else {
            return $this->errorRespond('notValidUser', config('constants.CODE.badRequest'));
        }
    }


    public function getInstaToken(Request $request)
    {

        $data = $request->all();
        $token = json_encode($data);
        $detail = (object) [];
        GetData::create([
            'data' => $token
        ]);
        return $this->successRespond($detail, 'DataSaved');

    }



}