
<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="new-request-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('request.documents') }}" class="" method="POST" id="request-form" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Request Form</h5>
                        <button type="button" id="close-modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                            <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                                                
                                                <div class="row g-3">
                                                    
                                                    <div class="col-12">
                                                        <div class="mb-3 text-center">
                                                            <div class="border rounded p-2" id="image-preview" style="display: none; width:fit-content; margin:auto;">
                                                                <i class="far fa-file-alt fa-5x mb-2" style="position: relative;">
                                                                    <button type="button" class="btn btn-danger fas fa-times" id="cancel-preview" style="position: absolute; top: -10px; right: -20px;"></button>
                                                                </i> <!-- Larger document icon -->
                                                                <br> <!-- Line break to display timestamp below -->
                                                                <span class="mt-2" id="timestamp-placeholder"></span> <!-- Timestamp placeholder below -->
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6" style="margin: auto;">
                                                        <div class="mb-2 text-center">
                                                            <h4 class="card-title">Upload Documents</h4>
                                                            <input class="form-control" type="file" name="document" accept="image/*" id="image">
                                                        </div>
                                                    </div>

                                                    <div class="col-6" style="margin: auto;">
                                                        <div class="mb-2 text-center">
                                                            <h4 class="card-title">Forward to:</h4>
                                                            <select id="department-select" name="department" class="form-select" aria-label="Default select example">
                                                                <option selected>Select a department</option>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    
                                                    
                                                </div>

                                               

                                                <div class="mt-3">
                                                    <h4 class="card-title">Description</h4>
                                                    <textarea id="textarea" name="request-text" class="form-control" maxlength="225" rows="3" placeholder="This textarea has a limit of 225 chars."></textarea>
                                                </div>

                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>