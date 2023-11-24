@extends('layouts.AdminLTE.index')

@section('dashboard')

@section('title', 'DASHBOARD')

@section('menu_pagina')

@section('content')
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>


    <style>
        a.navbar-brand.heading {
            margin-top: 0% !important;
        }

        button.btn.button.apply {
            padding: 6px 30px;
        }

        .box.border-box.rounded-corner {
            margin-bottom: 0px;
        }

        .box.border-box.rounded-corner.main-box {
            margin-bottom: 20px;
        }

        .first {
            display: flex;
            margin-left: 1%;
        }

        .container.date-form {
            padding-left: 0px;
        }

        input.form-control.input {
            margin-top: 7px;
        }

        button.btn.button.apply {
            padding: 6px 30px;
            margin-top: 7px;
        }
    </style>

    <div style="font-family: 'Lato', sans-serif;" class=" box border-box rounded-corner main-box">
        <form action="{{ route('home') }}" method="get">
            <div class="row">
                <div class="col-md-12 first txt">
                    <label for="time-period" class="txt">
                        <h4 style="font-size: 24px;">Time Period :</h4>
                    </label>
                    <div class="radio-buttons">
                        <label class="radio-button">
                            <input type="radio" name="timePeriod" id="today" value="Today"
                                {{ $timePeriod == 'Today' ? 'checked' : '' }}>
                            <span class="radio-dot"></span>
                            <span class="radio-label">
                                <h4 style="font-size: 24px;">Today</h4>
                            </span>
                        </label>
                        <label class="radio-button">
                            <input type="radio" name="timePeriod" id="weekly" value="Weekly"
                                {{ $timePeriod == 'Weekly' ? 'checked' : '' }}>
                            <span class="radio-dot"></span>
                            <span class="radio-label">
                                <h4 style="font-size: 24px;">Weekly</h4>
                            </span>
                        </label>
                        <label class="radio-button">
                            <input type="radio" name="timePeriod" id="monthly" value="Monthly"
                                {{ $timePeriod == 'Monthly' ? 'checked' : '' }}>
                            <span class="radio-dot"></span>
                            <span class="radio-label">
                                <h4 style="font-size: 24px;">Monthly</h4>
                            </span>
                        </label>
                        <label class="radio-button">
                            <input type="radio" name="timePeriod" id="yearly" value="Yearly"
                                {{ $timePeriod == 'Yearly' ? 'checked' : '' }}>
                            <span class="radio-dot"></span>
                            <span class="radio-label">
                                <h4 style="font-size: 24px;">Yearly</h4>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="col-md-8 first" style="margin-left: 0px;">
                    <div class="container date-form">
                        <div class="container date-form">
                            <div class="form-group row">
                                <label for="date" class="col-form-label col-sm-1 txt">
                                    <h4 style="font-size: 24px;">Date :</h4>
                                </label>
                                <div class="col-sm-3" style="display: flex;">
                                    <label for="date" class="col-form-label txt">
                                        <h4 style="font-size: 24px;">From</h4>
                                    </label>
                                    <input type="date" class="form-control input" name="formDate" id="from_date"
                                        value="{{ $startDate ?? '' }}">
                                </div>
                                <div class="col-sm-3" style="display: flex;">
                                    <label for="date" class="col-form-label txt">
                                        <h4 style="font-size: 24px;">To</h4>
                                    </label>
                                    <input type="date" class="form-control input" name="to" id="to_date"
                                        value="{{ $endDate ?? '' }}">
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn button apply" name=""
                                        title="">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    </div>
    </div>

    <div style="font-family: 'Lato',sans-serif;" class="row">
        <div class="col-md-6">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/bi_people.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">App Installs</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                <h2>90,000</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/bi_people.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Active Users</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @php
                                $activeUserCount = 0;
                                $UserCount = 0;

                                if (session()->has('data')) {
                                    $filteredData = session('data');

                                    foreach ($filteredData as $u) {
                                        if ($u->active_status == 1) {
                                            $activeUserCount++;
                                        }

                                        $UserCount++;
                                    }
                                } else {
                                    foreach ($user as $u) {
                                        if ($u->active_status == 1) {
                                            $activeUserCount++;
                                        }

                                        $UserCount++;
                                    }
                                }
                            @endphp
                            <div class="count">
                                <h2>{{ $activeUserCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/bi_people.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Total Users</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @php
                                $activeUserCount = 0;
                                $UserCount = 0;

                                if (session()->has('data')) {
                                    $filteredData = session('data');

                                    foreach ($filteredData as $u) {
                                        $UserCount++;
                                    }
                                } else {
                                    foreach ($user as $u) {
                                        $UserCount++;
                                    }
                                }
                            @endphp
                            <div class="count">
                                <h2>{{ $UserCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/bi_people.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Standard Users</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                @php
                                    $countStandard = 0;
                                @endphp
                                @foreach ($user as $standard)
                                    @if ($standard['plan_id'] == 1)
                                        @php
                                            $countStandard++;
                                        @endphp
                                    @endif
                                @endforeach
                                <h2>{{ $countStandard }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/bi_people.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Premium Users</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                @php
                                    $countPremium = 0;
                                @endphp
                                @foreach ($user as $premium)
                                    @if ($premium['plan_id'] == 2)
                                        @php
                                            $countPremium++;
                                        @endphp
                                    @endif
                                @endforeach
                                <h2>{{ $countPremium }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/total_revenue.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Total Revenue</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                <h2>$ 90,000</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/total_revenue.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">In-app purchase</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                <h2>$ 50,000</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/total_revenue.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Premium plan</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                <h2>$ 40,000</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/visits_today.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Visits Today</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                <h2>9,000</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/conversion_rate.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Conversion rate</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                @php
                                    $total = $match->count();
                                    $met = $weMet->count();
                                    $roundedPercentage = $total > 0 ? number_format(($met / $total) * 100, 2) : 0;
                                @endphp
                                <h2>{{ $roundedPercentage }}%</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box border-box rounded-corner">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            {{-- <i class="fa fa-calendar size"></i> --}}
                            <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/assets/admin_icons/event.png"
                                alt="Logo" style="height: 50px; width:60px;">
                            <div class="element text">
                                <h4 style="font-size: 24px;">Event Organised</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="count">
                                <h2>{{ $event->count() }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>
        // JavaScript code goes here

        // Get the form and date input elements
        const form = document.querySelector('form');
        const fromDateInput = document.getElementById('from_date');
        const toDateInput = document.getElementById('to_date');
        const radioButtons = document.querySelectorAll('input[name="timePeriod"]');

        // Function to handle form submission
        form.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent the default form submission
            const fromValue = fromDateInput.value;
            const toValue = toDateInput.value;
            console.log('From:', fromValue, 'To:', toValue);

            // Here, you can perform additional actions, such as making an AJAX request to update the data on the page.
        });

        // Function to uncheck radio buttons when a date is selected
        function uncheckRadioButtons() {
            radioButtons.forEach((radioButton) => {
                radioButton.checked = false;
            });
        }

        // Event listener for date input changes
        fromDateInput.addEventListener('change', uncheckRadioButtons);
        toDateInput.addEventListener('change', uncheckRadioButtons);

        function clearDateInputs() {
            fromDateInput.value = '';
            toDateInput.value = '';
        }

        // Event listener for time period radio buttons
        radioButtons.forEach((radioButton) => {
            radioButton.addEventListener('change', clearDateInputs);
        });
    </script>
@endsection
