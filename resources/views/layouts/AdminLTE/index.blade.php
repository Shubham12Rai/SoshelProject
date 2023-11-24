<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.AdminLTE._includes._head')

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        .content-wrapper {
            background-color: white;
        }

        .page-title {
            position: absolute;
            font-family: 'Lato', sans-serif;
            font-size: 26px;
            letter-spacing: 3px;
        }
    </style>


</head>

<body class="hold-transition  {{ \App\Models\Config::find(1)->layout }} sidebar-mini"
    style="background-color: rgba(239,239,239);">
    <div class="wrapper">

        @include('layouts.AdminLTE._includes._menu_superior')

        {{-- <div style="margin-top: 3px;"> --}}
        @include('layouts.AdminLTE._includes._menu_lateral')
        {{-- </div> --}}

        <div class="content-wrapper">
            <nav class="navbar navbar-static-top" id="menu_sup_corpo"
                style="background-color:white; margin-bottom:0; padding-bottom:0;navbar-header.a:color:#fff;">
                <div class="navbar-header">
                    <a href="" class="navbar-brand heading" id=""
                        style="color:black; font-size: 23px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); margin-top:20%;'"><i
                            class="fa fa-@yield('icon_page')"></i> <b class = "page-title">@yield('title')</b></a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse-2" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
                {{-- <div class="navbar-collapse collapse" id="navbar-collapse-2" aria-expanded="false" style="height: 1px;">
                        <ul class="nav navbar-nav">

                            @yield('menu_pagina')

                        </ul>
                    </div> --}}
            </nav>

            @if (Session::has('flash_message'))
                <div class="{{ Session::get('flash_message')['class'] }}" style="padding: 10px 20px;"
                    id="flash_message">
                    <div style="color: #fff; display: inline-block; margin-right: 10px;">
                        {!! Session::get('flash_message')['msg'] !!}
                    </div>
                </div>
            @endif

            <section class="content" style="background-color: white;">
                <div class="row" style="height:100%;">
                    <div class="col-md-12">

                        @yield('content')

                    </div>
                </div>
            </section>

        </div>

        {{-- @include('layouts.AdminLTE._includes._footer') --}}

    </div>

    @include('layouts.AdminLTE._includes._script_footer')

    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="//code.jquery.com/jquery-1.12.3.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>
