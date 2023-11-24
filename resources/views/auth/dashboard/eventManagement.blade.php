@extends('layouts.AdminLTE.index')

{{-- @section('icon_page', 'user')  --}}
@section('title', 'EVENT MANAGEMENT')

<link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>

<style>
    /* Styling for header starts */
    nav#menu_sup_corpo {
        border-bottom: none;
    }

    .content {
        min-height: 250px;
        margin-right: auto;
        margin-left: auto;
        padding-left: 15px;
        padding-right: 15px;
        padding-top: 0px !important;
    }

    /* Styling for header ends */

    /* Styling for the box starts */
    .col-md-12 {
        border: 1px solid black;
        padding-bottom: 25px;
    }

    .box {
        position: relative;
        background: #ffffff;
        margin-bottom: 20px;
        width: 100%;
        border: none !important;
        box-shadow: none !important
    }

    /* Styling for the box ends */

    /* Styling for search starts */
    .col-sm-12 {
        padding: 10px;
    }

    .col-md-6 {
        padding: 0 !important;
    }

    input#keyWords {
        border: 1px solid #000000;
        border-radius: 23px;
        padding-left: 30px;
        /* width: 115%; */
    }

    /* Styling for search ends */

    /* Styling for filter by and date starts */
    .col-md-12.first {
        border: none;
        padding: 0px;
    }

    .container.date-form {
        width: 100%;
        display: flex;
        padding: 10px 0 0 0;
    }

    .form-group.row {
        width: 100%;
        display: flex;
    }

    input#from_date {
        border: 1px solid #000000;
        border-radius: 11px;
    }

    input#to_date {
        border: 1px solid #000000;
        border-radius: 11px;
    }

    /* Styling for filter by and date ends */


    /* Styling for table starts */
    table#table {
        border: 1px solid #000000;
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
        font-size: 21px;
        border-top: 2px solid #B0A7A7 !important;
        border-bottom: 3px solid #B0A7A7 !important;
        border-right: none;
        border-left: none;
    }

    th.sorting_disabled.head1 {
        width: 5%;
    }

    th.sorting_disabled.head2 {
        width: 20%;
    }

    th.sorting_disabled.head3 {
        width: 15%;
    }

    th.sorting_disabled.head4 {
        width: 15%;
    }

    th.sorting_disabled.head5 {
        width: 15%;
    }

    th.sorting_disabled.head6 {
        width: 15%;
    }

    th.sorting_disabled.head7 {
        width: 20%;
    }

    tr {
        text-align: center;
        font-family: 'Lato', sans-serif;
        font-size: 18px;
    }

    .table>tbody>tr>td {
        padding-left: 20px !important;
        padding-right: 20px !important;
    }

    /* Styling for table ends */

    /* Styling for eye icon and dustbin icon starts */
    .view-icon {
        width: 25px;
        height: 18px;
    }

    .dustbin-icon {
        width: 28px;
        height: 25px;
    }

    /* Styling for eye icon and dustbin icon ends */
    /* Styling  for filter by date */

    .box.event-management {
        margin-top: 10px;
    }

    .row.date-part {
        display: flex;
        margin-right: 0px;
        justify-content: end
    }

    .from-to {
        display: flex;
    }

    .from-input {
        display: flex;
        align-items: center;

    }

    .to-input {
        display: flex;
        margin-left: 15px;
        align-items: center;
    }

    input.form-control.input.inputdate-form {
        width: 110px;
        box-shadow: none;
        border-radius: 0px;
        border-radius: 11px;
    }

    input[type="date"] {
        padding: 0px 5px;
    }

    /* Styling  for filter by end */

    /* Styling for Add New Button starts */
    .butt-on {
        position: absolute;
        right: 13px;
    }

    button.apply-btn {
        padding: 3px 48px;
        background: rgba(253, 101, 211, 1);
        border: none;
        border-radius: 10px;
        font-size: 20px;
        font-weight: 700;
        font-family: 'Lato', sans-serif;
    }

    a.add-event {
        color: rgba(255, 255, 255, 1);
        text-decoration: none;
    }

    a.add-event:hover {
        color: rgba(255, 255, 255, 1);
        text-decoration: none;
    }

    /* Styling for Add New Button end*/

    /*  styling for table starts */
    .box-body.app-list-ingore {
        padding: 10px 0px 0px 0px;
    }

    /*  styling for table ends*/

    /* styling  for pagination starts*/
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

    li#table_previous:focus {
        border: none !important;
    }

    /*   styling for pagination ends*/

    a.logo-link:hover {
        text-decoration: none;
    }

    /* Styling for eye icon and dustbin icon ends */

    .swal2-icon {

        border: none !important;

    }



    #swal2-title {

        font-size: 30px;

        font-family: 'Lato', sans-serif;

    }



    h2#swal2-title {

        color: #000000;

    }



    #swal2-content {

        font-size: 20px;

        font-family: 'Lato', sans-serif;

    }



    div#swal2-content {

        color: #000000;

    }



    .swal2-actions {

        margin-top: 40px !important;

    }



    .swal2-styled.swal2-confirm {

        display: inline-block;

        box-shadow: none !important;

        height: 40px;

        font-size: 20px !important;

        width: 136px;

        padding: inherit;

        background-color: #FD65D3 !important;

    }



    .swal2-styled.swal2-deny {

        display: inline-block;

        box-shadow: none !important;

        height: 40px;

        font-size: 20px !important;

        width: 136px;

        padding: inherit;

        background-color: rgb(221, 51, 51);

    }



    .swal2-styled.swal2-cancel {

        display: inline-block;

        box-shadow: none !important;

        height: 40px;

        font-size: 20px !important;

        width: 136px;

        padding: inherit;

        background-color: #BDC1C8 !important;

    }



    .swal2-popup {

        display: none;

        position: relative;

        box-sizing: border-box;

        flex-direction: column;

        justify-content: center;

        width: 35em !important;

        height: 25em !important;

        max-width: 100%;

        padding: 1.25em;

        border: none;

        border-radius: 11px;

        background: #fff;

        font-family: inherit;

        min-width: 475px;

        min-height: 375px;

        font-size: 25px;



    }



    .swal2-actions {

        display: flex;

        gap: 40px;

        z-index: 1;

        box-sizing: border-box;

        flex-wrap: wrap;

        align-items: center;

        justify-content: center;

        width: 100%;

        margin: 1.25em auto 0;

        padding: 0;

    }

    .button-part {
        text-decoration: none;
        color: white;
    }

    .button-part:hover {
        text-decoration: none;
        color: white;
    }

    img.search-img {
        padding: 10px;
        position: absolute;
    }
