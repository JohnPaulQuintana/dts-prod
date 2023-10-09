<!doctype html>
<html lang="en">

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">

       {{-- @include('departments.components.header.links') --}}
        @yield('head')
    </head>

    <body data-topbar="dark">
    
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <header id="page-topbar">
                @include('departments.components.header.header')
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">
                @include('departments.components.sidebar.sidebar')
            </div>
            <!-- Left Sidebar End -->

            
            <!-- Start right Content here -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                    
                        
                            @yield('content')
                        
                    </div>
                    
                </div>
                <!-- End Page-content -->
               
                {{-- modals --}}
                @include('departments.components.modals.event')

                <footer class="footer">
                    @include('departments.components.footer.footer')
                </footer>
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->


        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        {{-- @include('departments.components.footer.scripts') --}}
        @yield('script')


        
        {{-- custom script --}}

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
                            "preventDuplicates": true,
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
                        // Clear any existing toastr notifications
                        // toastr.clear();
                        toastr['info'](message);
            }
            // department
            function getDepartment(){
                var csrfToken = $('#csrf-token').val();
                    $.ajax({
                        type: "GET",
                        url: "/dept",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken
                        },
                        success: function(res){
                            $('.dept').text(res.department[0].office_name)
                            console.log(res)
                        },
                        error: function(err){
                            console.log(err)
                        }
                    })
            }
            
            function getNotification(){
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

            function getOnGoing(){
                var csrfToken = $('#csrf-token').val();
                    $.ajax({
                        type: "GET",
                        url: "/on",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken
                        },
                        success: function(res){
                            $('.on-card').text(res.response.forwarded)
                            $('.accomplished').text(res.response.accomplished)
                            $('.rejected').text(res.response.rejected)
                            console.log(res)
                        },
                        error: function(err){
                            console.log(err)
                        }
                    })
            }
            getDepartment()
            getOnGoing()
            getNotification()
           
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            var pusher = new Pusher('60b56d1ff7cab3fbbbee', {
            cluster: 'ap1'
            });

            var channel = pusher.subscribe('update-dashboard');
            channel.bind('initialize-dashboard', function(data) {
            console.log(JSON.stringify(data));
                getNotification();
                // Reload the page when the event is received
                window.location.reload();
            });

            })
        </script>

        {{-- <script>
            $(document).ready(function(){
                // $('#send-documents-btn').on('click',function(){
                //     $('#send-documents').modal('show')

                //     // Dropzone.options.myDocuments = {
                //     //     paramName: "file",
                //     //     maxFilesize: 2, // Max file size in MB
                //     //     acceptedFiles: ".jpg, .jpeg, .png, .gif",
                //     //     addRemoveLinks: true,
                //     //     autoProcessQueue: false, // Set autoProcessQueue to false

                //     //     init: function () {
                //     //         var myDropzone = this;

                //     //         // Add a click event handler to the "Upload" button using jQuery
                //     //         $('#uploadButton').click(function (e) {
                //     //             e.preventDefault();
                //     //             e.stopPropagation();
                //     //             // Process and upload the queued files when the button is clicked
                //     //             myDropzone.processQueue();
                //     //         });

                //     //         this.on('success', function (file, response) {
                //     //             console.log('File uploaded successfully');
                //     //             // Display uploaded file
                //     //             $('#filePreviews').append('<p>' + file.name + '</p>');
                //     //         });

                //     //         this.on('error', function (file, response) {
                //     //             console.error('File upload error');
                //     //         });
                //     //     }
                //     // };

                // })
            })
        </script> --}}
    </body>

</html>