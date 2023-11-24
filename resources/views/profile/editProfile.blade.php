@extends('layouts.AdminLTE.index')

@section('title', 'ADMIN PROFILE')
<link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>

<style>
    .tab-content {
        margin-bottom: 20px;
    }

    .tab-content label {
        display: block;
    }

    .tab-content input {
        width: 100%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .tab-content .error {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }
</style>

@section('content')
    <div>
        <div class="tab-content">
            <div class="active tab-pane" id="profile" style="margin-left:10%; ">
                <form action="{{ url('/update/profile/' . $user->id) }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="post">

                    <div class="">
                        <a href="{{ route('home') }}" id="btn"
                            style="background-color:#fd65d3;float: right; padding-left:20px; padding-top:5px;font-family: 'Lato', sans-serif;width: 133px;height:35px;padding: 2px 0 0 30px; ">Cancel</a>
                        <button type="submit" id="btn"
                            style="margin-left: px; background-color:#fd65d3;float: right;font-family: 'Lato', sans-serif;width: 133px;height: 35px; ">Save</button>

                    </div>
                    <div class="col-sm-12" style="margin-top: 100px;">
                        <div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="name" style="color:black;width: 250px;">
                                <h4 style = "font-family: 'Lato', sans-serif;font-size:24px;font-weight:500;">Name :</h4>
                            </div>
                            <input type="text" name="name" value="{{ $user->name }}" required id="inputfield" style = "margin-left: 0;font-family: 'Lato', sans-serif;font-size:22px;font-weight:500;">
                        </div>
                        <div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="email" style="color:black;width: 250px;">
                                <h4 style = "font-family: 'Lato', sans-serif;font-size:24px;font-weight:500;">Email Id :</h4>
                            </div>
                            <div style="color:#908282;">
                                <h4 style = "font-family: 'Lato', sans-serif;font-size:22px;font-weight:500;">{{ $user->email }}</h4>
                            </div>
                        </div>
                        <div class="form-group" style="display: flex; margin-left: 30px;">
                            <div for="mobile_number" style="color:black;width: 250px;">
                                <h4 style = "font-family: 'Lato', sans-serif;font-size:24px;font-weight:500;">Phone Number :</h4>
                            </div>
                            <input type="text" name="mobile" value="{{ $user->mobile_number }}" required style = "margin-left: 0;font-family: 'Lato', sans-serif;font-size:22px;font-weight:500;"
                                id="secondInput">
                        </div>
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                        @if ($errors->has('mobile'))
                            <span class="error">{{ $errors->first('mobile') }}</span>
                        @endif
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
