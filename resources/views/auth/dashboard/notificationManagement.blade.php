@extends('layouts.AdminLTE.index')

{{-- @section('icon_page', 'user')  --}}

@section('title', 'NOTIFICATION MANAGEMENT')
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

    }

    .box {
        position: relative;
        background: #ffffff;
        margin-bottom: 20px;
        width: 100%;
        border: none !important;
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
        /* width: 115%; */
        padding-left: 30px;
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
        padding: 10px 0 0 10px;
    }

    .form-group.row {
        width: 100%;
        display: flex;
    }

    input#from-date {
        border: 1px solid #000000;
        border-radius: 11px;
    }

    input#to-date {
        border: 1px solid #000000;
        border-radius: 11px;
    }

    /* Styling for filter by and date ends */

    /* Styling for Add New Button starts */
    button.btn.button.apply {
        /* width: 215px; */
        border-radius: 9px;
        font-family: 'Lato', sans-serif;
        font-size: 24px;
        font-weight: 700;
        /* height: 46px; */
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 6px 35px;
        border: 0.5px solid rgba(0, 0, 0, 1);
    }

    .butt-on {
        position: absolute;
        right: 0;
        padding: 5px 10px 0 0;
        margin-right: 12px;
    }

    /* Styling for Add New Button starts */

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
        width: 10%;
    }

    th.sorting_disabled.head2 {
        width: 20%;
        text-align: left;
    }

    th.sorting_disabled.head3 {
        width: 35%;
        text-align: left;
        padding-left: 10px;
    }

    th.sorting_disabled.head4 {
        width: 15%;
        text-align: left;
        padding-left: 25px;

    }

    th.sorting_disabled.head5 {
        width: 10%;

    }

    tr {
        text-align: center;
        font-family: 'Lato', sans-serif;
        font-size: 18px;
    }

    .table>tbody>tr>td {
        /* padding-left: 20px !important;
        padding-right: 20px !important; */
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
    .button-part {
        text-decoration: none;
        color: white;
    }

    .button-part:hover {
        text-decoration: none;
        color: white;
    }

    td.description {
        text-align: left !important;
        font-size: 13px;
    }

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

    .pagination {
        padding: 20px;
    }

    .pagination>li:first-child>span {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        border: none;
    }

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
        min-width: 500px;
        min-height: 325;
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

    img.search-img {
        padding: 10px;
        position: absolute;
    }

    td.title-name {
        text-align: left;
    }



    div#table_wrapper {
        margin-top: 15px;
    }
</style>

