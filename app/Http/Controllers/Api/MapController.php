<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MapController extends ApiHelper
{
	public function showUsersOnMap(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"lat" => 'required',
			"lon" => 'required',
			"radius" => 'required',
			"address" => 'required|string'
		]);

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}
		$user = Auth::user();

		$earthRadius = config('constants.LOCATION.earthRadius');
		$radius = $request->radius;


		$users = User::select("users.*")
			->selectRaw("{$earthRadius} * 2 * ASIN(SQRT(POWER(SIN(({$request->lat} - ABS(users.latitude)) * pi()/180 / 2), 2) + COS({$request->lat} * pi()/180) * COS(ABS(users.latitude) * pi()/180) * POWER(SIN(({$request->lon} - users.longitude) * pi()/180 / 2), 2))) AS distance")
			->where('users.id', '!=', $user->id)
			->havingRaw("distance <= {$radius}")
			->orderBy('distance')
			->get();
		if (!$users->isEmpty()) {
			$maleCount = $users->where('gender', 'Male')->count();
			$femaleCount = $users->where('gender', 'Female')->count();
			$activeUsers = $users->where('active_status', '1')->count();
			return $this->successRespond(["users" => $users, "male_count" => $maleCount, "female_count" => $femaleCount, "address" => $request->address, "active_users" => $activeUsers], 'Success');
		}
		return $this->successRespond($users, 'NoUserFound');
	}
}
