<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LocationController extends ApiHelper
{

	/**
	 * To setup user account details based on login user token
	 * @param req :[latitude, longitude]
	 * @return res : [user data with his latitude and longitude values]
	 */
	public function locationDetail(Request $request)
	{
		$user = auth()->user();
		$validator = Validator::make($request->all(), [
			'longitude' => 'required',
			'latitude' => 'required'
		]);

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}

		$updatedData = User::select("*")->where("id", $user->id)
			->update(['longitude' => $request->longitude, 'latitude' => $request->latitude]);

		return $this->successRespond($updatedData, 'LocationSave');
	}
}
