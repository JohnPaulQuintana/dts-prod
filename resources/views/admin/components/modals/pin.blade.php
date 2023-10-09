
<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="pin-document-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('administrator.dashboard.incoming.request.forward') }}" class="pin-form" method="POST" id="request-form" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Forward Document <span id="stats"></span></h5>
                        <button type="button" id="close-modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-center">Tracking No. - <span class="text-primary trkNo" id="trkNo">TRK-12345678</span></h5>
                        <div class="row">
                            <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                            <input type="number" name="id" class="doc-id" value="" hidden>
                            <input type="text" name="document" class="doc" value="" hidden>
                            <input type="text" name="trk_id" class="trk" value="" hidden>
                                                
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="mb-3 text-center">
                                                                <div class="border rounded p-3" id="image-preview" style="width:200px; margin:auto;">
                                                                    <i class="far fa-file-alt fa-6x mb-2" style="position: relative;">
                                                                        <button type="button" class="btn btn-success fas fa-check" id="success-preview" style="position: absolute; top: -10px; right: -20px;"></button>
                                                                    </i> <!-- Larger document icon -->
                                                                    <br>
                                                                    <span class="mt-2 timestamp-placeholder" id="timestamp-placeholder">xxxxxx</span> <!-- Timestamp placeholder below -->
                                                                
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6" style="margin: auto;">
                                                            <div class="mb-2 text-center">
                                                                <h4 class="card-title">Departments:</h4>
                                                                <select id="department-select" name="department" class="form-select" aria-label="Default select example" required>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-6" style="margin: auto;">
                                                            <div class="mb-2 text-center">
                                                                <h4 class="card-title">Department Staffs:</h4>
                                                                <select id="department-staff-select" name="department_staff" class="form-select" aria-label="Default select example" required>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-12 mt-3" style="margin: auto;">
                                                            <div class="mb-2 text-center">
                                                                <h4 class="card-title">Notes:</h4>
                                                                <textarea name="notes" id="textarea" class="form-control" maxlength="225" rows="3" placeholder="Add notes here."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        </div> <!-- end row -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success waves-effect" id="btn-approved">Forward Document</button>
                        {{-- <button type="submit" class="btn btn-danger waves-effect waves-light">Archived</button> --}}
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>