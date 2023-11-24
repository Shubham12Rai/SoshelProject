@extends('layouts.AdminLTE.index')

<title>
    USER MANAGEMENT> User Details</title>
@section('title')
    <a href="{{ url('/user-management') }}" class="logo-link">
        <img src="{{ asset('img/back-btn.svg') }}" alt="Your Logo" class="logo-link">
    </a>
    <span class="user-manage">USER MANAGEMENT> User details</span>
@endsection
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>

    <style>
        a.logo-link:hover {
            text-decoration: none !important;
        }

        a.logo-link {
            text-decoration: none !important;


        }

        img.logo-link {
            margin-left: -1.8%;
        }

        .user-manage {
            color: rgba(0, 0, 0, 1);
        }

        b.page-title {
            padding-top: 15px;
        }

        .box {
            border: 1px solid black;
            padding: 10px;
            background-color: #f1f1f1;
        }

        table {
            border-collapse: collapse;
        }

        td {
            padding: 10px;
            font-family: 'Lato', sans-serif;
            font-size: 24px;
        }

        .list {
            padding-top: 0;
            padding-left: 20px;
        }

        td+td {
            padding-left: 100px;
        }

        .empty-row td {
            border: none;
            /* Remove border from empty row cells */
            padding: 0;
            /* Reset padding for empty row cells */
        }

        .corner-image {
            position: absolute;
            top: 5px;
            right: 20px;
            width: 150px;
            height: 140.36px;
            border-radius: 50%;
            border: 2px solid #fff;
            object-fit: cover;
            font-family: 'Lato', sans-serif;
        }

        nav#menu_sup_corpo {
            border-bottom: none;
        }

        .profile-link {
            position: absolute;
            top: 140px;
            /* Adjust this value to control the distance between image and link */
            right: 30;
            text-align: center;
            color: #1137FF;
            padding-top: 15px;
            text-decoration: underline;
            font-size: 18px;
        }

        .col-md-4 img {
            border-radius: 45px;
        }

        .modal-header.imgcross {
            border: none;
        }

        .spacer-row {
            height: 15px;
            /* Adjust the height to control the gap size */
        }

        #blockButton {
            position: fixed;
            bottom: 20px;
            background-color: rgba(0, 0, 0, 0.1);
            /* Red color, you can change it to your preferred color */
            color: #000000;
            border: 1px solid #000000;
            font-size: 23px;
            cursor: pointer;
            width: 156px;
            height: 38px;
            font-family: 'Lato', sans-serif;
        }

        .block-img-icon {
            padding-left: 10px;
            padding-bottom: 2px;
        }

        #nextLink {
            position: absolute;
            margin-right: 40px;
            bottom: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            text-decoration: underline;
            color: #0029FF;
            font-family: 'Lato', sans-serif;
        }

        tr.spacer-row {
            height: 40px;
        }

        .modal-dialog {
            top: 5%;
            right: 6%;
        }

        .modal-dialog .modal-body {
            padding: 10 70 20 70px;
        }

        .modal-dialog .modal-body .row .col-md-4 {
            min-height: 115px;
        }

        .table-txt-sm {
            font-size: 20px;
            text-align: justify;
            font-family: 'Lato', sans-serif;
        }

        .table-txt-bg {
            font-family: 'Lato', sans-serif;
        }

        .reprted-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        button.modal-nav.next {
            border: none;
            background-color: white;
            font-size: 24px;
            font-weight: 500;
            color: rgba(0, 41, 255, 1);
        }

        button.modal-nav.previous {
            border: none;
            background-color: white;
            font-size: 24px;
            font-weight: 500;
            color: rgba(0, 41, 255, 1);

        }

        button.blocbtn {
            font-size: 26px;
            font-weight: 500;
            border: 1px solid rgba(0, 0, 0, 1);
            border-radius: 3px;
            padding: 4px 20px;
        }

        .modal-body.manageUser-content {
            padding: 10px 25px 20px 50px;
        }

        img.block-cross {
            padding-left: 10px;
            padding-bottom: 5px;
        }

        .modal.fade.in {
            overflow-y: auto;
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
    </style>

@section('content')

    <div class="box" style="border: 1px solid black">

        <table>
            <tr>
                <td class="list">Full Name </td>
                <td>{{ $user->full_name }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>

            <tr>
                <td class="list">Mobile Number</td>
                <td>{{ $user->mobile_number }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Date of birth</td>
                @php
                    $dateString = $user->dob;
                    $dateTime = new DateTime($dateString);
                    $formattedDate = $dateTime->format('F/d/Y');
                @endphp
                <td>{{ $formattedDate }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Status</td>
                @if ($user->active_status == 1)
                    <td>Active</td>
                @endif
                @if ($user->active_status == 4)
                    <td>Blocked</td>
                @endif
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Report count</td>
                <td>{{ $reportedUser->count() }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Reported by</td>
                <td>
                    @php
                        $names = [];
                        $count = 0;
                        
                        foreach ($reportedByUsers as $index => $data) {
                            if (!empty($data[0]['user'])) {
                                $names[] = $data[0]['user']->full_name;
                                $count++;
                            }
                        }
                    @endphp

                    @for ($i = 0; $i < min(3, $count); $i++)
                        <a class="open-modal" data-toggle="modal" data-target="#exampleModals{{ $i }}"
                            data-backdrop="" data-index="{{ $i }}">
                            {{ $names[$i] }}
                        </a>
                        @if ($i < min(2, $count - 1))
                            ,
                        @elseif ($i == 2 && $count > 3)
                            and {{ $count - 3 }} more
                        @endif
                    @endfor

                    @if (empty($names))
                        NA
                    @endif
                </td>

            </tr>

            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Gender</td>
                <td>{{ $user->gender }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Interested for</td>
                <td>{{ $user->interested_for }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class=list>Interested in</td>
                <td>{{ $user->interested_in }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Height</td>
                <td>{{ $user->height_in_feet }}' {{ $user->height_in_inch }}"</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Ethnicity</td>
                <td>{{ $user['ethnicity']['name'] ?? '' }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Sexuality</td>
                <td>{{ $user['sexuality']['name'] ?? '' }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Dating intention</td>
                <td>{{ $user['datingIntention']['name'] ?? '' }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Educational status</td>
                <td>{{ $user['educationStatus']['name'] ?? '' }}</td>
            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="list">Interests</td>
                <td>
                    @foreach ($user['user_going_out'] as $users)
                        {{ $users->going_out->name }},
                    @endforeach
                    @foreach ($user['user_musics'] as $users)
                        {{ $users->music->name }},
                    @endforeach
                    @foreach ($user['user_pets'] as $users)
                        {{ $users->pet->name }},
                    @endforeach
                    @foreach ($user['user_sports'] as $users)
                        @if (!$loop->last)
                            {{ $users->sport->name }},
                        @else
                            {{ $users->sport->name }}
                        @endif
                    @endforeach
                </td>

            </tr>
            <tr class="empty-row">
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
        <div class="corner-image-container">
            <img src="{{ $user['profileImage'][0]['image_path'] ?? '' }}" alt="Corner Image" class="corner-image">
            <a href="#" class="profile-link" data-toggle="modal"
                data-target="#exampleModal">+{{ $profileImageCount - 1 }} more images</a>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header imgcross">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($user['profileImage'] as $image)
                                <div class="col-md-4">
                                    {{-- {{ $image->image_path }} --}}
                                    <img src="{{ $image->image_path }}" alt="Instagram Image"width="90px"
                                        height="90px"></a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @foreach ($reportedByUsers as $index => $data)
            {{-- @dd($reportedByUsers) --}}
            <div class="modal fade in" id="exampleModals{{ $index }}" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content reported-data" style="width: 715px;">
                        <div class="modal-header imgcross">
                            <button type="button" class="close cancel cncl-btn" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body manageUser-content">

                            <table>
                                <tr>
                                    <td style="width:25%"><b class="table-txt-bg">Reported by</b></td>
                                    <td><b>
                                            @if (!empty($data[0]['user']->full_name))
                                                {{ $data[0]['user']->full_name }}
                                            @endif
                                        </b></td>
                                </tr>
                                <tr class="spacer-row">
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td><b class="table-txt-bg">Reported on</b></td>
                                    <td class="table-txt-sm">
                                        {{ date('d-m-Y', strtotime($data[0]->created_at)) }}
                                    </td>
                                </tr>
                                <tr class="spacer-row">
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td><b class="table-txt-bg">Reason</b></td>
                                    <td><b>
                                            @if ($data[0]->reasons_id == 6)
                                                Other
                                            @else
                                                {{ $data[0]->reason }}
                                            @endif
                                        </b></td>
                                </tr>
                                <tr class="spacer-row">
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="table-txt-sm">
                                        @if ($data[0]->reasons_id == 6)
                                            {{ $data[0]->other_reason }}
                                        @endif
                                    </td>
                                </tr>
                                <tr class="spacer-row">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="spacer-row">
                                    <td colspan="2"></td>
                                </tr>

                            </table>

                            <div class="reprted-footer ">
                                <!-- "Previous" and "Next" buttons -->
                                <div>

                                    <button class="blocbtn" data-user-id="{{ $data[0]->to }}">Block<span><img
                                                class="block-cross" src="{{ asset('img/akar-icons_cross.svg') }}"
                                                alt=""></span></button>
                                </div>
                                <div>
                                    <button class="modal-nav previous" data-direction="prev"><u>Previous</u></button>
                                    <button class="modal-nav next" data-direction="next"><u>Next</u></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script> --}}
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>


        <script>
            $(document).ready(function() {
                $('.cancel').click(function() {
                    location.reload();
                });
                let currentModalIndex = -1;
                const modalCount = {{ count($reportedByUsers) }};

                // Open the modal when a link is clicked
                $('.open-modal').click(function() {
                    const index = $(this).data('index');
                    toggleNavButtons(index);
                    currentModalIndex = index; // Update the current modal index
                    showHideModal(index);
                });


                // Handle Next/Previous button clicks
                $('.modal-nav').click(function() {
                    const index = $(this).data('index');
                    toggleNavButtons(index);
                    const direction = $(this).data('direction');
                    if (direction === 'next' && currentModalIndex < (modalCount - 1)) {
                        currentModalIndex++;

                    } else if (direction === 'prev' && currentModalIndex > 0) {
                        currentModalIndex--;
                    }

                    showHideModal(currentModalIndex);
                });
            });

            function toggleNavButtons(index) {
                var modalCount = {{ count($reportedByUsers) }};
                if (modalCount <= 1) {
                    // If there's only one item, hide both buttons
                    $('.modal-nav').hide();
                } else if (index === 0) {
                    // If it's the first item, hide the "Previous" button
                    $('.modal-nav[data-direction="prev"]').hide();
                    $('.modal-nav[data-direction="next"]').show();
                } else if (index === (modalCount - 1)) {
                    // If it's the last item, hide the "Next" button
                    $('.modal-nav[data-direction="prev"]').show();
                    $('.modal-nav[data-direction="next"]').hide();
                } else {
                    // Otherwise, show both buttons
                    $('.modal-nav').show();
                }
            }

            function showHideModal(index) {
                console.log('Showing modal with index:', index);
                var modalCount = {{ count($reportedByUsers) }};
                toggleNavButtons(index);
                for (let i = 0; i < modalCount; i++) {
                    if (i === index) {
                        // $('#exampleModals' + i).modal('show');
                        $('#exampleModals' + i).modal({
                            backdrop: 'static'
                        }).modal('show');
                    } else {
                        $('#exampleModals' + i).modal({
                            backdrop: 'static'
                        }).modal('hide');
                    }
                }
            }
            // JS for block user
            $(window).on("load", function() {
                $(document).on('click', '.blocbtn', function() {
                    const blockButtons = document.querySelectorAll('.block-btn');
                    const userId = $(this).attr('data-user-id');
                    console.log(userId);
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
        </script>


    @endsection
