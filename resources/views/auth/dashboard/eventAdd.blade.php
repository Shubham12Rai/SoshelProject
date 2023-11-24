@extends('layouts.AdminLTE.index')

<title>EVENT MANAGEMENT> Add Event Details</title>
@section('title')
    <a href="{{ url('/event-management') }}" class="logo-link">
        <img src="{{ asset('img/back-btn.svg') }}" alt="Your Logo" class="logo-link">
    </a>
    <span class="user-manage">EVENT MANAGEMENT> Add Event details</span>
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
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 20px;
        padding: 0;
        line-height: 1;
        margin-right: 30%;
    }

    #image {
        display: none;
    }

    /* Style the "Add image" link as a regular link */
    .add-image-link {
        cursor: pointer;
        color: #007bff;
        text-decoration: underline;
    }

    /* Optional: Add some hover effect to make it more interactive */
    .add-image-link:hover {
        color: #0056b3;
    }

    /* input#from_date {
        border: 1px solid #000000;
        border-radius: 11px;
    } */

    /* input#to_date {
        border: 1px solid #000000;
        border-radius: 11px;
    } */


    .container.date-form {
        width: 100%;
        display: flex;
        padding: 10px 0 0 0;
    }

    .form-group.row {
        width: 100%;
        display: flex;
    }

    span.error {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }

    .mainevent-section {
        border: 1px solid black;
        padding: 20px;
    }

    .event-form {
        height: 38px !important;
        border-radius: 15px !important;
    }

    .event-address {
        border-radius: 15px !important;
    }

    .image-section {
        padding-left: 15px;
        margin-top: 30px;
    }

    section.description-section {
        margin-top: 30px;
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

    button.adb-btn {
        padding: 10px 50px;
        border-radius: 9px;
        background: linear-gradient(0deg, #FD65D3, #FD65D3);
        color: rgba(255, 255, 255, 1);
        font-size: 20px;
        font-weight: 700;
        margin-left: 90px;
        border: 1px solid black;
    }

    button.adb-btn:hover {
        color: rgba(255, 255, 255, 1);
        background: linear-gradient(0deg, #FD65D3, #FD65D3);
    }

    button.cancel-btn {
        padding: 10px 38px;
        border-radius: 9px;
        background: rgba(189, 193, 200, 1);
        color: rgba(255, 255, 255, 1);
        font-size: 20px;
        font-weight: 700;
        border: 1px solid black;
    }

    button.cancel-btn:hover {
        color: rgba(255, 255, 255, 1);
        background: rgba(189, 193, 200, 1);
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

    span.error.address-error {
        padding-left: 33px;
    }

    select.user-type {
        padding: 7px 30px;
        margin-left: 10px;
        border-radius: 10px;
        background: white;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    section.usertype-section {
        margin-top: 30px;
    }

    section.lat-long-section {
        margin-top: 30px;
    }

    .default-option:hover {
        background-color: transparent !important;
    }

    .swal2-popup {
        width: 40em !important;
    }
</style>

@section('content')
    <div class="mainevent-section">
        @if (session('statusEvent'))
            <div class="alert-success" role="alert">
            </div>
        @endif
        <form class="form" action="{{ url('/add-event') }}" enctype="multipart/form-data" method='POST'>
            @csrf
            <section class="event-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="form-group">
                            <label for="eventtitle">Event title</label>
                            <input type="text" class="form-control event-form" id="eventtitle" name="eventtitle"
                                aria-describedby="title" placeholder="">
                            @if ($errors->has('eventtitle'))
                                <span class="error">{{ $errors->first('eventtitle') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            <div class="image-section">
                <label for="images">Image/s</label>
                <div class="mb-3">
                    <label for="image" class="form-label add-image-link">Add image</label>
                    <input class="form-control" type="file" id="image" name="image[]" multiple>
                    <div id="selected-file-names" class="mt-2"></div>
                </div>

                @if ($errors->has('image.*'))
                    <span class="error">{{ $errors->first('image.*') }}</span>
                @endif
            </div>
            <div id="image-carousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <!-- Indicator elements will be added here -->
                </ol>

                <div class="carousel-inner" role="listbox">
                    <!-- Carousel items will be added here -->
                </div>

                <a class="carousel-control-prev" href="#image-carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#image-carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <section class="description-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="form-group">
                            <label for="eventdescription">Description</label>
                            <textarea class="form-control event-form" id="description" name="description" placeholder=""></textarea>
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
                        <select class="user-type" id="userType" name="userType">
                            <option value="">Select User Type</option>
                            <option value="All">All</option>
                            <option value="Standard">Standard</option>
                            <option value="Premium">Premium</option>
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
                                            id="from_date" value="">

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
                                            id="to_date" value="">
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
                                            id="from_time" value="">
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
                                            id="to_time" value="">
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
                                <img src="http://127.0.0.1:8000/img/material-symbols_call.svg" alt="Country Code"
                                    class="call-img">
                            </label>
                            <div class=" col-2 c-code">
                                <div class="form-group">
                                    <input type="text" class="form-control call-code" id="countryCode"
                                        name="countryCode" placeholder="+1">
                                </div>
                                @if ($errors->has('countryCode'))
                                    <span class="error">{{ $errors->first('countryCode') }}</span>
                                @endif
                            </div>
                            <div class=" col-10 c-number">
                                <div class="form-group">
                                    <input type="tel" class="form-control mobile-input" id="mobileNumber"
                                        name="mobileNumber" placeholder="Enter your mobile number">
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
                                aria-describedby="title" placeholder="">
                        </div>
                    </div>
                </div>
            </section>
            <section class="address-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <div class="form-group ">
                            <div class="img-address">
                                <img src="http://127.0.0.1:8000/img/material-symbols_location-on.svg" alt="Address">
                                <textarea class="form-control event-address" id="address" name="address" rows="4"
                                    placeholder="Enter your address"></textarea>
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
                                        placeholder="latitude">
                                    @if ($errors->has('latitude'))
                                        <span class="error">{{ $errors->first('latitude') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control event-form" name="longitude"id="longitude"
                                        placeholder="longitude">
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
                <div class="row ">
                    <div class="col-lg-7 col-sm-12">
                        <button class="btn cancel-btn">Cancel</button>
                        <button type="submit" class="btn adb-btn">Add</button>
                    </div>
                </div>
            </section>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#image").change(function() {
                const files = $(this)[0].files;
                const selectedFileNames = [];

                for (const file of files) {
                    selectedFileNames.push(file.name);
                }

                // Update the selected-file-names element with the list of file names
                $("#selected-file-names").html(
                    selectedFileNames.length > 0 ?
                    "<p>Selected files: " + selectedFileNames.join(", ") + "</p>" :
                    ""
                );
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
                    title: 'Event Added Sucessfully',
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
