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
            height: 290px;
            margin-left: 0px;
        }
        .login-box-body {
            width: 60%;
            margin-left: 2%;
            padding: 0% 7%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Align content to the top */

        }

        .login-box-msg {
            color: black;
            font-size: 24px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Add text shadow */
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

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        .button-container button {
            width: 150px;
            height: 40px;
        }
        .button-space {
            width: 20px;
        }
        a {
            color: white;
        }
        a:hover {
            color: inherit;
            text-decoration: none;
        }
        .text-red {
            color: red;
            font-size: 14px;
            margin-block-end: 30px;
            text-align: left;
        }

        form{
            width: 102%;"
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
        <div class="cont" >
            <div id="box-login-personalize">
                    <div class="container1">
                        <img src="{{ asset('img/soshel.png') }}" alt="Logo">
                    </div>
                    <div class="vl"></div>

                    <div class="login-box-body">

                        <form  method="POST" action="{{ route('password.reset') }}">

                            @csrf
                            <div class="form-group has-feedback password-container">
                                <input type="text" class="form-control" placeholder="Enter OTP here" name="otp" required autofocus AUTOCOMPLETE='off'>
                                @error('otp')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="button-container">
                                {{-- <div class="col-xs-12"> --}}
                                <button type="submit" id="sign-btn">Verify</button>
                                <div class="button-space"></div>
                                <a href="{{ route('forget.password') }}" id="sign-btn" class="back">Back</a>

                                {{-- </div> --}}
                            </div>
                        </form>
                    </div>
            </div>
        </div>
        
        
    </body>
</html>