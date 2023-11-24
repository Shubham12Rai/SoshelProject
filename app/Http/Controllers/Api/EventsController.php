<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\EventJoinStatus;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Illuminate\Support\Facades\DB;


class EventsController extends ApiHelper
{
	/**
	 * To get event and join/not_interested status list data
	 * @param req :[event_date]
	 * @return res : [event and join/not_interested status list]
	 */
	public function getEventList(Request $request)
	{
		$user = auth()->user();

		$validator = Validator::make($request->all(), [
			'event_date' => 'required',
		]);

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}

		$eventDate = Carbon::createFromFormat('m-d-Y', $request->event_date)->format('Y-m-d');
		$dateTime = new DateTime($eventDate);
		$fullDayName = $dateTime->format('l');

		$events = Event::where('start_date', '<=', $eventDate)
			->where('end_date', '>=', $eventDate)
			->with('event_images')
			->select(
				'id',
				'title',
				'plan_id',
				'description',
				'start_date',
				'end_date',
				'address_on',
				'lat',
				'lon',
				'country_code',
				'contact_no',
				DB::raw("DATE_FORMAT(STR_TO_DATE(start_time, '%H:%i'), '%h:%i%p') as start_time"), 
        		DB::raw("DATE_FORMAT(STR_TO_DATE(end_time, '%H:%i'), '%h:%i%p') as end_time"), 
				'venue_name'
			)
			->get();

		$eventId = array();

		$eventId = $events->pluck('id')->toArray();

		$dataExist = EventJoinStatus::whereIn('event_id', $eventId)
			->where('user_id', $user->id)
			->select('event_id', 'user_id', 'join_status')
			->get();

		foreach ($events as $event) {
			$event->event_day = $fullDayName;

			$found = false;
			foreach ($dataExist as $data) {
				if ($event->id === $data->event_id) {
					$event->join_status = $data->join_status;
					$found = true;
				}
			}
			if ($found === false) {
				// If the element is not found in $dataExist, set the join_status to 0
				$event->join_status = 0;
			}
		}

		return $this->successRespond($events, 'EventList');
	}

	/**
	 * To react to the event of Join/Not_Interested or unjoin
	 * @param req :[event_id, event_join_status, event_unjoined]
	 * @return res : []
	 */
	public function eventJoinStatus(Request $request)
	{
		$user = auth()->user();

		$validator = Validator::make($request->all(), [
			'event_id' => 'required',
			'event_join_status' => 'required|in:0,1,2',
			'event_unjoined' => 'required|in:0,1',
		]);

		if ($validator->fails()) {
			return $this->errorRespond($validator->messages(), config('constants.CODE.badRequest'));
		}

		$eventData = [
			'event_id' => $request->event_id,
			'user_id' => $user->id,
			'join_status' => $request->event_join_status
		];

		if ($request->event_unjoined == 1 && !in_array($request->event_join_status, [1, 2])) {
			EventJoinStatus::where('event_id', $request->event_id)
				->where('user_id', $user->id)
				->delete();

			return $this->successRespond((object) [], 'EventUnjoined');
		}

		$dataExist = EventJoinStatus::where('event_id', $request->event_id)
			->where('user_id', $user->id)
			->first();

		if ($dataExist) {
			$dataExist->update(['join_status' => $request->event_join_status]);
			return $this->successRespond($dataExist, 'EventJoined');
		}

		$eventCreatedData = EventJoinStatus::create($eventData);

		return $this->successRespond($eventCreatedData, 'EventJoined');
	}

}
