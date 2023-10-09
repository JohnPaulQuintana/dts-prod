<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="new-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('administrator.dashboard.user.add') }}" class="" method="POST" id="newUsers">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                                        <input type="number" id="office_id_user" name="office_id" value="0" hidden>
                                        
                                        <div class="row g-3">
                                           <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="first-name" class="form-label">First Name</label>
                                                    <input type="text" name="first_name" class="form-control" id="first-name">
                                                </div>
                                           </div>
                                            
                                           <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="middle-name" class="form-label">Middle Name</label>
                                                    <input type="text" name="middle_name" class="form-control" id="middle-name">
                                                </div>
                                           </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="last-name" class="form-label">Last Name</label>
                                                    <input type="text" name="last_name" class="form-control" id="last-name">
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="type" class="form-label">Type</label>
                                                    <select class="form-select" name="type" aria-label="Type">
                                                        <option value="staff">Staff</option>
                                                        <option value="viewing">Viewing</option>
                                                    </select>
                                                </div>
                                           </div>

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="user-name" class="form-label">Username</label>
                                                    <input type="text" name="username" class="form-control" id="user-name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email - <span class="text-primary">(for Confirmation of account)</span></label>
                                                    <input type="email" name="email" class="form-control" id="email">
                                                </div>
                                           </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" name="password" class="form-control" id="password">
                                                </div>
                                           </div>

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <div class="mb-3">
                                                        <label for="confirmed-password" class="form-label">Confirmed Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control" id="confirmed-password">
                                                    </div>
                                                </div>
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