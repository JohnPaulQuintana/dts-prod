<!-- Add New Event MODAL -->
<div class="modal fade" id="event-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3 px-4">
                <h5 class="modal-title" id="modal-title">Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form method="POST" action="{{ route('events.push') }}" class="needs-validation" name="event-form" id="form-event" novalidate>
                    @csrf
                    <div class="row">
                        <input type="number" name="event_id" id="event_id" value="" hidden>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Event Name</label>
                                <input class="form-control" placeholder="Insert Event Name" type="text"
                                    name="title" id="event-title" required value="">
                                <div class="invalid-feedback">Please provide a valid event name
                                </div>
                            </div>
                        </div> <!-- end col-->

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Time - optional</label>
                                <input class="form-control" rows="5" type="time"
                                    name="notes" id="event-time" required value="">
                                <div class="invalid-feedback">Please provide a valid event notes
                                </div>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Notes - Explanation of events</label>
                                <textarea class="form-control" rows="5" type="text"
                                    name="notes" id="event-notes" required value=""></textarea>
                                <div class="invalid-feedback">Please provide a valid event notes
                                </div>
                            </div>
                        </div> <!-- end col-->

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" id="event-category">
                                    <option  selected> --Select-- </option>
                                    <option value="bg-danger">Danger</option>
                                    <option value="bg-success">Success</option>
                                    <option value="bg-primary">Primary</option>
                                    <option value="bg-info">Info</option>
                                    <option value="bg-dark">Dark</option>
                                    <option value="bg-warning">Warning</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid event
                                    category</div>
                            </div>
                        </div> <!-- end col-->

                    </div> <!-- end row-->
                    <div class="row mt-2">
                        <div class="col-6">
                            {{-- <button type="button" class="btn btn-danger"
                                id="btn-delete-event">Delete</button> --}}
                        </div> <!-- end col-->
                        <div class="col-6 text-end">
                            {{-- <button type="button" class="btn btn-light me-1"
                                data-bs-dismiss="modal">Close</button> --}}
                            <button type="submit" class="btn btn-success" id="btn-save-event">Add new events</button>
                        </div> <!-- end col-->
                    </div> <!-- end row-->
                </form>
            </div>
        </div>
        <!-- end modal-content-->
    </div>
    <!-- end modal dialog-->
</div>
<!-- end modal-->