@extends('layouts.AdminLTE.index')
<title>
    Notification Management</title>
@section('title')
    <a href="{{ url('/notification-management') }}" class="logo-link">
        <img src="{{ asset('img/back-btn.svg') }}" alt="Your Logo" class="logo-link">
    </a>
    <span class="user-manage">Notification Management</span>
@endsection
@section('content')

    <style>
        a.logo-link:hover {
            text-decoration: none !important;
        }

        a.logo-link {
            text-decoration: none !important;
        }

        .navbar.navbar-static-top {
            border-bottom: none !important;
        }
        a.logo-link {
            margin-left: -2%;
        }
        header.main-header.menu {
            border-bottom: 1px solid rgb(236, 235, 235);
        }

        .user-manage {
            color: rgba(0, 0, 0, 1);
        }

        b.page-title {
            padding-top: 15px;
        }

        .main-section {
            border: 1px solid black;
            padding: 30px;
            min-height: 750px;
            max-height:100%;
        }

        .heading-title {
            font-size: 22px;
            font-weight: 700;
            font-family: 'Lato', sans-serif;
        }

        .text-content {
            font-size: 20px;
            font-weight: 500;
            font-family: 'Lato', sans-serif;
            padding-top: 5px;
            margin: 0px !important;
        }

        .description-part {
            margin-top: 40px;
        }

        .usertype {
            margin-top: 40px;
        }

        .selectedUser {
            margin-top: 40px;
        }

        .sentOn {
            margin-top: 40px;
        }
        .title-section {
    padding-top: 50px;
}
    </style>
    <section class="main-section">
        <div class="title-section">
        <div class="row">
            <div class="col-lg-3">
                <h4 class="heading-title">Title -</h4>
            </div>
            <div class="col-lg-9">
                <p class="text-content">{{ $notifications[0]->title }}</p>
            </div>
        </div>
        </div>
       
        <div class="description-part">
            <div class="row">
                <div class="col-lg-3">
                    <h4 class="heading-title">Description -</h4>
                </div>
                <div class="col-lg-9">
                    <p class="text-content">{{ $notifications[0]->description }}</p>
                </div>
            </div>
        </div>
        <div class="usertype">
            <div class="row">
                <div class="col-lg-3">
                    <h4 class="heading-title">User type - </h4>
                </div>
                <div class="col-lg-9">
                    <p class="text-content">{{ $notifications[0]->user_type }}</p>
                </div>
            </div>
        </div>



        <div class="selectedUser">
            <div class="row">
                <div class="col-lg-3">
                    <h4 class="heading-title">Selected users - </h4>
                </div>
                <div class="col-lg-9">
                    <p class="text-content">
                        @if ($notifications[0]->type == 'All')
                            {{ $notifications[0]->type }}
                        @endif
                        @if ($notifications[0]->type == 'Selected User')
                            {{ $notifications[0]->user_list }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @php
            $dateString = $notifications[0]->created_at;
            $timestamp = strtotime($dateString);
            $newDateFormat = date('d-m-Y', $timestamp);
        @endphp
        <div class="sentOn">
            <div class="row">
                <div class="col-lg-3">
                    <h4 class="heading-title">Sent on -</h4>
                </div>
                <div class="col-lg-9">
                    <p class="text-content">{{ $newDateFormat }}</p>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
@endsection
