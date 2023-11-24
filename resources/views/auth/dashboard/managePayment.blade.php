@extends('layouts.AdminLTE.index')

{{-- @section('icon_page', 'user')  --}}

@section('title', 'MANAGE PAYMENTS')
<link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>

<style>
    /* Styling for header starts */
    nav#menu_sup_corpo {
        border-bottom: none;
    }

    /* Styling for header ends */

    /* Styling for the box starts */
    .box {
        height: auto;
        background-color: #f2f2f2;
        border: 1px solid black;
        padding: 20px;
        border-top: 1px solid black !important;
        margin-left: 20px;
        margin-right: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 99% !important;
        margin-top: 0px;
    }

    section.content {
        margin-right: 15px;
        padding-left: 0px;
        padding-top: 0px;
    }

    .col-md-12 {
        padding: 0px !important;
    }

    /* Styling for the box ends */
    table#table {
        border: 1px solid #000000;
    }

    /* Styling for search box starts */
    .box .col-sm-12 {
        padding-left: 0;
        padding-right: 0;
        justify-content: space-between;
    }

    .box .col-md-6 {
        padding: 0 !important;
        width: 50%;
    }

    input#keyWords {
        border-radius: 23px;
        border-color: black;
        padding-left: 30px;
    }

    /* Styling for search box ends */

    /* Styling for transaction medium starts */
    .trans {
        padding-left: 20px;
        padding-top: 2px;
    }

    #transactionmedium {
        width: 140px !important;
        border-radius: 12px;
        background-color: #FD65D3;
        color: white;
        height: 28px;
        padding-left: 5px;
        padding-bottom: 2px;
        font-size: 16px;
        border-color: black;
        font-weight: 700;
        font-family: 'Lato', sans-serif;
        border: none;
    }

    .trans-label {
        font-size: 16px;
        font-family: 'Lato', sans-serif;
    }

    /* Styling for transaction medium ends */

    /* Styling for Export to CSV button starts */
    .csv {
        padding-top: 5px;
    }

    .btn-csv {
        width: 130px;
        height: 37px;
        background-color: #FD65D3;
        border: 1px solid black;
        border-radius: 7px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        font-family: 'Lato', sans-serif;
        border: none;
    }

    /* Styling for Export to CSV button ends */

    /* Additional styles for content inside the box */
    .box h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .box p {
        color: #666;
        font-size: 16px;
        line-height: 1.5;
    }

    /* Styling of Filter By starts */
    .filter {
        font-size: 18px;
        font-family: 'Lato', sans-serif;
        padding-bottom: 5px;
        color: black;
    }

    /* Styling of Filter By ends */

    /* Styling of innerbox starts */
    .innerbox {
        border: 1px solid black;
    }

    .tp {
        font-size: 18px;
        font-family: 'Lato', sans-serif;
    }

    .rl {
        font-size: 21px;
        font-family: 'Lato', sans-serif;
    }

    /* Styling of innerbox ends */

    .container.row-1 {
        width: 100%;
    }

    .first {
        margin-left: 0 !important;
    }

    .radio-buttons {
        margin-left: 5% !important;
        flex-wrap: wrap;
    }

    .radio-button {
        margin-right: 0 !important;
        padding-right: 30px;
    }

    /* Styling of 'Date','To' and 'Apply' starts */
    .col-md-8.first {
        padding: 0;
    }

    .container.date-form {
        padding: 0;
        width: 100%;
        padding-left: 9px;
        display: flex;
        justify-content: flex-start;
        padding-left: 0;
    }

    .form-group.row {
        padding: 0;
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    label.col-form-label.col-sm-1.txt {
        padding: 0;
    }

    .col-sm-3 {
        width: 180px !important;
        display: contents;
    }

    .butt-on {
        position: absolute;
        right: 0;
    }

    button.btn.button.apply {
        width: 90px;
        height: 37px;
        font-size: 20px;
        padding: 0;
        font-family: 'Lato', sans-serif;
        border-radius: 7px;
    }

    input#from_date {
        border: 1px solid #000000;
        border-radius: 11px;
    }

    input#to_date {
        border: 1px solid #000000;
        border-radius: 11px;
    }

    /* Styling of 'Date','To' and 'Apply' ends */

    /* Styling of Table starts */
    .box-body.app-list-ingore {
        padding: 0px;
    }

    table tr:nth-child(odd) td {
        background-color: #DFE7E4;
    }

    table tr:nth-child(even) td {
        background-color: #F6F8FB;
    }

    th.sorting_disabled {
        text-align: center;
        font-family: 'Lato', sans-serif;
        font-size: 20px;
        border-top: 2px solid #B0A7A7 !important;
        border-bottom: 3px solid #B0A7A7 !important;
        border-right: none;
        border-left: none;
    }

    table.dataTable tbody tr {
        text-align: center;
        font-family: 'Lato', sans-serif;
        font-size: 18px;
    }

    /* Styling of Table ends */

    ul.pagination {
        display: flex;
        align-items: center;
    }

    li.paginate_button {
        padding: 0px !important;
    }

    li.paginate_button:hover {
        border: none;
        /* background: white; */
        background: white !important;
    }

    img.view {
        width: 25px;
        height: 21px;
    }

    img.search-img {
        padding: 10px;
        position: absolute;
    }
