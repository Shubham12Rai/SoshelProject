@extends('layouts.AdminLTE.index')

<title>Notification Management>Add New</title>
@section('title')
    <a href="{{ url('/notification-management') }}" class="logo-link">
        <img src="{{ asset('img/back-btn.svg') }}" alt="Your Logo" class="logo-link">
    </a>
    <span class="user-manage">Notification Management>Add New</span>
@endsection

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script src="{{ asset('/js/notification.js') }}"></script>
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

        .navbar.navbar-static-top {
            border-bottom: none !important;
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
            "

        }

        .event-form {
            border-radius: 15px !important;
            border: 0.5px solid #000000
        }

        select.user-type {
            padding: 7px 21px;
            margin-left: 10px;
            border-radius: 10px;
            background: white;
            border: 1px solid #ccc;
            font-size: 18px;
        }

        .heading-title {
            font-size: 22px;
            font-weight: 700;
            font-family: 'Lato', sans-serif;
        }

        section.usertype-section {
            margin-top: 70px;
        }

        section.selectuser-section {
            margin-top: 70px;
        }

        button.btnn {
            padding: 4px 85px;
            font-size: 24px;
            font-weight: 700;
            font-family: 'Roboto', sans-serif;
            border: 0.5px solid rgba(0, 0, 0, 1)
        }

        .adb-btn {
            color: rgba(255, 255, 255, 1);
            background: linear-gradient(0deg, #FD65D3, #FD65D3) !important;
            border-radius: 10px;

        }

        .cancel-btn {
            background: rgba(189, 193, 200, 1);
            color: rgba(255, 255, 255, 1);
            margin-left: 20px;
            border-radius: 10px;
        }

        section.button-section {
            margin-top: 80px;
        }

        button.multiselect.dropdown-toggle.btn.btn-default {
            margin-left: 16px;
            padding: 7px 25 7px 26px;
            font-size: 18px;
            border-radius: 10px;

        }

        .btn .caret {
            margin-left: 20px;
        }

        ul.multiselect-container.dropdown-menu {
            overflow-y: auto;
            max-height: 150px;
        }

        ul.multiselect-container.dropdown-menu {
            margin-left: 15px;
            max-width: 200px;
            width: 100%;
        }

        .dropdown-menu>.active>a,
        .dropdown-menu>.active>a:focus,
        .dropdown-menu>.active>a:hover {
            color: black;
            background-color: white !important;

        }

        span.error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .swal2-popup {
            width: 40em;
        }

        form.form {
            margin: 50px 0px 50px 0px;
        }
    </style>
    <section class="main-section">
        @if (session('statusNotification'))
            <div class="alert-success" role="alert">
            </div>
        @endif
        @if (session('statusToken'))
            <div class="alert alert-warning" role="alert">
                {{ session('statusToken') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form class="form" action="{{ url('/add-notification') }}" enctype="multipart/form-data" method='POST'>
            @csrf
            <section>
                <div class="row">
                    <div class="col-lg-3">
                        <h4 class="heading-title">Title -</h4>
                    </div>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control event-form" name="title" id="title"
                                        placeholder="" oninput="limitInputWords(this, 10);">
                                    @if ($errors->has('title'))
                                        <span id="title-error" class="error">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                                <strong class="wordcount"><span id="wordCount">0</span>/10</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="row">
                    <div class="col-lg-3">
                        <h4 class="heading-title">Description -</h4>
                    </div>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="position: relative;">
                                        <textarea type="text" class="form-control event-form" rows="6" cols="50" name="description"
                                            id="description" placeholder=""></textarea>
                                        <strong><span id="textAreawordCount"
                                                style="position: absolute; bottom: 5px; right: 5px;">0/30</span></strong>

                                    </div>
                                    @if ($errors->has('description'))
                                        <span id="descriptionError"
                                            class="error">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
            <section class="usertype-section">
                <div class="row">
                    <div class="col-lg-3">
                        <h4 class="heading-title">User type - </h4>
                    </div>
                    <div class="col-lg-9">
                        <select class="user-type" id="userType" name="userType">
                            <option class="select-option" value="">Select User Type</option>
                            <option class="select-option" value="All">All</option>
                            <option class="select-option" value="Standard">Standard</option>
                            <option class="select-option" value="Premium">Premium</option>

                        </select>
                        @if ($errors->has('userType'))
                            <span class="error" id="userType-error">{{ $errors->first('userType') }}</span>
                        @endif
                    </div>
                </div>
            </section>
            <section class="selectuser-section">
                <div class="row">
                    <div class="col-lg-3">
                        <h4 class="heading-title">Select users - </h4>
                    </div>
                    <div class="col-lg-9">
                        <div class="">
                            <select class="user-type" id="selectUser" name="selectUser[]" multiple="multiple"
                                style="height:100px; overflow-y:auto;">

                            </select>
                        </div>
                    </div>
                </div>
            </section>
            <input type="hidden" id="selectUserAll" name="selectUserAll" value="0">

            <section class="button-section">
                <div class="row">
                    <div class="col-lg-7 col-sm-12">
                        <button type="submit" class="btnn adb-btn">Send</button>
                        <button type="button" class="btnn cancel-btn" id="backButton">Cancel</button>
                    </div>
                </div>
            </section>
        </form>
    </section>

    <script>
        document.getElementById('backButton').addEventListener('click', function() {
            // Redirect to the desired URL when "Back" button is clicked
            window.location.href = "{{ URL::to('/addView-notification') }}";

        });


        const userTypeSelect = document.getElementById('userType');
        const userTypeError = document.getElementById('userType-error'); // Get the error message element
        const selectUserSelect = document.getElementById('selectUser');

        userTypeSelect.addEventListener('change', function() {
            userTypeError.style.display = 'none';
        });

        $j('#selectUser').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Select User',
            onSelectAll: function() {
                // Set the hidden input value to 1 when "Select All" is clicked
                selectUserAllInput.value = '1';


            },
            onDeselectAll: function() {
                // Set the hidden input value back to 0 when all options are deselected
                selectUserAllInput.value = '0';
            }
        });
        const controllerActionUrl = "{{ URL::to('/get-users') }}";
        userTypeSelect.addEventListener('change', async function() {
            const selectedUserType = userTypeSelect.value;

            // Fetch users based on the selected user type
            const response = await fetch(`${controllerActionUrl}/${selectedUserType}`);
            const users = await response.json();

            // Clear existing options and populate the multiselect dropdown
            $j('#selectUser').multiselect('destroy');
            selectUserSelect.innerHTML = '';

            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = user.full_name;
                selectUserSelect.appendChild(option);
            });

            // Reinitialize the multiselect
            $j('#selectUser').multiselect({
                includeSelectAllOption: true,
                nonSelectedText: 'Select User',
                onSelectAll: function() {
                    selectUserAllInput.value = '1';
                },
                onDeselectAll: function() {
                    selectUserAllInput.value = '0';
                }
            });
        });
    </script>

    <script>
        const titleInput = document.getElementById('title');
        const titleError = document.getElementById('title-error');

        // Add an event listener to clear the error message when the input field is focused
        titleInput.addEventListener('focus', function() {
            titleError.textContent = ''; // Clear the error message
        });

        function limitInputWords(inputField, maxWords) {
            const words = inputField.value.trim().split(/\s+/);
            if (words.length > maxWords) {
                words.splice(maxWords); // Keep only the first maxWords words
                inputField.value = words.join(' ');
                inputField.disabled = true; // Disable the input field
            }
            document.getElementById('wordCount').textContent = words.length;
        }
    </script>
    <script>
        // Get the textarea element, word count element, and error message element
        const textarea = document.getElementById('description');
        const wordCount = document.getElementById('textAreawordCount');
        const errorElement = document.getElementById('descriptionError');

        // Add an event listener to the textarea for input
        textarea.addEventListener('input', function() {
            // Split the input value into words and count them
            const words = textarea.value.split(/\s+/);
            const wordCountValue = words.length;

            // Update the word count
            wordCount.textContent = ` ${wordCountValue}/30`;

            // Check if the word count exceeds the limit (30)
            if (wordCountValue > 30) {
                // Trim the textarea content to 30 words
                const trimmedWords = words.slice(0, 30);
                textarea.value = trimmedWords.join(' ');

                // Update the word count again
                wordCount.textContent = '30/30';

                // Disable the textarea
                textarea.disabled = true;

                // Display the validation error
                // errorElement.textContent = 'Maximum word count reached.';
            } else {
                // Enable the textarea if it was previously disabled
                textarea.disabled = false;

                // Clear the validation error
                errorElement.textContent = '';
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Check if the success message is present in the page
            const successMessage = document.querySelector('.alert-success');

            if (successMessage) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Notification Added Sucessfully',
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    window.location.href = "{{ URL::to('/notification-management') }}";
                }, 2000);
            }
        });
    </script>
@endsection
