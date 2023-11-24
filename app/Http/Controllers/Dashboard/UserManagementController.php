<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GoingOut;
use App\Models\Music;
use App\Models\Pet;
use App\Models\Sport;
use App\Models\ProfileImage;
use App\Models\ReportedUser;
use App\Models\Report;
use DB;

class UserManagementController extends Controller
{
    public function userManagementLoad(Request $request)
    {
        $data = User::all();
        return view('auth.dashboard.userManagement', compact('data'));

    }

    public function userViewData($id)
    {
        $user = User::with('ethnicity', 'sexuality', 'datingIntention', 'educationStatus', 'user_going_out.going_out', 'user_musics.music', 'user_pets.pet', 'user_sports.sport', 'profileImage', )
            ->select('users.*')
            ->where('users.id', $id)
            ->first();

        $reportedUser = ReportedUser::where('to', $id)->get();
        $reportedByUsers = [];
        foreach ($reportedUser as $report) {

            $reportedById = $report->from;
            $reportedUsers = ReportedUser::join('reports', 'reported_users.reasons_id', '=', 'reports.id')
                ->where('reported_users.from', $reportedById)
                ->where('reported_users.to', $id)
                ->with('user')
                ->get(['reported_users.*', 'reports.name as reason']);
            $reportedByUsers[] = $reportedUsers;

        }
        $profileImageCount = $user->profileImage->count();
        return response()->view('auth.dashboard.userView', [
            'user' => $user,
            'reportedUser' => $reportedUser,
            'reportedByUsers' => $reportedByUsers,
            'profileImageCount' => $profileImageCount,
        ]);
    }
    public function blockUser($id)
    {
        $user = User::find($id);
        $user->active_status = 4;
        $user->save();
        $data = User::all();
        return back()->withData($data);
    }
    public function unblockUser($id)
    {
        $user = User::find($id);
        $user->active_status = 1;
        $user->save();
        $data = User::all();
        return back()->withData($data);
    }
    public function filter(Request $request)
    {
        $status = $request->get('status');
        $keyword = $request->input('keyword');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = $request->input('user');
        $request->session()->forget('keyword', $keyword);
        $request->session()->forget('status', $status);
        // $data = User::paginate(10);
        $data = User::all();


        if ($request->get('status')) {
            $status = $request->get('status');
            $request->session()->put('status', $status);
        }
        if ($startDate && $endDate) {
            $data = DB::table('users')
                ->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate])
                ->get();


            return response()->view('auth.dashboard.userManagement', [
                'data' => $data,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
        } elseif ($status == "active") {

            $data = User::where('active_status', 1)
                ->get();


            return view('auth.dashboard.userManagement')->withData($data);
        } elseif ($status == "blocked") {

            $data = User::where('active_status', 0)
                ->get();


            return view('auth.dashboard.userManagement')->withData($data);

        } elseif ($keyword) {

            $data = User::where('full_name', 'like', "%{$keyword}%")
                ->get();

            $request->session()->put('keyword', $keyword);
            return view('auth.dashboard.userManagement', [
                'data' => $data,
                'keyword' => $keyword
            ]);
            // return response()->json($data);

        } elseif ($user === "standard User") {

            $data = User::where('plan_id', 1)
                ->get();

            return response()->view('auth.dashboard.userManagement', [
                'data' => $data,
                'user' => $user

            ]);
        } elseif ($user === "premium User") {

            $data = User::where('plan_id', 2)
                ->get();

            return response()->view('auth.dashboard.userManagement', [
                'data' => $data,
                'user' => $user

            ]);
        } elseif ($user === "all User") {

            $data = User::all();

            return response()->view('auth.dashboard.userManagement', [
                'data' => $data,
                'user' => ''

            ]);
        }

        return view('auth.dashboard.userManagement', [
            'data' => $data
        ]);

    }

}