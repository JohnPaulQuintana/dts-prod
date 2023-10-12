
<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="open-document-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('administrator.dashboard.incoming.request.update') }}" class="" method="POST" id="request-form" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Document Preview <span id="stats"></span></h5>
                        <button type="button" id="close-modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-center">Tracking No. - <span class="text-primary" id="trkNo">TRK-12345678</span></h5>
                        <div class="row">
                            <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                            <input type="number" name="id" id="doc-id" value="" hidden>
                                                
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body">
                    
                    
                                                    <div class="embed-responsive embed-responsive-16by9" style="height: 400px;">
                                                        {{-- <iframe id="preview-doc" src="" frameborder="0" class="img-fluid w-100 embed-responsive-item" style="height: 100%;"></iframe> --}}
                                                        <img id="preview-doc" src="assets/images/small/img-2.jpg" class="img-fluid" alt="Responsive image">
                                                    </div>
                                                </div>
                                                <br>
                                                <textarea class="form-control text-center event-notes-open" rows="3" type="text"
                                                    name="notes" id="event-notes" required value=""></textarea>
                                            </div>
                                        </div>

                                        
                        </div> <!-- end row -->
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="action" class="btn btn-success waves-effect" id="btn-approved" value="Approved">
                        <input type="submit" name="action" class="btn btn-danger waves-effect waves-light documents-archive" value="Archived">
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>