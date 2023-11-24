@extends('layouts.AdminLTE.index')

{{-- @section('icon_page', 'user')  --}}

@section('title', 'CHANGE PASSWORD')
<link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>

<style>
    .content-wrapper {
        background-color: white !important;
    }

    .input-field {
        font-size:24px;
        font-weight:500;
        font-family: 'Lato', sans-serif;
    }

    .input-enter-field {
        font-size:22px;
        font-weight:500;
        font-family: 'Lato', sans-serif;
    }
</style>
@section('content')
		<div>
			
			<div class="tab-content">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('message'))
                    <div class="alert alert-{{ session('message_type') }}">
                        {{ session('message') }}
                    </div>
                @endif

				<div class="active tab-pane" id="profile" style="margin-left:10%; ">
					<form action="{{ route('profile.update.password',$user->id) }}" method="post" style="">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        
                        <div>
                            <a  href="{{ route('home') }}" id="btn" style="font-family: 'Lato', sans-serif;background-color:#fd65d3;float: right; padding-left:39px; padding-top:7px;width:148px;height:44px;">Cancel</a>
                            <button type="submit" href="" id="btn" style="font-family: 'Lato', sans-serif;background-color:#fd65d3;float: right;width:148px;height:44px;">Update</button>
            
                        </div>
                        <div class="col-sm-12" style="margin-top: 60px;">
						<div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="password" style="color:black;width:250px;"><h4 class = "input-field">Old Password :</h4></div>
                            <input class = "input-enter-field" type="password" name="old_password" placeholder="Enter Old Password" required id="field" style="border: 1px solid #ccc; width:468.01px; border-radius: 5px; outline: none; padding-left: 10px;">
                            
                        </div>
						<div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="password" style="color:black;width:250px;"><h4 class = "input-field">New Password :</h4></div>
                            <input class = "input-enter-field" type="password" name="password" placeholder="Enter New Password" required id="password" style="border: 1px solid #ccc; width:468.01px; border-radius: 5px; padding-left: 10px;">
                            <span class="password-toggle" onclick="togglePasswordVisibility()"><i id="eyeChangeId" class="fa fa-eye"></i></span>
                        </div>
                        <div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="password" style="color:black;width:250px;"><h4 class = "input-field">Confirm Password :</h4></div>
                            <input class = "input-enter-field" type="password" name="password_confirmation" placeholder="Confirm New Password" required id="field" style="border: 1px solid #ccc; width:468.01px; border-radius: 5px; padding-left: 10px;">
                        </div>
                        </div>
					</form>
			</div>
		</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
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
@endsection