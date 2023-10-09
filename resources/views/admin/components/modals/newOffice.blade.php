<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="new-office" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('administrator.dashboard.offices.add') }}" class="" method="POST" id="new-office">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">New Office</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                                        
                                        <div class="row">

                                            <div class="mb-3">
                                                <label for="office-name" class="form-label">Office Name</label>
                                                <input type="text" name="office_name" class="form-control" id="office-name" placeholder="Office Name">
                                            </div>

                                            <div class="mb-3">
                                                <label for="office-desc" class="form-label">Office Description</label>
                                                <input type="text" name="office_desc" class="form-control" id="office-description" placeholder="Office Description">
                                            </div>

                                            <div class="mb-3">
                                                <label for="office-head" class="form-label">Office Head</label>
                                                <input type="text" name="office_head" class="form-control" id="office-head" placeholder="Office Head">
                                            </div>

                                            <div class="mb-3">
                                                <select class="form-select" name="office_type" aria-label="Office Type">
                                                    <option selected>Office Type</option>
                                                    <option value="viewing">Viewing</option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="text-white font-size-18 p-2 btn btn-success waves-effect" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New Office">Submit</button>
                        {{-- <button type="submit" class="ri-close-line text-white font-size-18 btn btn-danger p-2 waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Reject Documents"></button> --}}
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>