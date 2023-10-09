@extends('admin.index')

@section('head')
    <meta charset="utf-8" />
    <title>Administrator Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Plugin css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/@fullcalendar/core/main.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/libs/@fullcalendar/daygrid/main.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/libs/@fullcalendar/bootstrap/main.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/libs/@fullcalendar/timegrid/main.min.css') }}" type="text/css">

    {{-- toast css --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/toastr/build/toastr.min.css') }}">

    <!-- twitter-bootstrap-wizard css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/twitter-bootstrap-wizard/prettify.css') }}">
    
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/libs/spectrum-colorpicker2/spectrum.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet">

    <!-- jquery.vectormap css -->
    <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />  

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        /* modal */
        #departments-card{
            max-height: 260px;
            margin-bottom: 10px;
            /* border: 1px solid red; */
            overflow-x: auto;
        }
        #departments-card::-webkit-scrollbar{
            width: 0;
        }
        #departments-card-items{
            height: 65px;
            /* border: 1px solid red; */
        }
    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        @include('admin.components.parts.title')
    </div>
    <!-- end page title -->

    <div class="row">
        @include('admin.components.parts.card')
    </div><!-- end row -->

    {{-- calendar events --}}

    <div class="row mb-4">
        <div class="col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <button type="button" class="btn font-16 btn-primary waves-effect waves-light w-100"
                        id="btn-new-event" data-bs-toggle="modal" data-bs-target="#event-modal">
                        Create New Event
                    </button>

                    <div id="external-events">
                        <br>
                        <p class="text-muted">Events history</p>
                        <div class="ready-events" style="overflow-y: auto;height:350px;">
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div> <!-- end col-->
        <div class="col-xl-9">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="h-50" id="calendar" style="height: 30vh;"></div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row-->
    <div style='clear:both'></div>

    

@endsection

@section('script')
    <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

        
        <!-- apexcharts -->
        {{-- <script src="{{ asset('') }}assets/libs/apexcharts/apexcharts.min.js"></script> --}}

        <!-- jquery.vectormap map -->
        <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        
        <!-- Responsive examples -->
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>

        {{-- <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script> --}}

        <!-- toastr plugin -->
        <script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
        <!-- toastr init -->
        <script src="{{ asset('assets/js/pages/toastr.init.js') }}"></script>

        <!-- plugin js -->
        <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jquery-ui-dist/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/libs/@fullcalendar/core/main.min.js') }}"></script>
        <script src="{{ asset('assets/libs/@fullcalendar/bootstrap/main.min.js') }}"></script>
        <script src="{{ asset('assets/libs/@fullcalendar/daygrid/main.min.js') }}"></script>
        <script src="{{ asset('assets/libs/@fullcalendar/timegrid/main.min.js') }}"></script>
        <script src="{{ asset('assets/libs/@fullcalendar/interaction/main.min.js') }}"></script>

        <!-- Calendar init -->
        <script src="{{ asset('assets/js/pages/calendar.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
@endsection