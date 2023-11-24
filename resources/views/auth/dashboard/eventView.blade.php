@extends('layouts.AdminLTE.index')

<title>EVENT MANAGEMENT> Event Details</title>
@section('title')
    <a href="{{ url('/event-management') }}" class="logo-link">
        <img src="{{ asset('img/back-btn.svg') }}" alt="Your Logo" class="logo-link">
    </a>
    <span class="user-manage">EVENT MANAGEMENT> Event details</span>
@endsection

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

    .navbar.navbar-static-top {
        border-bottom: none !important;
    }

    header.main-header.menu {
        border-bottom: 1px solid rgb(236, 235, 235);
    }

    label.title {
        font-size: 22px;
        font-weight: 700;
        font-family: 'Lato', sans-serif;
    }

    .content-part {
        font-size: 22px;
        font-weight: 400;
        font-family: 'Lato', sans-serif;
        padding-top: 10px;
    }

    .image-part {
        margin-top: 20px;
    }

    .img-corousel {
        padding: 0px !important;
    }

    .image-title {
        padding-bottom: 10px;
    }

    .description {
        margin-top: 35px;
    }

    .desc-content {
        padding-top: 5px;
        padding-bottom: 5px;
        font-size: 18px;
        font-weight: 400;
        font-family: 'Lato', sans-serif;
    }

    .days-part {
        margin-top: 20px;
    }

    .days-content {
        font-size: 16px;
        font-weight: 500;
    }

    .date-part {
        margin-top: 20px;
    }

    .time-content {
        font-size: 16px;
        font-weight: 500;
    }

    .phone-part {
        margin-top: 20px;
    }

    .phone-content {
        font-size: 16px;
        font-weight: 500;
    }

    .address-part {
        margin-top: 20px;
    }

    .addres-content {
        font-size: 16px;
        font-weight: 500;
    }
</style>
@section('content')
    <div class="box" style="border: 1px solid black; padding:30px;">
        <section>
            <div class="event-part">
                <label class="title"> Event title </label>
                <div class="content-part">{{ $event->title }}</div>
            </div>

            <div class="image-part">
                <label class="title image-title">Image/s</label>
                <div class="row">
                    <div class="col-lg-7 col-sm-12 img-corousel">
                        <div id="my-pics" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                @foreach ($event->event_images as $index => $image)
                                    <li data-target="#my-pics" data-slide-to="{{ $index }}"
                                        @if ($index === 0) class="active" @endif></li>
                                @endforeach
                            </ol>

                            <!-- Content -->
                            <div class="carousel-inner" role="listbox">
                                @foreach ($event->event_images as $index => $image)
                                    <div class="item @if ($index === 0) active @endif">
                                        <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/{{ $image->image_path }}"
                                            alt="{{ $image->alt }}">
                                    </div>
                                @endforeach

                            </div>

                            <!-- Previous/Next controls -->
                            <a class="left carousel-control" href="#my-pics" role="button" data-slide="prev">
                                <span class="icon-prev" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#my-pics" role="button" data-slide="next">
                                <span class="icon-next" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>

                        </div>
                    </div>
                </div>

            </div>

            <div class="description">
                <label class="title"> Description </label>
                <div class="desc-content">{{ $event->description }}</div>
            </div>
            <div class="days-part">
                @php
                    $originalDate = $event->start_date;
                    $date = new DateTime($dateString ?? '');
                    $formattedDate = $date->format('d F Y, l');
                @endphp
                <div class="days-content"><img src="http://127.0.0.1:8000/img/material-symbols_date-range-rounded.svg"
                        alt="">{{ $formattedDate }}</div>
            </div>
            <div class="date-part">
                @php
                    $originalDateTime = $event->created_at;
                    $dateTime = new DateTime($originalDateTime);
                    $formattedTime = $dateTime->format('g:i A');
                @endphp
                <div class="time-content">
                    <img src="http://127.0.0.1:8000/img/ic_sharp-access-time.svg" alt="">
                    Time:{{ $formattedTime }} onwards
                </div>
            </div>
            <div class="phone-part">
                <div class="phone-content">
                    <img src="http://127.0.0.1:8000/img/material-symbols_call.svg" alt=""> +1
                    {{ $event->contact_no }}
                </div>
            </div>
            <div class="address-part">
                <div class="address-content">
                    <img src="http://127.0.0.1:8000/img/material-symbols_location-on.svg" alt="">
                    {{ $event->address_on }}
                </div>
            </div>
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
@endsection
