var limit = 3;
$('input.single-checkbox').on('change', function(evt) {
    if ($(this).siblings(':checked').length >= limit) {
        this.checked = false;
        document.getElementById("messaggio").innerHTML = "limite raggiunto";
    } else {
        document.getElementById("messaggio").innerHTML = "";
    }
});