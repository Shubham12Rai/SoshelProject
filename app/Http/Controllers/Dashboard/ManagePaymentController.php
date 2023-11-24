<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactiondetail;
use Carbon\Carbon;
use DB;

class ManagePaymentController extends Controller
{
    public function managePaymentsLoad()
    {

        $detail = Transactiondetail::all();
        $timePeriod = "";
        return response()->view('auth.dashboard.managePayment', [
            'detail' => $detail,
            'timePeriod' => $timePeriod,
        ]);
    }
    public function timePeriodFilter(Request $request)
    {
        $detail = Transactiondetail::paginate(10);
        $timePeriod = $request->input('timePeriod');
        $startDate = $request->input('formDate');
        $endDate = $request->input('to');

        if ($startDate && $endDate) {
            $detail = DB::table('transaction_details')
                ->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate])
                ->get();

        } elseif ($timePeriod === config('constants.TIME_PERIOD.currentMonth')) {

            $currentMonth = Carbon::now()->format('m');
            $detail = Transactiondetail::whereMonth('created_at', $currentMonth)->get();
        } elseif ($timePeriod === config('constants.TIME_PERIOD.lastThreeMonths')) {

            $threeMonthsAgo = Carbon::now()->subMonths(3)->format('Y-m-d');
            $detail = Transactiondetail::whereDate('created_at', '>=', $threeMonthsAgo)->get();
        } elseif ($timePeriod === config('constants.TIME_PERIOD.lastSixMonths')) {

            $sixMonthsAgo = Carbon::now()->subMonths(6)->format('Y-m-d');
            $detail = Transactiondetail::whereDate('created_at', '>=', $sixMonthsAgo)->get();
        } elseif ($timePeriod === config('constants.TIME_PERIOD.oneYear')) {

            $oneYearAgo = Carbon::now()->subYear()->format('Y-m-d');
            $detail = Transactiondetail::whereDate('created_at', '>=', $oneYearAgo)->get();
        }

        return response()->view('auth.dashboard.managePayment', [
            'detail' => $detail,
            'timePeriod' => $timePeriod,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

    }
    public function searchFilter(Request $request)
    {
        $transactionMedium = $request->get('transactionmedium');
        $keyword = $request->input('keywords');
        $detail = Transactiondetail::all();
        $timePeriod = "";

        if ($request->get('transactionmedium')) {
            $transactionMedium = $request->get('transactionmedium');
            $request->session()->put('transactionmedium', $transactionMedium);
        }

        if ($transactionMedium === config('constants.TRANSACTION_MEDIUM.applePay')) {

            $detail = Transactiondetail::where('transaction_medium', 'Apple Pay')
                ->get();

        } elseif ($transactionMedium === config('constants.TRANSACTION_MEDIUM.debitCard')) {

            $detail = Transactiondetail::where('transaction_medium', 'Debit Card')
                ->get();
        } elseif ($transactionMedium === config('constants.TRANSACTION_MEDIUM.creditCard')) {

            $detail = Transactiondetail::where('transaction_medium', 'Credit Card')
                ->get();
        } elseif ($keyword) {

            $detail = Transactiondetail::where('user_name', 'like', "%{$keyword}%")
                ->orWhere('transcation_id', 'like', "%{$keyword}%")
                ->get();
            $request->session()->put('keywords', $keyword);
            return view('auth.dashboard.managePayment', [
                'detail' => $detail,
                'timePeriod' => $timePeriod,
                'keywords' => $keyword
            ]);
        }
        return response()->view('auth.dashboard.managePayment', [
            'detail' => $detail,
            'timePeriod' => $timePeriod,
            'transactionMedium' => $transactionMedium
        ]);
    }
    public function exportTransaction()
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=transactionlist.csv');

        // Open the output stream in write mode
        $output = fopen('php://output', 'w');

        $column = array('Transaction ID', 'Name', 'Mobile Number', 'Transaction medium', 'Date & time', 'Price');
        fputcsv($output, $column);

        // Fetch data using the Eloquent query
        $transactionDetail = Transactiondetail::select('transcation_id', 'user_name', 'mobile_no', 'transaction_medium', 'created_at', 'amount')->get();


        foreach ($transactionDetail as $transactionDetails) {
            // Convert the query result to an associative array before writing to CSV
            $transactionData = $transactionDetails->toArray();
            fputcsv($output, $transactionData);
        }
        // Close the output stream
        fclose($output);

        exit();
    }
    public function viewTransaction($id)
    {
        $detail = Transactiondetail::all()
            ->where('user_id', $id)
            ->first();

        return response()->view('auth.dashboard.viewTransaction', [
            'detail' => $detail
        ]);
    }
}