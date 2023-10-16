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
                <li class="breadcrumb-item active">My Document's</li>
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
                    <a class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a id="new-request" href="javascript:void(0);" class="dropdown-item text-success">Request a Documents</a>
                        <a  href="{{ route('administrator.dashboard.offices') }}" class="dropdown-item text-info">Go to Office</a>
                        <a id="new-request" href="javascript:void(0);" class="dropdown-item text-danger">Report</a>
                        <!-- item-->
                        <a  href="{{ route('administrator.dashboard') }}" class="dropdown-item text-danger">Back to Dashboard</a>
                    </div>
                </div>

                <h4 class="card-title mb-4">
                    <span class="me-2">Docement's List</span>
                    
                </h4>
                <div class="mb-2">
                    <a class="filter-button text-white font-size-13 btn btn-info p-1" data-filter="all"  data-bs-toggle="tooltip" data-bs-placement="top" title="All Document">All Documents</a>
                        <a class="filter-button text-white font-size-13 btn btn-warning p-1" data-filter="approved"  data-bs-toggle="tooltip" data-bs-placement="top" title="On-going Document">On-going</a>
                        <a class="filter-button text-white font-size-13 btn btn-danger p-1" data-filter="archived" data-bs-toggle="tooltip" data-bs-placement="top" title="Archieved Document">Archived</a>
                        <a class="filter-button text-white font-size-13 btn btn-warning p-1" data-filter="pending" data-bs-toggle="tooltip" data-bs-placement="top" title="Pending Document">Pending</a>
                        <a class="filter-button text-white font-size-13 btn btn-success p-1" data-filter="completed" data-bs-toggle="tooltip" data-bs-placement="top" title="Completed Document">Completed</a>
                </div>
                {{-- {{ $logs }} --}}
                <div class="table-responsive">
                    <table class="table table-centered mb-0 align-middle table-hover table-nowrap req-table">
                        <thead class="table-light">
                            <tr>
                                <th>Tracking No.</th>
                                <th>Document</th>
                                <th>Purpose</th>
                                <th>Office (Requestor)</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead><!-- end thead -->
                        <tbody>
                            {{-- {{ $documents }} --}}
                            @foreach ($documents as $document)
                               
                                <tr data-status="{{ $document['status'] }}" data-requestor-id="{{ $document['requestor_user_id'] }}">
                                    <td class="text-center">
                                        @switch($document['trk_id'])
                                            @case(null)
                                                @if ($document['status'] !== 'archived')
                                                    <h6 class="mb-0 text-warning"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>{{ __('Pending') }}</h6>
                                                @else
                                                    <h6 class="mb-0 text-danger"><i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>{{ __('rejected') }}</h6>
                                                @endif
                                                
                                                @break
                                    
                                            @default
                                                <h6 class="mb-0 position-relative">
                                                    {!! DNS1D::getBarcodeHTML("579503", 'PHARMA') !!}
                                                    <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                                        TRK-{{ $document['trk_id'] }}
                                                    @if ($document['status'] === 'forwarded')
                                                        <span class="position-absolute bottom-50 left-100 translate-middle badge bg-danger">
                                                            {{ __('requested') }}
                                                        </span>
                                                    @else
                                                        <span class="position-absolute bottom-50 left-100 translate-middle badge bg-success">
                                                            {{ __('approved') }}
                                                        </span>
                                                    @endif
                                                </h6>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <i class="far fa-file-alt fa-3x"></i> <!-- Larger document icon -->
                                        <a class="position-relative track-document" data-id="{{ $document['document_id'] }}" data-trk="{{ $document['trk_id'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Track document...">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><b><i class="fas fa-route"></i></b></span>
                                        </a>
                                    </td>
                                    <td>{{ $document['purpose'] }}</td>
                                    <td>
                                        {{ $document['corporate_office']['office_name'] }}
                                        <span class="badge bg-info p-1"><b>{{ $document['corporate_office']['office_abbrev'] }}</b></span>
                                        
                                    </td>
                                    <td>
                                        @switch($document['status'])
                                            @case('archived')
                                                <span class="badge bg-danger p-2"><b>{{ $document['status'] }}</b></span>
                                                @break
                                            @case('forwarded')
                                                <span class="badge bg-warning p-2"><b>{{ $document['status'] }}</b></span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success p-2"><b>{{ $document['status'] }}</b></span>
                                                @break
                                        
                                            @default
                                                
                                        @endswitch
                                        
                                    </td>
                                    <td><b>{{ $document['created_at'] }}</b></td>
                                    <td width="50px">
                                        <span class="">
                                            {{-- for barcodes --}}
                                            @if ($document['status'] !== 'pending' && $document['status'] !== 'archived')
                                                <a class="ri-barcode-line text-white font-size-18 btn btn-dark p-2 barcode-document-btn" data-trk="{{ $document['trk_id'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Barcode"></a>
                                            @endif
                                            <a class="ri-eye-line text-white font-size-18 btn btn-info p-2 view-document-btn" data-purpose="{{ $document['purpose'] }}" data-trk="{{ $document['trk_id'] }}" data-id="{{ $document['document_id'] }}" data-document-id="{{ $document['documents'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Document"></a>
                                            {{-- <a id="scan-document-btn" class="ri-camera-line text-white font-size-18 btn btn-success p-2" data-office-id="2" data-bs-toggle="tooltip" data-bs-placement="top" title="Scan Document"></a> --}}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody><!-- end tbody -->
                    </table> <!-- end table -->
                </div>
            </div><!-- end card -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>

