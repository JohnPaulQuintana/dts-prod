<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="send-documents" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('request.documents') }}" class="" method="POST" id="my-documents">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Request Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                        <h4 class="card-title">Description</h4>
                                        
        
                                        <div>
                                            
                                                @csrf
                                                {{-- <div class="fallback">
                                                    <input name="file" type="file" multiple="multiple">
                                                </div>
                                                <div class="dz-message needsclick">
                                                    <div class="mb-3">
                                                        <i class="display-4 text-muted ri-upload-cloud-2-line"></i>
                                                    </div>
                                                    
                                                    <h4>Drop files here or click to upload.</h4>
                                                </div> --}}

                                                <div class="mt-3">
                                                    <textarea id="textarea" name="request-text" class="form-control" maxlength="225" rows="3" placeholder="This textarea has a limit of 225 chars."></textarea>
                                                </div>

                                                {{-- <div class="text-center mt-4">
                                                    <button id="uploadButton" type="submit" class="btn btn-primary waves-effect waves-light">Send Files</button>
                                                </div> --}}
                                            
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