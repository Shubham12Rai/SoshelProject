<?php

namespace App\Http\Controllers\Dashboard;

use DateTime;
use App\Models\Event;
use App\Helpers\ApiHelper;
use App\Models\EventImage;
use App\Models\UsersEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use DB;

class EventManagementController extends ApiHelper
{
    public function eventManagementLoad()
    {
        $event = Event::all();
        return view('auth.dashboard.eventManagement', compact('event'));
    }

    public function eventAddView()
    {
        return view('auth.dashboard.eventAdd');
    }
    public function eventAdd(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'eventtitle' => 'required',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'countryCode' => 'required',
            'mobileNumber' => 'required|regex:/^[+]?[0-9]+([\-\\s]?[0-9]+)*$/',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'userType' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $planType = $request['userType'];

        if ($planType == "Standard") {
            $value = config('constants.PLAN_TYPE.Standard');
        } elseif ($planType == "Premium") {
            $value = config('constants.PLAN_TYPE.Premium');
        } elseif ($planType == "All") {
            $value = config('constants.PLAN_TYPE.All');
        }

        $event = new Event;
        $event->title = $request['eventtitle'];
        $event->description = $request['description'];
        $event->start_date = $request['from_date'];
        $event->end_date = $request['to_date'];
        $event->start_time = $request['from_time'];
        $event->end_time = $request['to_time'];
        $event->country_code = $request['countryCode'];
        $event->contact_no = $request['mobileNumber'];
        $event->venue_name = $request['venueName'];
        $event->address_on = $request['address'];
        $event->lat = $request['latitude'];
        $event->lon = $request['longitude'];
        $event->plan_id = $value;
        $event->save();
        $lastInsertedId = $event->id;

        $eventImage = $request['image'];
        $uploadedImages = $request->file('image');

        foreach ($eventImage as $index => $title) {
            $uploadedImage = $uploadedImages[$index];
            $filename = time() . '_' . $uploadedImage->getClientOriginalName();
            $s3FilePath = Storage::disk('s3')->putFileAs('manager/uploads/eventimages', $uploadedImage, $filename);

            $image = new EventImage;
            $image->event_id = $lastInsertedId;
            $image->image_path = $s3FilePath;
            $image->save();
        }

        return redirect()->back()->with('statusEvent', 'Event Added Sucessfully');
    }

    public function eventView($id)
    {
        $event = Event::with('event_images')
            ->where('id', $id)
            ->first();
        return view('auth.dashboard.eventView', compact('event'));
    }

    public function eventEdit($id)
    {
        $event = Event::with('event_images')
            ->where('id', $id)
            ->first();
        return view('auth.dashboard.eventEdit', compact('event'));
    }
    public function eventUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'eventtitle' => 'required',
            'description' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'countryCode' => 'required',
            'mobileNumber' => 'required|regex:/^[+]?[0-9]+([\-\\s]?[0-9]+)*$/',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'userType' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        foreach ($request->input('removed_images', []) as $removedImageId) {
            $removedImage = EventImage::find($removedImageId);
            if ($removedImage) {
                $removedImage->delete();
            }
        }

        $planType = $request['userType'];

        if ($planType == "Standard") {
            $value = config('constants.PLAN_TYPE.Standard');
        } elseif ($planType == "Premium") {
            $value = config('constants.PLAN_TYPE.Premium');
        } elseif ($planType == "In-app purchase") {
            $value = config('constants.PLAN_TYPE.In-app purchase');
        }

        $event = Event::find($id);
        $event->title = $request['eventtitle'];
        $event->description = $request['description'];
        $event->start_date = $request['from_date'];
        $event->end_date = $request['to_date'];
        $event->start_time = $request['from_time'];
        $event->end_time = $request['to_time'];
        $event->country_code = $request['countryCode'];
        $event->contact_no = $request['mobileNumber'];
        $event->venue_name = $request['venueName'];
        $event->address_on = $request['address'];
        $event->plan_id = $value;
        $event->lat = $request['latitude'];
        $event->lon = $request['longitude'];
        $event->save();

        $uploadedImages = $request->file('images', []);
        foreach ($request->file('images', []) as $index => $newImage) {
            $uploadedImage = $uploadedImages[$index];
            $filename = time() . '_' . $uploadedImage->getClientOriginalName();
            $s3FilePath = Storage::disk('s3')->putFileAs('manager/uploads/eventimages', $uploadedImage, $filename);

            $image = new EventImage;
            $image->event_id = $id;
            $image->image_path = $s3FilePath;
            $image->save();
        }
        return redirect()->back()->with('statusEvent', 'Event Update Sucessfully');
    }

    public function eventDelete($id)
    {
        $event = Event::find($id);
        $event->delete();
        $event = Event::all();
        return back()->withData($event);
    }
    public function eventFilter(Request $request)
    {
        $keyword = $request->input('keyword');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $event = Event::all();

        if ($keyword) {

            $event = Event::where('title', 'like', "%{$keyword}%")
                ->get();
            return view('auth.dashboard.eventManagement', [
                'event' => $event,
                'keyword' => $keyword
            ]);
        } elseif ($startDate && $endDate) {

            $event = DB::table('events')
                ->whereRaw('DATE(start_date) BETWEEN ? AND ?', [$startDate, $endDate])
                ->get();

            return response()->view('auth.dashboard.eventManagement', [
                'event' => $event,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
        }

        return view('auth.dashboard.eventManagement', [
            'event' => $event
        ]);

    }

}