{{-- new request modal --}}
@include('admin.components.modals.requestDocument')
{{-- open document modal --}}
@include('admin.components.modals.openDocument')
{{-- open timeline modal --}}
@include('admin.components.modals.timeline')
{{-- open pin modal --}}
@include('admin.components.modals.pin')
{{-- open pin modal --}}
@include('admin.components.modals.print')
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
            // filter table base on id
             // Parse the URL search parameters
            const urlSearchParams = new URLSearchParams(window.location.search);

            // Get the value of the 'search_id' parameter
            const searchId = urlSearchParams.get('search_id');

            // Check if 'searchId' has a value and use it
            if (searchId !== null) {
                // console.log('search_id:', searchId);
                 // Show/hide rows based on the selected user ID
                $('.req-table tr').each(function () {
                    var rowUserId = $(this).data('requestor-id');

                    if (rowUserId == searchId) {
                        var $this = $(this).addClass('position-relative');
                        $(this).show();
                        $(this).addClass('border border-danger bg-light');
                        // Remove the 'border-danger' class after 5 seconds
                        setTimeout(function () {
                            // alert('remove')
                            $this.addClass('border border-white');
                        }, 5000); // 5000 milliseconds (5 seconds)
                    } else {
                        $(this).hide();
                    }
                });
            } else {
                console.log('search_id parameter not found in the URL');
            }

            // search functionality
            // Handle input changes in real-time
            $('#search-input').on('input', function () {
                var searchText = $(this).val().toLowerCase();

                // Loop through each list item and hide/show based on the search text
                $('.req-table tbody tr').each(function () {
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
            

            // Handle button click
            $(".filter-button").click(function() {
                var filter = $(this).data("filter").trim().toLowerCase();
                
                // Show all rows initially
                $("tbody tr").show();
                
                // Hide rows that don't match the filter
                if (filter !== "all") {
                    $("tbody tr").each(function() {
                        var status = $(this).find("td:eq(4)").text().trim().toLowerCase(); // Assuming status is in the 5th column (index 4)
                        if (status !== filter) {
                            $(this).hide();
                        }
                    });
                }
            });

            // new request
            $('#new-request').on('click',function(){
                 // Prevent modal from closing when clicking outside
                $('#new-request-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('#new-request-modal').modal('show')
                var departmentJson = {!! json_encode($departments)!!};
                console.log(departmentJson)
                var html = ''
                departmentJson.forEach(department => {
                   if(department.office_abbrev !== 'ADM'){
                        html += `<option value="${department.office_abbrev} | ${department.office_name}">${department.office_name}</option>`
                   }
                });

                // var trkId = $(this).data("trk-id");
                $('#department-select').html(html)

                // Reset the form when clicking the "x" button
                $('#close-modal').on('click', function () {
                    $('#request-form')[0].reset();
                    $("#image").val(""); // Clear the file input
                    $("#image-preview").hide(); // Hide the image preview container
                });
            })
           
            // documents open
            $('.view-document-btn').on('click', function(){
                $('#open-document-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })

                
                    const baseUrls = `${window.location.protocol}//${window.location.hostname}:${window.location.port}`;
                    var docPath = $(this).data("document-id");
                    var id = parseInt($(this).data("id"));
                    var trkId = $(this).data("trk");
                    var purpose = $(this).data("purpose");
                    if(trkId == ''){
                        trkId = 'Pending Approval'
                        $('#btn-approved').css({'display':'block'})
                    }else{
                        $('#btn-approved').css({'display':'none'})
                    }
                     // Construct the full URL to the document
                    var fullDocUrl = `${baseUrls}/storage/documents/` + docPath;
                    // Set the src attribute of the iframe in the modal
                    $('#preview-doc').attr('src', fullDocUrl);
                    $('#doc-id').val(id)
                    $('#trkNo').html(trkId)
                    $('.event-notes-open').val(purpose)
                    // add data-id on archived button
                    // $('.documents-archive').attr('data-archived-id',id)
                    $('#open-document-modal').modal('show')

                   
            })

            // pin open
            $('.pin-document-btn').on('click',function(){
                var trkId = $(this).data('trk')//trk_id
                var documentId = parseInt($(this).data('id'))//documents id
                var document = $(this).data('document-id')//documents
                var officeId = $(this).data('office-id')//documents

                console.log(trkId, documentId, document)

                $('.trkNo').text(trkId)
                $('.timestamp-placeholder').text(document)
                $('.doc-id').val(documentId)
                $('.doc').val(document)
                $('.trk').val(trkId)

                var departementHtml = ''
                var departementUsersHtml = ``
        
                getDepartmentWithUsers(officeId)
                    .then(function(response) {
                        // Process the response (logs) here
                        // console.log(response.departmentWithUsers);
                        response.departmentWithUsers.forEach(data => {
                            console.log(data)
                            //office_id | office_name | office_abbrev
                            departementHtml += `
                                <option value='${data.offices[0].office_id} | ${data.offices[0].office_name} | ${data.offices[0].office_abbrev}'>
                                    ${data.offices[0].office_name}
                                </option>`
                            //User id | user_office_id | name
                            departementUsersHtml += `
                                <option value='${data.user_id} | ${data.user_office_id} | ${data.user_name}'>
                                    ${data.user_name}
                                </option>
                            `
                        });
                        $('.department-select').html(departementHtml)

                        $('#department-staff-select').html(departementUsersHtml)

                    })
                    .catch(function(err){
                        console.log(err)
                    })
                
                    // Attach event listeners to both selects
                    $('#department-select').on('change', function() {
                            // const selectedDepartment = $(this).val();
                            const selectedDepartmentOfficeId = $(this).val().split(' | ')[0];
                            console.log(selectedDepartmentOfficeId)

                            // Clear the options in #department-staff-select


                            // Iterate through all options in #department-staff-select
                            $('#department-staff-select option').each(function() {
                                const departmentUserOfficeId = $(this).val().split(' | ')[1];

                                if (departmentUserOfficeId == selectedDepartmentOfficeId) {
                                    // If the user's office matches the selected department, display the option
                                    $(this).prop('disabled', false);
                                    $(this).show();
                                    $(this).removeAttr('selected');
                                    
                                } else {
                                    // Otherwise, disable the option
                                    $(this).prop('disabled', true);
                                    $(this).hide();
                                }
                            });
                    });
                
                    $('#pin-document-modal').modal('show')
            })

            // tracking documents
            $('.track-document').on('click',function(){
                $('#timeline-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })

                var trackNo = $(this).data("trk");
                var trackId = $(this).data("id");
                var timelineHtml = ''
                var timelineTrk = '******'
                var className = ''
                var noteClass = ''
                // alert(trackNo);
                if (trackNo != '') {
                    // Usage example
                    getLogs(trackId,trackNo)
                        .then(function(response) {
                            // Process the response (logs) here
                            console.log(response);
                            response.logs.forEach(log => {
                                // timelineTrk = log.trk_id;
                                // Split the value into parts
                                var parts = log.current_location.split('|');
                            
                                if(log.trk_id != null){
                                    timelineTrk = log.trk_id;
                                    console.log(timelineTrk)
                                }
                                
                                if(log.notes_user !== 'false'){
                                    noteClass = 'border border-danger rounded text-danger'
                                }else{
                                    noteClass = 'border border-0 text-white'
                                }
                                switch (log.status) {
                                    case 'pending':
                                        className = 'bg-warning text-white'
                                        break;
                                    case 'on-going':
                                        className = 'bg-warning text-white'
                                        break;
                                    case 'forwarded':
                                        className = 'bg-warning text-white'
                                        break;
                                    case 'approved':
                                        className = 'bg-info text-white'
                                        break;
                                    case 'success':
                                        className = 'bg-success text-white'
                                        break;
                                    case 'archived':
                                        className = 'bg-danger text-white'
                                        break;
                                
                                    default:
                                        break;
                                }
                                timelineHtml += 
                                    `
                                    <div class="cd-timeline-block">
                                        <div class="cd-timeline-img cd-success">
                                            <i class="mdi mdi-adjust"></i>
                                        </div>

                                        <div class="cd-timeline-content text-center ${log.bgclass}" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">
                                            <p class="text-info" style="font-size:18px;">Document</p>
                                            <p class="mb-0 text-muted font-14" style="margin-top: -15px;">
                                                ${parts[1]}
                                                <span class="badge bg-info p-1"><b>${parts[0]}</b></span>
                                            </p>
                                            <hr />
                                            <p class="mb-0 font-10 text-secondary text-center">${log.notes}</p>
                                            <hr />
                                            <p class="mb-0 font-18 ${className} text-center border rounded" style="font-size:16px;">${log.status} status</p>
                                            <h2 style="margin-top: -10px;" class="cd-date text-center ${log.class}">${log.now}</h2>
                                            <span style="margin-top: 10px;" class="cd-date text-center">${log.time_sent}</span>
                                            <span style="margin-top: 30px;" class="cd-date text-center">${log.time_spent}</span>  
                                            <span style="margin-top: 65px;" class="cd-date ${noteClass} text-center">
                                                <span><b>NOTE</b></span></br>
                                                ${ log.notes_user }
                                            </span>  
                                        </div>
                                    </div>
                                    `
                            });
                            $('#cd-timeline').html(timelineHtml)
                            $('#trk-timeline').html(timelineTrk)
                            $('#timeline-modal').modal('show')
                        })
                        .catch(function(error) {
                            // Handle any errors here
                            console.error(error);
                        });

                }else{
                    showalert('warning',"'This document's is in pending state. no history available!")
                }
            })

            //print open
            $('.barcode-document-btn').on('click',function(){
                    $('#print-barcode-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })

                    var trk = $(this).data('trk')
                    getDetailsForPrinting(trk)
                        .then(function(response){
                            response.records.forEach(record => {
                                console.log(record)
                                $('.pdf-container').attr('src',record.document_code)
                            });
                        })
                        .catch(function(error){
                            console.log(error)
                        })
                    $('#print-barcode-modal').modal('show')
                })    

            // When the file input changes
            $("#image").change(function () {
                readTimestamp(this);
            });

            // Function to read and display the timestamp
            function readTimestamp(input) {
                if (input.files && input.files[0]) {
                    var timestamp = new Date().toLocaleString(); // Generate a timestamp
                    $("#timestamp-placeholder").text(timestamp); // Display the timestamp
                    $("#image-preview").show(); // Display the image preview container
                }
            }

            // Handle the "Cancel" button click
            $("#cancel-preview").click(function () {
                // Clear the file input and reset the image preview
                $("#image").val(""); // Clear the file input
                $("#image-preview").hide(); // Hide the image preview container
            });

            // process request for logs
            function getLogs(id,trk) {
                // alert(id);
                // Return a promise
                return new Promise(function(resolve, reject) {
                    // Make an AJAX request to retrieve logs
                    $.ajax({
                        url: '/get-logs', // Replace with your route URL
                        type: 'POST',
                        data: {
                            trk: trk, // Include any additional data you need to send
                            id: id,
                        },
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
            function showalert(stats,message){
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
                toastr[stats](message);
            }

            // get details for printing
            function getDetailsForPrinting(trk){
                    // Return a promise
                    return new Promise(function(resolve, reject) {
                        // Make an AJAX request to retrieve logs
                        $.ajax({
                            url: '/get-barcode', // Replace with your route URL
                            type: 'GET',
                            data: {
                                trk: trk, // Include any additional data you need to send
                            },
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