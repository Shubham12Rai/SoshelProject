<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>
        Soshel | @yield('title')
</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/select2/dist/css/select2.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/AdminLTE.min.css') }}">
<!-- adminlte Skins. -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/skins/_all-skins.min.css') }}">
<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/morris.js/morris.css') }}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/jvectormap/jquery-jvectormap.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/bootstrap-datepicker/dist/css/
        bootstrap-datepicker.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/bootstrap-daterangepicker/
        daterangepicker.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/iCheck/square/blue.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,
        300italic,400italic,600italic">
<!-- CSS Custom -->
<link rel="stylesheet" href="{{ asset('assets/custom/style.css') }}">

<!-- jQuery 3 -->
<script src="{{ asset('assets/adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- MAskMoney -->
<script src="{{ asset('assets/plugins/maskMoney/jquery.maskMoney.min.js') }}"></script>

<!-- Datatable plugin CSS file -->
<link rel="stylesheet" href=
"https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
  
     <!-- jQuery library file -->
     <script type="text/javascript" 
     src="https://code.jquery.com/jquery-3.5.1.js">
     </script>
  
      <!-- Datatable plugin JS library file -->
     <script type="text/javascript" src=
"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js">
     </script>
<style>
        .link_menu_page{ color:#222d32; }
        .caixa-alta { text-transform:uppercase; }
        .caixa-baixa { text-transform:lowercase; }
        .input-text-center{ text-align:center; }
        .border-box{border: 1px solid rgb(24, 22, 22);}
        .row {margin-bottom: 10px; display: inline;}
        .col-md-6 {width: 50%; float: left; }
        .first{display: flex; margin-left: 5%; }
        .rounded-corner{border-radius: 10px;}
        .radio-buttons {
        display: flex;
        align-items: center;
        margin-left: 7%;

        }

        .input{
                width: 238px;
                height: 34px;
                color: #574C4C;
                margin-left: 20px;
        }

        .logo-lg{
                margin-top: 2%;
                width: 75%;
                height: auto;
        }

        .radio-button {
        display: flex;
        align-items: center;
        margin-right: 100px;
        cursor: pointer;
        }

        .radio-button input[type="radio"] {
        display: none;
        }

        .navbar.navbar-static-top{
                border-bottom: 1px solid rgb(236, 235, 235);
                border-left: none;
                border-right: none;
                border-top: none;
        }

        .radio-dot {
                width: 20px;
                height: 13px;
                border-radius: 50%;
                border: 2px solid rgb(14, 13, 13);
                margin-right: 5px;
                position: relative;
        }

        .radio-dot::after {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 7px;
                height: 4px;
                border-radius: 50%;
                background-color: rgb(24, 23, 23);
                opacity: 0;
        }

        .radio-dot:hover::after {
                opacity: 0.3;
        }

        .radio-button input[type="radio"]:checked + .radio-dot::after {
                opacity: 1;
        }

        .radio-label {
        font-size: 14px;
        }

        .date-select {
        margin-left: 30px;
        display: flex;
        align-items: center;
        }

        .date-separator {
        margin: 0 40px;
        }

        .apply{
                margin-left: 30px;
                text-align: center;
                background-color: #FD65D3;
                color: white;
        }
        .button.apply:hover {
                color: white;
                outline: none;
        }

        .icon{
                margin-top: 8%;
        }

        .count{
                float:right;
                color:black;
                white-space: nowrap;
        }

        .text{
                width: 600;
                size: 21px;
                line height: 25.2px;
                color:black;
                white-space: nowrap;
        }

        .txt{
                color:black;
                white-space: nowrap;

        }
        #btn {
            font-size: 20px;
            color: white;
            width: 12%;
            height: 40px;
            background-color: #FD65D3;
            border: 1px solid rgb(236, 230, 230);
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
            margin-left: 15px;
            text-decoration: none;

            
        }
        .btn-flat{
                color: black;
                margin-left: 10%;
                
        }
        a.btn-flat:hover{
                color: inherit;
                text-decoration: none;
                color: inherit;
        }
        #corner-dropdown {
                border: 0.5px solid gray;
                border-radius: 10px;
                overflow: hidden;
        }
        .arrow-down {
                width: 0;
                height: 0;
                border-left: 10px solid transparent;
                border-right: 10px solid transparent;
                border-top: 7px solid black;
                display: inline-block;
                margin-left: 5px;
                margin-top: 7px;
        }
        .skin-blue .sidebar-menu>li.active>a{
                background: rgba(214, 214, 214, 0.74) !important;
        }

        .hr {
            width: 70%;
            border-left: 2px solid rgb(190, 197, 190);
            height: 1px;
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 15px;
            background-color: rgb(190, 197, 190);
           
        }
        .field{
                width: 50%;
        }
        .password-toggle {
                position: inherit;
                margin-left: -40px;
                margin-top: 12px;
                cursor: pointer;
                right: 345px;
                cursor: pointer;
                font-size: 22px;
        }
        #inputfield{
                border: 1px solid #ccc;
                margin-left: 15%;
                width:40%;
                border-radius: 10px;
                outline: none;
                padding-left: 1%;
                color:#908282;
        }
        #secondInput{
                border: 1px solid #ccc;
                margin-left: 7%;
                width:40%;
                border-radius: 10px;
                outline: none;
                padding-left: 1%;
                color:#908282;

        }
        .view{
                height: 15px;
                width:20px;
        }
        .block{
                height: 15px;
                width:15px;
        }

</style>

<script>
        $(function(){
                $.fn.datepicker.dates['pt-br'] = {
                        days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                        daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
                        months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho",
                                "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                        monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set",
                                "Out", "Nov", "Dez"],
                        today: "Hoje",
                        monthsTitle: "Meses",
                        clear: "Limpar",
                        format: "dd/mm/yyyy"
                };
        });
</script>

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> --}}

@yield('layout_css')