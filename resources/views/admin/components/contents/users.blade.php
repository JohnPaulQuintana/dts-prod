@extends('admin.index')

@section('head')
    <meta charset="utf-8" />
    <title>Administrator Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

     <!-- Sweet Alert-->
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

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
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

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
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>
        
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Document Tracking</a></li>
                        <li class="breadcrumb-item active">User's</li>
                    </ol>
                </div>
        
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <div class="dropdown float-end">
                        <input type="text" id="search-input" class="" placeholder="Search" style="width: 80%; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a id="trigger-user" href="javascript:void(0);" class="dropdown-item text-success">New User</a>
                            <!-- item-->
                            <a  href="{{ route('administrator.dashboard.offices') }}" class="dropdown-item text-danger">Back to Office</a>
                        </div>
                    </div>

                    <h4 class="card-title mb-4">
                        <span class="text-info">{{ $currentOffice[0]->office_name }}</span> -
                        User List
                    </h4>
                    {{-- {{ $users }} --}}
                    <div class="table-responsive">
                        <table class="table table-centered mb-0 align-middle table-hover table-nowrap user-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Full Name</th>
                                    <th>{{ __('Username') }}</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                {{-- {{ $currentOffice }} --}}
                                @foreach ($users as $user)
                                    <tr>
                                        <td><h6 class="mb-0"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>{{ $user->name }}</h6></td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ __('staff') }}</td>
                                        <td>
                                            @switch($user->status)
                                                @case("forwarded")
                                                    <!-- Display something when status is 1 -->
                                                    <span class="badge bg-info p-2"><b>{{ $user->status }}</b></span>
                                                    @break
                                                @case("rejected")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-danger p-2"><b>{{ $user->status }}</b></span>
                                                    @break
                                                @case("deactivated")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-danger p-2"><b>{{ $user->status }}</b></span>
                                                    @break
                                                @case("archived")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-danger p-2"><b>{{ $user->status }}</b></span>
                                                    @break
                                                @case("active")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-success p-2"><b>{{ $user->status }}</b></span>
                                                    @break
                                                @default
                                                    <!-- Display something for other status values -->
                                                    Other Status Content
                                            @endswitch
                                        </td>
                                        <td>{{ $user->created_at_formatted }}</td>
            
                                        <td width="50px">
                                            <span class="">
                                                 <!-- Archive Link -->

                                                @if ($user->status === 'deactivated' || $user->status === 'archived')
                                                    <a
                                                        class="ri-check-line text-white font-size-18 btn btn-success p-2 activate-account" 
                                                        data-user-id="{{ $user->id }}" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Activate Account">
                                                    </a>
                                                @else
                                                    {{-- {{ route('archive.user', ['user_id' => $user->id]) }} --}}
                                                    <a 
                                                        class="ri-archive-line text-white font-size-18 btn btn-danger p-2 archived" 
                                                        data-user-id="{{ $user->id }}" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Archive User">
                                                    </a>
                                                @endif
                                            
                                                <!-- Forgot Password Link -->
                                                {{-- {{ route('forgot.password', ['user_id' => $user->id]) }} --}}
                                                <a 
                                                    class="ri-key-line text-white font-size-18 btn btn-warning p-2 forgot-password" 
                                                    data-user-id="{{ $user->id }}" 
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-placement="top" 
                                                    title="Forgot Password">
                                                </a>
                                                
                                            </span>
                                        </td>

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
    </div>

    {{-- new user modal --}}
    @include('admin.components.modals.newUser')
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

        <!-- Sweet Alerts js -->
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <!-- Sweet alert init js-->
        <script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

        {{-- custom js --}}
        <script>
            $(document).ready(function(){

                // search functionality
                // Handle input changes in real-time
                $('#search-input').on('input', function () {
                    var searchText = $(this).val().toLowerCase();

                    // Loop through each list item and hide/show based on the search text
                    $('.user-table tbody tr').each(function () {
                        var row = $(this);
                        var rowMatches = false;
                        // Loop through each cell in the row
                        row.find('td').each(function () {
                            var cellText = $(this).text().toLowerCase();

                            // Check if the cell text contains the search text
                            if (cellText.includes(searchText)) {
                                rowMatches = true;
                                return false; // Exit the cell loop early if a match is found
                            }
                        });

                        // Show/hide the row based on whether it matches the search
                        if (rowMatches) {
                            row.show();
                        } else {
                            row.hide();
                        }
                    });
                });

                $('#trigger-user').on('click',function(){
                        // var csrfToken = $('meta[name="csrf-token"]').attr('content');
                        // $.ajax({
                        //     type: "POST",
                        //     url: "/account-manage",
                        //     headers: {
                        //         "X-CSRF-TOKEN": csrfToken
                        //     },
                        //     data: { 'id': id, 'req': req },
                        //     success: function (response) {
                        //         resolve(response); // Resolve the Promise with the response
                        //         // Redirect to the logout route
                        //         // window.location.href = '/logout';
                        //     },
                        //     error: function (err) {
                        //         reject(err); // Reject the Promise with the error
                        //     }
                        // });

                    $('#new-user').modal('show')
                   // Create a URL object from the current window's URL
                    const currentUrl = new URL(window.location.href);

                    // Get the value of the "2" parameter from the URL
                    const officeId = currentUrl.pathname.split('/').pop();

                    $('#office_id_user').val(officeId)
                })

                // archived user account
                $('.archived').on('click',function(){
                    var id = $(this).data('user-id')
                    // alert($id)
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't to Archived this user!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Archived!'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            sendRequests(id,'archived')
                            .then(function (response) {
                                console.log(response); // Log the success message
                                Swal.fire(
                                    'Archived!',
                                    response.message,
                                    response.status
                                )
                                
                                // You can perform other actions here based on the response
                            })
                            .catch(function (error) {
                                console.error(error); // Handle errors here
                            });
                            // Swal.fire(
                            // 'Deleted!',
                            // 'Your file has been deleted.',
                            // 'success'
                            // )
                        }
                    })
                })

                // archived user account
                $('.forgot-password').on('click',function(){
                    var id = $(this).data('user-id')
                    // alert($id)
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Your attempting to reset the password of this account!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Forgot!'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            sendRequests(id,'forgot-password')
                            .then(function (response) {
                                console.log(response); // Log the success message
                                Swal.fire(
                                    'Forgot User Password!',
                                    response.message,
                                    response.status
                                )
                                
                                // You can perform other actions here based on the response
                            })
                            .catch(function (error) {
                                console.error(error); // Handle errors here
                            });
                            // Swal.fire(
                            // 'Deleted!',
                            // 'Your file has been deleted.',
                            // 'success'
                            // )
                        }
                    })
                })

                // archived user account
                $('.activate-account').on('click',function(){
                    var id = $(this).data('user-id')
                    // alert($id)
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Once activated user can login to the system and verify the email!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Activate!'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            sendRequests(id,'activate')
                            .then(function (response) {
                                console.log(response); // Log the success message
                                Swal.fire(
                                    'Activated Account!',
                                    response.message,
                                    response.status
                                )
                                
                                // You can perform other actions here based on the response
                            })
                            .catch(function (error) {
                                console.error(error); // Handle errors here
                            });
                            // Swal.fire(
                            // 'Deleted!',
                            // 'Your file has been deleted.',
                            // 'success'
                            // )
                        }
                    })
                })

                // send requestest for account
                function sendRequests(id, req) {
                    return new Promise(function (resolve, reject) {
                        var csrfToken = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            type: "POST",
                            url: "/account-manage",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken
                            },
                            data: { 'id': id, 'req': req },
                            success: function (response) {
                                resolve(response); // Resolve the Promise with the response
                                // Redirect to the logout route
                                // window.location.href = '/logout';
                            },
                            error: function (err) {
                                reject(err); // Reject the Promise with the error
                            }
                        });
                    });
                }
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
