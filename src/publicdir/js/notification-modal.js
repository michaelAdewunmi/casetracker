if (undefined == $) {
    var $ = jQuery;
}

class Notifier {
    openNotifier(hideBtn=false, msg) {
        $("#msg").html(msg);
        $("#notification").css({
            'opacity' : '1',
            'z-index': '999999999999999999'
        });
        $(".notifier").css({
            'transform' : 'scaleY(1)',
        });
        if(!hideBtn) {
            $("#notification-btn").show();
        } else {
            $("#notification-btn").hide();
        }
    }

    closeNotifier() {
        $("#notification").css({
            'opacity' : '0',
            'z-index': '-20',
        });
        $(".notifier").css({ 'transform' : 'scaleY(0)' });
        $(window).scrollTop(0);
        $(this).html("Close Notification");
        $("#msg").html("");
    }
}

const notifier = new Notifier;
