

var $;
var start = 2016;
var end = new Date().getFullYear();
var currentMonth = ("0" + (new Date().getMonth() + 1));
var options = "";
var year;

//zistenie práve lognutého užívateľa
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

for (year = end; year >= start; year--) {
    options += "<option value='" + year + "'>" + year + "</option>";
}
if(loggedPermissions.responseText != 'brigadnik'){
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
if (loggedPermissions.responseText == 'supervizor') {
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
        if (loggedPermissions.responseText == 'supervizor') {
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
        } else if ($isRegistered == 2) {
            $('.alert').hide().slideDown(500);
            document.getElementById('alert-message').innerHTML = "Uživatel s tímto e-mailem již existuje!!";
        } else if ($isRegistered == 3) {
            $('.alert').hide().slideDown(500);
        } else if ($isRegistered == 4) {
            $('.alert').hide().slideDown(500);
            document.getElementById('alert-message').innerHTML = "Uživatel s touto smenou již existuje!!";
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
