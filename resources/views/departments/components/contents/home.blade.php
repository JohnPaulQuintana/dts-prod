@extends('departments.index')

@section('head')
<meta charset="utf-8" />
<title>Departments Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesdesign" name="author" />
<!-- App favicon -->
<link rel="shortcut icon" href="assets/images/favicon.ico">

<!-- Plugin css -->
<link rel="stylesheet" href="{{ asset('assets/libs/@fullcalendar/core/main.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('assets/libs/@fullcalendar/daygrid/main.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('assets/libs/@fullcalendar/bootstrap/main.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('assets/libs/@fullcalendar/timegrid/main.min.css') }}" type="text/css">

{{-- toast css --}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/toastr/build/toastr.min.css') }}">

<!-- twitter-bootstrap-wizard css -->
<link rel="stylesheet" href="assets/libs/twitter-bootstrap-wizard/prettify.css">

<link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css">

<link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">

<link href="assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">

<link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet">

<!-- Plugins css -->
<link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

<!-- jquery.vectormap css -->
<link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- Responsive datatable examples -->
<link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />  

<!-- Bootstrap Css -->
<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <!-- start page title -->
    <div class="row">
        @include('departments.components.parts.title')
    </div>
    <!-- end page title -->

    <div class="row">
        @include('departments.components.parts.card')
    </div><!-- end row -->

    {{-- calendar events --}}

    <div class="row mb-4">
        <div class="col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    {{-- <button class="btn font-16 btn-primary waves-effect waves-light w-100">
                        Event's list
                    </button> --}}

                    <div id="external-events">
                        <br>
                        <h5 class="text-info text-center">Event List's</h5>
                        <div class="ready-events" style="overflow-y: auto;max-height:400px;">
                            
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
    {{-- <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item text-danger">Reports</a>
                        </div>
                    </div>

                    <h4 class="card-title mb-4">Latest Documents</h4>

                    <div class="table-responsive">
                        <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Tracking No.</th>
                                    <th>Requested To</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td><h6 class="mb-0"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>{{ $log->trk_id }}</h6></td>
                                        <td>{{ $log->user_department }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>
                                            @switch($log->status)
                                                @case("forwarded")
                                                    <!-- Display something when status is 1 -->
                                                    <span class="badge bg-info p-2"><b>{{ $log->status }}</b></span>
                                                    @break
                                                @case("rejected")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-danger p-2"><b>{{ $log->status }}</b></span>
                                                    @break
                                                @case("on-going")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-warning p-2"><b>{{ $log->status }}</b></span>
                                                    @break
                                                @case("done")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-success p-2"><b>{{ $log->status }}</b></span>
                                                    @break
                                                @default
                                                    <!-- Display something for other status values -->
                                                    Other Status Content
                                            @endswitch
                                        </td>
                                        <td>{{ $log->formatted_created_at }}</td>
                                        <td>{{ $log->formatted_time }}</td>
                                    </tr>
                                @endforeach
                                
                                <!-- end -->
                                
                            </tbody><!-- end tbody -->
                        </table> <!-- end table -->
                    </div>
                </div><!-- end card -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div> --}}

@endsection

@section('script')
        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>

        <!-- jquery.vectormap map -->
        <script src="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js"></script>

        <!-- Required datatable js -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

        <!-- Responsive examples -->
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

        <!-- Plugins js -->
        <script src="assets/libs/dropzone/min/dropzone.min.js"></script>

        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
        <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/js/pages/form-advanced.init.js"></script>

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
        <script src="{{ asset('assets/js/pages/calendar.dept.js') }}"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>

        @if (session()->has('notification'))
        <script>
            $(document).ready(function() {
                // Set Toastr options
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": 300,
                    "hideDuration": 1000,
                    "timeOut": 5000,
                    "extendedTimeOut": 1000,
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
                var notificationJson = {!! json_encode(session('notification')) !!};
                var notification = JSON.parse(notificationJson);
                console.log(notification)
                toastr[notification.status](notification.message);
            });
        </script>
        @endif
@endsection