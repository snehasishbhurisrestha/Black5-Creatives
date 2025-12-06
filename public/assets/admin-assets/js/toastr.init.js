function showToast(e, t, o) {
    var n, i = 0,
        a = o,
        s = i++;
    toastr.options = {
        closeButton: !0,
        debug: !1,
        newestOnTop: 1,
        progressBar: !0,
        rtl: !1,
        positionClass: "toast-top-right",
        preventDuplicates: !1,
        onclick: null,
        showDuration: 300,
        hideDuration: 1e3,
        timeOut: 5e3,
        extendedTimeOut: 1e3,
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    }, a || (a = getMessage());
    var c = toastr[e](a, t);
    n = c, void 0 !== c && (c.find("#okBtn").length && c.delegate("#okBtn", "click", function() {
        alert("you clicked me. i was toast #" + s + ". goodbye!"), c.remove()
    }), c.find("#surpriseBtn").length && c.delegate("#surpriseBtn", "click", function() {
        alert("Surprise! you clicked me. i was toast #" + s + ". You could perform an action here.")
    }), c.find(".clear").length && c.delegate(".clear", "click", function() {
        toastr.clear(c, {
            force: !0
        })
    }))
}

function clearTost() {
    toastr.clear()
}