</style>
@section('content')
    <div class="box">
        <div class="col-sm-12" style="display: flex;">
            <div class="col-md-6">
                <img src="{{ asset('/img/search-icon.svg') }}" alt="Search Icon" class="search-img"
                    style="vertical-align: middle; margin-right: 5px;">
                <input placeholder="Search the transaction id, name," type="text" name="search_value"
                    onkeyup="getuser_record(this.value)" class="form-control" value="" id="keyWords">
            </div>
            <div class="trans">
                <label class="trans-label" for="transaction-medium">Transaction Medium - </label>
                <select onchange="reloadPaymentPage()" id="transactionmedium" name="transactionmedium">
                    <option value="All" {{ ($transactionMedium ?? '') == 'All' ? 'selected' : '' }}>All</option>
                    <option value="Debit Card" {{ ($transactionMedium ?? '') == 'Debit Card' ? 'selected' : '' }}>Debit Card
                    </option>
                    <option value="Credit Card" {{ ($transactionMedium ?? '') == 'Credit Card' ? 'selected' : '' }}>Credit
                        Card</option>
                    <option value="Apple Pay" {{ ($transactionMedium ?? '') == 'Apple Pay' ? 'selected' : '' }}>Apple Pay
                    </option>
                </select>

            </div>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <form action="{{ url('exportTransaction') }}" method="post">
                @csrf
                <!-- Include CSRF token for Laravel -->
                <button type="submit" name="export" class="btn-csv" value="csv">Export CSV</button>
            </form>

        </div>
        <div class="filter">Filter By :-</div>

        <form action="{{ route('timeperiod.filter') }}" class="innerbox" method="get">
            <div class="container row-1">
                <div class="col-md-12 first txt">
                    <label for="time-period" class="txt">
                        <h4 class="tp">Time Period :</h4>
                    </label>
                    <div class="radio-buttons">
                        <label class="radio-button">
                            <input type="radio" name="timePeriod" id="currentmonth" value="Currentmonth"
                                {{ $timePeriod == 'Currentmonth' ? 'checked' : '' }}>
                            <span class="radio-dot"></span>
                            <span class="radio-label">
                                <h4 class="rl">Current month</h4>
                            </span>
                        </label>
                        <label class="radio-button">
                            <input type="radio" name="timePeriod" id="threemonth"
                                value="Lastthreemonth"{{ $timePeriod == 'Lastthreemonth' ? 'checked' : '' }}>
                            <span class="radio-dot"></span>
                            <span class="radio-label">
                                <h4 class="rl">Last three months</h4>
                            </span>
                        </label>
                        <label class="radio-button">
                            <input type="radio" name="timePeriod" id="sixmonth"
                                value="Lastsixmonth"{{ $timePeriod == 'Lastsixmonth' ? 'checked' : '' }}>
                            <span class="radio-dot"></span>
                            <span class="radio-label">
                                <h4 class="rl">Last six months</h4>
                            </span>
                        </label>
                        <label class="radio-button">
                            <input type="radio" name="timePeriod" id="oneyear"
                                value="Oneyear"{{ $timePeriod == 'Oneyear' ? 'checked' : '' }}>
                            <span class="radio-dot"></span>
                            <span class="radio-label">
                                <h4 class="rl">One year</h4>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-md-12 first" style="margin-left: 0px;">
                    <div class="container date-form">
                        <div class="container date-form">
                            <div class="dt">
                                <label for="date" class="col-form-label col-sm-1 txt">
                                    <h4 style="font-size: 18px;font-family: 'Lato',sans-serif;">Dates :</h4>
                                </label>
                            </div>
                            <div class="form-group row">
                                <div style="display: flex;">
                                    <label for="date" class="col-form-label txt">
                                        <h4 style="font-size: 15px;"></h4>
                                    </label>
                                    <input type="date" class="form-control input" name="formDate" id="from_date"
                                        value="{{ $startDate ?? '' }}">
                                </div>
                                <div style="display: flex;">
                                    <label for="date" class="col-form-label txt">
                                        <h4 style="font-size: 15px;">To</h4>
                                    </label>
                                    <input type="date" class="form-control input" name="to" id="to_date"
                                        value="{{ $endDate ?? '' }}">
                                </div>
                                <div class="butt-on">
                                    <button type="submit" class="btn button apply" name=""
                                        title="">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    <div class="box-body app-list-ingore">
        <table class="table" id="table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Name</th>
                    <th>Mobile Number</th>
                    <th>Transaction medium</th>
                    <th>Date & time</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $transaction)
                    <tr>
                        <td>{{ $transaction->transcation_id }}</td>
                        <td>{{ $transaction->user_name }}</td>
                        <td>{{ $transaction->mobile_no }}</td>
                        <td>{{ $transaction->transaction_medium }}</td>
                        @php
                            $inputDateString = $transaction->created_at;
                            $inputDateTime = new DateTime($inputDateString);
                            $outputDateString = $inputDateTime->format('d/m/Y,');
                            $outputTimeString = $inputDateTime->format('g:i A');
                        @endphp
                        <td>{{ $outputDateString }}<br>{{ $outputTimeString }}</td>
                        <td>$ {{ $transaction->amount }}</td>
                        <td> <a href="{{ url('view-transaction') }}/{{ $transaction->user_id }}" class="tip"
                                data-placement="left" data-element="user38" title="viewTransaction"><i
                                    class=""></i>
                                <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/uploads/profile_photo/1689270233_View.png"
                                    alt="Logo" class="view"></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                "ordering": false,
                "lengthChange": false,
                "searching": false,
                "pageLength": 10,
                "pagingType": "simple_numbers",

            });
        });

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


        // For transaction medium

        function reloadPaymentPage() {
            var transactionmedium = $("#transactionmedium").val();
            location.href = "{{ url('searchFilter') }}?transactionmedium=" + transactionmedium;

        }

        function getuser_record(value) {

            const fromDateInput = $('#from_date');
            const toDateInput = $('#to_date');
            fromDateInput.val('');
            toDateInput.val('');
            $('input[name="timePeriod"]').prop('checked', false);
            $('#transactionmedium').val('All');
            history.replaceState({}, document.title, window.location.pathname);

            var selected = $('#selectedOption :selected').text();
            $.ajax({
                url: "{{ url('searchFilter') }}",
                method: 'get',
                data: {
                    keywords: value,
                    selected_option: selected
                },
                contentType: 'json',
                cache: false,
                success: function(data) {

                    $('.box-body').html($(data).find('.box-body').html());
                    $(document).ready(function() {
                        $('#table').DataTable({
                            "ordering": false,
                            "lengthChange": false,
                            "searching": false,
                            "pageLength": 10,
                            "pagingType": "simple_numbers",

                        });
                    });

                },
                error: function(data) {
                    console.log(data);
                    console.log("error");
                }
            });
        }
    </script>
@endsection
