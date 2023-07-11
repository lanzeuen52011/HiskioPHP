<html>
    <head>
        <title>電商管理平台</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
        <script
            src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
            crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{ asset('/vendor/fontawesome-free/css/all.min.css') }}" type="text/css">
        <!-- href最左邊加個"/"才會抓到東西 -->
        <!-- <link rel="stylesheet" href="/resources/vendor/"> -->
        <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    </head>
    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            @include('layouts.admin_nav')
            <div id="content-wrapper" class="d-flex flex-column p-3 mt-3">
                @yield('content')
            </div> 
        </div> 
    </body>
    @stack('scripts')
    <script src="{{ asset('/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</html>