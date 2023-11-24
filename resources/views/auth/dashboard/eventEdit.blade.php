@extends('layouts.AdminLTE.index')

<title>EVENT MANAGEMENT> Edit Event Details</title>
@section('title')
    <a href="{{ url('/event-management') }}" class="logo-link">
        <img src="{{ asset('img/back-btn.svg') }}" alt="Your Logo" class="logo-link">
    </a>
    <span class="user-manage">EVENT MANAGEMENT> Edit Event details</span>
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

    .image-container {
        position: relative;
        display: inline-block;
    }

    .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: white;
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        color: black;
        cursor: pointer;
        font-size: 20px;
        padding: 0;
        line-height: 1;
        margin: 2%;
    }

    .pointer {
        height: 30px;
        margin-top: 30%;
        background-color: none;
    }

    .carousel-control.left {
        background-image: none !important;
    }

    .carousel-control.right {
        background-image: none !important;
    }

    section.description-section {
        margin-top: 30px;
    }

    .event-form {
        border-radius: 15px !important;
        height: 38px !important;
    }

    .event-address {
        border-radius: 15px !important;
    }

    section.date-section {
        margin-top: 30px;
    }

    .date-heading {
        font-weight: 700;
        color: rgba(0, 0, 0, 1);
    }

    input[type="date"] {
        border: none
    }

    input[type="time"] {
        border: none
    }

    .date-part {
        display: flex;
        flex-wrap: wrap;
    }

    .from-to {
        display: flex;
    }

    .to-from {
        display: flex;
        margin-left: 30px;
    }

    input.form-control.input.date-form {
        width: 150px;
        border-bottom: 1px solid;
        box-shadow: none;
        border-radius: 0px;
    }

    section.time-section {
        margin-top: 30px;
    }

    section.country-section {
        margin-top: 30px;
        margin-left: 15px;
    }

    .country-call {
        display: flex;
    }

    input.form-control.call-code {
        width: 40px;
        margin-left: 29px
    }

    input.form-control.mobile-input {
        width: 115px;
        border-radius: 3px;
        margin-left: 5px;
    }

    section.venue-section {
        margin-top: 30px;
        /* margin-left: 15px; */
    }

    section.address-section {
        margin-top: 30px;
    }

    .img-address {
        display: flex;
    }

    section.button-section {
        margin-top: 50px;
    }

    button.update-btn {
        padding: 10px 50px;
        border-radius: 9px;
        color: rgba(255, 255, 255, 1);
        font-size: 20px;
        font-weight: 700;
        margin-left: 50px;
        border: 1px solid black;
    }

    textarea#address {
        margin-left: 10px;
    }

    .row.date-part {
        display: flex;
        margin-left: 0px;
    }

    .from-input {
        display: flex;
    }

    .to-input {
        display: flex;
    }

    .row.country-call {
        display: flex;
    }

    .col-2.c-code {
        width: 75px;
    }

    .col-10.c-number {
        margin-left: 9px;
    }

    section.usertype-section {
        margin-top: 30px;
    }

    select.user-type {
        padding: 7px 30px;
        margin-left: 10px;
        border-radius: 10px;
        background: white;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    section.lat-long-section {
        margin-top: 30px;
    }

    span.error {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }

    .mainevent-section {
        border: 1px solid black;
        padding: 30px;
    }

    .carousel-inner .item {
        width: 510px !important;
        height: 335px !important;
    }

    img.corousel-img {
        height: 100% !important;
        width: 100%;
    }

    .swal2-popup {
        width: 40em;
    }
</style>

@section('content')
    <div class="mainevent-section">
        @if (session('statusEvent'))
            <div class="alert-success" role="alert">
            </div>
        @endif
        <form class="form" action="{{ url('/update-event') }}/{{ $event->id }}" enctype="multipart/form-data"
            method='POST'>
            @csrf
            <section class="event-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="form-group">
                            <label for="title">Event title</label>
                            <input type="text" class="form-control event-form" id="eventtitle" name="eventtitle"
                                value="{{ $event->title }}">
                            @if ($errors->has('eventtitle'))
                                <span class="error">{{ $errors->first('eventtitle') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            <section class="image-section">
                <div class="row">
                    <div class="col-lg-8 col-sm-12 img-corousel">
                        <div class="form-group">
                            <label for="eventImages">Event Images</label>
                            <div class="img-slider"style="display:flex;align-items:center;">
                                <div id="my-pics" class="carousel slide" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        @foreach ($event->event_images as $index => $image)
                                            <li data-target="#my-pics" data-slide-to="{{ $index }}"
                                                class="{{ $index === 0 ? 'active' : '' }}"></li>
                                        @endforeach
                                    </ol>

                                    <!-- Content -->
                                    <div class="carousel-inner" role="listbox">
                                        @foreach ($event->event_images as $index => $image)
                                            <div class="item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="https://soshel-dev.s3.us-east-2.amazonaws.com/{{ $image->image_path }}"
                                                    class="corousel-img" alt="">
                                                <input type="hidden" name="existing_images[{{ $index }}][id]"
                                                    value="{{ $image->id }}">
                                                <button class="remove-btn"><i class="fas fa-times"></i></button>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Previous/Next controls -->
                                    <a class="left carousel-control pointer" href="#my-pics" role="button"
                                        data-slide="prev">
                                        <span class="icon-prev " aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>

                                    <a class="right carousel-control pointer" href="#my-pics" role="button"
                                        data-slide="next">
                                        <span class="icon-next" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>

                                <div>
                                    <a id="add-more-images-btn" class="btn" href="javascript:void(0);">Add More
                                    </a>
                                    <input type="file" id="image-input" name="images[]" style="display: none;" multiple>
                                </div>
                            </div>
                        </div>
                        <!-- Hidden input fields for images to be removed -->
                        <div id="removed-images-container"></div>
                    </div>
                </div>
            </section>
            <section class="description-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="form-group">
                            <label for="eventdescription">Description</label>
                            <textarea class="form-control event-form" id="description" name="description" placeholder="">{{ $event->description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="error">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <section class="usertype-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <label for="cars">User types :</label>
                        <select class="user-type" name="userType" id="userType">
                            <option value="">Select User Type</option>
                            <option value="Standard"{{ ($event->plan_id ?? '') == 1 ? 'selected' : '' }}>
                                Standard</option>
                            <option value="Premium"{{ ($event->plan_id ?? '') == 2 ? 'selected' : '' }}>
                                Premium</option>
                            <option value="In-app purchase"{{ ($event->plan_id ?? '') == 3 ? 'selected' : '' }}>
                                In-app purchase</option>
                        </select>
                        @if ($errors->has('userType'))
                            <span class="error">{{ $errors->first('userType') }}</span>
                        @endif
                    </div>
                </div>
            </section>
            <section class="date-section">
                <div class=row>
                    <div class="col-lg-7 col-sm-12">
                        <h5 class="date-heading">Select Date</h5>
                        <div class="row date-part">
                            <div class="col-6 from-to">
                                <div class="form-group">
                                    <div class="from-input">
                                        <span>From</span>
                                        <input type="date" class="form-control input date-form" name="from_date"
                                            id="from_date" value="{{ $event->start_date }}">

                                    </div>
                                    @if ($errors->has('from_date'))
                                        <span class="error">{{ $errors->first('from_date') }}</span>
                                    @endif

                                </div>
                            </div>
                            <div class=" col-6 to-from">
                                <div class="form-group">
                                    <div class="to-input">
                                        <span>To</span>
                                        <input type="date" class="form-control input date-form" name="to_date"
                                            id="to_date" value="{{ $event->end_date }}">
                                    </div>
                                    @if ($errors->has('to_date'))
                                        <span class="error">{{ $errors->first('to_date') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="time-section">
                <div class=row>
                    <div class="col-lg-7 col-sm-12">
                        <h5 class="date-heading">Select Time</h5>
                        <div class="row date-part">
                            <div class=" col-6 from-to ">
                                <div class="form-group">
                                    <div class="from-input">
                                        <span>From</span>
                                        <input type="time" class="form-control input date-form" name="from_time"
                                            id="from_time" value="{{ $event->start_time }}">
                                    </div>
                                    @if ($errors->has('from_time'))
                                        <span class="error">{{ $errors->first('from_time') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6 to-from ">
                                <div class="form-group">
                                    <div class="from-input">
                                        <span>To</span>
                                        <input type="time" class="form-control input date-form" name="to_time"
                                            id="to_time" value="{{ $event->end_time }}">
                                    </div>
                                    @if ($errors->has('to_time'))
                                        <span class="error">{{ $errors->first('to_time') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="country-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="row country-call">
                            <label for="countryCode">
                                <img src="{{ asset('img/material-symbols_call.svg') }}" alt="Country Code"
                                    class="call-img">
                            </label>
                            <div class=" col-2 c-code">
                                <div class="form-group">
                                    <input type="text" class="form-control call-code" id="countryCode"
                                        name="countryCode" value="{{ $event->country_code }}">
                                </div>
                                @if ($errors->has('countryCode'))
                                    <span class="error">{{ $errors->first('countryCode') }}</span>
                                @endif
                            </div>
                            <div class=" col-10 c-number">
                                <div class="form-group">
                                    <input type="tel" class="form-control mobile-input" id="mobileNumber"
                                        name="mobileNumber" value="{{ $event->contact_no }}">
                                </div>
                                @if ($errors->has('mobileNumber'))
                                    <span class="error">{{ $errors->first('mobileNumber') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="venue-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="form-group">
                            <label for="venuename">Venue Name</label>
                            <input type="text" class="form-control event-form" id="venueName" name="venueName"
                                value="{{ $event->venue_name }}">
                        </div>
                    </div>
                </div>
            </section>
            <section class="address-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="form-group ">
                            <div class="img-address">
                                <img src="{{ asset('img/material-symbols_location-on.svg') }}" alt="Address">
                                <textarea class="form-control event-address" id="address" name="address" rows="4">{{ $event->address_on }}</textarea>
                            </div>
                            @if ($errors->has('address'))
                                <span class="error address-error">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            <section class="lat-long-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" class="form-control event-form" name="latitude"id="latitude"
                                        placeholder="latitude" value="{{ $event->lat }}">
                                    @if ($errors->has('latitude'))
                                        <span class="error">{{ $errors->first('latitude') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control event-form" name="longitude"id="longitude"
                                        placeholder="longitude" value="{{ $event->lon }}">
                                    @if ($errors->has('longitude'))
                                        <span class="error">{{ $errors->first('longitude') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="button-section">
                <button type="submit" class="btn btn-primary update-btn">Update Event</button>
        </form>
        </section>
    </div>
    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#add-more-images-btn").click(function() {
                $("#image-input").click();
            });

            $("#image-input").change(function() {
                const files = $(this)[0].files;
                const carouselInner = $("#my-pics .carousel-inner");
                const indicators = $("#my-pics .carousel-indicators");

                for (const file of files) {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        const imageUrl = event.target.result;
                        const newSlide = `
                        <div class="item">
                            <img src="${imageUrl}" alt="New Image">
                            <button class="remove-btn"><i class="fas fa-times"></i></button>
                        </div>
                    `;
                        carouselInner.append(newSlide);

                        const newIndex = carouselInner.find(".item").length - 1;
                        const newIndicator =
                            `<li data-target="#my-pics" data-slide-to="${newIndex}"></li>`;
                        indicators.append(newIndicator);

                        if (newIndex === 0) {
                            carouselInner.find(".item").addClass("active");
                            indicators.find("li").addClass("active");
                        }
                        $("#my-pics").carousel();
                    };
                    reader.readAsDataURL(file);
                }
            });

            $(document).on("click", ".remove-btn", function() {
                const slide = $(this).closest(".item");
                const slideIndex = slide.index();
                const removedImagesContainer = $("#removed-images-container");
                const imageId = slide.find("input[type=hidden]").val();

                if (imageId) {
                    // If the image has an ID, mark it for removal
                    removedImagesContainer.append(
                        `<input type="hidden" name="removed_images[]" value="${imageId}">`);
                }

                const indicators = $("#my-pics .carousel-indicators li");
                indicators.eq(slideIndex).remove();

                slide.remove();

                // Update the active slide
                const activeSlideIndex = $("#my-pics .carousel-inner").find(".item.active").index();
                if (activeSlideIndex === -1) {
                    $("#my-pics .carousel-inner").find(".item").eq(0).addClass("active");
                    $("#my-pics .carousel-indicators li").eq(0).addClass("active");
                }
            });
        });
        //Js for sucess message
        document.addEventListener("DOMContentLoaded", function() {
            // Check if the success message is present in the page
            const successMessage = document.querySelector('.alert-success');

            if (successMessage) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Event Updated Sucessfully',
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    window.location.href = "{{ URL::to('/event-management') }}";
                }, 2000);
            }
        });
    </script>
@endsection
