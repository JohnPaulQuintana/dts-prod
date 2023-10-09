<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="view-documents" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('request.documents.update') }}" class="" method="POST" id="my-documents">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Documents Copy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <input type="text" name="trk_id" id="data-trk-id" value="" hidden>
                                        <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                                        
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h4 class="card-title">Document Files</h4>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="ri-close-line text-white font-size-18 btn btn-danger p-2 waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Reject Documents"></button>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <textarea id="textarea" name="request-text" class="form-control" maxlength="225" rows="3" placeholder="This is your file copy png. just click the here to view full details"></textarea>
                                        </div>
                                        <br>
                                        
                                        <div class="mt-2" id="departments-card">
                                            @for ($i=2;$i<=5;$i++)
                                                {{-- <input type="text" name="department_id" id="data-trk-id" value="{{ $i }}"> --}}
                                                <div class="col-lg-12" id="departments-card-items">
                                                    <div class="card border bg-dark text-light">
                                                        <div class="card-header bg-transparent border-success">
                                                            <div class="row align-items-center"> <!-- Use align-items-center class here -->
                                                                <div class="col">
                                                                    <h5 class="my-0 text-light"><i class="mdi mdi-check-all me-3"></i>Department</h5>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <!-- Your button here -->
                                                                    <a data-department-id="{{ $i }}" type="submit" class="btn btn-light ri-send-plane-line forward-documents" data-bs-toggle="tooltip" data-bs-placement="top" title="Forward Documents"></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        
                                        
                                
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div>
                    {{-- <div class="modal-footer">
                        <button type="submit" class="ri-send-plane-line text-white font-size-18 p-2 btn btn-success waves-effect" data-bs-toggle="tooltip" data-bs-placement="top" title="Forward Documents"></button>
                        <button type="submit" class="ri-close-line text-white font-size-18 btn btn-danger p-2 waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Reject Documents"></button>
                    </div> --}}
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>