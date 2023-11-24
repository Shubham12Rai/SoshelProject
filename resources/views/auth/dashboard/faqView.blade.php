@extends('layouts.AdminLTE.index')

{{-- @section('icon_page', 'user')  --}}

@section('title', 'Static Content Management')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
    <style>
        .navbar.navbar-static-top {
            border-bottom: none !important;
        }

        header.main-header.menu {
            border-bottom: 1px solid rgb(236, 235, 235);
        }

        .main-section {
            border: 1px solid black;
            padding: 20px;
        }

        .answer-content {
            background: rgba(234, 230, 230, 1);
            padding: 5px 10px 1px 10px;
        }

        p.question-content {
            margin-bottom: 5px;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            font-weight: 500;
            /* text-align: left; */
        }

        hr.horizontal-line {
            border: 1px solid rgba(196, 181, 181, 1);
            margin: -10px 0px 0px 0px;
        }

        .action-perform {
            margin-top: 28px;
        }

        .dustbin-icon {
            width: 21px;
            height: 23px;
        }

        .edit-icon {
            width: 27px;
            height: 25px;
        }

        .butt-on {
            float: right;
        }

        button.btn.button.apply {
            font-family: 'Roboto', sans-serif;
            font-size: 24px;
            font-weight: 700;
            text-align: left;
        }

        .action-part {
            padding: 0px;
        }

        button.btn-class {
            border: none;
            background: white;
            font-size: 31px;
            font-weight: 700;
            padding-bottom: 15px;
            font-family: 'Lato', sans-serif;
        }

        .btn-class.btn-selected {
            background-color: red;
            color: white;
        }

        button#userp {
            color: rgba(253, 101, 211, 1);
        }

        button#users {
            color: rgba(0, 0, 0, 0.79);
        }

        button#user {
            color: rgba(0, 0, 0, 0.79);
        }


        /* Style the icon  start */
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
            min-width: 490px;
            min-height: 300px;
        }

        .swal2-icon {
            width: 6em;
            height: 6em;
            border-radius: 50%;
            border-color: red;
        }

        #swal2-title {
            font-size: 30px;
            font-family: 'Lato', sans-serif;
            color: rgba(0, 0, 0, 1);
        }

        #swal2-content {
            font-size: 20px;
            font-family: 'Lato', sans-serif;
            color: rgba(0, 0, 0, 1);
        }

        .swal2-icon {
            border: none !important;
        }

        /* Style the icon */
    </style>
    <div class="user-category">
        <a href="{{ url('/static-content-management') }}">
            <button type="button" class="btn-class" id="user">Privacy Policy</button>
        </a>
        <a href="{{ url('/view-term-condition') }}">
            <button type="button" class="btn-class" id="users">Terms & Conditions</button>
        </a>
        <a href="{{ url('/view-FAQ') }}">
            <button type="button" class="btn-class" id="userp">FAQs</button>
        </a>
    </div>
    <div class="main-section">
        <div>
            <div class="butt-on">
                <a href="{{ url('/Add-FAQ') }}" class="button-part">
                    <button class="btn button apply">Add New</button>
                </a>
            </div>
        </div>
        @foreach ($faq as $faqs)
            <div class="row">
                <div class="col-md-11">
                    <div class="main-content">
                        <p class="question-content"><strong>{{ $faqs->question }}</strong></p>
                        <div class="answer-content">
                            <p>{{ $faqs->answer }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 action-part">
                    <div class="action-perform">
                        <a href="#">
                            <img src="{{ asset('img/deleteicon.png') }}" alt="Logo" class="dustbin-icon"
                                data-user-id="{{ $faqs->id }}">
                        </a>
                        <a href="{{ url('/edit-FAQ') }}/{{ $faqs->id }}">
                            <img src="{{ asset('img/editicon.png') }}" alt="Logo" class="edit-icon" data-user-id="">
                        </a>
                    </div>
                </div>
            </div>
            <hr class="horizontal-line">
        @endforeach

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>
        //Js for Delete Faqs
        $(window).on("load", function() {
            $(document).on('click', '.dustbin-icon', function() {
                const faqsId = $(this).attr('data-user-id');
                Swal.fire({
                    title: 'Are You Sure ?',
                    text: 'You want to delete this question and answer',
                    iconHtml: '<i class="fas fa-ban" style="font-size: 55px; border:none; color: red;"></i>',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ URL::to('/delete-FAQ') }}/" + faqsId;
                    }
                });
            });
        });
    </script>
@endsection
