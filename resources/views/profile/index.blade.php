@extends('layouts.AdminLTE.index')

@section('title', 'ADMIN PROFILE')
<link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>


@section('content')
    <style>
        .content-wrapper {
            background-color: white !important;
        }
         #btn {
            font-size: 20px;
            color: white;
            width: 133px;
            height: 35px;
            background-color: #FD65D3;
            border: 1px solid rgb(236, 230, 230);
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
            margin-left: 15px;
            text-decoration: none;
         }

         .input-field {
            font-family: 'Lato', sans-serif;
            font-size:24px;
            font-weight:500;
         }

         .input-enter-field {
            font-family: 'Lato', sans-serif;
            font-size:22px;
            font-weight:500;
         }
    </style>
    <div>
        <div class="tab-content">
            <div class="active tab-pane" id="profile" style="margin-left:10%; ">
                <form action="{{ url('/edit/profile/' . $user->id) }}" method="get">

                    <div class="">
                        <a href="{{ route('home') }}" id="btn"
                            style="background-color:#fd65d3;float: right; padding-left:20px; padding-top:5px;font-family: 'Lato', sans-serif; width: 133px;height:35px;padding: 2px 0 0 30px; ">Cancel</a>
                        <button type="submit" id="btn" style="background-color:#fd65d3;float: right;font-family: 'Lato', sans-serif; ">Save</button>
                        <button type="submit" id="btn" style="background-color:#fd65d3;float: right;font-family: 'Lato', sans-serif; ">Edit</button>

                    </div>
                    <div class="col-sm-12" style="margin-top: 60px;">
                        <div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="name" style="color:black;width: 250px;">
                                <h4 class = "input-field">Name :</h4>
                            </div>
                            <div style="color:#908282">
                                <h4 class = "input-enter-field">{{ $user->name }}</h4>
                            </div>
                        </div>
                        <div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="email" style="color:black;width: 250px;">
                                <h4 class = "input-field">Email Id :</h4>
                            </div>
                            <div style="color:#908282">
                                <h4 class = "input-enter-field">{{ $user->email }}</h4>
                            </div>
                        </div>
                        <div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="mobile_number" style="color:black;width: 250px;">
                                <h4 class = "input-field">Phone Number :</h4>
                            </div>
                            <div style="color:#908282">
                                <h4 class = "input-enter-field">{{ $user->mobile_number }}</h4>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
@endsection
