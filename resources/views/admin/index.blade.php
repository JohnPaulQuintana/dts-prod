<!doctype html>
<html lang="en">

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('head')
        <!-- Scripts -->
        @vite(['resources/js/app.js'])

    </head>

    <body data-topbar="dark">
    
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <header id="page-topbar">
                @include('admin.components.header.header')
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">
                @include('admin.components.sidebar.sidebar')
            </div>
            <!-- Left Sidebar End -->

            
            <!-- Start right Content here -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                    
                        <div class="row">
                            @yield('content')
                        </div>
                        <!-- end row -->
                    </div>
                    
                </div>
                <!-- End Page-content -->
               
                {{-- modals --}}
                @include('admin.components.modals.event')
                @include('admin.components.modals.event-edit')

                <footer class="footer">
                    @include('admin.components.footer.footer')
                </footer>
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center px-3 py-4">
            
                    <h5 class="m-0 me-2">Settings</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
                <h6 class="text-center mb-0">Choose Layouts</h6>

                <div class="p-4">
                    <div class="mb-2">
                        <img src="{{ asset('assets/images/layouts/layout-1.jpg') }}" class="img-fluid img-thumbnail" alt="layout-1">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked>
                        <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                    </div>
    
                    <div class="mb-2">
                        <img src="{{ asset('assets/images/layouts/layout-2.jpg') }}" class="img-fluid img-thumbnail" alt="layout-2">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch" data-bsStyle="assets/css/bootstrap-dark.min.css" data-appStyle="assets/css/app-dark.min.css">
                        <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                    </div>
            
                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        @yield('script')

        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            $(document).ready(function(){

                 // custom popups
                function notifyPopUps(message){
                    // Set Toastr options
                    toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": 300,
                            "hideDuration": 1000,
                            "timeOut": 5000,
                            "extendedTimeOut": 1000,
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };
                        toastr['info'](message);
                }

                function getNotification(){
                    // Make the AJAX request with CSRF token in headers
                    // Get the CSRF token from the hidden input field
                    var csrfToken = $('#csrf-token').val();
                    $.ajax({
                        type: "GET",
                        url: "/notification",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken
                        },
                        success: function (response) {
                            // Handle the AJAX response here
                            console.log(response);
                            var notifHtml = ''
                            // Using a conditional statement
                            if (response.notifications.length > 0) {
                                $('noti-dot').css({'display':'block'})
                                response.notifications.forEach(notif => {
                                    notifyPopUps(`You have a notification from ${notif.notification_from_name}`)
                                    notifHtml += `
                                        <a href="" class="text-reset notification-item">
                                            <div class="d-flex">
                                                <img src="{{ asset('assets/images/users/default-user.png') }}"
                                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="mb-1 text-primary">${notif.notification_from_name}</h6>
                                                    <div class="font-size-12 text-muted">
                                                        <p class="mb-1">${notif.notification_message}</p>
                                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> ${notif.created_at}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        `
                                });
                                $('.notif-container').html(notifHtml)
                            } else {
                                // The response is empty or falsy
                                console.log("Response is empty or falsy:", response);
                                $('.noti-dot').css({'display':'none'})
                            }
                        },
                        error: function (error) {
                            // Handle AJAX error here
                            console.error(error);
                        }
                    });
                }
                getNotification()
                // Enable pusher logging - don't include this in production
                Pusher.logToConsole = true;

                var pusher = new Pusher('60b56d1ff7cab3fbbbee', {
                cluster: 'ap1'
                });

                var channel = pusher.subscribe('update-dashboard');
                channel.bind('initialize-dashboard', function(data) {
                    console.log(JSON.stringify(data));
                    // Reload the page when the event is received
                    getNotification()
                    window.location.reload();
                });

            

            // // remove this later
            // $('#view-documents-btn').on('click',function(){
            //     $('#view-documents').modal('show')
            //     var trkId = $(this).data("trk-id");
            //     $('#data-trk-id').val(trkId)
            // })

            // // remove this later
            // $('.forward-documents').on('click',function(){
            
            //     var dprtId = $(this).data("department-id");
            //     var trk = $('#data-trk-id').val();

            //     var data = {
            //         'trk_id': trk,
            //         'department_id':dprtId,
            //     }
            //     // Get the CSRF token from the hidden input field
            //     var csrfToken = $('#csrf-token').val();

            //     // Make the AJAX request with CSRF token in headers
            //     $.ajax({
            //         type: "POST",
            //         url: "/updates",
            //         data: data,
            //         headers: {
            //             "X-CSRF-TOKEN": csrfToken
            //         },
            //         success: function (response) {
            //             // Handle the AJAX response here
            //             console.log(response);
            //         },
            //         error: function (error) {
            //             // Handle AJAX error here
            //             console.error(error);
            //         }
            //     });
                
            // })

            })
        </script>

        @if (session()->has('notification'))
            <script>
                $(document).ready(function() {
                    // Set Toastr options
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": 300,
                        "hideDuration": 1000,
                        "timeOut": 5000,
                        "extendedTimeOut": 1000,
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    var notificationJson = {!! json_encode(session('notification')) !!};
                    var notification = JSON.parse(notificationJson);
                    console.log(notification)
                    toastr[notification.status](notification.message);
                });
            </script>
        @endif
    </body>

</html>