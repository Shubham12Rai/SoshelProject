@extends('layouts.AdminLTE.index')

{{-- @section('icon_page', 'user')  --}}

@section('title', 'STATIC CONTENT MANAGEENT')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sweetalert2.min.css">

    <style>
        .navbar.navbar-static-top {
            border-bottom: none !important;
        }

        header.main-header.menu {
            border-bottom: 1px solid rgb(236, 235, 235);
        }

        .static-content {
            border: 1px solid black;
            padding: 20px;
        }

        .descript {
            padding: 0px;
        }

        .descript-head {
            font-family: 'Lato', sans-serif;
            font-size: 24px;
            font-weight: 500;
            letter-spacing: 0em;
            text-align: left;
            margin: 0px;
        }

        .ck-editor {
            padding: 0px;
        }

        .btn-section {
            float: right;
            margin-top: 20px;
        }

        button.btn.adb-btn {
            background-color: rgba(253, 101, 211, 1);
            padding: 6px 25px;
            font-size: 24px;
            font-weight: 700;
            color: white;
            border-radius: 9px;
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

        button#user {
            color: rgba(253, 101, 211, 1);
        }

        button#users {
            color: rgba(0, 0, 0, 0.79);
        }

        button#userp {
            color: rgba(0, 0, 0, 0.79);
        }

        .cke_editable {
            height: 500px;
            /* Set the desired height */
            width: 790px;
            /* Set the desired width */
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
    <section>
        <div class="user-category" id="updateForm">
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
        @if (session('statusPrivacy'))
            <div class=" alert-success" role="alert">
            </div>
        @endif
        <form class="form" action="{{ url('/update-privacy-policy') }}/{{ $privacyPolicy[0]->id }} " method='POST'>
            @csrf
            <div class="static-content">
                <div class="description-part">
                    <div class="row">
                        <div class="col-md-2 descript">
                            <h4 class="descript-head">Description - </h4>
                        </div>
                        <div class="col-md-10 ck-editor">
                            <textarea class="ckeditor" name="description" id="description" rows="4" cols="50">{{ $privacyPolicy[0]->option_detail }}</textarea>
                            @if ($errors->has('description'))
                                <span class="error">{{ $errors->first('description') }}</span>
                            @endif
                            <div class="btn-section">
                                <button type="submit" class="btn adb-btn">Update</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
    </section>
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include SweetAlert library -->

    <script type="text/javascript">
        CKEDITOR.replace('description', {
            width: '790px', // Set the desired width
            height: '600px', // Set the desired height
        });


        document.addEventListener("DOMContentLoaded", function() {
            // Check if the success message is present in the page
            const successMessage = document.querySelector('.alert-success');

            if (successMessage) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Privacy Policy Update Sucessfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    </script>


@endsection
