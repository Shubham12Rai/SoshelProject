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
            height: 70vh;
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
            height: 300px;
            margin-left: 0px;
           
        }
        .login-box-body {
            width: 60%;
            margin-left: 2%;
            padding: 0% 7%;
            text-align: center;
            

        }

        .log-body{
            width: 100%;
            padding: 0% 0%;
        }

        .login-box-msg {
            color: black;
            font-size: 24px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Add text shadow */
            margin-bottom: 40px;
            
        }
        .form-control {
            border: 1px solid rgb(236, 230, 230);
            border-radius: 10px;
            height: 47px;
            width: 450px;
            background-color: rgb(232, 240, 254);




        }
        .form-control.input-field{
            background: #C4C4C454;
            font-weight: 600;
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
            
        }

        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            top: 25%;
            right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
            
        }

        .forgot-password-link {
            float: right;
            color: #212D99;
            margin-bottom: 20px;
        }
        .text-red {
            color: red;
            font-size: 14px;
            margin-block-end: 30px;
            text-align: left;
        }
        .log-body{
            margin-bottom: 20px;
            width: 100%;
        }
        
    </style>
@stop

<!DOCTYPE html>
<html lang="en">
    <head>

        @include('layouts.AdminLTE._includes._head')

    </head>
    <body>
        @if (session('status'))
            <div id="flash-message" class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="cont">
            <div id="box-login-personalize">
                    <div class="container1">
                        <img style="" src="{{ asset('img/soshel.png') }}" alt="Logo">
                    </div>
                    <div class="vl"></div>

                    <div class="login-box-body">
                        <h3><p class="login-box-msg">Log-in</p></h3>

                        <form  method="POST" action="{{ route('login') }}" style="width: 102%;">

                            @csrf
                            <div class="form-group has-feedback password-container">
                                <input id="email" type="text" class="form-control input-field" placeholder="Email Address" name="email" value="{{ old('email') }}" required autofocus  AUTOCOMPLETE='off'>
                                @if ($errors->has('email'))
                                    <div><p class="text-red">{{ $errors->first('email') }}</p></div>
                                @endif
                            </div>
                            <div class="form-group has-feedback">
                                <input id="password" type="password" class="form-control input-field" placeholder="Password" name="password" required AUTOCOMPLETE='off'>
                                <span class="password-toggle" onclick="togglePasswordVisibility()"><i id="eyeChangeId" class="fa fa-eye"></i></span>
                                @error('password')
                                    <div><p class="text-red">{{ $message }}</p></div>
                                @enderror
                            </div>
                            <div>
                                <a href="{{ route('forget.password') }}" class="forgot-password-link">Forgot Password ?</a>
                            </div>
                            <div class="row">
                                <!--<div class="col-xs-8">
                                <div class="checkbox icheck">
                                    <label>
                                    <input name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}> Remember me
                                    </label>
                                </div>
                                </div>-->
                                <div class="col-sm-12 log-body">
                                <button type="submit" id="sign-btn" >Log In</button>
                                </div>
                                <br/><br/><br/>
                                <!--<div class="col-xs-12">
                                    <center>
                                        <a href="{{ route('password.request') }}">Forgot password?</a>
                                        <br/>
                                        <a href="{{ route('register') }}">Sign up</a>
                                    </center> -->
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
        @include('layouts.AdminLTE._includes._script_footer')
        <script>
          $(function () {
            $('input').iCheck({
              checkboxClass: 'icheckbox_square-blue',
              radioClass: 'iradio_square-blue',
              increaseArea: '20%'
            });
          });
        </script>

        <script type="text/javascript">
             function togglePasswordVisibility() {
                var passwordInput = document.getElementById("password");
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

        <script>
            setTimeout(function() {
                $('#flash-message').fadeOut('slow');
            }, 3000);
        </script>
    </body>
</html>