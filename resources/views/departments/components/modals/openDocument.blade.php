
<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="open-document-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('administrator.dashboard.incoming.request.update') }}" class="" method="POST" id="request-form" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Document Preview <span id="stats"></span></h5>
                        <button type="button" id="close-modal" class="btn-close openBtnClose" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                            <input type="number" name="id" id="doc-id" value="" hidden>
                                             {{-- {{ $documents }}    --}}
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body">
                    
                                                    <div class="embed-responsive embed-responsive-16by9" style="height: 400px;overflow-y:auto;">
                                                        {{-- <iframe id="preview-doc" src="" frameborder="0" class="img-fluid w-100 embed-responsive-item" style="height: 100%;"></iframe> --}}
                                                        <img id="preview-doc" src="assets/images/small/img-2.jpg" class="img-fluid" alt="Responsive image">
                                                    </div>
                                                    <br>
                                                    <label for="po" class="text-center">Purchase Order</label>
                                                    <input type="number" name="po" value="" class="form-control text-center mb-2 po" placeholder="PO-123456">
                                                    <label for="amount" class="text-center">Requested Amount</label>
                                                    <input type="text" name="amount" value="" class="form-control text-center mb-2 amount" readonly>
                                                    <label for="amount" class="text-center">Description</label>
                                                    <textarea class="form-control text-center event-notes-open" rows="3" type="text"
                                                        name="notes" id="event-notes" required value="" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                        </div> <!-- end row -->
                    </div>
                    <div class="modal-footer">
                        @if (Auth::user()->assigned !== 'viewing' && Auth::user()->id)
                            <input type="submit" name="action" class="btn btn-success openbtn waves-effect btn-r btn-approved" id="btn-approved" value="Approved">
                            <input type="submit" name="action" class="btn btn-danger openbtn waves-effect waves-light documents-archive btn-a btn-archived" id="btn-archived" value="Archived">
                            {{-- <input type="button" class="btn btn-success waves-effect btn-r">
                            <input type="submit" class="btn btn-danger waves-effect waves-light btn-a"> --}}
                        @endif
                        
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>