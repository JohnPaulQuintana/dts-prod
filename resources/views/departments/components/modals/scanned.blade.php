
<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="scanned-barcode-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">

            <form class="pin-form" method="POST" id="scanned-form" action="{{ route('recieved.document') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Receive Document <span id="stats"></span></h5>
                        <button type="button" id="close-modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                        <input type="number" class="rdi" value="" name="document_id" hidden>
                        <input type="text" class="loc" value="" name="document_current_loc" hidden>


                        <div class="row g-3">
                            <div class="col-12 p-2 text-center" style="position: relative;top: 20px;">
                                <h6>Wait for the documents to proceed to this section.</h6>
                            </div>

                            <div class="col-12" id="scanner-activation">
                                <div class="mb-3 text-center">
                                    <div class="border rounded p-1" style="width:200px; height:150px; margin:auto;">
                                        <p class="" style="position: relative;top: 10px;">TRK-XXXXXX</p>
                                        <i class="ri-barcode-line fa-6x mb-2" style="position: relative;top: -35px;"></i> <!-- Larger document icon -->
                                        <p class="text-danger" style="position: relative;top: -65px;"><b>Scanned Required!</b></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-8 text-center" style="margin: auto;">
                                <h4 class="card-title">Tracking Number</h4>
                                <input class="form-control text-center trk-input" type="text" name="tracking_no" placeholder="TRK-XXXXXX" required>
                            </div>

                            <div class="col-8 text-center mt-3" style="margin: auto;">
                                <input class="btn btn-info text-center me-2" type="submit" value="Recieved" data-bs-toggle="tooltip" data-bs-placement="top" title="Manual Received">
                                <span style="padding-right: 10px;">-OR-</span>
                                <input class="btn btn-success text-center scanner-btn" type="button" value="Scanner" data-bs-toggle="tooltip" data-bs-placement="top" title="Scanner Received">
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>