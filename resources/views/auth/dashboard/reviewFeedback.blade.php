@extends('layouts.AdminLTE.index')

{{-- @section('icon_page', 'user')  --}}

@section('title', 'REVIEW FEEDBACK')

@section('content')

    <style>
        b.page-title {
            margin-left: 0%;
        }

        section.content {
            padding: 0px;
            margin-top: -15px;
        }

        .navbar.navbar-static-top {
            border-bottom: none !important;
        }

        header.main-header.menu {
            border-bottom: 1px solid rgb(236, 235, 235);
        }

        .mainevent-section {
            border: 1px solid #ccc;
            padding: 20px;
        }

        .box.event-management {
            margin-top: 10px;
        }

        input#keyWords {
            border: 1px solid #000000;
            border-radius: 23px;
        }

        .row.date-part {
            display: flex;
            margin-left: 0px;
            margin-bottom: 0px;
        }

        .from-input {
            display: flex;
            align-items: center;
            gap: 0px;

        }

        .filterBy {
            flex-shrink: 0;
            /* Prevent label from shrinking */
        }

        input#to-date {
            margin-left: 10px;
        }


        .to-input {
            display: flex;
            margin-left: 10px;
            align-items: center;
        }

        input.form-control.input.inputdate-form {
            border: 1px solid #000000;
            width: 146px;
            box-shadow: none;
            border-radius: 0px;
            border-radius: 16px;
        }

        input[type="date"] {
            padding: 0px 5px;
        }

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
            background: white !important;
        }

        li#table_previous:focus {
            border: none !important;
        }

        /*   styling for pagination ends*/

        select.user-type {
            padding: 7px 40px;
            margin-left: 10px;
            border-radius: 16px;
            background: #FD65D3;
            border: 1px solid #ccc;
            font-size: 20px;
            font-weight: 700;
            color: white;
        }

        .select-part {
            float: right;
            margin-right: 10px;
        }

        label.sort-by {
            font-size: 14px;
            font-weight: 500;
        }

        .to-from {
            padding-left: 0px;
        }

        .from-to {
            padding-right: 0px !important;
        }

        div#table_wrapper {
            margin-top: -30px;
        }
    </style>

    <section class="mainevent-section">
        <div class="row">
            <div class="col-lg-6 col-lg-12">
                <div class="row date-part">
                    <!-- <div class="col-lg-6 from-to">
                                            <div class="form-group">
                                                <div class="from-input">
                                                    <span><strong>Filter By</strong>-</span>
                                                    <input type="date" class="form-control input inputdate-form" name="start_date"
                                                        id="from-date" value="{{ $startDate ?? '' }}" required>
                                                </div>
                                            </div>
                                        </div> -->
                    <div class="col-lg-6 from-to">
                        <div class="form-group">
                            <div class="from-input">
                                <label class="filterBy"><strong>Filter By -</strong></label>
                                <input type="date" class="form-control input inputdate-form" name="start_date"
                                    id="from-date" value="" required="">
                            </div>
                        </div>
                    </div>
                    <div class=" col-lg-6 to-from">
                        <div class="form-group">
                            <div class="to-input">
                                <span><strong>To</strong></span>
                                <input type="date" class="form-control input inputdate-form" name="end_date"
                                    id="to-date" value="{{ $endDate ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-6 col-lg-12">
                <div class="select-part">
                    <label class="sort-by">Sort By-</label>
                    <select class="user-type" onchange="sortBy()" name="sortby" id="sortby">
                        <option class="select-optionvalue" value="all"{{ ($sortBy ?? '') == 'all' ? 'selected' : '' }}>
                            All</option>
                        <option class="select-optionvalue" value="like"{{ ($sortBy ?? '') == 'like' ? 'selected' : '' }}>
                            Like</option>
                        <option class="select-optionvalue"
                            value="dislike"{{ ($sortBy ?? '') == 'dislike' ? 'selected' : '' }}>Dislike</option>
                    </select>
                </div>
            </div> --}}
        </div>
        <div class="table-content">
            <div class="box-body app-list-ingore">
                <table class="table" id="table" style="width:100%">
                    <thead>
                        <tr>
                            <th class="sorting_disabled head1">#</th>
                            <th class="sorting_disabled head2">Given by</th>
                            <th class="sorting_disabled head3">Mobile Number</th>
                            <th class="sorting_disabled head4">Feedback</th>
                            <th class="sorting_disabled head5">Dislike reason</th>
                            <th class="sorting_disabled head6">Added On</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($feedback as $result)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                    {{-- {{ $result['users']->full_name }} --}}

                                    {{-- In case the user has deleted his/her account --}}
                                    @if (isset($result['users']->full_name) && !empty($result['users']->full_name))
                                        {{ $result['users']->full_name }}
                                    @else
                                        Anonymous
                                    @endif
                                </td>
                                <td>
                                    {{-- {{ $result['users']->country_code }} {{ $result['users']->mobile_number }} --}}

                                    {{-- In case the user has deleted his/her account --}}
                                    @if (isset($result['users']->country_code) &&
                                            !empty($result['users']->country_code) &&
                                            isset($result['users']->mobile_number) &&
                                            !empty($result['users']->mobile_number))
                                        {{ $result['users']->country_code }} {{ $result['users']->mobile_number }}
                                    @else
                                        Anonymous
                                    @endif
                                </td>
                                <td>
                                    @if ($result->status == 1)
                                        <img src="{{ asset('img/thumbs-up.png') }}" alt="Your Logo" class="logo-link">
                                    @elseif ($result->status == 2)
                                        <img src="{{ asset('img/thumbs-down.png') }}" alt="Your Logo" class="logo-link">
                                    @endif

                                </td>
                                <td>
                                    @if ($result->status == 1)
                                        NA
                                    @elseif ($result->status == 2)
                                        {{-- {{ $result->feedback }} --}}

                                        {{-- Dislike reason --}}
                                        {{ $result->feedback->name }}
                                    @endif
                                </td>
                                @php
                                    $dateString = $result->created_at;
                                    $timestamp = strtotime($dateString);
                                    $newDateFormat = date('d-m-Y', $timestamp);
                                @endphp
                                <td>{{ $newDateFormat }}</td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
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

        function sortBy() {
            var status = $("#sortby").val();
            location.href = "{{ url('/filter-feedback') }}?status=" + status;
        }

        //JS For Filter Feedback

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
        //         const startDate = fromDateInput.val();
        //         const endDate = toDateInput.val();

        //         // Check if both dates are filled
        //         if (startDate && endDate) {
        //             // Define the URL of the controller action you want to redirect to
        //             const controllerActionUrl = "{{ URL::to('/filter-feedback') }}";

        //             // Construct the URL with query parameters (if needed)
        //             const urlWithParams = controllerActionUrl + '?start_date=' + encodeURIComponent(startDate) +
        //                 '&end_date=' + encodeURIComponent(endDate);
        //             // Redirect to the controller action
        //             window.location.href = urlWithParams;
        //         }
        //     }
        // });
    </script>
@endsection
