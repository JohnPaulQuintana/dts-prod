
<div class="col-sm-6 col-md-4 col-xl-3">
    <div class="modal fade" id="print-barcode-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form class="pin-form" method="POST" id="request-form" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Print Barcode Document <span id="stats"></span></h5>
                        <button type="button" id="close-modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      
                          <iframe class="pdf-container" src="" style="top:0; left:0; bottom:0; right:0; width:100%; height:480px; border:none; margin:0; padding:0; overflow:hidden;"></iframe>
                          
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>