@section('content')
    <div class="box">
        <div class="col-sm-12" style="display: flex;">
            <div class="col-md-6">
                <img src="{{ asset('/img/search-icon.svg') }}" alt="Search Icon" class="search-img"
                    style="vertical-align: middle; margin-right: 5px;">
                <input placeholder="Search here" type="text" name="search_value"onkeyup="getuser_record(this.value)"
                    class="form-control" id="keyWords">
            </div>
            <td>&nbsp;&nbsp;&nbsp;</td>
        </div>
        <div class="col-md-12 first" style="margin-left: 0px;">
            <div class="container date-form">
                <div class="fb">
                    <label for="date" class="col-form-label col-sm-1 txt">
                        <h4 style="font-size: 14px;font-family: 'Lato',sans-serif;"><strong>Filter By-</strong> </h4>
                    </label>
                </div>
                <div class="form-group row">
                    <div style="display: flex;">
                        <label for="date" class="col-form-label txt">
                            <h4 style="font-size: 15px;"></h4>
                        </label>
                        <input type="date" class="form-control input" name="formDate" id="from-date"
                            value="{{ $startDate ?? '' }}">
                    </div>
                    <div style="display: flex; padding-left: 20px;">
                        <label for="date" class="col-form-label txt">
                            <h4 style="font-size: 15px;"><strong>To</strong></h4>
                        </label>
                        <input type="date" class="form-control input" name="to" id="to-date"
                            value="{{ $endDate ?? '' }}">
                    </div>
                </div>
            </div>
            <div class="butt-on">
                <a href="{{ url('/addView-notification') }}" class="button-part">
                    <button class="btn button apply">Add New</button>
                </a>
            </div>
        </div>
    </div>

    <div class="box-body app-list-ingore">
        <table class="table" id="table" style="width:100%">
            <thead>
                <tr>
                    <th class="sorting_disabled head1">#</th>
                    <th class="sorting_disabled head2">Title</th>
                    <th class="sorting_disabled head3">Description</th>
                    <th class="sorting_disabled head4">Sent On</th>
                    <th class="sorting_disabled head5">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($detail as $details)
                    <tr>
                        <td>{{ $i }}</td>
                        <td class="title-name">{{ $details->title }}</td>
                        <td class="description">{{ $details->description }}</td>
                        @php
                            $dateString = $details->created_at;
                            $timestamp = strtotime($dateString);
                            $newDateFormat = date('d-m-Y', $timestamp);
                        @endphp
                        <td class="time-format">{{ $newDateFormat }}</td>
                        <td class="edit-delete">
                            <a href="{{ url('/view-notification') }}/{{ $details->group_message_id }} ">
                                <i class="icons"></i>
                                <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/uploads/profile_photo/1689270233_View.png"
                                    alt="Logo" class="view-icon">
                            </a>
                            <a href="#">
                                <img src="{{ asset('img/fluent_delete-28-filled.svg') }}" alt="Logo"
                                    class="dustbin-icon" data-user-id="{{ $details->group_message_id }}">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>
        //JS For Datatable  Notification
        $(document).ready(function() {
            $('#table').DataTable({
                "ordering": false,
                "lengthChange": false,
                "searching": false,
                "pageLength": 10,
                "pagingType": "simple_numbers",
            });
        });
        //JS For Search Notification
        // function getuser_record(value) {
        //     const fromDateInput = $('#from-date');
        //     const toDateInput = $('#to-date');
        //     fromDateInput.val('');
        //     toDateInput.val('');
        //     history.replaceState({}, document.title, window.location.pathname);
        //     var selected = $('#selectedOption :selected').text();
        //     $.ajax({
        //         url: "{{ url('/filter-notification') }}",
        //         method: 'get',
        //         data: {
        //             keyword: value,
        //             selected_option: selected
        //         },
        //         contentType: 'json',
        //         cache: false,
        //         success: function(data) {
        //             $('.box-body').html($(data).find('.box-body').html());
        //             $(document).ready(function() {
        //                 $('#table').DataTable({
        //                     "ordering": false,
        //                     "lengthChange": false,
        //                     "searching": false,
        //                     "pageLength": 10,
        //                     "pagingType": "simple_numbers",
        //                 });
        //             });
        //         },
        //         error: function(data) {
        //             console.log(data);
        //             console.log("error");
        //         }
        //     });
        // }
        //JS For Filter Notification
        // $(document).ready(function() {
        //     // Get the date inputs
        //     const fromDateInput = $('#from-date');
        //     const toDateInput = $('#to-date');
        //     // Attach onchange event handler to the date inputs
        //     fromDateInput.on('change', function() {
        //         redirectToControllerIfBothDatesFilled();
        //     });
        //     toDateInput.on('change', function() {
        //         redirectToControllerIfBothDatesFilled();
        //     });

        //     function redirectToControllerIfBothDatesFilled() {
        //         console.log('hello');
        //         const startDate = fromDateInput.val();
        //         const endDate = toDateInput.val();
        //         // Check if both dates are filled
        //         if (startDate && endDate) {
        //             // Define the URL of the controller action you want to redirect to
        //             const controllerActionUrl = "{{ URL::to('/filter-notification') }}";
        //             // Construct the URL with query parameters (if needed)
        //             const urlWithParams = controllerActionUrl + '?start_date=' + encodeURIComponent(startDate) +
        //                 '&end_date=' + encodeURIComponent(endDate);
        //             // Redirect to the controller action
        //             window.location.href = urlWithParams;
        //         }
        //     }
        // });
        // JS For delete Notification
        $(window).on("load", function() {
            $(document).on('click', '.dustbin-icon', function() {
                const blockButtons = document.querySelectorAll('.dustbin-icon');
                const notificationId = $(this).attr('data-user-id');
                Swal.fire({
                    title: 'Are You Sure ?',
                    text: `You want to delete the notification?`,
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
                            "{{ URL::to('delete-notification') }}/" +
                            notificationId;
                    }
                });
            });
        });
    </script>
@endsection
