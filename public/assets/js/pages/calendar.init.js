!(function (g) {
    "use strict";
    function e() {}
    (e.prototype.init = function () {
        var draggingContent; // Declare a global variable to store dragging content

        var l = g("#event-modal"),
            le = g("#event-modal-edit"),
            t = g("#modal-title"),
            a = g("#form-event"),
            i = null,
            r = null,
            s = document.getElementsByClassName("needs-validation"),
            i = null,
            r = null,
            e = new Date(),
            n = e.getDate(),
            d = e.getMonth(),
            o = e.getFullYear();
        // new FullCalendarInteraction.Draggable(
        //     document.getElementById("external-events"),
        //     {
        //          // Store the dragging content when an item is dragged
        //         // eventDragStart: function (info) {
        //         //     draggingContent = info.event.title;
        //         //     console.log(info)
        //         // },
        //         // eventData: function (e) {
        //         //     // Store the dragging content in the global variable
        //         //     // draggingContent = e.innerText;
        //         //     return {
        //         //         title: e.innerText,
        //         //         className: g(e).data("class"),
        //         //     };
        //         // },
        //     }
        // );
            
        var c = [];

        function readyEvents(E){
            // Clear the existing content of '.ready-events'
            $('.ready-events').html('');
            var rEvents = '';
            E.forEach(element => {
                rEvents += `
                    <div class="external-event fc-event ${element.className}" data-class="${element.className}">
                        <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>${element.title}
                    </div>
                `
            });
            $('.ready-events').html(rEvents)
        }
        // Function to fetch events from the server
        function fetchEvents() {
            // Make an AJAX GET request to retrieve events data
            $.ajax({
                type: "GET",
                url: "/fetch-events", // Replace with your server endpoint URL for fetching events
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    console.log(response)
                    // Populate the 'c' array with the fetched events data
                    c = response;
                    // Render the calendar with the fetched events
                    m.removeAllEvents(); // Remove existing events
                    m.addEventSource(c); // Add the fetched events

                    //populated Event Option
                    readyEvents(response)
                },
                error: function (error) {
                    console.error("Error fetching events:", error);
                },
            });
        }

        // Call the fetchEvents function to populate 'c' with initial data
        fetchEvents();
         var v =
                (document.getElementById("external-events"),
                document.getElementById("calendar"));
        function u(e) {
            l.modal("show"),
                a.removeClass("was-validated"),
                a[0].reset(),
                g("#event-title").val(),
                g("#event-category").val(),
                t.text("Add Event"),
                (r = e);
        }
        var m = new FullCalendar.Calendar(v, {
            plugins: ["bootstrap", "interaction", "dayGrid", "timeGrid"],
            editable: !0,
            droppable: !0,
            selectable: !0,
            defaultView: "dayGridMonth",
            themeSystem: "bootstrap",
            height: 500,
            header: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
            },
            eventClick: function (e) {
                le.modal("show"),
                    a[0].reset(),
                    (i = e.event),
                    console.log(e.event)
                    g("#event-id-edit").val(i.id),
                    // g("#event-start-edit").val(i.start),
                    g("#event-title-edit").val(i.title),
                    g("#event-time-edit").val(i.extendedProps.time),
                    g("#event-notes-edit").val(i.extendedProps.notes),
                    g("#event-category-edit").val(i.classNames[0]),
                    (r = null),
                    t.text("Edit Event"),
                    (r = null);
            },
            dateClick: function (e) {
                u(e);
            },
            eventDragStart: function (info) {
                
                draggingContent = info.event.title;
            },
            eventDrop: function (eventDropInfo) {
                // This function is called when you drop an event.
                // The new start date is available in eventDropInfo.event.start.
        
                var newStartDate = eventDropInfo.event.start;
                // Do something with the new start date, for example, display it in the console
                console.log("Event dropped to:", newStartDate,  draggingContent);
                // Send an AJAX POST request to the server
                $.ajax({
                    type: "POST", // Use POST method
                    url: "/update-events", // Replace with your server endpoint URL
                    data: JSON.stringify({ start: newStartDate, content: draggingContent }), // Send data as JSON
                    contentType: "application/json", // Set content type to JSON
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (response) {
                        // Handle success response from the server
                        console.log("Event Moved successfully:", response);
                        notifyPopUps(JSON.parse(response.notification))

                        fetchEvents()
                    },
                    error: function (error) {
                        // Handle error response from the server
                        console.error("Error adding event:", error);
                    },
                });
            },
            events: c,
        });
        m.render(),

            //   add events forms
            g(a).on("submit", function (e) {
                e.preventDefault();

                var $form = g("#form-event"); // Store the form element in a variable
                var $inputs = $form.find(":input");

                var title = g("#event-title").val();
                var category = g("#event-category").val();
                var time = g("#event-time").val();
                var notes = g("#event-notes").val();

                // Check form validity using HTML5 validation
                if (!$form[0].checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    $form[0].classList.add("was-validated");
                } else {
                    var start = new Date(r.date);
                    var formattedStart = start
                        .toISOString()
                        .slice(0, 19)
                        .replace("T", " ");

                    // var end = new Date("your-end-date-here"); // Replace with the end date
                    // var formattedEnd = end.toISOString().slice(0, 19).replace("T", " ");

                    var eventData = {
                        title: title,
                        time: time,
                        note:notes,
                        start: formattedStart,
                        allDay: r.allDay,
                        className: category,
                    };

                    // Send an AJAX POST request to the server
                    $.ajax({
                        type: "POST", // Use POST method
                        url: "/events", // Replace with your server endpoint URL
                        data: JSON.stringify(eventData), // Send eventData as JSON data
                        contentType: "application/json", // Set content type to JSON
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (response) {
                            // Handle success response from the server
                            console.log("Event added successfully:", response);

                            // Add the event to the calendar if needed
                            m.addEvent(eventData);

                            // Hide the modal
                            l.modal("hide");
                            fetchEvents()
                        },
                        error: function (error) {
                            // Handle error response from the server
                            console.error("Error adding event:", error);
                        },
                    });
                }
            });

            function notifyPopUps(message){
                console.log(message)
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
                        toastr[message.status](message.message);
            }

            g(".btn-delete-event").on("click", function (e) {
                //   i && (i.remove(), (i = null), l.modal("hide"));
                if (i) {
                    // Make an AJAX request to delete the event on the server
                    $.ajax({
                        url: "/delete-event", // Replace with your server endpoint
                        type: "POST",
                        data: { event_id: i.id }, // Provide the event ID or necessary data
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (response) {
                            // Handle the success response here
                            console.log("Event deleted successfully:", response);
                            i.remove(); // Remove the event from the calendar
                            i = null;
                            le.modal("hide");
                            fetchEvents()
                        },
                        error: function (error) {
                            // Handle the error response here
                            console.error("Error deleting event:", error);
                        },
                    });
                }
            }),

            g("#btn-new-event").on("click", function (e) {
                u({ date: new Date(), allDay: !0 });
            });
    }),
        (g.CalendarPage = new e()),
        (g.CalendarPage.Constructor = e);
})(window.jQuery),
    (function () {
        "use strict";
        window.jQuery.CalendarPage.init();
    })();
