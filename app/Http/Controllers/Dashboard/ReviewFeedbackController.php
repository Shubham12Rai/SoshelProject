<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VenueFeedback;

class ReviewFeedbackController extends Controller
{
    public function reviewFeedbackLoad()
    {
        $feedback = VenueFeedback::with('users', 'feedback')
            ->select('venue_feedback.*')
            ->get();

        return view('auth.dashboard.reviewFeedback', compact('feedback'));
    }

    public function feedbackFilter(Request $request)
    {
        $sortBy = $request['status'];
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $feedback = VenueFeedback::with('users')
            ->select('venue_feedback.*')
            ->get();

        if ($sortBy === "like") {
            $feedback = VenueFeedback::with('users')
                ->select('venue_feedback.*')
                ->where('status', config('constants.FEEDBACK_TYPE.like'))
                ->get();

            return response()->view('auth.dashboard.reviewFeedback', [
                'feedback' => $feedback,
                'sortBy' => $sortBy,
            ]);
        } elseif ($sortBy === "dislike") {
            $feedback = VenueFeedback::with('users')
                ->select('venue_feedback.*')
                ->where('status', config('constants.FEEDBACK_TYPE.dislike'))
                ->get();

            return response()->view('auth.dashboard.reviewFeedback', [
                'feedback' => $feedback,
                'sortBy' => $sortBy,
            ]);
        } elseif ($startDate && $endDate) {
            $feedback = VenueFeedback::with('users')
                ->select('venue_feedback.*')
                ->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate])
                ->get();

            return response()->view('auth.dashboard.reviewFeedback', [
                'feedback' => $feedback,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);

        }

        return response()->view('auth.dashboard.reviewFeedback', [
            'feedback' => $feedback,
            'sortBy' => $sortBy,
        ]);
    }
}