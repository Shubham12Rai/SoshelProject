<style>


</style>
<header class="main-header menu">
  <a href="{{ route('home') }}" class="logo" style="background-color: white;">
    
    <span class="logo-mini">{!! \App\Models\Config::find(1)->app_name_abv !!}</span>
    {{-- <span class="logo-lg">{!! \App\Models\Config::find(1)->app_name !!}</span> --}}
    <div style="height: 100%; width:150px;">
      <img src="{{ asset('img/soshel.png') }}" style="height: 100%; width: 100%;" alt="">

    </div>

  </a>
  <nav class="navbar navbar-static-top" style="background-color: white;">

    <div class="dropdown" style="float:right; margin-right: 5px; margin-top:7px;">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" style=" background-color:white; border: 1px solid lightgray; outline: none; box-shadow: none;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="hidden-xs" style="color:black; display: flex">
          @if(Auth::user('name'))
            Welcome {{ Auth::user()->name }}
          @endif
          <span class="arrow-down"></span>
          </div>
          
      </button>
      <div class="dropdown-menu" aria-labelledby="" style="width: 100%; margin-top: 5%; height: 135px;padding-top: 15px;">
        <a href="{{ route('profile') }}" class="btn-flat">My Profile</a>
        <div class="hr"></div>
        {{-- <hr style="width: 80%; "> --}}
        <a href="{{ url('/update/password/'.Auth::user()->id) }}" class="btn-flat">Change Password</a>
        <div class="hr"></div>
        <a href="{{ route('logout') }}" class="btn-flat"
            onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log Out</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
      </div>
    </div>
  </nav>
</header>