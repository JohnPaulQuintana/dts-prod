@extends('admin.index')

@section('head')
    <meta charset="utf-8" />
    <title>Administrator Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Include the CSRF token in the head section -->
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

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
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Document Tracking</a></li>
                    <li class="breadcrumb-item active">Report's</li>
                </ol>
            </div>

        </div>
    </div>
    {{-- per tracking report --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">


                    <h4 class="card-title mb-4">
                        <span class="me-2">Generate History Reports</span>   
                    </h4>
                       {{-- {{ $creds['trackingNos'] }} --}}
                    <form action="{{ route('generate.reports') }}" method="POST" class="row g-3">
                        @csrf
                        <input type="text" name="action" id="" value="per-tracking">
                        <div class="col-sm-3">
                            <label for="tracking-number"><span class="">Tracking Number<span class="required text-danger">*</span></span></label>
                            {{-- <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="email@example.com"> --}}
                            <select name="trk" id="from" class="form-control">
                                <option value="all">Tracking Number</option>
                                @foreach ($creds['trackingNos'] as $trk)
                                @if ($trk->trk_id)
                                    <option value="{{ $trk->id }}">{{ $trk->trk_id }}</option>
                                @endif
                                    
                                @endforeach      
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="from">From <span class="required text-danger">*</span></label>
                            <input type="date" class="form-control" id="from" value="" name="from" required>
                        </div>
                        <div class="col-sm-3">
                            <label for="to">To <span class="required text-danger">*</span></label>
                            <input type="date" class="form-control" id="to" value="" name="to" required>
                        </div>
                        <div class="col-sm-3">
                            <label for="office">Office <span class="required text-danger">*</span></label>
                            <select name="office" id="office" class="form-control">
                                {{-- <option value="all">All</option> --}}
                                @foreach ($creds['offices'] as $office)
                                    <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="processed-by">Processed By <span class="required text-danger">*</span></label>
                            <select name="processed-by" id="processed-by" class="form-select">
                                {{-- <option value="all">All</option> --}}
                                @foreach ($creds['users'] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="status">Status <span class="required text-danger">*</span></label>
                            <select name="status" id="" class="form-select">
                                {{-- <option value="all">All</option> --}}
                                <option value="*">All</option>
                                <option value="completed">Completed</option>
                                <option value="archived">Discontinued</option>
                                <option value="approved">On Going</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="order-by">Order By <span class="required text-danger">*</span></label>
                            <select name="order-by" id="order-by" class="form-select">
                                <option value="asc">Ascending Order</option>
                                <option value="desc">descending Order</option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <input type="submit" class="form-control btn btn-success" id="submit" value="Generate" name="">
                            
                        </div>
                          
                    </form>
                </div><!-- end card -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>

    {{-- report for every end user's request --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">


                    <h4 class="card-title mb-4">
                        <span class="me-2">Generate History Reports for every user's</span>   
                    </h4>
                       {{-- {{ $creds['trackingNos'] }} --}}
                    <form action="{{ route('generate.reports') }}" method="POST" class="row g-3">
                        @csrf
                        <input type="text" name="action" id="" value="all-user">
                        
                        <div class="col-sm-3">
                            <label for="processed-by">Processed By <span class="required text-danger">*</span></label>
                            <select name="processed-by" id="processed-by" class="form-select">
                                {{-- <option value="all">All</option> --}}
                                <option value="*">All user's</option>
                                {{-- @foreach ($creds['users'] as $user)
                                    
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="status">Status <span class="required text-danger">*</span></label>
                            <select name="status" id="" class="form-select">
                                {{-- <option value="all">All</option> --}}
                                <option value="*">All</option>
                                <option value="completed">Completed</option>
                                <option value="archived">Discontinued</option>
                                <option value="approved">On Going</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="order-by">Order By <span class="required text-danger">*</span></label>
                            <select name="order-by" id="order-by" class="form-select">
                                <option value="asc">Ascending Order</option>
                                <option value="desc">descending Order</option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <input type="submit" class="form-control btn btn-success" id="submit" value="Generate" name="">
                            
                        </div>
                          
                    </form>
                </div><!-- end card -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>

    {{-- report modal --}}
    @include('admin.components.modals.report')
    {{-- open document modal --}}
    {{-- @include('admin.components.modals.openDocument') --}}
    {{-- open timeline modal --}}
    {{-- @include('admin.components.modals.timeline') --}}
    {{-- open pin modal --}}
    {{-- @include('admin.components.modals.pin') --}}
@endsection

@section('script')
    <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>


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

        <!-- toastr plugin -->
        <script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
        <!-- toastr init -->
        <script src="{{ asset('assets/js/pages/toastr.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

        {{-- custom js --}}
        <script>
            $(document).ready(function(){
                
                // process request for all departments and users
                function getDepartmentWithUsers(office_id) {
                    // alert(id);
                    // Return a promise
                    return new Promise(function(resolve, reject) {
                        // Make an AJAX request to retrieve logs
                        $.ajax({
                            url: `/departments-with-users/${office_id}`, // Replace with your route URL
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                // Resolve the promise with the response
                                resolve(response);
                            },
                            error: function(xhr, status, error) {
                                // Reject the promise with an error
                                reject(xhr.responseText);
                            }
                        });
                    });
                }

                // custom alert
                // function showalert(stats,message){
                //     toastr.options = {
                //     "closeButton": false,
                //     "debug": false,
                //     "newestOnTop": false,
                //     "progressBar": false,
                //     "positionClass": "toast-top-right",
                //     "preventDuplicates": false,
                //     "onclick": null,
                //     "showDuration": 300,
                //     "hideDuration": 1000,
                //     "timeOut": 5000,
                //     "extendedTimeOut": 1000,
                //     "showEasing": "swing",
                //     "hideEasing": "linear",
                //     "showMethod": "fadeIn",
                //     "hideMethod": "fadeOut"
                //     };
                //     toastr[stats](message);
                // }
            })
        </script>
        {{-- // notification --}}
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
                    "preventDuplicates": true,
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
                if(notification.modal){
                    $('#print-report-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('.report_id').val(notification.id)
                    $('.pdf-container').attr('src',notification.path)
                    $('#print-report-modal').modal('show')
                }
            });
        </script>
    @endif
@endsection
