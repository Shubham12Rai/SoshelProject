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
            /* height: 70vh; */
            max-height:600px;
        }

        .container1{
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .vl {
            /* width: 0%; */
            /* border-left: 1px solid rgb(117, 126, 117); */
            border: 1px solid rgba(144, 130, 130, 1);
            height: 300px;
            margin-top: 50px;
            opacity: 0.3;
            /* margin-left: 0px; */
        }
        p.login-box-msg {
            padding: 0 20px 60px 20px;
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

        .password-toggle {
            position: absolute;
            top: 51%;
            right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .button-container button {
            width: 150px;
            height: 40px;
            
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
        .confirmed{
                float: left;
                margin-left: 0%;
                color: red;

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
                    <div class="container1" style="margin-left: 40px">
                        <img src="{{ asset('img/soshel.png') }}" alt="Logo">
                    </div>
                    <div class="vl"></div>

                    <div class="login-box-body">
                        <h3><p class="login-box-msg">Set New Password</p></h3>

                        <form  method="POST" action="{{ route('password.update') }}" style="width: 102%;">

                            @csrf
                            <div class="form-group has-feedback password-container">
                                <label for="" style="float: left;">Set New Password</label>
                                <input type="password" class="form-control" placeholder="Enter password" name="password" required="" minlength="6" AUTOCOMPLETE='off'>
                            </div>
                            
                            <div class="form-group has-feedback password-container">
                                <label for="password_confirmation" style="float: left;">Confirm Password</label>
                                <input id="password_confirmation" class="form-control" type="password" placeholder="Retype password" name="password_confirmation" minlength="6" required>
                                <span class="password-toggle" onclick="togglePasswordVisibility()"><i id="eyeChangeId" class="fa fa-eye"></i></span>
                                @error('password')
                                    <div class="confirmed">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="button-container">
                                {{-- <div class="col-xs-12"> --}}
                                <button type="submit" id="sign-btn">Update</button>
                                <div class="button-space"></div>
                                <a href="{{ route('login') }}" id="sign-btn" class="back">Back</a>
                                {{-- </div> --}}
                            </div>
                        </form>
                    </div>
            </div>
        </div>
        
        <script type="text/javascript">
            function togglePasswordVisibility() {
               var passwordInput = document.getElementById("password_confirmation");
               var eyeIcon = document.getElementById("eyeChangeId");
               if (passwordInput.type === "password") {
                   passwordInput.type = "text";
                   eyeIcon.classList.remove("fa-eye");
                   eyeIcon.classList.add("fa-eye-slash");
               } else {
                   passwordInput.type = "password";
                   eyeIcon.classList.remove("fa-eye-slash");
                   eyeIcon.classList.add("fa-eye");
               }
           }
       </script>
    </body>
</html>