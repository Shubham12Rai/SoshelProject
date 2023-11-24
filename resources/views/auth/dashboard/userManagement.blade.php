@extends('layouts.AdminLTE.index')

@section('title', 'USER MANAGEMENT')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<style>
    .block-btn {
        border: none;
        background: none;
        padding: 0;
        /* Remove any padding */
    }

    .unblock-btn {
        border: none;
        background: none;
        padding: 0;
        /* Remove any padding */
    }

    nav#menu_sup_corpo {
        border-bottom: none;
    }

    .col-md-12 {
        border: 1px solid black;
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

    /* Style the icon */
    .block-btn i {
        font-size: 17px;
        color: blue;
    }

    .unblock-btn i {
        font-size: 17px;
        /* Adjust the size as needed */
        color: red;
    }

    .user-category {
        display: flex;
        gap: 30px;
        padding-left: 20px;
        padding-top: 10px;
    }

    button.btn-class {
        border: none;
        background: white;
        font-size: 30px;
        font-weight: 700;
        padding-bottom: 15px;
        font-family: 'Lato', sans-serif;
    }

    .btn-class.btn-selected {
        background-color: red;
        color: white;
    }

    .table>tbody>tr>td {
        font-size: 18px;
        text-align: center;
    }

    table tr:nth-child(odd) td {
        background-color: #DFE7E4;
    }

    table tr:nth-child(even) td {
        background-color: #F6F8FB;
    }

    table tbody tr td nav {
        background-color: white;
    }

    th.sorting_disabled {
        border-top: 2px solid #B0A7A7 !important;
        border-bottom: 3px solid #B0A7A7 !important;
        border-right: none;
        border-left: none;
        font-size: 18px;
        text-align: center;
    }

    .pagination>li:first-child>span {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        border: none;
    }

    .action-icons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .action-icons .tip {
        display: grid;
    }

    .pagination {
        padding: 20px;
    }

    #status {
        width: 105px;
        height: 34px;
        border: 1px solid #000000;
        border-radius: 11px;
        background-color: #FD65D3;
        color: #ffffff;
        font-weight: 700;
        font-size: 16px;
        padding-left: 5px;
        padding-bottom: 1px;
        margin-left: 13px;
    }

    .navibar {
        display: flex;
        width: 100%;
        max-width: 100%;
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

    .selected {
        background-color: white;
        color: #FD65D3;
    }

    img.search-img {
        padding: 10px;
        position: absolute;
    }

    label.filter-by {
        width: 67px;
    }
</style>

@section('content')

    <div class="user-category">
        <button type="button" class="btn-class {{ ($user ?? '') === '' ? 'selected' : '' }}" onClick="findUser();"
            id="user" value="all User">
            All users</button>
        <button type="button" class="btn-class {{ ($user ?? '') === 'standard User' ? 'selected' : '' }}"
            onClick="findUsers();" id="users" value="standard User">
            Standard Users</button>
        <button type="button" class="btn-class {{ ($user ?? '') === 'premium User' ? 'selected' : '' }}"
            onClick="findUserp();" id="userp" value="premium User">
            Premium Users</button>
    </div>


    <div class="row">
        <div class="col-md-5" style="padding-right:0px;">
            <div class="search-bar" style="padding: 9px; width: 100%;">
                <img src="{{ asset('/img/search-icon.svg') }}" alt="Search Icon" class="search-img"
                    style="vertical-align: middle; margin-right: 5px;">
                <input placeholder="Search here" type="text" name="search_value" onkeyup="getuser_record(this.value)"
                    class="form-control" value="" id="keyWord"
                    style="border-radius: 23px;width: 100%;border: 1px solid black; padding-left: 30px;">
            </div>
        </div>
        <div class="col-md-7">
            <div class="status-date" style="display:flex;margin-top: 7px;">
                <div style="display:flex;align-items:center">
                    <label for="status">Status</label>
                    <select onchange="reloadattributeList()" name="status" id="status">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
                <div style="display:flex; padding-left:10px; align-items:center">
                    <label for="from-date" class="filter-by">Filter By-</label>
                    <input style="border: 1px solid black; border-radius: 11px;width: 130px;" type="date"
                        name="start_date" id="from-date" class="form-control" value="{{ $startDate ?? '' }}">
                </div>
                <div style="display:flex; padding-left:10px; align-items:center">
                    <label for="to-date">To</label>
                    <input style=" border: 1px solid black; border-radius: 11px; margin-left:10px;width: 130px;"
                        type="date" name="end_date" id="to-date" class="form-control" value="{{ $endDate ?? '' }}">
                </div>
            </div>

        </div>
    </div>

    <div class="box-body app-list-ingore">
        <table class="table" id="table" class="display"
            style="width:100%; border: 1px solid black;font-family:'Lato',sans-serif;">

            <thead>
                <tr>
                    <th style="padding-left: 25px;">#</th>
                    <th>Name</th>
                    <th>Mobile Number</th>
                    <th>User type</th>
                    <th>Report Count</th>
                    <th>Added On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($data as $item)
                    <tr>
                        <td style="padding-left: 25px;">{{ $i }}</td>
                        <td>{{ $item->full_name }}</td>
                        <td>{{ $item->country_code }} {{ $item->mobile_number }}</td>
                        @if ($item->plan_id === 1)
                            <td>Standard</td>
                        @elseif ($item->plan_id === 2)
                            <td>Premium</td>
                        @elseif ($item->plan_id === 3)
                            <td>In-app purchase</td>
                        @else
                            <td></td>
                        @endif
                        @php
                            
                            $id = $item->id;
                            $reportedUser = \App\Models\ReportedUser::where('to', $id)->get();
                        @endphp

                        <td>{{ str_pad($reportedUser->count(), 2, '0', STR_PAD_LEFT) }}</td>
                        @if ($item->created_at != '')
                            @php
                                $dateString = $item->created_at;
                                $formattedDate = date('d-m-Y', strtotime($dateString));
                            @endphp
                        @else
                            @php
                                $formattedDate = '';
                            @endphp
                        @endif
                        <td>{{ $formattedDate }}</td>
                        @if ($item->active_status == 1)
                            <td>
                                Active
                            </td>
                        @else
                            <td>
                                Blocked
                            </td>
                        @endif
                        <td>
                            <div class="action-icons">
                                <a href="{{ url('view-user') }}/{{ $item->id }}" class="tip" data-placement="left"
                                    data-element="user38" title="viewUser"><i class=""></i>
                                    <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/manager/uploads/profile_photo/1689270233_View.png"
                                        alt="Logo" class="view"></a>

                                @if ($item->active_status == 1)
                                    <button type="button" class="block-btn" data-user-id="{{ $item->id }}">
                                        <i class="fas fa-ban"></i>

                                    </button>
                                @endif
                                @if ($item->active_status == 4)
                                    <button type="button" class="unblock-btn" data-user-id="{{ $item->id }}">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @endif

                            </div>
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

        function reloadattributeList() {
            var status = $("#status").val();
            var keyword = $("#keyWord").val();
            location.href = "{{ url('filter') }}?status=" + status + "&keyword=" + keyword;
        }

        function findUser(status) {
            var user = $("#user").val();
            location.href = "{{ url('filter') }}?user=" + user;
        }

        function findUsers(status) {
            var user = $("#users").val();
            // console.log(user);
            location.href = "{{ url('filter') }}?user=" + user;
        }

        function findUserp(status) {
            var user = $("#userp").val();
            // console.log(user);
            location.href = "{{ url('filter') }}?user=" + user;
        }


        $(window).on("load", function() {
            $(document).on('click', '.block-btn', function() {
                const blockButtons = document.querySelectorAll('.block-btn');
                const userId = $(this).attr('data-user-id');
                Swal.fire({
                    title: 'Are You Sure ?',
                    text: `You want to block this user`,
                    iconHtml: '<i class="fas fa-ban" style="font-size: 55px; border:none; color: red;"></i>',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false,
                    confirmButtonText: 'Yes'

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            "{{ URL::to('/block-user/') }}/" +
                            userId;
                    }
                });
            });
        });

        $(window).on("load", function() {
            $(document).on('click', '.unblock-btn', function() {
                const blockButtons = document.querySelectorAll('.unblock-btn');
                const userId = $(this).attr('data-user-id');
                // Add a click event listener to each 'Block User' button
                Swal.fire({
                    title: 'Are You Sure ?',
                    text: `You want to unblock this user`,
                    iconHtml: '<i class="fas fa-ban" style="font-size: 55px; border:none; color: red;"></i>',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false,
                    confirmButtonText: 'Yes'

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            "{{ URL::to('/unblock-user/') }}/" +
                            userId;
                    }
                });
            });
        });

        function getuser_record(value) {

            const fromDateInput = $('#from-date');
            const toDateInput = $('#to-date');
            fromDateInput.val('');
            toDateInput.val('');
            $('#status').val('all');
            history.replaceState({}, document.title, window.location.pathname);

            var selected = $('#selectedOption :selected').text();
            $.ajax({
                url: "{{ url('/filter') }}",
                method: 'get',
                data: {
                    keyword: value,
                    selected_option: selected
                },
                contentType: 'json',
                cache: false,
                success: function(data) {
                    $('.box-body').html($(data).find('.box-body').html());
                    // Update the selected state of user-category buttons
                    $('.user-category button').removeClass('selected');
                    $('#user').addClass('selected');
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
                const startDate = fromDateInput.val();
                const endDate = toDateInput.val();

                // Check if both dates are filled
                if (startDate && endDate) {
                    // Define the URL of the controller action you want to redirect to
                    const controllerActionUrl = "{{ URL::to('/filter') }}";

                    // Construct the URL with query parameters (if needed)
                    const urlWithParams = controllerActionUrl + '?start_date=' + encodeURIComponent(startDate) +
                        '&end_date=' + encodeURIComponent(endDate);
                    console.log(urlWithParams);

                    // Redirect to the controller action
                    window.location.href = urlWithParams;
                }
            }
        });

        $(document).ready(function() {
            // Get the value of the 'status' query parameter from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');

            // Set the selected option based on the 'status' query parameter
            if (statusParam) {
                $('#status').val(statusParam);
            }
        });
    </script>
@endsection
