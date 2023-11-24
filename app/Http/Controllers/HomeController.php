<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Models\Matchs;
use App\Models\WeMet;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = User::all();
        $event = Event::all();
        $match = Matchs::all();
        $weMet = WeMet::all();

        $timePeriod = $request->input('timePeriod');
        $startDate = $request->input('formDate');
        $endDate = $request->input('to');

        if ($startDate && $endDate) {
            $user = DB::table('users')
                ->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate])
                ->get();

        } elseif ($timePeriod === config('constants.TIME_PERIOD.today')) {
            $currentDate = \Carbon\Carbon::today();
            $user = User::whereDate('created_at', $currentDate)->get();

        } elseif ($timePeriod === config('constants.TIME_PERIOD.weekly')) {
            $currentDate = Carbon::now();
            $startWeek = $currentDate->startOfWeek()->toDateString();
            $endWeek = $currentDate->endOfWeek()->toDateString();
            $user = User::whereBetween('created_at', [$startWeek, $endWeek])->get();

        } elseif ($timePeriod === config('constants.TIME_PERIOD.monthly')) {

            $currentDate = \Carbon\Carbon::now();
            $monthly = $currentDate->month;
            $user = User::whereMonth('created_at', $monthly)->get();

        } elseif ($timePeriod === config('constants.TIME_PERIOD.yearly')) {
            $currentDate = \Carbon\Carbon::now();
            $year = $currentDate->year; // Get the current year
            $user = User::whereYear('created_at', $year)->get();
        }
        return response()->view('home', [
            'timePeriod' => $timePeriod,
            'user' => $user,
            'event' => $event,
            'match' => $match,
            'weMet' => $weMet,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

    }

}