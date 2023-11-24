@extends('layouts.AdminLTE.index')

{{-- @section('icon_page', 'user')  --}}

@section('title', 'Static Content Management')

@section('content')
    <style>
        .main-section {
            border: 1px solid black;
            padding: 20px;
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

        .butt-on {
            float: right;
        }

        .input-section {
            padding-top: 40px;
        }

        .event-form {
            height: 38px !important;
            border-radius: 15px !important;
        }

        .eventt-form {
            border-radius: 15px !important;
        }

        .question-part {
            padding-right: 0px;
        }

        .answer-part {
            padding-left: 0px;
        }

        h4.heading {
            font-family: 'Lato', sans-serif;
            font-size: 18px;
            font-weight: 500;
        }

        button.btn.button.apply {
            font-family: 'Lato', sans-serif;
            font-size: 24px;
            font-weight: 700;
            line-height: 28px;
        }

        span.error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .swal2-popup {
            width: 40em;
        }
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
    <p style="font-size:30px" ><strong>Edit Q&A</strong></p>
        @if (session('statusFaqEdit'))
            <div class=" alert-success" role="alert">
            </div>
        @endif
        <form class="form" action="{{ url('/edit-update-FAQ') }}/{{ $faq->id }}" method='POST'>
            @csrf
            <div>
                <div class="butt-on">
                    <button type="button" class="btn button apply" id="backButton">Back</button>

                    <button class="btn button apply">Save</button>

                </div>
            </div>

            <div class="input-section">
                <div class="row">
                    <div class="col-md-2 question-part">
                        <h4 class="heading">Question</h4>
                    </div>
                    <div class="col-md-10 answer-part">
                        <div class="form-group">
                            <input type="text" class="form-control eventt-form" id="question" name="question"
                                value="{{ $faq->question }}">
                        </div>
                        @if ($errors->has('question'))
                            <span class="error">{{ $errors->first('question') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 question-part">
                        <h4 class="heading">Answer</h4>
                    </div>
                    <div class="col-md-10 answer-part">
                        <div class="form-group">
                            <textarea type="text" class="form-control eventt-form" name="answer" id="answer" row="4" column="20">{{ $faq->answer }}</textarea>
                        </div>
                        @if ($errors->has('answer'))
                            <span class="error">{{ $errors->first('answer') }}</span>
                        @endif
                    </div>
                </div>
            </div>

        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>
        //Js for Back button
        document.getElementById('backButton').addEventListener('click', function() {
            // Redirect to the desired URL when "Back" button is clicked
            window.location.href = "{{ URL::to('/view-FAQ') }}";
        });


        document.addEventListener("DOMContentLoaded", function() {
            // Check if the success message is present in the page
            const successMessage = document.querySelector('.alert-success');

            if (successMessage) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'FAQs Update Sucessfully',
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    window.location.href = "{{ URL::to('/view-FAQ') }}";
                }, 2000);
            }
        });
    </script>
@endsection
