@section('title', 'Login')
@section('layout_css')
    <style>
        #box-login-personalize{
            width: 100%;
            /* margin: 3% auto; */
            display: flex; /* Added display: flex */
            flex-direction: row; /* Horizontal arrangement */
            /* justify-content: space-between; Equal space between the divs */
            align-items: center;
            
        }
        .cont {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            width: 1000px;
            margin: 0 auto;
            margin-top: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh;
        }

        .container1{
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .vl {
            width: 0%;
            border-left: 1px solid rgb(117, 126, 117);
            height: 270px;
            margin-left: 6%;
        }
        .Forgot-box-body {
            width: 60%;
            margin-left: 2%;
            padding: 0% 7%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Align content to the top */


        }

        .log-body{
            width: 100%;
            padding: 0% 0%;
        }

        .Forgot-box-msg {
            color: black;
            font-size: 24px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Add text shadow */
            margin-bottom: 30px;
            
        }
        .form-control {
            border: 1px solid rgb(236, 230, 230);
            border-radius: 10px;
            height: 47px;
            width: 450px;
            background-color: rgba(196, 196, 196, 0.33);


        }
        #sign-btn {
            font-size: 24px;
            color: white;
            width: 100%;
            height: 45px;
            background-color: #FD65D3;
            border: 1px solid rgb(236, 230, 230);
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
            margin-top: 10%;
            
        }

        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .text-red {
            color: red;
            font-size: 14px;
            margin-block-end: 30px;
            text-align: left;
        }
        .button-space {
            width: 20px; /* Adjust the width to add the desired space */
        }
        a {
            color: white;
        }
        a:hover {
            color: inherit;
            text-decoration: none;
        }
        .back{
            padding-top: 0.8%;
        }
        
    </style>
@stop

<!DOCTYPE html>
<html lang="en">
    <head>

        @include('layouts.AdminLTE._includes._head')

    </head>
    <body>
        <div class="cont">
            <div id="box-login-personalize">
                    <div class="container1">
                        {{-- <h4 style="color: #47091b; font-size: 40px; "><b>SOSHEL</b></h4> --}}
                        <img src="{{ asset('img/soshel.png') }}" alt="Logo">
                    </div>
                    <div class="vl"></div>

                    <div class="Forgot-box-body">
                        <h3><p class="Forgot-box-msg">Forgot Password</p></h3>

                        <form  method="POST" action="{{ route('otp.verify') }}" style="width: 102%;">

                            @csrf
                            <div class="form-group has-feedback password-container">
                                <label style="color: #000000; float:left;">Enter Email Address</label>
                                <input id="email" type="text" class="form-control" placeholder="Enter email here" name="email" required autofocus AUTOCOMPLETE='off'>
                                @error('email')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                           
                            <div class="button-container">
                                {{-- <div class="col-xs-12"> --}}
                                <button type="submit" id="sign-btn" >Continue</button>
                                <div class="button-space"></div>
                                <a href="{{ route('login') }}" id="sign-btn" class="back">Back</a>
                                {{-- </div> --}}
                            </div>
                            
                        </form>
                    </div>
            </div>
        </div>
       
    </body>

</html>