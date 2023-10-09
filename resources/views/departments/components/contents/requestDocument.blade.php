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
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a id="new-request" href="javascript:void(0);" class="dropdown-item text-success">New Request</a>
                        </div>
                    </div>
                    {{-- {{ Auth::user() }} --}}
                    <h4 class="card-title mb-4">Request List</h4>
                    {{-- {{ $logs }} --}}
                    <div class="table-responsive">
                       
                        <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Tracking No.</th>
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
                                    {{-- {{ $document }} --}}
                                    @php
                                        $trk = $document['trk_id'];
                                        // dd($trk); // Check the value of $trkId
                                    @endphp
                                    <tr>
                                        <td>
                                            @switch($document['trk_id'])
                                                @case(null)
                                                    <h6 class="mb-0"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>{{ __('Pending') }}</h6>
                                                    @break
                                        
                                                @default
                                                    <h6 class="mb-0 position-relative">
                                                        {!! DNS1D::getBarcodeHTML("579503", 'PHARMA') !!}
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
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                                                {{ __('TRK-XXXXXX') }}
                                                                <span class="position-absolute bottom-50 left-100 translate-middle badge bg-danger">
                                                                    {{ $document['type'] }}
                                                                </span>
                                                                @break
                                                        @endswitch
                                                    </h6>
                                                    @break
                                            @endswitch
                                        </td>
                                        {{-- for now id muna --}}
                                        <td>
                                            <i class="far fa-file-alt fa-3x"></i> <!-- Larger document icon -->
                                            <a class="position-relative track-document" data-id="{{ $document['document_id'] }}" data-trk="{{ $document['trk_id'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Track document...">
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><b>+</b></span>
                                            </a>
                                    
                                        </td>
                                        <td>{{ $document['purpose'] }}</td>
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
                                        <td><span class="badge bg-warning p-2"><b>{{ $document['status'] }}</b></span></td>
                                        <td><b>{{ $document['created_at'] }}</b></td>
                                        <td width="50px">
                                            <span class="">
                                                @if ($document['type'] !== 'my document')
                                                {{-- data-scanned-id="{{ $document['scanned'] }}" --}}
                                                    <a class="ri-map-pin-line text-white font-size-18 btn btn-danger p-2 pin-document-btn" data-current-loc="{{ $document['current_location'] }}" data-scanned-id="{{ $document['scanned'] }}" data-trk="{{ $document['trk_id'] }}" data-id="{{ $document['document_id'] }}" data-document-id="{{ $document['documents'] }}" data-office-id="{{ $document['corporate_office']['office_id'] }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Forward Document"></a>
                                                @endif
                                                {{-- for barcodes --}}
                                                @if ($document['type'] === 'my document' && $document['status'] !== 'pending')
                                                    <a class="ri-barcode-line text-white font-size-18 btn btn-dark p-2 barcode-document-btn" data-trk="{{ $document['trk_id'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Barcode"></a>
                                                @endif
                                                <a id="view-document-btn" class="ri-eye-line text-white font-size-18 btn btn-info p-2 view-document-btn" data-document-id="{{ $document['documents'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Document"></a>
                                                <a id="scan-document-btn" class="ri-camera-line text-white font-size-18 btn btn-success p-2" data-office-id="2" data-bs-toggle="tooltip" data-bs-placement="top" title="Scan Document"></a>
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
                        html += `<option value="${department.office_abbrev} | ${department.office_name}">${department.office_name}</option>`
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
                                                <p class="text-info">Document</p>
                                                <p class="mb-0 text-muted font-14" style="margin-top: -15px;">
                                                    ${parts[1]}
                                                    <span class="badge bg-info p-1"><b>${parts[0]}</b></span>
                                                </p>
                                                <hr />
                                                <p class="mb-0 font-10 text-secondary text-center">${log.notes}</p>
                                                <hr />
                                                <p class="mb-0 font-14 ${className} text-center border rounded">${log.status} status</p>
                                                <h2 style="margin-top: -10px;" class="cd-date text-center ${log.class}">${log.now}</h2>
                                                <span style="margin-top: 10px;" class="cd-date text-center">${log.time_sent}</span>
                                                <span style="margin-top: 30px;" class="cd-date">${log.time_spent}</span>  
                                                <span style="margin-top: 65px;" class="cd-date ${noteClass}">
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
                         // Construct the full URL to the document
                        var fullDocUrl = `${baseUrls}/storage/documents/` + docPath;
                        // Set the src attribute of the iframe in the modal
                        $('#preview-doc').attr('src', fullDocUrl);
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
                    // alert(scannedId)
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
                        // alert(currentLoc)
                        switch (scannedId) {
                            case 1:
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
