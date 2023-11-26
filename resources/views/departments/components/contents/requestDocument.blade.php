@extends('departments.index')

{{-- notes important 
    0 – not approved 
    1 – approved // for admin
    2 - scanned
 --}}

@section('head')
    <meta charset="utf-8" />
    <title>Departments Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
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

        @media print {
            .modal-content.print-mode * {
                display: none !important;
            }
            .modal-content.print-mode .printable-content * {
                display: block !important;
            }
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
                    <li class="breadcrumb-item active">Request's</li>
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
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a id="new-request" href="javascript:void(0);" class="dropdown-item text-success">New Request</a>
                        </div>
                    </div>
                    {{-- {{ Auth::user() }} --}}
                    <h4 class="card-title mb-4">Document's List</h4>

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
                                    <th>PR</th>
                                    <th>Document</th>
                                    <th>Purpose</th>
                                    <th>Received Offices</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                {{-- {{ $documents }} --}}
                                @php
                                     $badges = []
                                @endphp
                                @foreach ($documents as $document)
                                        
                                    @php
                                        // print_r($document['belongsTo']);
                                        $trk = $document['trk_id'];
                                        // dd($trk); // Check the value of $trkId
                                    @endphp
                                   
                                   @if (in_array(Auth::user()->id, $document['destination']))
                                   <tr data-requestor-trk="{{ $document['trk_id'] }}">
                                    <td>
                                        @switch($document['trk_id'])
                                            @case(null)
                                            @if ($document['status'] !== 'archived')
                                                <h6 class="mb-0 text-warning"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>{{ __('Pending') }}</h6>
                                            @else
                                                <h6 class="mb-0 text-danger"><i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>{{ __('rejected') }}</h6>
                                            @endif
                                                {{-- <h6 class="mb-0"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>{{ __('Pending') }}</h6> --}}
                                                @break
                                    
                                            @default
                                                <h6 class="mb-0 position-relative">
                                                    {!! DNS1D::getBarcodeHTML($document['trk_id'], 'PHARMA') !!}
                                                    @switch($document['type'])
                                                        @case('my document')
                                                        {{-- for barcodes --}}
                                                        <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                                           TRK-{{ $document['trk_id'] }}
                                                            <span class="position-absolute bottom-50 left-100 translate-middle badge bg-info">
                                                                {{ $document['type'] }}
                                                            </span>
                                                            @break
                                                        @case('requested')

                                                            @if ($document['status'] !== 'forwarded')
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>
                                                                {{-- {{ __('TRK-XXXXXX') }} this is the original --}}
                                                                    TRK-{{ $document['trk_id'] }}
                                                                <span class="position-absolute bottom-50 left-100 translate-middle badge bg-danger">
                                                                    {{ $document['type'] }}
                                                                </span>
                                                            @else
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                                                <span class="trk-display">TRK-{{ $document['trk_id'] }}</span>
                                                                <span class="position-absolute bottom-50 left-100 translate-middle badge bg-danger">
                                                                    {{ $document['type'] }}
                                                                </span>
                                                            @endif
                                                            @break
                                                    @endswitch
                                                </h6>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($document['pr'] != null)
                                            <span class="badge bg-success p-2"><b>{{ $document['pr'] }}</b></span>
                                        @else
                                            <span class="badge bg-danger p-2"><b>not available</b></span>
                                        @endif
                                    </td>
                                    {{-- for now id muna --}}
                                    <td>
                                        <i class="far fa-file-alt fa-3x"></i> <!-- Larger document icon -->
                                        <a class="position-relative track-document" data-id="{{ $document['document_id'] }}" data-trk="{{ $document['trk_id'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Track document...">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><b><i class="fas fa-route"></i></b></span>
                                        </a>
                                
                                    </td>
                                    <td style="word-break: break-all; max-width:150px;white-space:nowrap;overflow: hidden; text-overflow: ellipsis;"><span class="" >
                                        {{ $document['purpose'] }}    
                                    </span></td>
                                    <td>
                                        @php
                                            // $badges = ['BGA', 'BGB', 'BGX', 'BGC', 'BGI', 'BGK']; // Replace this with your data
                                            $partAbbr = explode(' | ', $document['logs']);
                                            
                                            $uniqueBadges = array_unique($partAbbr);
                                            $maxBadgesToShow = 3;
                                            $remainingBadges = count($badges) - $maxBadgesToShow;
                                            $uniqueId = uniqid(); // Generate a unique ID for the badge container
                                        @endphp
                                        <div id="{{ $uniqueId }}">
                                            @foreach ($uniqueBadges as $badge)
                                                @if ($loop->index < $maxBadgesToShow)
                                                    <span class="badge bg-info p-1"><b>{{ $badge }}</b></span>
                                                @endif
                                            @endforeach
                                    
                                            @if ($remainingBadges > 0)
                                                <a href="#" class="position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="+{{ $remainingBadges }} more...">
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><b>+{{ $remainingBadges }}</b></span>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @switch($document['status'])
                                            @case('archived')
                                                <span class="badge bg-danger p-2"><b>{{ $document['status'] }}</b></span>
                                                @break
                                            @case('forwarded')
                                                <span class="badge bg-info p-2"><b>{{ $document['status'] }}</b></span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success p-2"><b>{{ $document['status'] }}</b></span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning p-2"><b>{{ $document['status'] }}</b></span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success p-2"><b>{{ $document['status'] }}</b></span>
                                                @break
                                            @default
                                                
                                        @endswitch
                                       
                                    </td>
                                    <td><b>{{ $document['created_at'] }}</b></td>
                                    <td width="50px">
                                        {{-- problem on this buttons --}}
                                        {{-- {{ Auth::user()->assigned }} --}}
                                        <span class="">
                                            @if (Auth::user()->assigned !='viewing' && $document['type'] !== 'my document' && $document['status'] !== 'pending' && $document['status'] !== 'completed' && Auth::user()->id === end($document['destination']))
                                            {{-- data-scanned-id="{{ $document['scanned'] }}" --}}
                                                <a class="ri-map-pin-line text-white font-size-18 btn btn-danger p-2 pin-document-btn" data-from="{{ $document['from'] }}" data-current-loc="{{ $document['current_location'] }}" data-scanned-id="{{ $document['scanned'] }}" data-trk="{{ $document['trk_id'] }}" data-id="{{ $document['document_id'] }}" data-document-id="{{ $document['documents'] }}" data-office-id="{{ $document['corporate_office']['office_id'] }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Forward Document"></a>
                                            @endif
                                            {{-- for barcodes --}}
                                            @if ($document['type'] === 'my document' && $document['status'] !== 'pending' && $document['status'] !== 'archived')
                                                <a class="ri-barcode-line text-white font-size-18 btn btn-dark p-2 barcode-document-btn" data-trk="{{ $document['trk_id'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Barcode"></a>
                                            @endif
                                            
                                            <a id="view-document-btn" class="ri-eye-line text-white font-size-18 btn btn-info p-2 view-document-btn" data-stats="{{ $document['status'] }}" data-po="{{ $document['po'] }}" data-amount="{{ $document['amount'] }}" data-id="{{ $document['document_id'] }}" data-type="{{ $document['belongsTo'] }}" data-purpose="{{ $document['purpose'] }}" data-document-id="{{ $document['documents'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Document"></a>
                                            {{-- <a id="scan-document-btn" class="ri-camera-line text-white font-size-18 btn btn-success p-2" data-office-id="2" data-bs-toggle="tooltip" data-bs-placement="top" title="Scan Document"></a> --}}
                                        </span>
                                    </td>
                                </tr>
                                   @endif
                                  
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
    @include('departments.components.modals.requestDocument')
    {{-- open document modal --}}
    @include('departments.components.modals.openDocument')
    {{-- timeline modal --}}
    @include('departments.components.modals.timeline')
    {{-- open pin modal --}}
    @include('departments.components.modals.pin')
    {{-- open print modal --}}
    @include('departments.components.modals.print')
    {{-- open print modal --}}
    @include('departments.components.modals.scanned')
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                        var rowUserId = $(this).data('requestor-trk');

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
                            var status = $(this).find("td:eq(5)").text().trim().toLowerCase(); // Assuming status is in the 5th column (index 4)
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
                    // departmentJson.forEach(department => {
                    //     html += `<option value="${department.office_abbrev} | ${department.office_name}">${department.office_name}</option>`
                    // });

                     // updates
                     $.each(departmentJson, function(officeAbbrev, officeData){
                        var office = officeData.office;
                        var officeUsers = officeData.users;
                        console.log(office.office_abbrev,' ',officeUsers)
                        // Access and loop through users for this office
                        $.each(officeData.users, function(index, user) {
                            html += `<option value="${office.office_abbrev}|${office.office_name}|${user.name}|${user.id}">${office.office_abbrev} - ${user.name}</option>`
                            
                        });
                    })

                    // var trkId = $(this).data("trk-id");
                    // $('#department-select').attr('value','ADM|Administrator|Administrator|1') //old

                    $('#department-select').html(html)//new added


                    // Reset the form when clicking the "x" button
                    $('#close-modal').on('click', function () {
                        $('#request-form')[0].reset();
                        $("#image").val(""); // Clear the file input
                        $("#image-preview").hide(); // Hide the image preview container
                    });
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
                                    // Split the value into parts
                                    var parts = log.current_location.split('|');
                                
                                    if(log.scanned == 2){
                                        timelineTrk = log.trk_id;
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
                                        case 'completed':
                                            className = 'bg-success text-white'
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

                $('.scanner-btn').on('click', function () {
                    $('#scanner-activation').html(
                        `<div id="reader" class="border" style="margin:auto;" width="600px">Barcode Scanner</div>`
                    );

                    let html5QrcodeScanner; // Declare the scanner variable outside the click handler

                    function onScanSuccess(decodedText, decodedResult) {
                        // handle the scanned code as you like, for example:
                        console.log(`Code matched = ${decodedText}`, decodedResult);

                        // Set the value in an input field
                        $('.trk-input').val(`TKR-${decodedText}`).addClass('text-success border border-success');

                        // Stop the scanner
                        // stopScanner();

                         // Close the modal after a delay
                        setTimeout(closeModal, 5000); // 5000 milliseconds (5 seconds)
                        $("#scanned-form").submit();
                    }

                    function stopScanner() {
                        if (html5QrcodeScanner) {
                            console.log('Stopping scanner...');
                            // Stop the scanner
                            html5QrcodeScanner.stop().then(() => {
                                // QR Code scanning is stopped.
                                console.log('Scanner stopped');
                            }).catch((err) => {
                                // Handle stop error, if any
                                console.error('Error stopping scanner:', err);
                            });
                        }
                    }


                    function closeModal(){
                        $('#scanned-barcode-modal').modal('hide')
                    }

                    html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader",
                        { fps: 10, qrbox: { width: 250, height: 100 } },
                        /* verbose= */ false
                    );
                    html5QrcodeScanner.render(onScanSuccess);
                });


                // documents open
                $('.view-document-btn').on('click', function(){
                    $('#open-document-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })

                    $('#open-document-modal').modal('show')
                        const baseUrls = `${window.location.protocol}//${window.location.hostname}:${window.location.port}`;
                        var docPath = $(this).data("document-id");
                        var purpose = $(this).data("purpose");
                        var amount = $(this).data("amount");
                        var id = parseInt($(this).data("id"));
                        var po = $(this).data("po");
                        var stats = $(this).data("stats");
                        // alert(stats)
                        var belongsTo = $(this).data('type');
                         // Construct the full URL to the document
                        var fullDocUrl = `${baseUrls}/storage/documents/` + docPath;
                        // Set the src attribute of the iframe in the modal
                        $('#preview-doc').attr('src', fullDocUrl);
                        $('.event-notes-open').val(purpose)
                        $('.amount').val(amount)
                        $('#doc-id').val(id)
                        if (po !== '' || stats === 'completed' || stats === 'archived') {
                            // alert('yes')
                            $('.po').val(po).prop('readonly', true);
                        } else {
                            // alert('yes')
                            $('.po').prop('readonly', false);
                        }

                        // alert(belongsTo)
                        if(belongsTo !== 1){
                            $('.btn-r').show();
                            $('.btn-a').show();
                        }else{
                            $('.btn-r').hide();
                            $('.btn-a').hide();
                        }

                        checkIfAlreadyProcessed(id)
                        .then((res)=>{
                            console.log(res)
                            //hide the buttons for already processed documents by this user
                            if(res === '1'){
                                console.log('ginagawa')
                                $('.btn-approved').prop('disabled', true);
                                $('.btn-archived').prop('disabled', true);
                                $('.po').prop('readonly', false);
                                
                            }
                        })
                })

                //close modal set modal to default
                $('.openBtnClose').on('click', function(){
                    $('.btn-approved').prop('disabled', false);
                    $('.btn-archived').prop('disabled', false);
                })

                // pin open
                $('.pin-document-btn').on('click',function(){
                    $('#pin-document-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                    $('#scanned-barcode-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })

                    var trkId = $(this).data('trk')//trk_id
                    var scannedId = $(this).data('scanned-id')//scanned_id
                    var currentLoc = $(this).data('current-loc')//scanned_id
                    var documentId = parseInt($(this).data('id'))//documents id
                    var document = $(this).data('document-id')//documents
                    var officeId = $(this).data('office-id')//documents
                    var from = $(this).data('from')
                    // alert(scannedId)
                    console.log(trkId, documentId, document)

                    $('.trkNo').text(trkId)
                    $('.timestamp-placeholder').text(document)
                    $('.doc-id').val(documentId)
                    $('.doc').val(document)
                    $('.trk').val(trkId)

                    var departementHtml = ''
                    var departementUsersHtml = ``
            
                    getDepartmentWithUsers(from,officeId)
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
                        // alert(currentLoc)
                        switch (scannedId) {
                            case 1:
                                // alert('yes')
                                $('.rdi').val(documentId)
                                $('.loc').val(currentLoc)
                                $('#scanned-barcode-modal').modal('show')
                                break;
                            case 2:
                                $('#pin-document-modal').modal('show')
                                break;
                        
                            default:
                                break;
                        }
                        // if(scannedId !== 1){
                        //     $('#pin-document-modal').modal('show')
                        // }else{
                        //     alert("Wait for the documents to proceed to this section. Documents is not Scanned!");
                        // }
                        
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
                function getDepartmentWithUsers(from,office_id) {
                    // alert(id);
                    // Return a promise
                    return new Promise(function(resolve, reject) {
                        // Make an AJAX request to retrieve logs
                        $.ajax({
                            url: `/departments-with-users/${from}`, // Replace with your route URL
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

                //check if a user is already process a documents base on scaanned
                function checkIfAlreadyProcessed(doc_id){
                    return new Promise(function(resolve, reject) {
                        // Make an AJAX request to retrieve logs
                        $.ajax({
                            url: '/already-processed', // Replace with your route URL
                            type: 'POST',
                            data: {doc_id : doc_id},
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
