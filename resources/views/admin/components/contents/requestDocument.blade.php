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

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

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
                    <li class="breadcrumb-item active">Request's</li>
                </ol>
            </div>

        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    {{-- <div class="dropdown float-end">
                        <input type="text" id="search-input" class="" placeholder="Search" style="width: 80%; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                        <a class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            
                            <a  href="{{ route('administrator.dashboard.offices') }}" class="dropdown-item text-info">Go to Office</a>
                            <a href="{{ route('reportsPdf') }}" class="dropdown-item text-danger">Report</a>
                            <!-- item-->
                            <a  href="{{ route('administrator.dashboard') }}" class="dropdown-item text-danger">Back to Dashboard</a>
                        </div>
                    </div> --}}

                    <h4 class="card-title mb-4">
                        <span class="me-2">Docement's List</span>
                        
                    </h4>
                    <div class="mb-2">
                        <a class="filter-button text-white font-size-13 btn btn-info p-1" data-filter="all"  data-bs-toggle="tooltip" data-bs-placement="top" title="All Document">All Documents</a>
                        <a class="filter-button text-white font-size-13 btn btn-warning p-1" data-filter="approved"  data-bs-toggle="tooltip" data-bs-placement="top" title="On-going Document">On-going</a>
                        <a class="filter-button text-white font-size-13 btn btn-danger p-1" data-filter="archived" data-bs-toggle="tooltip" data-bs-placement="top" title="Discontinued Document">Discontinued</a>
                        <a class="filter-button text-white font-size-13 btn btn-warning p-1" data-filter="pending" data-bs-toggle="tooltip" data-bs-placement="top" title="Pending Document">Pending</a>
                        <a class="filter-button text-white font-size-13 btn btn-success p-1" data-filter="completed" data-bs-toggle="tooltip" data-bs-placement="top" title="Completed Document">Completed</a>
                    </div>
                    {{-- {{ $logs }} --}}
                    <div class="table-responsive">
                        {{-- <table class="table table-centered mb-0 align-middle table-hover table-nowrap req-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Tracking No.</th>
                                    <th>PR.</th>
                                    <th>Document</th>
                                    <th>Purpose</th>
                                    <th>Office (Requestor)</th>
                                    <th>Status</th>
                                    
                                    <th>Action</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                
                                @foreach ($documents as $document)
                                    
                                    <tr data-status="{{ $document['status'] }}" data-requestor-id="{{ $document['requestor_user_id'] }}">
                                        <td class="text-center">
                                            @switch($document['trk_id'])
                                                @case(null)
                                                    @if ($document['status'] !== 'archived')
                                                        <h6 class="mb-0 text-warning"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>{{ __('requesting') }}</h6>
                                                    @else
                                                        <h6 class="mb-0 text-danger"><i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>{{ __('rejected') }}</h6>
                                                    @endif
                                                    
                                                    @break
                                        
                                                @default
                                                    <h6 class="mb-0 position-relative">
                                                        {!! DNS1D::getBarcodeHTML("579503", 'PHARMA') !!}
                                                        <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                                            TRK-{{ $document['trk_id'] }}
                                                        @if ($document['status'] !== 'forwarded')
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
                                            @if ($document['pr'] != null)
                                                <span class="badge bg-success p-2"><b>{{ $document['pr'] }}</b></span>
                                            @else
                                                <span class="badge bg-danger p-2"><b>not available</b></span>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="far fa-file-alt fa-3x"></i> <!-- Larger document icon -->
                                            <a class="position-relative track-document" data-id="{{ $document['document_id'] }}" data-trk="{{ $document['trk_id'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Track document...">
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><b><i class="fas fa-route"></i></b></span>
                                            </a>
                                        </td>
                                        <td style="word-break: break-all; max-width:150px;white-space:nowrap;overflow: hidden; text-overflow: ellipsis;">{{ $document['purpose'] }}</td>
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
                                        
                                        <td width="50px">
                                            <span class="">
                                                
                                                @if ($document['status'] !== 'pending' && $document['status'] !== 'archived' && $document['status'] !== 'completed' && $document['status'] !== 'forwarded')
                                                    <a class="ri-map-pin-line text-white font-size-18 btn btn-danger p-2 pin-document-btn" data-current-loc="{{ $document['current_location'] }}" data-scanned-id="{{ $document['scanned'] }}" data-requestor-id="{{ $document['requestor_user_id'] }}" data-trk="{{ $document['trk_id'] }}" data-id="{{ $document['document_id'] }}" data-document-id="{{ $document['documents'] }}" data-office-id="{{ $document['corporate_office']['office_id'] }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Forward Document"></a>
                                                @endif
                                                
                                                <a class="ri-eye-line text-white font-size-18 btn btn-info p-2 view-document-btn" data-pr="{{ $document['pr'] }}" data-po="{{ $document['po'] }}" data-stat="{{ $document['status'] }}" data-amount="{{ $document['amount'] }}" data-purpose="{{ $document['purpose'] }}" data-trk="{{ $document['trk_id'] }}" data-id="{{ $document['document_id'] }}" data-document-id="{{ $document['documents'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Document"></a>
                                               
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                
                            </tbody><!-- end tbody -->
                        </table> <!-- end table --> --}}
                        <table id="requested-table" class="table activate-select dt-responsive nowrap w-100 text-center" style="width:100%;border:0 solid transparent; padding:10px;font-weight:700;text-transform:capitalize;"></table>

                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div>
                            {{-- {{ $documents->links('pagination::simple-bootstrap-5') }} --}}
                        </div>
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
    @include('admin.components.modals.scanned')
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

        {{-- datatables --}}
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

        {{-- custom js --}}
        <script>
            var dataToRender =  @json($documents);
            console.log(dataToRender)
            $(document).ready(function(){
               
                $('#requested-table').DataTable({
                data: dataToRender,
                columns: [
                    { 
                        data: null, 
                        title: 'Tracking No : ',
                        render: function(data, type, row){
                            switch (row.trk_id) {
                                case null:
                                    if(row.status !== 'archived'){
                                        return `<h6 class="mb-0 text-warning"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>{{ __('Pending') }}</h6>`
                                    }else{
                                        return `<h6 class="mb-0 text-danger"><i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>{{ __('rejected') }}</h6>`
                                    } 
                                    break;
                            
                                default:
                                    var renderStatus = ''
                                    switch (row.status) {
                                        case 'forwarded':
                                            renderStatus = `<span class="position-absolute bottom-50 left-100 translate-middle badge bg-info">
                                                                {{ __('requested') }}
                                                            </span>`
                                            break;
                                        case 'pending':
                                            renderStatus = `<span class="position-absolute bottom-50 left-100 translate-middle badge bg-warning">
                                                                {{ __('pending') }}
                                                            </span>`
                                            break;
                                        case 'approved':
                                            renderStatus = `<span class="position-absolute bottom-50 left-100 translate-middle badge bg-info">
                                                                {{ __('approved') }}
                                                            </span>`
                                            break;
                                        case 'archived':
                                            renderStatus = `<span class="position-absolute bottom-50 left-100 translate-middle badge bg-danger">
                                                                {{ __('discontinued') }}
                                                            </span>`
                                            break;
                                        case 'completed':
                                            renderStatus = `<span class="position-absolute bottom-50 left-100 translate-middle badge bg-success">
                                                                {{ __('completed') }}
                                                            </span>`
                                            break;
                                    
                                        default:
                                            break;
                                    }

                                    return `<h6 class="mb-0 position-relative">
                                                {!! DNS1D::getBarcodeHTML("579503", 'PHARMA') !!}
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                                            TRK-${row.trk_id}

                                                ${renderStatus}
                                            </h6>
`
                                    break;
                            }
                            
                        } 
                    },
                    { 
                        data: null, 
                        title: 'Purchased Request : ',
                        render: function(data, type, row){
                            var renderPR = ''
                            var renderClass = ''
                            if(row.pr){
                                renderPR = row.pr
                                renderClass = "badge p-2 bg-success"
                            }else{
                                renderPR = 'not-available'
                                renderClass = "badge p-2 bg-danger"
                            }
                            return `<span class="${renderClass}">${renderPR}</span>`;
                            
                        }
                    },
                    { 
                        data: null,
                        title: 'Document : ', 
                        render: function(data, type, row){
                            return `
                                <i class="far fa-file-alt fa-3x"></i>
                                <a class="position-relative track-document" data-id="${row.document_id}" data-trk="${row.trk_id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Track document...">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><b><i class="fas fa-route"></i></b></span>
                                </a>

                            `
                        }
                    },
                    { data: 'purpose', title: 'Purpose : ' },
                    { 
                        data: null, 
                        title: 'Office (Requestor) : ',
                        render: function(data, type, row){
                            return `
                                ${row.corporate_office.office_name}
                                <span class="badge bg-info p-1"><b>${row.corporate_office.office_abbrev}</b></span>
                            `
                        } 
                    },
                    { 
                        data: null, 
                        title: 'Status : ',
                        render: function(data, type, row){
                            var renderStat = ''
                            switch (row.status) {
                                case 'archived':
                                    renderStat = `<span class="badge bg-danger p-2"><b>${row.status}</b></span>`
                                    break;
                                case 'forwarded':
                                    renderStat = `<span class="badge bg-warning p-2"><b>${row.status}</b></span>`
                                    break;
                                case 'approved':
                                    renderStat = `<span class="badge bg-success p-2"><b>${row.status}</b></span>`
                                    break;
                                case 'pending':
                                    renderStat = `<span class="badge bg-warning p-2"><b>${row.status}</b></span>`
                                    break;
                                case 'completed':
                                    renderStat = `<span class="badge bg-success p-2"><b>${row.status}</b></span>`
                                    break;
                                
                                default:
                                    break;
                            }

                            return renderStat;
                        }
                    },
                    
                    { 
                        data: null, 
                        title: 'Action : ',
                        render: function(data, type, row){
                            var renderAction = ''
                            var renderClass = ''
                            if(row.status !== 'pending' && row.status !== 'archived' && row.status !== 'completed' && row.status !== 'forwarded'){
                                renderAction = `
                                    <a class="ri-map-pin-line text-white font-size-18 btn btn-danger p-2 pin-document-btn" data-current-loc="${row.current_location}" data-scanned-id="${row.scanned}" data-requestor-id="${row.requestor_user_id}" data-trk="${row.trk_id}" data-id="${row.document_id}" data-document-id="${row.documents}" data-office-id="${row.corporate_office.office_id}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Forward Document"></a>
                                `
                                
                            }
                                renderAction = `
                                    <a class="ri-eye-line text-white font-size-18 btn btn-info p-2 view-document-btn" data-pr="${row.pr}" data-po="${row.po}" data-stat="${row.status}" data-amount="${row.amount}" data-purpose="${row.purpose}" data-trk="${row.trk_id}" data-id="${row.document_id}" data-document-id="${row.documents}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Document"></a>
                                `
                                
                            
                            return `${renderClass}`;
                            
                        }
                    },
                   
                    
                ],
                responsive: true,
                "initComplete": function (settings, json) {
                    $(this.api().table().container()).addClass('bs4');
                },
            });
            
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
                $(document).on("click", ".filter-button", function() {
                    var filter = $(this).data("filter").trim().toLowerCase();
                    
                    // Show all rows initially
                    $("tbody tr").show();
                    
                    // Hide rows that don't match the filter
                    if (filter !== "all") {
                        $("tbody tr").each(function() {
                            var status = $(this).find("td:eq(5)").text().trim().toLowerCase(); // Assuming status is in the 5th column (index 4)
                            // console.log('start')
                            // console.log(filter)
                            // console.log(status)
                            if (status != filter) {
                                // console.log('false')
                                $(this).hide();
                            }
                        });
                    }
                });

               
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
                        var amount = $(this).data("amount");
                        var stats = $(this).data('stat')
                        var pr = $(this).data('pr')
                        var po = $(this).data('po')
                        // alert(pr)
                        if(trkId == ''){
                            trkId = 'Pending Approval'
                            $('#btn-approved').css({'display':'block'})
                        }else{
                            $('#btn-approved').css({'display':'none'})
                        }

                        if(pr !== ''){
                            $('.pr').val(pr).attr('readonly', true)
                        }
                        if(po !== ''){
                            $('.po').val(po).attr('readonly', true)
                        }
                         // Construct the full URL to the document
                        var fullDocUrl = `${baseUrls}/storage/documents/` + docPath;
                        // Set the src attribute of the iframe in the modal
                        $('#preview-doc').attr('src', fullDocUrl);
                        $('#doc-id').val(id)
                        $('#trkNo').html(trkId)
                        $('.event-notes-open').val(purpose)
                        $('.amount').val(amount)
                        // add data-id on archived button
                        // $('.documents-archive').attr('data-archived-id',id)
                        switch (stats) {
                        case 'pending':
                            $('#btn-reprocess').hide()
                            $('.status-badge').html(` <h5 class="badge bg-warning p-2">${stats}</h5>`)
                        case 'forwarded':
                            $('#btn-approved').show()
                            $('#btn-reprocess').hide()
                            $('.status-badge').html(` <h5 class="badge bg-warning p-2">${stats}</h5>`)
                            break;
                        case 'approved':
                            $('#btn-reprocess').hide()
                            $('.status-badge').html(` <h5 class="badge bg-success p-2">${stats}</h5>`)
                            break;
                        case 'archived':
                            $('#btn-reprocess').show()
                            $('.pr-text').hide();
                            $('.pr').hide();
                            $('.reason').hide();
                            $('.reason-text').hide();
                            $('#btn-arc').css({'display':'none'})
                            $('.status-badge').html(` <h5 class="badge bg-danger p-2">${stats}</h5>`)
                            break;
                        case 'completed':
                            $('.pr-text').hide();
                            $('.pr').hide();
                            $('.reason').hide();
                            $('.reason-text').hide();
                            $('#btn-arc').css({'display':'none'})
                            $('#btn-reprocess').show()
                            $('.status-badge').html(` <h5 class="badge bg-success p-2">${stats}</h5>`)
                            break;
                    
                        default:
                        $('.status-badge').html(``)
                            break;
                    }
                        $('#open-document-modal').modal('show')

                       
                })

                function populateUsersForDepartment(response, selectedDepartmentValue) {
                    // Extract office_id from the selected value
                    const selectedOfficeId = selectedDepartmentValue.split('|')[0].trim();
                    // alert(selectedOfficeId)
                    // Filter users based on the selected department
                    const filteredUsers = response.departmentWithUsers.find(data => data.office_id ==
                        selectedOfficeId);
                    console.log(filteredUsers)
                    // Populate users
                    let departementUsersHtml = "";
                    if (filteredUsers) {
                        filteredUsers.users.forEach(user => {
                            departementUsersHtml += `
                                <div class="card p-2 border border-success" style="max-width: 250px;margin:10px auto;">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" value='${user.user_id} | ${user.user_office_id} | ${user.user_name}' name="department_staff" type="radio" role="switch" id="flexSwitchCheckDefault" required>
                                        <label class="form-check-label" for="flexSwitchCheckDefault">${user.user_name}</label>
                                    </div>
                                </div>`;
                        });

                    }

                    $('.con-user').html(departementUsersHtml);
                }

                // pin open
                $('.pin-document-btn').on('click',function(){
                    var trkId = $(this).data('trk')//trk_id
                    var scannedId = $(this).data('scanned-id') //scanned_id
                    var documentId = parseInt($(this).data('id'))//documents id
                   
                    var currentLoc = $(this).data('current-loc') //scanned_id
                    var document = $(this).data('document-id')//documents
                    var officeId = $(this).data('office-id')//documents
                    var requestor = $(this).data('requestor-id')//documents

                    // console.log(trkId, documentId, document)

                    $('.trkNo').text(trkId)
                    $('.timestamp-placeholder').text(document)
                    $('.doc-id').val(documentId)
                    $('.doc').val(document)
                    $('.trk').val(trkId)

                    var departementHtml = ''
                    var departementUsersHtml = ``
            
                    getDepartmentWithUsers(requestor)
                        .then(function(response) {
                            // Process the response (logs) here
                            console.log(response.departmentWithUsers);
                            response.departmentWithUsers.forEach(data => {
                                console.log(data)
                                //office_id | office_name | office_abbrev
                                departementHtml += `
                                    <option value='${data.office_id} | ${data.office_name} | ${data.office_abbrev}'>
                                        ${data.office_name}
                                    </option>`
                                //User id | user_office_id | name
                                // departementUsersHtml += `
                                //     <option value='${data.user_id} | ${data.user_office_id} | ${data.user_name}'>
                                //         ${data.user_name}
                                //     </option>
                                // `
                            });
                            $('.department-select').html(departementHtml)
                            const defaultValue = $('.department-select').val()
                            populateUsersForDepartment(response, defaultValue);
                            $(".department-select").on("change", function() {
                                // Get the selected department value
                                const selectedDepartmentValue = $(this).val();
                                console.log(selectedDepartmentValue);

                                // Populate users based on the selected department
                                populateUsersForDepartment(response, selectedDepartmentValue);
                            });
                            // $('#department-staff-select').html(departementUsersHtml)

                        })
                        .catch(function(err){
                            console.log(err)
                        })

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
                    
                        // $('#pin-document-modal').modal('show')
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
                function getDepartmentWithUsers(requestor) {
                    // alert(id);
                    // Return a promise
                    return new Promise(function(resolve, reject) {
                        // Make an AJAX request to retrieve logs
                        $.ajax({
                            url: `/departments-with-users/${requestor}`, // Replace with your route URL
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
