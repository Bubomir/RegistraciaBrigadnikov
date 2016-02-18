var defDate = $.ajax({
    type: 'POST', // Send post data
    url: 'process.php',
    data: 'type=get_default_date',
    async: false,
    done: function (response) {
        return response;
    }
});

var defNow = $.ajax({
    type: 'POST',
    url: 'process.php',
    data: 'type=get_now',
    async: false,
    done: function (response) {
        return response;
    }
});

var loggedEmail = $.ajax({
    type: 'POST',
    url: 'process.php',
    data: 'type=get_loggedEmail',
    async: false,
    done: function (response) {
        return response;
    }
});

var loggedPermissions = $.ajax({
    type: 'POST',
    url: 'process.php',
    data: 'type=get_loggedPermissions',
    async: false,
    done: function (response) {
        return response;
    }
});
$(document).ready(function () {
    /***********************************************************************/
    /******** Loop for Checking multiple Log In SET UP on every 1 SEC ******/
    /***********************************************************************/
    setInterval(ajaxCall, 1000); //1000 ms = 1 second
    function ajaxCall() {
        var returndata;
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: 'type=session_check',
            success: function (data) {
                returndata = data;

                if (returndata == '{"status":"success"}') {
                    alert("boli ste odpojeny");

                    window.location.replace("index.php");

                }
            }
        });
    }
    var zone = "01:00"; //TIME ZONE FOR MIDLE OF EUROPE
    $.ajax({
        url: 'process.php',
        type: 'POST', // Send post data
        data: 'type=fetch',
        async: false,
        success: function (s) {
            json_events = s;
        }
    });
    /*****************************************************/
    /*************** Data for mouse cursor possion *******/
    /****************************************************/
    /*
    	var currentMousePos = {
    	    x: -1,
    	    y: -1
    	};
    		jQuery(document).on("mousemove", function (event) {
            currentMousePos.x = event.pageX;
            currentMousePos.y = event.pageY;
        });
    */
    /* initialize the external events
    -----------------------------------------------------------------*/
    $('#external-events .fc-event ').each(function () {
        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            color: $.trim($(this).data('color')),
            description: $.trim($(this).data('description')),
            stick: true // maintain when user navigates (see docs on the renderEvent method)

        });
        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true, // will cause the event to go back to its
            revertDuration: 0 //  original position after the drag
        });
    });
    /* initialize the calendar
    -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({
        events: JSON.parse(json_events),
        utc: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month'
        },
        droppable: true,
        allDaySlot: false,
        defaultTimedEventDuration: '12:00:00',
        defaultDate: defDate,

        /**********************************************/
        /*************** ADD EVENTS********************/
        /**********************************************/

        eventReceive: function (event) {

            var now = moment(defNow);
            var click_time = moment(event.start.format() + '+' + zone);

            var duplicity_bool;
            duplicity_bool = $.ajax({
                url: 'process.php',
                type: 'POST',
                data: 'type=duplicity_ceck&startDate=' + event.start.format() + '&zone=' + zone + '&emailHash=' + event.description,
                async: false,
                done: function (response) {
                    return response;
                }
            });



            if (duplicity_bool.responseText == 'failed') {
                if (moment.duration(click_time.diff(now)).asMinutes() > 0) {
                    if (event.title == 'Brigádnici R' || event.title == 'Brigádnici N') {
                        //vstup pre kapacitu brigadnikov a pretypovanie string na INT
                        var worker_capacity = parseInt(prompt('počet brigádnikov:', "", {
                            buttons: {
                                Ok: true,
                                Cancel: false
                            }
                        }));
                        //Osetrenie na datovy typ INT a na min kapacitu smeny jednej brigadnik
                        if (!isNaN(worker_capacity) && worker_capacity > 0) {
                            eventAdd(event, worker_capacity);
                        } else {
                            alert("Zle zadane cislo");
                            refreshEvents();
                        }
                    } else {
                        eventAdd(event, null);
                    }
                } else {
                    alert("Nemozno pridat smenu");
                    refreshEvents();
                }
            } else {
                alert("Duplicitny zapis");
                refreshEvents();
            }

        },

        /**********************************************/
        /******** Drag and drop remove EVENTS *********/
        /**********************************************/

        //DROP listerner for EVENTS
        /*eventDrop: function(event, delta, revertFunc) {

                    var title = event.title;
                    var start = event.start.format();
                    var end = (event.end == null) ? start : event.end.format();
                    $.ajax({
                        url: 'process.php',
                        data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(response){
                            if(response.status != 'success')
                            revertFunc();
                        },
                        error: function(e){
                            revertFunc();
                            alert('Error processing your request: '+e.responseText);
                        }
                    });

		    },*/

        /**********************************************/
        /*************** CLICK EVENTS******************/
        /**********************************************/

        eventClick: function (event, jsEvent, view) {
            //Only brigadier accounts may click on brigadier button.
            var permissions = $.ajax({
                type: 'POST',
                url: 'process.php',
                data: 'type=get_loggedPermissions',
                async: false,
                done: function (response) {
                    return response;
                }
            }).responseText;


            var email = $.ajax({
                type: 'POST',
                url: 'process.php',
                data: 'type=get_loggedEmail',
                async: false,
                done: function (response) {
                    return response;
                }
            }).responseText;

            var check_logIn_logOut = $.ajax({
                type: 'POST', // Send post data
                url: 'process.php',
                data: 'type=check_log_in_log_out&event_id=' + event.id + '&email=' + email,
                async: false,
                done: function (response) {
                    return response;
                }
            });

            var check_interval_time = $.ajax({
                type: 'POST', // Send post data
                url: 'process.php',
                data: 'type=check_interval_time&event_id=' + event.id,
                async: false,
                done: function (response) {
                    return response;
                }
            });


            if (permissions == 'brigadnik') {

                if (check_logIn_logOut.responseText != 0) {
                    if(check_interval_time.responseText>5){
                        if (event.title.search(" Brigádnici:") == 0) {
                            var confirmDialog = confirm('Naozaj sa chcete odhlásiť z tejto zmeny?');
                            if (confirmDialog == true) {
                                loggedInUpdate(event, email, -1); // -1 == log out from event
                            }
                        }
                    } else {
                        alert("Nemozno sa odhlasit v tejto lehote");
                    }
                }

                else {
                    if (event.title.search(" Brigádnici:") == 0) {
                        var confirmDialog = confirm('Naozaj sa chcete prihlásiť na tuto smenu?');
                        if (confirmDialog == true) {
                            loggedInUpdate(event, email, 1); // 1 == log in on event
                        }
                    }
                }
            }
            if (permissions == 'admin' || permissions == 'supervizor') {
                if (event.title.search(" Brigádnici:") == 0) {
                    //vstup pre kapacitu brigadnikov a pretypovanie string na INT
                    var worker_capacity = parseInt(prompt('počet brigádnikov:', "", {
                        buttons: {
                            Ok: true,
                            Cancel: false
                        }
                    }));
                    //Osetrenie na datovy typ INT a na min kapacitu smeny jednej brigadnik
                    if (!isNaN(worker_capacity) && worker_capacity > -1) {

                        var varning_resposne = $.ajax({
                            url: 'process.php',
                            type: 'POST',
                            data: 'type=changeCapacity&eventID=' + event.id + '&capacity=' + worker_capacity,
                            async: false,
                            done: function (response) {
                                return response;
                            }
                        });
                        if (varning_resposne.responseText == 'failed') {
                            alert("Najskor treba odhlasit brigadnika/ov");
                        }
                        refreshEvents();
                    } else {
                        alert("Zle zadane cislo");
                        refreshEvents();
                    }
                } else {
                    deleteEvent(event);
                }

            }

        }

        /*eventResize: function(event, delta, revertFunc) {
				console.log(event);
				var title = event.title;
				var end = event.end.format();
				var start = event.start.format();
		        update(title,start,end,event.id);
		    },*/


    });

    /**********************************************/
    /*************** RENDER EVENTS*****************/
    /**********************************************/

    function getFreshEvents() {
        $.ajax({
            url: 'process.php',
            type: 'POST', // Send post data
            data: 'type=fetch',
            async: false,
            success: function (s) {
                freshevents = s;
            }
        });

        $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
    }

    /***********************************************************/
    /***********Test If Cursor is Over the Calendar DIV*********/
    /***********************************************************/
    /*
    	function isElemOverDiv() {
            var trashEl = jQuery('#trash');
            var ofs = trashEl.offset();
            var x1 = ofs.left;
            var x2 = ofs.left + trashEl.outerWidth(true);
            var y1 = ofs.top;
            var y2 = ofs.top + trashEl.outerHeight(true);
            if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
                currentMousePos.y >= y1 && currentMousePos.y <= y2) {
                return true;
            }
            return false;
        }
    */
    /**********************************************/
    /*************** ADD EVENTS********************/
    /**********************************************/

    function eventAdd(event, capacity) {

        var return_response = $.ajax({
            url: 'process.php',
            data: 'type=new&email=' + event.description + '&start_date=' + event.start.format() + '&zone=' + zone + '&capacity=' + capacity + '&logged_in=' + 0 + '&color=' + event.color,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                return response;
            },
            error: function(e){
                    console.log(e.responseText);
            }

        });
       refreshEvents();

    }

    function loggedInUpdate(event, email, logIn_logOut) {

         var return_response = $.ajax({
            url: 'process.php',
            data: 'type=change_number_of_logged_in&email=' + email + '&logIn_logOut=' + logIn_logOut + '&event_id=' + event.id,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                    return response;
                },
             error: function(e){
                    console.log(e.responseText);
            }
        });

        refreshEvents();
    }

    /*************************************************/
    /***************REFRESH EVENTS *******************/
    /*************************************************/
    function refreshEvents() {

        console.log('Render');
        $('#calendar').fullCalendar('removeEvents');
        getFreshEvents();
        $('#calendar').fullCalendar('rerenderEvents');
        location.reload;
    }
    /**********************************************/
    /*************** DELETE EVENTS*****************/
    /**********************************************/

    function deleteEvent(event) {
        var con = confirm('Naozaj sa chcete odhlásiť z tejto zmeny?');
        if (con == true) {
            var return_response = $.ajax({
                url: 'process.php',
                data: 'type=remove&event_id=' + event.id,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    return response;
                },
                error: function(e){
                    console.log(e.responseText);
                }
            });
        }
        refreshEvents();
    }
});
