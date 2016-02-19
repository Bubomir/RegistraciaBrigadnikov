var $;
var loggedEmail = $.ajax({
    type: 'POST',
    url: 'process.php',
    data: 'type=get_loggedEmail',
    async: false,
    done: function (response) {
        "use strict";
        return response;
    }
});

var loggedPermissions = $.ajax({
    type: 'POST',
    url: 'process.php',
    data: 'type=get_loggedPermissions',
    async: false,
    done: function (response) {
        "use strict";
        return response;
    }
});
$(document).ready(function () {
    "use strict";
    /**********************************************/
    /*************** RENDER EVENTS*****************/
    /**********************************************/

    //Define variables
    var return_response;

    function getFreshEvents() {

        var freshevents,
            start_month,
            end_month;

        start_month = moment($('#calendar').fullCalendar('getView').start._d).format('YYYY-MM-DD HH:mm:ss');
        end_month = moment($('#calendar').fullCalendar('getView').end._d).format('YYYY-MM-DD HH:mm:ss');

        return_response = $.ajax({
            url: 'process.php',
            type: 'POST', // Send post data
            data: 'type=fetch&start_month=' + start_month + '&end_month=' + end_month,
            async: false,
            success: function (s) {
                freshevents = s;
                return s;
            }
        });

        $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
    }
    /*************************************************/
    /***************REFRESH EVENTS *******************/
    /*************************************************/
    function refreshEvents() {
        $('#calendar').fullCalendar('removeEvents');
        getFreshEvents();
        $('#calendar').fullCalendar('updateEvents');
        $('#calendar').fullCalendar('rerenderEvents');

    }
    /**********************************************/
    /*************** DELETE EVENTS*****************/
    /**********************************************/

    function deleteEvent(event) {

        var con = window.confirm('Naozaj sa chcete odhlásiť z tejto zmeny?');
        if (con === true) {
            return_response = $.ajax({
                url: 'process.php',
                data: 'type=remove&event_id=' + event.id,
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function (response) {
                    return response;
                },
                error: function (e) {
                    window.console.log(e.responseText);
                }
            });
        }
        refreshEvents();
    }
    /**********************************************/
    /*************** ADD EVENTS********************/
    /**********************************************/

    function eventAdd(event, capacity) {

        var return_response = $.ajax({
            url: 'process.php',
            data: 'type=new&email=' + event.description + '&start_date=' + event.start.format() + '&capacity=' + capacity + '&logged_in=' + '0' + '&color=' + event.color,
            type: 'POST',
            dataType: 'json',
            async: false,
            success: function (response) {
                return response;
            },
            error: function (e) {
                window.console.log(e.responseText);
            }

        });
        refreshEvents();
    }


    /**********************************************/
    /*********** Log In Log Out EVENTS *************/
    /**********************************************/
    function loggedInUpdate(event, email, logIn_logOut) {
        var return_response;
        return_response = $.ajax({
            url: 'process.php',
            data: 'type=change_number_of_logged_in&email=' + email + '&logIn_logOut=' + logIn_logOut + '&event_id=' + event.id,
            type: 'POST',
            dataType: 'json',
            async: false,
            success: function (response) {
                return response;
            },
            error: function (e) {
                window.console.log(e.responseText);
            }
        });

        refreshEvents();
    }


    /***********************************************************************/
    /******** Loop for Checking multiple Log In SET UP on every 2 SEC ******/
    /***********************************************************************/
    function ajaxCall() {
        var returndata = $.ajax({
            url: 'process.php',
            type: 'POST',
            data: 'type=session_check',
            async: false,
            success: function (data) {
                return data;

            }
        });

        if (returndata.responseText === 'success') {
            window.alert("boli ste odpojeny");
            window.location.replace("index.php");
            return 'success';
        }

    }

   // setInterval(ajaxCall, 2000); //2000 ms = 2 second

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

    /* initialize the external events */
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
        utc: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: false
        },
        droppable: true,
        allDaySlot: false,
        defaultTimedEventDuration: '12:00:00',
        defaultDate: moment(new Date()).format('YYYY-MM-DD'),

        /**********************************************/
        /*************** Render EVENTS*****************/
        /**********************************************/

        events: function () {
            if (ajaxCall() !== 'success') {
                refreshEvents();
            }
        },

        /**********************************************/
        /*************** ADD EVENTS********************/
        /**********************************************/

        eventReceive: function (event) {
            if (ajaxCall() !== 'success') {
                //Define variables
                var now,
                    worker_capacity,
                    click_time,
                    duplicity_bool;

                now = moment(new Date()).format();
                click_time = moment(event.start.format());


                duplicity_bool = $.ajax({
                    url: 'process.php',
                    type: 'POST',
                    data: 'type=duplicity_ceck&startDate=' + event.start.format() + '&emailHash=' + event.description,
                    async: false,
                    done: function (response) {
                        return response;
                    }
                });

                if (duplicity_bool.responseText === 'failed') {
                    if (moment.duration(click_time.diff(now)).asMinutes() > 0) {
                        if (event.title === 'Brigádnici R' || event.title === 'Brigádnici N') {
                            //vstup pre kapacitu brigadnikov a pretypovanie string na INT
                            worker_capacity = parseInt(window.prompt('počet brigádnikov:', "", {
                                buttons: {
                                    Ok: true,
                                    Cancel: false
                                }
                            }), 10); //10 RADIX parameter
                            //Osetrenie na datovy typ INT a na min kapacitu smeny jednej brigadnik
                            if (!isNaN(worker_capacity) && worker_capacity > 0) {
                                eventAdd(event, worker_capacity);
                            } else {
                                window.alert("Zle zadane cislo");
                                refreshEvents();
                            }
                        } else {
                            eventAdd(event, null);
                        }
                    } else {
                        window.alert("Nemozno pridat smenu");
                        refreshEvents();
                    }
                } else {
                    window.alert("Duplicitny zapis");
                    refreshEvents();
                }
            }
        },

        /**********************************************/
        /*************** CLICK EVENTS******************/
        /**********************************************/

        eventClick: function (event, jsEvent, view) {

            if (ajaxCall() !== 'success') {
                //Define variables
                var permissions,
                    email,
                    check_logIn_logOut,
                    check_interval_time,
                    confirmDialog,
                    varning_resposne,
                    worker_capacity;

                permissions = $.ajax({
                    type: 'POST',
                    url: 'process.php',
                    data: 'type=get_loggedPermissions',
                    async: false,
                    done: function (response) {
                        return response;
                    }
                }).responseText;


                email = $.ajax({
                    type: 'POST',
                    url: 'process.php',
                    data: 'type=get_loggedEmail',
                    async: false,
                    done: function (response) {
                        return response;
                    }
                }).responseText;

                check_logIn_logOut = $.ajax({
                    type: 'POST', // Send post data
                    url: 'process.php',
                    data: 'type=check_log_in_log_out&event_id=' + event.id + '&email=' + email,
                    async: false,
                    done: function (response) {
                        return response;
                    }
                });

                check_interval_time = $.ajax({
                    type: 'POST', // Send post data
                    url: 'process.php',
                    data: 'type=check_interval_time&event_id=' + event.id,
                    async: false,
                    done: function (response) {
                        return response;
                    }
                });

                if (permissions === 'brigadnik') {
                    if (check_logIn_logOut.responseText !== '0' && event.title.search(" Brigádnici:") === 0) {
                        if (check_interval_time.responseText > 5) {
                            confirmDialog = window.confirm('Naozaj sa chcete odhlásiť z tejto zmeny?');
                            if (confirmDialog === true) {
                                loggedInUpdate(event, email, -1); // -1 == log out from event
                            }
                        } else {
                            window.alert("Nemozno sa odhlasit v tejto lehote");
                        }
                    } else {
                        if (event.title.search(" Brigádnici:") === 0) {
                            confirmDialog = window.confirm('Naozaj sa chcete prihlásiť na tuto smenu?');
                            if (confirmDialog === true) {
                                loggedInUpdate(event, email, 1); // 1 == log in on event
                            }
                        }
                    }
                }
                if (permissions === 'admin' || permissions === 'supervizor') {
                    if (event.title.search(" Brigádnici:") === 0) {
                        //vstup pre kapacitu brigadnikov a pretypovanie string na INT
                        worker_capacity = parseInt(window.prompt('počet brigádnikov:', "", {
                            buttons: {
                                Ok: true,
                                Cancel: false
                            }
                        }), 10); //10 RADIX parameter
                        //Osetrenie na datovy typ INT a na min kapacitu smeny jednej brigadnik
                        if (!isNaN(worker_capacity) && worker_capacity > -1) {

                            varning_resposne = $.ajax({
                                url: 'process.php',
                                type: 'POST',
                                data: 'type=changeCapacity&eventID=' + event.id + '&capacity=' + worker_capacity,
                                async: false,
                                done: function (response) {
                                    return response;
                                }
                            });
                            if (varning_resposne.responseText === 'failed') {
                                window.alert("Najskor treba odhlasit brigadnika/ov");
                            }
                            refreshEvents();
                        } else {
                            window.alert("Zle zadane cislo");
                            refreshEvents();
                        }
                    } else {
                        deleteEvent(event);
                    }
                }
            }
        }

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

        /*eventResize: function(event, delta, revertFunc) {
				console.log(event);
				var title = event.title;
				var end = event.end.format();
				var start = event.start.format();
		        update(title,start,end,event.id);
		    },*/


    });



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

});
