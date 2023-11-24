<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\FcmToken;
use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Validator;
use DB;

class NotificationManagementController extends Apihelper
{
    public function notificationManagementLoad()
    {
        $detail = Notification::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('notification')
                ->whereNotNull('group_message_id')
                ->groupBy('group_message_id');
        })->get();

        return view('auth.dashboard.notificationManagement', compact('detail'));
    }
    public function notificationAddView()
    {
        return view('auth.dashboard.notificationAdd');
    }
    public function notificationAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'selectUser' => 'required',
            'userType' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $selectedUser = $request['selectUser'];
        $userType = $request['userType'];
        $title = $request['title'];
        $description = $request['description'];
        $notificationType = $request['selectUserAll'];
        $users = [];
        $route = "";

        foreach ($selectedUser as $selectedUsers) {
            $userId = User::where('id', $selectedUsers)->get();
            if ($userId) {
                $users[] = $userId->toArray();
            }
        }

        //For making group_message_id
        $groupId = Notification::orderBy('id', 'desc')
            ->limit(1)
            ->whereNotNull('group_message_id')
            ->value('group_message_id');

        $ids = explode('group_', $groupId);

        if (count($ids) > 1) {
            $numericpPart = intval($ids[1]);
            $groupMsgIid = 'group_' . ($numericpPart + 1);
        } else {
            $groupMsgIid = 'group_1';
        }

        foreach ($users as $userData) {

            $token = FcmToken::Where('user_id', '=', $userData[0]['id'])->first();

            if ($token) {
                $this->sendFirebasePush($userData[0]['id'], $title, $description, $route);
            } else {
                return redirect()->back()->with('statusToken', 'Token key Not Exist');
            }
            $notification = new Notification;
            $notification->title = $title;
            $notification->description = $description;
            $notification->user_id = $userData[0]['id'];
            $notification->selected_user = $userData[0]['full_name'];
            $notification->user_type = $userType;
            if ($notificationType == 1) {
                $notification->type = config('constants.NOTIFICATION_TYPE.all');
            }
            if ($notificationType == 0) {
                $notification->type = config('constants.NOTIFICATION_TYPE.selectedUser');
            }
            $notification->group_message_id = $groupMsgIid;
            $notification->save();
        }
        return redirect()->back()->with('statusNotification', 'Notification Sent Sucessfully');
    }
    public function listingUser($selectedUserType)
    {
        if ($selectedUserType == "Standard") {
            $type = config('constants.PLAN_TYPE.Standard');
        } elseif ($selectedUserType == "Premium") {
            $type = config('constants.PLAN_TYPE.Premium');
        }
        if ($selectedUserType == "All") {
            $users = User::all();
            return response()->json($users);
        }
        $users = User::where('plan_id', $type)->get();
        return response()->json($users);
    }
    public function notificationView($id)
    {
        $notifications = Notification::select('group_message_id', 'description', 'title', 'created_at', 'user_type', 'type', DB::raw('GROUP_CONCAT(selected_user) as user_list'))
            ->groupBy('group_message_id', 'title', 'user_type', 'created_at', 'type', 'description')
            ->whereNotNull('group_message_id')
            ->Where('group_message_id', '=', $id)
            ->get();

        return view('auth.dashboard.notificationView', compact('notifications'));
    }
    public function notificationFilter(Request $request)
    {
        $keyword = $request->input('keyword');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $detail = Notification::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('notification')
                ->whereNotNull('group_message_id')
                ->groupBy('group_message_id');
        })->get();

        if ($keyword) {

            $detail = Notification::whereIn('id', function ($query) use ($keyword) {
                $query->selectRaw('MIN(id)')
                    ->from('notification')
                    ->whereNotNull('group_message_id')
                    ->where('title', 'like', "%{$keyword}%")
                    ->groupBy('group_message_id');
            })->get();
            return view('auth.dashboard.notificationManagement', [
                'detail' => $detail,
                'keyword' => $keyword
            ]);
        } elseif ($startDate && $endDate) {

            $detail = Notification::whereIn('id', function ($query) {
                $query->selectRaw('MIN(id)')
                    ->from('notification')
                    ->whereNotNull('group_message_id')
                    ->groupBy('group_message_id');
            })
                ->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate]) // Add this condition
                ->get();

            return response()->view('auth.dashboard.notificationManagement', [
                'detail' => $detail,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
        }

        return view('auth.dashboard.notificationManagement', [
            'detail' => $detail
        ]);

    }

    public function notificationDelete($id)
    {
        $detail = Notification::Where('group_message_id', '=', $id);
        $detail->delete();
        $detail = Notification::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('notification')
                ->whereNotNull('group_message_id')
                ->groupBy('group_message_id');
        })->get();
        return back()->withData($detail);

    }
}