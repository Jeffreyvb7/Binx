var navBreakPoint = 1200;

var oldWidth = $(window).width();
var binx = function () {
};

binx.toasts = {};

binx.registerToast = function (id, e, fade, dur) {
    var toast = {
        timer: null,
        id: id,
        element: e,
        fade: fade,
        dur: dur
    };

    binx.toasts[id] = toast;

    return toast;
};

binx.setTimerForToast = function (toast) {
    toast.timer = setTimeout(function () {
        binx.fadeToast(toast);
    }, toast.dur);
};

binx.isRegisteredToast = function (id) {
    return binx.toasts.hasOwnProperty(id);
};

binx.fadeToast = function (toast) {
    var e = toast.element;
    e.fadeOut(toast.fade, function () {
        e.remove();
        if (e.is('[toast-id]')) binx.unregisterToast(e.attr('toast-id'));
    });
};

binx.unregisterToast = function (id) {
    if (binx.isRegisteredToast(id)) {
        delete binx.toasts[id];
    }
};

binx.toast = function (title, body, addClass = null, duration = 3000, fade = 500) {
    var toast = $("<div></div>");
    toast.addClass('toast');
    if(addClass !== null && addClass !== '') toast.addClass(addClass);
    toast.attr({'toast-duration': duration, 'toast-fade': fade, 'toast-id': uuidv4()});

    var toastTitle = $("<div></div>");
    toastTitle.addClass('title');
    toastTitle.text(title);

    var toastBody = $("<div></div>");
    toastBody.addClass('body');
    toastBody.html(body);

    toast.append(toastTitle).append(toastBody);

    $('#toasts').append(toast);
};

binx.modals = [];

binx.newModalZIndex = function () {
    return binx.modals.reduce(function (acc, curr) {
        return (acc >= curr.z_index ? acc : curr.z_index);
    }, 9) + 1;
};

binx.findModal = function(id) {
    return binx.modals.reduce(function(acc, curr) {
        return (id === curr.id ? curr : acc);
    }, null);
};

binx.getLatestModal = function() {
    return binx.modals.reduce(function(acc, curr) {
        return (acc === null || (acc !== null && curr.z_index !== null && acc.z_index !== null && curr.z_index > acc.z_index) ? curr : acc);
    }, null);
};

binx.modal = function (id) {
    var modal = null;
    if(typeof id === 'string') modal = $('.modal#' + id);
    else modal = $(id).closest('.modal');

    if (modal.length) {
        var modalUnderlay = $('#modalUnderlay');

        if(binx.findModal(modal.attr('id')) === null) {
            binx.modals.push({
                id: modal.attr('id'),
                element: modal,
                z_index: null
            });
        }

        return {
            open: function (event) {
                modalUnderlay.show();
                modal.addClass('open');

                var rModal = binx.findModal(modal.attr('id'));

                if(rModal !== null) {
                    rModal.z_index = binx.newModalZIndex();
                    modal.css({'z-index': rModal.z_index});
                }

                if(event) event.preventDefault();
                return false;
            },
            close: function (event) {

                var rModal = binx.findModal(modal.attr('id'));

                if(rModal !== null) {
                    rModal.z_index = null;

                    modal.addClass('closing');
                    setTimeout(function() {
                        modal.removeClass('open').removeClass('closing');
                        if (!$('.modal.open').length) modalUnderlay.fadeOut(200);
                        modal.css({'z-index': 'unset'});
                    }, 500);
                }

                if(event) event.preventDefault();
                return false;
            }
        };
    }

    return null;
};

$('body').on('DOMNodeInserted', '#toasts > div.toast', function (event) {
    var e = $(event.target);
    var dur = 3000;
    var fade = 500;
    var id = null;

    if (e.is('[toast-duration]')) dur = parseInt(e.attr('toast-duration'));
    if (e.is('[toast-fade]')) fade = parseInt(e.attr('toast-fade'));
    if (e.is('[toast-id]')) id = e.attr('toast-id');

    var toast = binx.registerToast(id, e, fade, dur);
    binx.setTimerForToast(toast);

    e.on('click', function () {
        binx.fadeToast(toast);
    });
});

$('body').on('DOMNodeInserted', '.modal', function (event) {
    binx.modal($(event.target).attr('id'));
});

$(document).ready(function() {
    $('.modal').each(function() {
        binx.modal($(this).attr('id'));
    });
});

$('body').on('mouseover', '#toasts > div.toast', function (event) {
    var e = $(event.target);
    if (e.is('[toast-id]')) {
        var id = e.attr('toast-id');
        if (binx.isRegisteredToast(id) && binx.toasts[id].timer !== null) {
            clearInterval(binx.toasts[id].timer);
            binx.toasts[id].timer = null;
        }
    }
});

$('body').on('mouseleave', '#toasts > div.toast', function (event) {
    var e = $(event.target);
    if (e.is('[toast-id]')) {
        var id = e.attr('toast-id');
        if (binx.isRegisteredToast(id)) {
            binx.setTimerForToast(binx.toasts[id]);
        }
    }
});

$('body').on('click', '#navUnderlay', function (event) {
    $('body').removeClass('toggled-nav');
    $('body').removeClass('toggled-nav');
    event.preventDefault();
});

$('body').on('click', '#modalUnderlay', function() {
    binx.modal(binx.getLatestModal().id).close();
});

$('body').on('click', '#navTogglerHitbox', function () {
    $('body').toggleClass('toggled-nav');
});

$(window).on('resize', function () {
    var win = $(this);
    if ((win.width() >= navBreakPoint && oldWidth < navBreakPoint) || (win.width() < navBreakPoint && oldWidth >= navBreakPoint)) {
        $('body').removeClass('toggled-nav');
    }
    oldWidth = win.width();
});

function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}