var $,
    moment,
    swal;
var tooltip = $('#popup-info').detach();

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
    /*************** ADD NOTIFICATIONS **************/
    /**********************************************/

    function addNotification(eventID, activity) {

        var returndata = $.ajax({
            url: 'process.php',
            type: 'POST',
            data: 'type=addNotification&email_KTO=' + loggedEmail.responseText + '&eventID=' + eventID + '&activity=' + activity,
            async: false,
            success: function (data) {
                return data;

            }
        });
        console.log('vdsvdv', returndata.responseText);
    }



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

    function deleteEvent(event, accountPermmision) {

        /*Check if can be master delete*/
        return_response = $.ajax({
            url: 'process.php',
            data: 'type=canDelete&start_date=' + event.start.format() + '&event_id=' + event.id,
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

        if (return_response.responseText == 0) {

            swal({
                    title: "Smazat?",
                    text: "Opravdu chcete smazat tuto změnu nebo odhlásit brigádníka?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "orange",
                    confirmButtonText: "Smazat",
                    cancelButtonText: "Zrušit",
                    closeOnConfirm: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        //CREATE NOTIFICATION
                        addNotification(event.id, 'odhlasenie');

                        return_response = $.ajax({
                            url: 'process.php',
                            data: 'type=remove&event_id=' + event.id + '&permissionAcount=' + accountPermmision,
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


                        if ('success' === return_response.responseText) {
                            swal({
                                title: "Smazáno",
                                text: "Tato změna byla vymazána.",
                                type: "success",
                                confirmButtonColor: "#005200"
                            });

                            refreshEvents();

                        } else {
                            window.alert('chyba pri zmazavani');
                        }
                    }
                }

            );
        } else {
            window.alert('najprv treba vymazat eventy');
        }
    }
    /**********************************************/
    /*************** ADD EVENTS********************/
    /**********************************************/

    function eventAdd(event, capacity) {

        /*check if can be add brig event*/
        var return_response = $.ajax({
            url: 'process.php',
            data: 'type=canAdd&start_date=' + event.start.format() + '&emailHash=' + event.description,
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

        if (return_response.responseText == 1) {
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
        else{
            window.alert("najprv pridaj majstra");
        }
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

    /**********************************************/
    /******** Manual Log In brig on EVENT *********/
    /**********************************************/

    function brigadniciClickLogIn(divObject) {
        var i;
        for (i = 0; i < divObject.length; i++) {
            divObject[i].addEventListener('click', (function (i) {
                return function () {

                    var emailHash = divObject[i].dataset.description,
                        eventId = divObject[i].dataset.event_id,
                        eventStartDate = divObject[i].dataset.start,
                        newEventID,
                        return_response = $.ajax({
                            url: 'process.php',
                            data: 'type=change_number_of_logged_in&email=' + emailHash + '&logIn_logOut=' + 'emailhash' + '&event_id=' + eventId,
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
                    newEventID = JSON.parse(return_response.responseText).eventID;


                    addNotification(newEventID, 'prihlasenie');
                    //background Refresh events
                    refreshEvents();
                    //render list of brigadnici
                    brigadniciListRender(eventId, eventStartDate);

                };
            })(i), false);
        }
    }

    function brigadniciListRender(eventID, eventStartDate) {
        var elements,

            brigadniciListResponse = $.ajax({
                type: 'POST', // Send post data
                url: 'process.php',
                data: 'type=brigadnici_list&eventID=' + eventID + '&start_date=' + eventStartDate,
                async: false,
                done: function (response) {
                    return response;
                }
            });

        swal({
            title: "HTML <small>Title</small>!",
            text: '<div class="testing" style="height: 400px; overflow-y:scroll;">' + brigadniciListResponse.responseText + '</div>',
            html: true
        });

        //feed new elements for log in on event
        elements = document.getElementsByClassName('brigLogIn2');
        brigadniciClickLogIn(elements);
    }


    /***********************************************************************/
    /********************* Checking multiple Log In ************************/
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

            //sweetAlert("Oops...", "Something went wrong!", "error");

            //return 'success';

            /*
            swal({
                title: "Byli jste odpojen!",
                text: "Přihlášení z jiného místa!",
                type: "error",
                confirmButtonColor: "#d62633"
            });
            */
            window.location.replace("index.php");

        }

    }



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
        eventLimit: true,
        droppable: true,
        allDaySlot: false,
        defaultTimedEventDuration: '12:00:00',
        defaultDate: moment(new Date()).format('YYYY-MM-DD'),

        //Remove time from event
        eventRender: function (event, element) {
            $(element).find(".fc-time").remove();
        },

        /**********************************************/
        /*************** Render EVENTS*****************/
        /**********************************************/

        events: function () {
            if (ajaxCall() !== 'success') {
                refreshEvents();
            }
        },

        eventMouseover: function (event) {
            var mouseOverResponse = $.ajax({
                    type: 'POST',
                    url: 'process.php',
                    data: 'type=mouseOver&eventID=' + event.id,
                    dataType: 'json',
                    async: false,
                    done: function (response) {

                        return response;
                    }
                }),

                mouseOver = JSON.parse(mouseOverResponse.responseText),
                name = mouseOver[0].Name,
                email = mouseOver[0].Email,
                phone_num = mouseOver[0].Phone_num,
                permission = mouseOver[0].Permissions;


            //var tooltip = document.getElementById('popup-info');
            //var tooltip = $('#phantom-popup').load('template/popup_info.php');
            if (permission === 'brigadnik') {
                $("body").prepend(tooltip);
                document.getElementById('popup-name').innerHTML = name;
                document.getElementById('popup-email').innerHTML = email;
                document.getElementById('popup-number').innerHTML = phone_num;

                $(this).mouseover(function (e) {
                    $(this).css('z-index', 10000);
                    $('#popup-info').fadeIn('500');
                    $('#popup-info').fadeTo('10', 1.9);
                }).mousemove(function (e) {
                    $('#popup-info').css('top', e.pageY + 10);
                    $('#popup-info').css('left', e.pageX + 20);
                });
            }
        },


        eventMouseout: function () {
            $(this).css('z-index', 8);
            $('#popup-info').detach();
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

                            eventAdd(event, '9999');

                            // UPOZORNENIE !!!
                            //sluz na nastavovanie kapacity brigadnikov na smeny nepouzite pretoze firma si neziadala tuto funkcionalitu

                            /* swal({
                                     title: "Přidání brigádníků",
                                     text: "Zvolte počet brigádníků:",
                                     type: "input",
                                     showCancelButton: true,
                                     closeOnConfirm: false,
                                     confirmButtonText: "Potvrdit",
                                     cancelButtonText: "Zrušit",
                                     inputPlaceholder: "Zvolte kapacitu",
                                     confirmButtonColor: "#005200"
                                 },
                                 function (worker_capacity) {
                                     if (!worker_capacity) {
                                         refreshEvents();
                                     }
                                     if (worker_capacity === false) {
                                         return false;
                                     }
                                     refreshEvents();
                                     if (worker_capacity === "") {
                                         swal.showInputError("Prosím zadejte počet brigádníků!");
                                         return false;
                                     }
                                     if (isNaN(worker_capacity)) {
                                         swal.showInputError("Prosím zadajte číslo!");
                                         return false;
                                     }
                                     if (worker_capacity <= 0) {
                                         swal.showInputError("Počet brigádníků musí být více než nula!");
                                         return false;
                                     }

                                     swal({
                                         title: "Brigádníci přidány :)",
                                         text: "Počet brigádníků je: " + worker_capacity,
                                         type: "success",
                                         confirmButtonColor: "#005200"
                                     });
                                     eventAdd(event, worker_capacity);
                                 });*/

                        } else {
                            eventAdd(event, null);
                        }
                    } else {

                        swal({
                            title: "Chyba...",
                            text: "Změnu nelze přidat!",
                            type: "error",
                            confirmButtonColor: "#d62633"
                        });
                        refreshEvents();
                    }
                } else {

                    swal({
                        title: "Chyba...",
                        text: "Duplicitní zápis!",
                        type: "error",
                        confirmButtonColor: "#d62633"
                    });
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
                    worker_capacity,
                    checkingForDelete;

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
                    if ((check_logIn_logOut.responseText !== '0' && event.title.search(" R Brigádnici:") === 0) || (check_logIn_logOut.responseText !== '0' && event.title.search(" N Brigádnici:") === 0)) {
                        if (check_interval_time.responseText > 5) {

                            swal({
                                    title: "Odhlásit?",
                                    text: "Opravdu se chcete odhlásit z této změny?",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "orange",
                                    confirmButtonText: "Odhlásit",
                                    cancelButtonText: "Zrušit",
                                    closeOnConfirm: false
                                },
                                function () {
                                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                                    swal({
                                        title: "Odhlášen",
                                        text: "Byly jste odhlášen.",
                                        type: "success",
                                        confirmButtonColor: "#005200"
                                    });
                                    loggedInUpdate(event, email, -1);
                                });
                        } else {

                            swal({
                                title: "Odhlášení zakázáno",
                                text: "Již není možné se odhlásit!",
                                type: "error",
                                confirmButtonColor: "#d62633"
                            });
                        }
                    } else {
                        if (event.title.search(" R Brigádnici:") === 0 || event.title.search(" N Brigádnici:") === 0) {

                            swal({
                                    title: "Přihlásit?",
                                    text: "Opravdu se chcete přihlásit na tuto změnu?",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "orange",
                                    confirmButtonText: "Přihlásit",
                                    cancelButtonText: "Zrušit",
                                    closeOnConfirm: false
                                },
                                function () {
                                    swal({
                                        title: "Přihlášen",
                                        text: "Byly jste přihlášen.",
                                        type: "success",
                                        confirmButtonColor: "#005200"
                                    });
                                    loggedInUpdate(event, email, 1);
                                });
                        }
                    }
                }
                if (permissions === 'admin' || permissions === 'supervizor') {

                    if (event.title.search(" R Brigádnici:") === 0 || event.title.search(" N Brigádnici:") === 0) {
                        brigadniciListRender(event.id, event.start.format());


                        // UPOZORNENIE !!!
                        //sluzi na nastavovanie kapacity brigadnikov na smeny nepouzite pretoze firma si neziadala tuto funkcionalitu

                        /*   swal({
                                   title: "Chcete zmeniť kapacitu alebo prihlásiť brigádnika?",
                                   text: "You will not be able to recover this imaginary file!",
                                   type: "warning",
                                   showCancelButton: true,
                                   confirmButtonColor: "#DD6B55",
                                   confirmButtonText: "Zmena kapcity",
                                   cancelButtonText: "Prihlásenie",
                                   closeOnConfirm: false,
                                   closeOnCancel: false,
                                   allowEscapeKey: false
                               },
                               function (isConfirm) {
                                   if (isConfirm) {
                                       swal({

                                               title: "Změnit počet brigádníků",
                                               text: "Chcete-li zmazat těchto brigádníků zvolte 0 \n Zvolte počet brigádníků:",
                                               type: "input",
                                               showCancelButton: true,
                                               closeOnConfirm: false,
                                               confirmButtonColor: "#005200",
                                               confirmButtonText: "Potvrdit",
                                               cancelButtonText: "Zrušit",
                                               inputPlaceholder: "Zvolte kapacitu"
                                           },
                                           function (worker_capacity) {
                                               if (worker_capacity === false) {
                                                   return false;
                                               }
                                               refreshEvents();
                                               if (worker_capacity === "") {
                                                   swal.showInputError("Prosím zadejte počet brigádníků!");
                                                   return false;
                                               }
                                               if (isNaN(worker_capacity)) {
                                                   swal.showInputError("Prosím zadajte číslo!");
                                                   return false;
                                               }
                                               if (worker_capacity < 0) {
                                                   swal.showInputError("Počet brigádníků nesmí být více záporný!");
                                                   return false;
                                               }

                                               $.ajax({
                                                   url: 'process.php',
                                                   type: 'POST',
                                                   data: 'type=changeCapacity&eventID=' + event.id + '&capacity=' + worker_capacity,
                                                   async: false,
                                                   success: function (msg) {
                                                       if (worker_capacity > 0) {
                                                           swal({
                                                               title: "Počet brigádníků změněn",
                                                               text: "Počet brigádníků je: " + worker_capacity,
                                                               type: "success",
                                                               confirmButtonColor: "#005200"
                                                           });
                                                           refreshEvents();
                                                       }
                                                       if (worker_capacity === '0') {
                                                           swal({
                                                               title: "Smazáno",
                                                               text: "Brigádníci byly smazány",
                                                               type: "success",
                                                               confirmButtonColor: "#005200"
                                                           });
                                                           refreshEvents();
                                                       }
                                                   },
                                                   error: function (obj, text, error) {
                                                       swal({
                                                           title: obj.responseText,
                                                           text: "Počet přihlášených je větší než celkový počet",
                                                           type: "error",
                                                           confirmButtonColor: "#d62633"
                                                       });
                                                   }
                                               });

                                           });
                                   } else {
                                       //Ajax here************************************

                                       brigadniciListRender(event.id, event.start.format());

                                   }


                               });*/


                    } else {


                        if (permissions === 'admin') {

                            deleteEvent(event, permissions);


                        } else {
                            checkingForDelete = $.ajax({
                                type: 'POST',
                                url: 'process.php',
                                data: 'type=checkingForDelete&eventID=' + event.id,
                                dataType: 'json',
                                async: false,
                                done: function (response) {

                                    return response;
                                }
                            });
                            if (checkingForDelete.responseText === 'supervizor') {
                                window.alert('nemas prava na vymazamnioe');
                            } else {
                                deleteEvent(event, permissions);

                            }

                        }

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
