
<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="print-report-modal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form class="report-form" method="POST" id="report-form" enctype="multipart/form-data" action="{{ route('cancel.reports') }}">
                @csrf

                <input type="number" name="id" id="id" value="" class="report_id" hidden>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Print Report Document <span id="stats"></span></h5>
                        {{-- <button type="button" id="close-modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                        <input type="submit" class="btn btn-danger waves-effect" name="action" value="Cancel">
                    </div>
                    <div class="modal-body">
                      
                          <iframe class="pdf-container" src="" style="top:0; left:0; bottom:0; right:0; width:100%; height:480px; border:none; margin:0; padding:0; overflow:hidden;"></iframe>
                          
                    </div>
                    <div class="modal-footer">
                        {{-- <input type="submit" class="btn btn-danger waves-effect" name="action" value="Cancel"> --}}
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>