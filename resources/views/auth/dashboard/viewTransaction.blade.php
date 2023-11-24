@extends('layouts.AdminLTE.index')

<title>PAYMENT DETAILS</title>
@section('title')
    <a href="{{ url('/manage-payments') }}" class="logo-link">
        <img src="{{ asset('img/back-btn.svg') }}" alt="Your Logo" class="logo-link">
    </a>
    <span class="user-manage">PAYMENT DETAILS</span>
@endsection
@section('content')
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
 
    <style>
        a.logo-link:hover {
            text-decoration: none !important;
        }

        a.logo-link {
            text-decoration: none !important;


        }
        img.logo-link {
            margin-left: -4.6%;
        }
        .user-manage {
            color: rgba(0, 0, 0, 1);
        }

        b.page-title {
            padding-top: 15px;
        }

        /* Styling of header starts */
        .navbar.navbar-static-top {
            border-bottom: none;
            border-left: none;
            border-right: none;
            border-top: none;
        }

        header.main-header.menu {
            border-bottom: 1px solid rgb(236, 235, 235);
        }

        .content {
            min-height: 250px;
            padding-top: 0;
            margin-right: auto;
            margin-left: auto;
            padding-left: 10px;
            padding-right: 10px;
        }

        /* Styling of header ends */

        /* Styling of box starts */
        .box {
            border: 1px solid black;
            padding: 10px;
            background-color: #ffffff;
        }

        /* Styling of box ends */

        /* Styling of Table-1 starts */
        .tbl-1 {
            width: 90%;
            margin: 20px 0 0 50px;
            border-bottom: 1px solid #C4C4C4;
        }

        .tbl-1 td {
            padding: 0px;
            font-family: 'Lato', sans-serif;
            font-size: 24px;
        }

        .tbl-1 .list {
            width: 30%;
            font-family: 'Lato', sans-serif;
            font-size: 27px;
            font-weight: 600;
        }

        .tbl-1 .list-value {
            font-family: 'Lato', sans-serif;
            font-size: 28px;
            font-weight: 500;
            color: #574C4C;
        }

        /* Styling of Table-1 ends */

        /* Styling of Table-2 starts */
        .tbl-2 {
            width: 90%;
            margin: 20px 0 0 50px;
        }

        .tbl-2 td {
            padding: 0px;
            font-family: 'Lato', sans-serif;
            font-size: 24px;
        }

        .tbl-2 .list {
            width: 50%;
            font-family: 'Lato', sans-serif;
            font-size: 27px;
            font-weight: 600;
        }

        .tbl-2 .list-value {
            font-family: 'Lato', sans-serif;
            font-size: 28px;
            font-weight: 500;
            color: #574C4C;
        }

        /* Styling of Table-2 ends */
    </style>

    <div class="box" style="border: 1px solid black">
        <table class="tbl-1">
            <tr>
                <td class="list">Name </td>
                <td class="list-value">{{ $detail->user_name }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>

            <tr>
                <td class="list">Mobile Number</td>
                <td class="list-value">{{ $detail->mobile_no }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
        <table class="tbl-2">
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Transaction id</td>
                <td class="list-value">{{ $detail->transcation_id }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Date & Time of Transaction</td>
                @php
                    $originalDateTime = $detail->created_at;
                    $convertedDateTime = date('d/m/Y H:i', strtotime($originalDateTime));
                @endphp
                <td>{{ $convertedDateTime }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Transaction Type</td>
                <td class="list-value">{{ $detail->transaction_medium }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">User`s Payment Method</td>
                <td class="list-value">{{ $detail->transaction_medium }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Subscription type</td>
                <td class="list-value">In-app purchase</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Paid for</td>
                <td class="list-value">Flash message </td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Total Amount</td>
                <td class="list-value">$ {{ $detail->amount }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Payment Status</td>
                <td class="list-value">{{ $detail->status }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
@endsection
