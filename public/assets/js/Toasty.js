function DisplayToast(title, body, delay) {
    console.log("toast opening");
    let embed = '' +
        '<div id="toast-alert" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="' + delay + '" style="position: fixed; top: 10px; right: 10px;z-index: 9999;max-width: none">' +
        '<div class="toast-header">' +
        '<strong class="mr-auto">' + title + '</strong>' +
        '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>' +
        '<div class="toast-body">' +
        body +
        '</div>' +
        '</div>';

    // Selection de l'élèment et affichage du contenue de la variable embed
    $("#toast-container").html(embed);
    $("#toast-alert").toast("show");

    $("#toast-alert").on("hidden.bs.toast", function () {
        $("#toast-container").html("");
    })
}

$("input[data-action='toast-display']").on("click", function () {
    let title = $(this).attr("data-title");
    let body = $(this).attr("data-body");
    let delay = parseInt($(this).attr("data-delay"));

    DisplayToast(title, body, delay);
})