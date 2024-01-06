
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
                        <div class="d-flex justify-content-center status-badge">
                           
                        </div>
                        <div class="row">
                            <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                            <input type="number" name="id" id="doc-id" value="" hidden>
                                                
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body">
                    
                    
                                                    <div class="embed-responsive embed-responsive-16by9" style="height: 400px;overflow-y:auto;">
                                                        {{-- <iframe id="preview-doc" src="" frameborder="0" class="img-fluid w-100 embed-responsive-item" style="height: 100%;"></iframe> --}}
                                                        <img id="preview-doc" src="assets/images/small/img-2.jpg" class="img-fluid" alt="Responsive image">
                                                    </div>
                                                </div>
                                                <br>
                                                <label for="amount" class="text-center">Requested Amount</label>
                                                <input type="text" name="amount" value="" class="form-control text-center mb-2 amount" readonly>
                                                <label for="amount" class="text-center">Description</label>
                                                <textarea class="form-control text-center event-notes-open" rows="3" type="text"
                                                    name="notes" id="event-notes" required value="" readonly></textarea>
                                                <br/>
                                               
                                                <label for="pr" class="text-center pr-text">Purchased Request</label>
                                                <input type="text" name="pr" value="" class="form-control text-center mb-2 pr" placeholder="Enter your purchased request">
                                                
                                                <label for="po" class="text-center po-text">Purchased Order</label>
                                                <input type="text" name="po" value="" class="form-control text-center mb-2 po" placeholder="Enter your purchased order">
                                                <label for="amount" class="text-center text-danger reason-text">give them a reason's why this documents archived!</label>
                                                <textarea class="form-control text-center reason" rows="3" type="text"
                                                    name="reason" id="event-notes" value="" placeholder="This section is for giving them a valid reason for being archived there documents optional"></textarea>
                                            </div>
                                        </div>

                                        
                        </div> <!-- end row -->
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="action" class="btn btn-success waves-effect" id="btn-approved" value="Approved">
                        
                        <input type="submit" name="action" class="btn btn-info waves-effect waves-light documents-reprocess" id="btn-reprocess" value="Re-process">
                        <input type="submit" name="action" class="btn btn-danger waves-effect waves-light documents-archive" id="btn-arc" value="Discontinued">
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>