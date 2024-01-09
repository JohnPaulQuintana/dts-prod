@extends('admin.index')

@section('head')
    <meta charset="utf-8" />
    <title>Administrator Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

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
                    <li class="breadcrumb-item active">Office's</li>
                </ol>
            </div>

        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <div class="dropdown float-end">
                        <input type="text" id="search-input" class="" placeholder="Search" style="width: 80%; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                        

                        <a id="trigger-office" class="dropdown-toggle btn btn-success btn-sm" aria-expanded="false" data-bs-toggle="tooltip" data-bs-placement="top" title="New Office">
                            <i class="mdi mdi-plus"></i>
                        </a>
                        
                    </div>

                    <h4 class="card-title mb-4">Office List</h4>
                    {{-- {{ $logs }} --}}
                    <div class="table-responsive">
                        {{-- <table class="table table-centered mb-0 align-middle table-hover table-nowrap office-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Office Name</th>
                                    <th>Office Description</th>
                                    <th>Office Head</th>
                                    <th>Office Type</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                
                                @foreach ($offices as $office)
                                    <tr>
                                        <td><h6 class="mb-0"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>{{ $office->office_name }}</h6></td>
                                        <td>{{ $office->office_description }}</td>
                                        <td>{{ $office->office_head }}</td>
                                        <td>{{ $office->office_type }}</td>
                                        <td>
                                            @switch($office->status)
                                                @case("forwarded")
                                                    <!-- Display something when status is 1 -->
                                                    <span class="badge bg-info p-2"><b>{{ $office->status }}</b></span>
                                                    @break
                                                @case("rejected")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-danger p-2"><b>{{ $office->status }}</b></span>
                                                    @break
                                                @case("on-going")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge p-2"><b>{{ $office->status }}</b></span>
                                                    @break
                                                @case("active")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-success p-2"><b>{{ $office->status }}</b></span>
                                                    @break
                                                @default
                                                    <!-- Display something for other status values -->
                                                    Other Status Content
                                            @endswitch
                                        </td>
                                        <td>{{ $office->created_at }}</td>
                                        
                                        <td width="50px">
                                            <span class="">
                                                <a href="{{ route('administrator.dashboard.offices.user', ['office_id' => $office->id]) }}" id="view-users-btn" class="ri-user-add-line text-white font-size-18 btn btn-success p-2" data-office-id="{{ $office->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Assigned Users"></a>
                                            </span>
                                            <span class="">
                                                <a class="ri-archive-line text-white font-size-18 btn btn-danger p-2 archived-offices-btn" data-office-id="{{ $office->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Archived Office"></a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <!-- end -->
                                
                            </tbody><!-- end tbody -->
                        </table> <!-- end table --> --}}

                        <table id="office-table" class="table activate-select dt-responsive nowrap w-100 text-center" style="width:100%;border:0 solid transparent; padding:10px;font-weight:700;text-transform:capitalize;"></table>

                    </div>
                </div><!-- end card -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>

    {{-- new office modal --}}
    @include('admin.components.modals.newOffice')
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
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

        {{-- datatables --}}
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

        {{-- custom js --}}
        <script>
            var dataToRender =  @json($offices);
           
            console.log(dataToRender)

            $(document).ready(function(){

                $('#office-table').DataTable({
                data: dataToRender,
                columns: [
                    {
                        data: null,
                        title: 'Office Name',
                        render: function(data, type, row){
                            return `<h6 class="mb-0"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>${row.office_name}</h6>`
                        }
                    },

                    { data: 'office_description', title: 'Office Description : ' },
                    { data: 'office_head', title: 'Office Head : ' },
                    { data: 'office_type', title: 'Office Type : ' },
                    { 
                        data: null, 
                        title: 'Status : ',
                        render: function(data, type, row){
                            var renderStat = ''
                            switch (row.status) {
                                case 'forwarded':
                                    renderStat = `<span class="badge bg-info p-2"><b>${row.status}</b></span>`
                                    break;
                                case 'rejected':
                                    renderStat = `<span class="badge bg-danger p-2"><b>${row.status}</b></span>`
                                    break;
                                case 'on-going':
                                    renderStat = `<span class="badge bg-success p-2"><b>${row.status}</b></span>`
                                    break;
                                case 'active':
                                    renderStat = `<span class="badge bg-success p-2"><b>${row.status}</b></span>`
                                    break;
                                
                                default:
                                    break;
                            }

                            return renderStat;
                        }
                    },
                    { data: 'created_at', title: 'Date Created : ' },
                    { 
                        data: null, 
                        title: 'Action : ',
                        render: function(data, type, row){
                            var officeId = row.id;
                            
                            var renderAction = `
                                <a id="view-users-btn" class="ri-user-add-line text-white font-size-18 btn btn-success p-2 view-users-btn" data-office-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Assigned Users"></a>
                                <a class="ri-archive-line text-white font-size-18 btn btn-danger p-2 archived-offices-btn" data-office-id="${officeId}" data-bs-toggle="tooltip" data-bs-placement="top" title="Archived Office"></a>
                            `
                            
                            
                            
                            return `${renderAction}`;
                            
                        }
                    },
                   
                    
                ],
                responsive: true,
                "initComplete": function (settings, json) {
                    $(this.api().table().container()).addClass('bs4');
                },
            });

            $('.view-users-btn').on('click', function(){
                alert('yes')
            })

                // console.log('ito')
                // var errorJson = {!! json_encode(session('errors')) !!};
                // var notification2 = JSON.parse(errorJson);
                // console.log(notification2)
                // search functionality
                // Handle input changes in real-time
                $('#search-input').on('input', function () {
                    var searchText = $(this).val().toLowerCase();

                    // Loop through each list item and hide/show based on the search text
                    $('.office-table tbody tr').each(function () {
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

                // archived office
                $('.archived-offices-btn').on('click', function(){
                    var officeID = $(this).data('office-id')
                    // alert(officeID)
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are attempting to archived the office!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Archived!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var csrfToken = $('meta[name="csrf-token"]').attr('content');
                            $.ajax({
                                type: "POST",
                                url: "/archived-office",
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken
                                },
                                data: { 'id': officeID},
                                success: function (response) {
                                    console.log(response)
                                    Swal.fire(
                                        'Successfully Archived!',
                                        response.message,
                                        'success'
                                    )
                                    window.location.href = '/offices'
                                },
                                error: function (err) {
                                   console.log(err); // Reject the Promise with the error
                                }
                            });
                        }
                    });
                })

                $('#trigger-office').on('click',function(){
                    $('#new-office').modal('show')
                    // var trkId = $(this).data("trk-id");
                    // $('#data-trk-id').val(trkId)
                })
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
