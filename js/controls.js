function different() {
    var pwd = document.getElementById("pwd");
    var repeat = document.getElementById("Cpwd");
    if (pwd.value == repeat.value) {
        repeat.style.borderBottom = "1px solid red";
    }
}



function ceck(form) {
    var error = document.getElementById("error");
    var nome = document.getElementById("nome");
    var cognome = document.getElementById("cognome");
    var mail = document.getElementById("mail");
    var pwd = document.getElementById("pwd");
    var repeat = document.getElementById("Cpwd");
    var birth = document.getElementById("birth");
    var tariffa = document.getElementById("tariffa");
    var today = new Date();
    var b = new Date(birth.value);
    var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var register = true
    if (nome.value == null || nome.value == "" || nome.value == " ") {
        nome.style.borderBottom = "2px solid red";
        register = false;
    }
    if (cognome.value == null || cognome.value == "" || cognome.value == " ") {
        cognome.style.borderBottom = "1px solid red";
        register = false;
    }
    if (mail.value == null || mail.value == "" || mail.value == " ") {
        mail.style.borderBottom = "1px solid red";
        register = false;
    }
    if (birth.value == null || birth.value == "" || birth.value == " " || date.getDate() <= b.getDate()) {
        birth.style.borderBottom = "1px solid red";
        register = false;
    }
    if (tariffa.value == null || tariffa.value == "" || tariffa.value == " ") {
        tariffa.style.borderBottom = "1px solid red";
        register = false;
    }
    if (pwd.value == null || pwd.value == "" || pwd.value == " ") {
        pwd.style.borderBottom = "1px solid red";
        register = false;
    }
    if (Cpwd.value == null || Cpwd.value == "" || Cpwd.value == " ") {
        Cpwd.style.borderBottom = "1px solid red";
        register = false;
    }
    if (register) {
        form.submit();
    } else {
        window.scrollTo(0, 0);
        error.className = "error";
        error.innerHTML = "Non tutti i campi sono stati inseriti";
    }

}