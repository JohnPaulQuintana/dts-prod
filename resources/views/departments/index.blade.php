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
                                        <a href="" class="text-reset notification-item" data-id="${notif.notification_from_id}" data-trk="${notif.notification_trk}">
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
                                
                                $('.notification-item').on("click", function(){
                                //    alert($(this).data('id'))
                                   updateNotif($(this).data('id'), $(this).data('trk'));
                                })
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
                            $('.assigned').text(res.response.assigned)

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

            function updateNotif($id, $trk){
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');;
                    // alert(csrfToken)
                    $.ajax({
                        type: "POST",
                        url: "/notification-update",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken
                        },
                        data: {'id':$id},
                        success: function (response) {
                            // console.log(response)
                             // Redirect to the desired URL using JavaScript
                           
                            if($trk !== null){
                                window.location.href = '/request-documents?search_id=' + $trk;
                            }
                        },
                        error: function(err){
                            console.log(err)
                        }
                    })
                }

                function updateTime() {
                    const currentTime = new Date();
                    const year = currentTime.getFullYear();
                    const month = currentTime.getMonth() + 1; // Months are zero-indexed
                    const day = currentTime.getDate();
                    const hours = currentTime.getHours();
                    const minutes = currentTime.getMinutes();
                    const seconds = currentTime.getSeconds();
                    const ampm = hours >= 12 ? 'PM' : 'AM';

                    // Format the time
                    const formattedTime = `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds} ${ampm}`;

                    // Format the date
                    const formattedDate = `${year}-${month < 10 ? '0' : ''}${month}-${day < 10 ? '0' : ''}${day}`;

                    // Use .text() to set the text content of elements with the class 'current-time'
                    $('.current-date').text(formattedDate);
                    $('.current-time').text(formattedTime);
                }

                // Update the time immediately and then every 1 second (1000 milliseconds)
                updateTime();
                setInterval(updateTime, 1000);


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