</style>

@section('content')
    <div class="box event-management">
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <img src="{{ asset('/img/search-icon.svg') }}" alt="Search Icon" class="search-img"
                    style="vertical-align: middle; margin-right: 5px;">
                <input placeholder="Search here" type="text" name="search_value"onkeyup="getuser_record(this.value)"
                    value="" class="form-control" id="keyWords">
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="row date-part">
                    <div class="col-6 from-to">
                        <div class="form-group">
                            <div class="from-input">
                                <span>Filter By-</span>
                                <input type="date" class="form-control input inputdate-form" name="start_date"
                                    id="from-date" value="{{ $startDate ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class=" col-6 to-from">
                        <div class="form-group">
                            <div class="to-input">
                                <span>To</span>
                                <input type="date" class="form-control input inputdate-form" name="end_date"
                                    id="to-date" value="{{ $endDate ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="butt-on">
            <a href="{{ url('/addview-event') }}" class="button-part">
                <button class="apply-btn">Add New</button>
            </a>
        </div>
    </div>

    <div class="box-body app-list-ingore">
        <table class="table" id="table" style="width:100%">
            <thead>
                <tr>
                    <th class="sorting_disabled head1">#</th>
                    <th class="sorting_disabled head2">Event title</th>
                    <th class="sorting_disabled head3">User type</th>
                    <th class="sorting_disabled head4">Event date</th>
                    <th class="sorting_disabled head5">Added On</th>
                    <th class="sorting_disabled head6">Last Updated</th>
                    <th class="sorting_disabled head7">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($event as $events)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $events->title }}</td>
                        @if ($events->plan_id === 1)
                            <td>Standard</td>
                        @elseif ($events->plan_id === 2)
                            <td>Premium</td>
                        @elseif ($events->plan_id === 4)
                            <td>All</td>
                        @else
                            <td></td>
                        @endif
                        @php
                            $inputsDate = $events->start_date;
                            $dateTime = new DateTime($inputsDate);
                            $fromDate = $dateTime->format('d-m-Y');
                        @endphp
                        <td>{{ $fromDate }}</td>
                        @php
                            $dateString = $events->created_at;
                            $formattedDate = date('d-m-Y', strtotime($dateString));
                        @endphp
                        <td>{{ $formattedDate }}</td>
                        @php
                            $dateString = $events->updated_at;
                            $formattedUpdateDate = date('d-m-Y', strtotime($dateString));
                        @endphp
                        <td>{{ $formattedUpdateDate }}</td>
                        <td>
                            <a href="{{ url('view-event') }}/{{ $events->id }}">
                                <i class="icons"></i>
                                <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/uploads/profile_photo/1689270233_View.png"
                                    alt="Logo" class="view-icon">
                            </a>
                            <a href="{{ url('edit-event') }}/{{ $events->id }}">
                                <img src="{{ asset('img/ci_edit.svg') }}" alt="Logo">
                            </a>
                            <a href="#">
                                <img src="{{ asset('img/fluent_delete-28-filled.svg') }}" alt="Logo"
                                    class="dustbin-icon" data-user-id="{{ $events->id }}">
                            </a>

                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
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
        $(window).on("load", function() {
            $(document).on('click', '.dustbin-icon', function() {
                const blockButtons = document.querySelectorAll('.dustbin-icon');
                const eventId = $(this).attr('data-user-id');
                Swal.fire({
                    title: 'Are You Sure ?',
                    text: `You want to delete the event?`,
                    iconHtml: '<i class=""',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false,
                    confirmButtonText: 'Yes'

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            "{{ URL::to('delete-event') }}/" +
                            eventId;
                    }
                });
            });
        });

        function getuser_record(value) {
            const fromDateInput = $('#from-date');
            const toDateInput = $('#to-date');
            fromDateInput.val('');
            toDateInput.val('');
            history.replaceState({}, document.title, window.location.pathname);

            var selected = $('#selectedOption :selected').text();
            $.ajax({
                url: "{{ url('/filter-event') }}",
                method: 'get',
                data: {
                    keyword: value,
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
        $(document).ready(function() {
            // Get the date inputs
            const fromDateInput = $('#from-date');
            const toDateInput = $('#to-date');
            // Attach onchange event handler to the date inputs
            fromDateInput.on('change', function() {
                redirectToControllerIfBothDatesFilled();
            });

            toDateInput.on('change', function() {
                redirectToControllerIfBothDatesFilled();
            });

            function redirectToControllerIfBothDatesFilled() {
                console.log('hello');
                const startDate = fromDateInput.val();
                const endDate = toDateInput.val();

                // Check if both dates are filled
                if (startDate && endDate) {
                    // Define the URL of the controller action you want to redirect to
                    const controllerActionUrl = "{{ URL::to('/filter-event') }}";

                    // Construct the URL with query parameters (if needed)
                    const urlWithParams = controllerActionUrl + '?start_date=' + encodeURIComponent(startDate) +
                        '&end_date=' + encodeURIComponent(endDate);
                    // Redirect to the controller action
                    window.location.href = urlWithParams;
                }
            }
        });
    </script>
@endsection
