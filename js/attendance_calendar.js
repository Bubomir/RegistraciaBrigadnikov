var $,
    moment,
    swal;
var tooltip = $('#popup-info').detach();


var loggedAjaxData = $.ajax({
    type: 'POST',
    url: 'process.php',
    data: 'type=get_loggedData',
    async: false,
    success: function (response) {
        "use strict";
        return response;
    }
});

var loggedData = JSON.parse(loggedAjaxData.responseText);



$(document).ready(function () {
    "use strict";
    //Define variables
    var return_response;
    /**********************************************/
    /*************** ADD NOTIFICATIONS **************/
    /**********************************************/

    function addNotification(eventID, activity) {

        var returndata = $.ajax({
            url: 'process.php',
            type: 'POST',
            data: 'type=addNotification&email_KTO=' + loggedData.email + '&eventID=' + eventID + '&activity=' + activity,
            async: false,
            success: function (data) {
                return data;
            }
        });

    }

    /**********************************************/
    /*************** RENDER EVENTS*****************/
    /**********************************************/


    function getFreshEvents() {

        var start_month,
            end_month;

        start_month = moment($('#calendar').fullCalendar('getView').start._d).format('YYYY-MM-DD HH:mm:ss');
        end_month = moment($('#calendar').fullCalendar('getView').end._d).format('YYYY-MM-DD HH:mm:ss');

        return_response = $.ajax({
            url: 'process.php',
            type: 'POST', // Send post data
            data: 'type=fetch&start_month=' + start_month + '&end_month=' + end_month,
            async: false,
            success: function (s) {
                return s;
            }
        });
        $('#calendar').fullCalendar('addEventSource', JSON.parse(return_response.responseText));
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
                //window.console.log(e.responseText);
            }
        });

        //console.log('dsads', return_response.responseText);

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
                        addNotification(event.id, 'logOut');

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
                                //window.console.log(e.responseText);
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
            swal({
                title: "Chyba...",
                text: "Pred zmazaním zmeny le potrebné odhlásiť všetkych brigádnikov a vymazať brigádnicky event!",
                type: "error",
                confirmButtonColor: "#d62633"
            });
            refreshEvents();
        }
    }

    /**********************************************/
    /*************** ADD EVENTS********************/
    /**********************************************/

    function eventAdd(event, capacity) {
        var return_response;
        /*check if can be add brig event*/
        return_response = $.ajax({
            url: 'process.php',
            data: 'type=canAdd&start_date=' + event.start.format() + '&emailHash=' + event.description,
            type: 'POST',
            dataType: 'json',
            async: false,
            success: function (response) {
                return response;
            },
            error: function (e) {
                // window.console.log(e.responseText);
            }
        });

        if (return_response.responseText == 1) {
            return_response = $.ajax({
                url: 'process.php',
                data: 'type=new&email=' + event.description + '&start_date=' + event.start.format() + '&capacity=' + capacity + '&logged_in=' + '0' + '&color=' + event.color,
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function (response) {
                    return response;
                },
                error: function (e) {
                    // window.console.log(e.responseText);
                }

            });
            refreshEvents();

        } else {
            refreshEvents();
            swal({
                title: "Chyba...",
                text: "Aby bylo možné přidat brigádníky je zapotřebí vytvořena změna!",
                type: "error",
                confirmButtonColor: "#d62633"
            });
        }
    }

    /**********************************************/
    /*********** Log In Log Out EVENTS ************/
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
                // window.console.log(e.responseText);
            }
        });

        refreshEvents();
    }

    /**********************************************/
    /******** Manual Log In brig on EVENT *********/
    /**********************************************/

    function brigadniciClickLogIn(divObject) {
        for (var i = 0; i < divObject.length; i++) {
            divObject[i].addEventListener('click', (function (i) {
                return function () {
                    var emailHash = divObject[i].dataset.description;
                    var eventId = divObject[i].dataset.event_id;
                    var eventStartDate = divObject[i].dataset.start;

                    swal({
                            title: "Přihlásit?",
                            text: "Zvolený brigádník bude přihlášen na tuto změnu.",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "orange",
                            confirmButtonText: "Přihlásit!",
                            cancelButtonText: "Zrušit!",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                var return_response = $.ajax({
                                    url: 'process.php',
                                    data: 'type=change_number_of_logged_in&email=' + emailHash + '&logIn_logOut=' + 'emailhash' + '&event_id=' + eventId,
                                    type: 'POST',
                                    dataType: 'json',
                                    async: false,
                                    success: function (response) {
                                        return response;
                                    },
                                    error: function (e) {
                                        // window.console.log(e.responseText);
                                    }
                                });
                                var newEventID = JSON.parse(return_response.responseText);

                                addNotification(newEventID.eventID, 'logIn');
                                //background Refresh events
                                refreshEvents();
                                //render list of brigadnici
                                swal({
                                        title: "Přihlášen!",
                                        text: "Brigádník byl přihlášen na tuto změnu.",
                                        type: "success",
                                        confirmButtonColor: "#005200",
                                        closeOnConfirm: false
                                    },
                                    function () {
                                        brigadniciListRender(eventId, eventStartDate);
                                    });

                            } else {
                                brigadniciListRender(eventId, eventStartDate);
                            }
                        });
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
                success: function (response) {
                    return response;
                }
            });
        swal({
                title: "Seznam brigádníků<br><small>na přihlášení</small>",
                text: '<div style="height: 250px; overflow-y:scroll;">' + brigadniciListResponse.responseText + '</div>',
                html: true,
                confirmButtonText: "Zavřít",
                confirmButtonColor: "#3a87ad",
                showCancelButton: true,
                cancelButtonText: "Smazat",
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {} else {
                    swal({
                            title: "Smazat?",
                            text: "Opravdu chcete smazat tento brigádně event?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "orange",
                            confirmButtonText: "Smazat",
                            cancelButtonText: "Zrušit",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $.ajax({
                                    url: 'process.php',
                                    type: 'POST',
                                    data: 'type=changeCapacity&eventID=' + eventID + '&capacity=' + 0,
                                    async: false,
                                    success: function (msg) {
                                        refreshEvents();
                                        swal({
                                            title: "Smazáno!",
                                            text: "Brigádně event z této změny byl smazán.",
                                            type: "success",
                                            confirmButtonColor: "#005200"
                                        });
                                    },
                                    error: function (obj, text, error) {
                                        swal({
                                                title: "Nelze smazat!",
                                                text: "Nejprve je třeba odhlásit všech brigádníků!!",
                                                type: "error",
                                                confirmButtonColor: "#d62633",
                                                closeOnConfirm: false
                                            },
                                            function () {
                                                brigadniciListRender(eventID, eventStartDate);
                                            });
                                    }
                                });
                            } else {
                                brigadniciListRender(eventID, eventStartDate);
                            }
                        });
                }
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

    /*---------------------------------------------------------------------*/
    /***********************************************************************/
    /********************* initialize the calendar ************************/
    /***********************************************************************/
    /*---------------------------------------------------------------------*/

    $('#calendar').fullCalendar({
        utc: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: false
        },
        eventLimit: true,
        droppable: true,
        defaultDate: moment(new Date()).format('YYYY-MM-DD'),

        //Remove time from events
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

            if (loggedData.permission !== 'brigadnik') {
                var mouseOverResponse = $.ajax({
                        type: 'POST',
                        url: 'process.php',
                        data: 'type=mouseOver&eventID=' + event.id,
                        dataType: 'json',
                        async: false,
                        success: function (response) {
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

                        $('#popup-info').css('top', e.pageY - 110);
                        $('#popup-info').css('left', e.pageX + 20);
                    });
                }
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
                    success: function (response) {
                        return response;
                    }
                });

                if (duplicity_bool.responseText === 'failed') {
                    if (moment.duration(click_time.diff(now)).asMinutes() > 0) {
                        if (event.title === 'Brigádnici R' || event.title === 'Brigádnici N') {

                            eventAdd(event, '9999'); //capacity 9999

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

        /****************************************************/
        /****************** CLICK EVENTS*********************/
        /****************************************************/

        eventClick: function (event, jsEvent, view) {

            if (ajaxCall() !== 'success') {
                //Define variables
                var check_logIn_logOut,
                    check_interval_time,
                    confirmDialog,
                    varning_resposne,
                    worker_capacity,
                    checkingForDelete;


                var check_ajax_data = $.ajax({
                    type: 'POST', // Send post data
                    url: 'process.php',
                    data: 'type=check_data&event_id=' + event.id + '&email=' + loggedData.email,
                    async: false,
                    success: function (response) {
                        return response;
                    }
                });
                var check_data = JSON.parse(check_ajax_data.responseText);



                if (loggedData.permission === 'brigadnik') {
                    if ((check_data.logIN_logOUT !== '0' && event.title.search(" R Brigádnici:") === 0) || (check_data.logIN_logOUT !== '0' && event.title.search(" N Brigádnici:") === 0)) {
                        if (check_data.interval > 5) {

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
                if (loggedData.permission === 'admin' || loggedData.permission === 'supervizor') {

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

                        if (loggedData.permission === 'admin') {

                            deleteEvent(event, loggedData.permission);

                        } else {
                            checkingForDelete = $.ajax({
                                type: 'POST',
                                url: 'process.php',
                                data: 'type=checkingForDelete&eventID=' + event.id,
                                dataType: 'json',
                                async: false,
                                success: function (response) {
                                    return response;
                                }
                            });
                            if (checkingForDelete.responseText === 'supervizor') {
                                swal({
                                    title: "Chyba...",
                                    text: "Pouze správce může změnit nebo vymazat změny!",
                                    type: "error",
                                    confirmButtonColor: "#d62633"
                                });
                            } else {
                                deleteEvent(event, loggedData.permission);
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


var $;
var start = 2016;
var end = new Date().getFullYear();
var currentMonth = ("0" + (new Date().getMonth() + 1));
var options = "";
var year;


for (year = end; year >= start; year--) {
    options += "<option value='" + year + "'>" + year + "</option>";
}
if (loggedData.permission != 'brigadnik') {
    document.getElementById("year").innerHTML = options;
}
//pick current month
$('option[name="' + currentMonth + '"]').attr('selected', 'selected');


//FEED NOTIFICATIoN DATA FOR RENDERING
function clickNotification(activity, interval) {
    "use strict";

    var notificationResponse,
        notficationData = {
            'activity': activity,
            'interval': interval
        };

    notificationResponse = $.ajax({
        type: 'POST', // Send post data
        url: 'notification.php',
        data: notficationData,
        dataType: 'json',
        async: false,
        success: function (response) {
            return response;
        }
    });

    document.getElementById("notifications-box").innerHTML = notificationResponse.responseText;

}
$("#notificationButton").click(function () {
    "use strict";

    var activityPick = document.getElementById("activity"),
        activityPickUser = activity.options[activity.selectedIndex].value,
        monthPick = document.getElementById("month"),
        monthPickUser = monthPick.options[monthPick.selectedIndex].value,
        yearPick = document.getElementById("year"),
        yearPickUser = yearPick.options[yearPick.selectedIndex].value,
        interval = yearPickUser + '-' + monthPickUser;
    clickNotification(activityPickUser, interval);
});


//Registration starts here
var numberOfChange = 0;
$('.alert-success').hide();
$('.alert').hide();
$('#numberOfChange').hide();
$("input[type=radio]").click(function () {
    "use strict";

    if (document.getElementById('exampleSwitch2').checked) {

        $('#numberOfChange').slideDown(500, function () {
            $('#numberOfChange').show;
        });
        $('input[name=numberOfChange]').attr('required', true);
        numberOfChange = $('input[name=numberOfChange]:checked').val();
    } else {
        $('#numberOfChange').slideUp(500, function () {
            $('#numberOfChange').hide();
        });
        $('input[name=numberOfChange]').removeAttr('required', false);
    }
});

//Ak je supervízor schovať možnosti zvolenia práv defaulnte brigadnicke práve
if (loggedData.permission == 'supervizor') {
    $('#permissionPicker').remove();
}

//Premenná ktorá sa napĺňa právami
$(document).ready(function () {
    "use strict";

    $('#registration_form').submit(function (event) {
        $('.alert-success').hide();
        $('.alert').hide();
        //Premenná ktorá sa napĺňa právami
        var tempPerm = null,
            formData,
            $isRegistered;

        //Ak je supervízor tak môže registrovať iba brigádnikov
        if (loggedData.permission == 'supervizor') {
            tempPerm = 'non-brigadnik';
        } else {
            tempPerm = $('input[name=permissions]:checked').val();
        }
        formData = {
            'first_name': $('input[name=first_name]').val(),
            'surname': $('input[name=surname]').val(),
            'email': $('input[name=email]').val(),
            'mobile_number': $('input[name=mobile_number]').val(),
            'permissions': tempPerm,
            'change_number': numberOfChange
        };

        //console.log('test',formData);

        // rocess the form
        $isRegistered = $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: 'registration.php', // the url where we want to POST
            data: formData, // our data object
            async: false,
            done: function (response) {
                return response;
            }
        }).responseText;



        if ($isRegistered == 1) {
            document.getElementById('alert-message-success').innerHTML = "Registrace byla úspešná";
            $('.alert-success').hide().slideDown(500);
            $('#registration_form').trigger('reset');
            $('#numberOfChange').slideUp(500, function () {
                $('#numberOfChange').hide();
            });
            numberOfChange = 0;
        } else if ($isRegistered == 2) {
            $('.alert').hide().slideDown(500);
            document.getElementById('alert-message').innerHTML = "Uživatel s tímto e-mailem již existuje!!";
            numberOfChange = 0;
        } else if ($isRegistered == 3) {
            $('.alert').hide().slideDown(500);
            document.getElementById('alert-message').innerHTML = "Nastala chyba pri registraci!!";
            numberOfChange = 0;
        } else if ($isRegistered == 4) {
            $('.alert').hide().slideDown(500);
            document.getElementById('alert-message').innerHTML = "Uživatel s touto smenou již existuje!!";
            numberOfChange = 0;
        }
        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

});

$('button[name=btn-close]').click(function () {
    "use strict";

    $('#registration_form').trigger('reset');
    $('.alert-success').hide();
    $('.alert').hide();
    $('#numberOfChange').hide();
});
