<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\UsersInsideVenues;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class PolorMapController extends ApiHelper
{
    /**
     * To get lists of all venues for polar-map
     * @param req :[radius]
     * @return res : [lists of all venues as per given radius or default radius 10km]
     */
    public function getPolorMapData(Request $request)
    {
        $user = auth()->user();
        $latitude = $user->latitude;
        $longitude = $user->longitude;
        $authLat = $user->latitude;
        $authLong = $user->longitude;

        $validator = Validator::make($request->all(), [
            'radius' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
        }

        $radius = $request->radius ? $request->radius : config('constants.LOCATION.polarMapDefaultRadius');

        $data = $this->getAllNearByVenuesForPolorMap($latitude, $longitude, $radius);
        $getData = [];
        $i = 0;
        $addedPlaceIds = [];

        foreach ($data as $value1) {
            $placeId = $value1['place_id'];

            // Check if this place_id has already exist
            if (!in_array($placeId, $addedPlaceIds)) {
                $checkedInVenueData = UsersInsideVenues::where('place_id', $placeId)
                    ->where('status', 1)
                    ->with('user')
                    ->get();

                // Calculate distance using Haversine formula
                $earthRadius = config('constants.LOCATION.earthRadius');
                $distance = User::selectRaw("{$earthRadius} * 2 * ASIN(SQRT(POWER(SIN(({$value1['latitude']} - ABS(latitude)) * pi()/180 / 2), 2) + COS({$value1['latitude']} * pi()/180) * COS(ABS(latitude) * pi()/180) * POWER(SIN(({$value1['longitude']} - longitude) * pi()/180 / 2), 2))) AS distance")
                    ->where('id', $user->id)
                    ->first()
                    ->distance;

                $countGenderMale = $checkedInVenueData->where('user.gender', 'Male')->count();
                $countGenderFemale = $checkedInVenueData->where('user.gender', 'Female')->count();

                $getData[$i]['name'] = $value1['place_name'];
                $getData[$i]['address'] = $value1['vicinity'];
                $getData[$i]['place_id'] = $placeId;
                $getData[$i]['latitude'] = $value1['latitude'];
                $getData[$i]['longitude'] = $value1['longitude'];
                $getData[$i]['type'] = $value1['name'];
                $getData[$i]['distnce'] = $distance <= 0.100 ? abs(number_format($distance, 2)) : 1;     //
                $getData[$i]['active_user'] = count($checkedInVenueData);
                $getData[$i]['male_female_ratio'] = $countGenderMale . ' : ' . $countGenderFemale;
                $i++;
                $addedPlaceIds[] = $placeId;
            }
        }

        $response = [
            'auth_lat' => $authLat,
            'auth_long' => $authLong,
            'venue_count' => count($addedPlaceIds),
            'data' => $getData
        ];
        return $this->successRespond($response, 'Success');
    }
}