@extends('departments.index')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Tracking Documents 
                    <i class="fas fa-arrow-right font-size-20 text-success align-middle mx-2"></i>
                    <span class="bg-success p-2 text-white">{{ $logs[0]->trk_id  }}</span>
                </h4>

                <div id="progrss-wizard" class="twitter-bs-wizard">
                    <ul class="twitter-bs-wizard-nav nav-justified">
                        @foreach ($departments as $key => $department)
                            <li class="nav-item">
                                <a href="#progress-{{ $department }}" class="nav-link" data-toggle="tab">
                                    <span class="step-number">0{{ $key+1 }}</span>
                                    <span class="step-title">{{ $department }}</span>
                                </a>
                            </li>
                        @endforeach
                        
                        <li class="nav-item">
                            <a href="#progress-confirm-detail" class="nav-link" data-toggle="tab">
                                <span class="step-number">04</span>
                                <span class="step-title">Confirm Detail</span>
                            </a>
                        </li>
                    </ul>

                    <div id="bar" class="progress mt-4">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"></div>
                    </div>
                    <div class="table-responsive">
                       
                        <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Tracking No.</th>
                                    <th>Requested To</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td><h6 class="mb-0"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>{{ $log->trk_id }}</h6></td>
                                        <td>{{ $log->user_department }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>
                                            @switch($log->status)
                                                @case("forwarded")
                                                    <!-- Display something when status is 1 -->
                                                    <span class="badge bg-info p-2"><b>{{ $log->status }}</b></span>
                                                    @break
                                                @case("rejected")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-danger p-2"><b>{{ $log->status }}</b></span>
                                                    @break
                                                @case("on-going")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-warning p-2"><b>{{ $log->status }}</b></span>
                                                    @break
                                                @case("done")
                                                    <!-- Display something when status is 2 -->
                                                    <span class="badge bg-success p-2"><b>{{ $log->status }}</b></span>
                                                    @break
                                                @default
                                                    <!-- Display something for other status values -->
                                                    Other Status Content
                                            @endswitch
                                        </td>
                                        
                                        <td>{{ $log->formatted_created_at }}</td>
                                        <td>{{ $log->formatted_time }}</td>
                                    </tr>
                                @endforeach
                                
                                <!-- end -->
                                
                            </tbody><!-- end tbody -->
                        </table> <!-- end table -->
                    </div>
                    
                    {{-- <div class="tab-content twitter-bs-wizard-tab-content">

                        <div class="tab-pane" id="progress-seller-details">
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-firstname-input">First name</label>
                                            <input type="text" class="form-control" id="progress-basicpill-firstname-input">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-lastname-input">Last name</label>
                                            <input type="text" class="form-control" id="progress-basicpill-lastname-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-phoneno-input">Phone</label>
                                            <input type="text" class="form-control" id="progress-basicpill-phoneno-input">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-email-input">Email</label>
                                            <input type="email" class="form-control" id="progress-basicpill-email-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-address-input">Address</label>
                                            <textarea id="progress-basicpill-address-input" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="progress-company-document">
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-pancard-input">PAN Card</label>
                                            <input type="text" class="form-control" id="progress-basicpill-pancard-input">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-vatno-input">VAT/TIN No.</label>
                                            <input type="text" class="form-control" id="progress-basicpill-vatno-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-cstno-input">CST No.</label>
                                            <input type="text" class="form-control" id="progress-basicpill-cstno-input">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-servicetax-input">Service Tax No.</label>
                                            <input type="text" class="form-control" id="progress-basicpill-servicetax-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-companyuin-input">Company UIN</label>
                                            <input type="text" class="form-control" id="progress-basicpill-companyuin-input">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-declaration-input">Declaration</label>
                                            <input type="text" class="form-control" id="progress-basicpill-declaration-input">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>

                        <div class="tab-pane" id="progress-bank-detail">
                            <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-namecard-input">Name on Card</label>
                                            <input type="text" class="form-control" id="progress-basicpill-namecard-input">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label>Credit Card Type</label>
                                            <select class="form-select">
                                                    <option selected>Select Card Type</option>
                                                    <option value="AE">American Express</option>
                                                    <option value="VI">Visa</option>
                                                    <option value="MC">MasterCard</option>
                                                    <option value="DI">Discover</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-cardno-input">Credit Card Number</label>
                                            <input type="text" class="form-control" id="progress-basicpill-cardno-input">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-card-verification-input">Card Verification Number</label>
                                            <input type="text" class="form-control" id="progress-basicpill-card-verification-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="progress-basicpill-expiration-input">Expiration Date</label>
                                            <input type="text" class="form-control" id="progress-basicpill-expiration-input">
                                        </div>
                                    </div>

                                </div>
                            </form>
                            </div>
                        </div>

                        <div class="tab-pane" id="progress-confirm-detail">
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <div class="text-center">
                                        <div class="mb-4">
                                            <i class="mdi mdi-check-circle-outline text-success display-4"></i>
                                        </div>
                                        <div>
                                            <h5>Confirm Detail</h5>
                                            <p class="text-muted">If several languages coalesce, the grammar of the resulting</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="pager wizard twitter-bs-wizard-pager-link">
                        <li class="previous"><a href="javascript: void(0);">Previous</a></li>
                        <li class="next"><a href="javascript: void(0);">Next</a></li>
                    </ul> --}}
                </div>

                
            </div>
        </div>
    </div>
@endsection

@section('script')
     <!-- twitter-bootstrap-wizard js -->
     <script src="assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

     <script src="assets/libs/twitter-bootstrap-wizard/prettify.js"></script>

     <!-- form wizard init -->
     <script src="assets/js/pages/form-wizard.init.js"></script>
@endsection