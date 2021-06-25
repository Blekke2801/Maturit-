function different() {
    var pwd = document.getElementById("pwd");
    var repeat = document.getElementById("Cpwd");
    if (pwd.value != repeat.value || repeat.value == null || repeat.value == "" || repeat.value == " ") {
        repeat.style.borderBottom = "2px solid red";
        return false;
    } else {
        repeat.style.borderBottom = "2px solid green";
        return true;
    }
}



function ceck(form) {
    var error = document.getElementById("error");
    var nome = document.getElementById("nome");
    var cognome = document.getElementById("cognome");
    var mail = document.getElementById("mail");
    var pwd = document.getElementById("pwd");
    var birth = document.getElementById("birth");
    var tariffa = document.getElementsByName("tariffa");
    var today = new Date();
    var b = new Date(birth.value);
    var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var register = true
    if (nome.value == null || nome.value == "" || nome.value == " ") {
        nome.style.borderBottom = "2px solid red";
        register = false;
    }
    if (cognome.value == null || cognome.value == "" || cognome.value == " ") {
        cognome.style.borderBottom = "2px solid red";
        register = false;
    }
    if (mail.value == null || mail.value == "" || mail.value == " " || mail.value.search("@") == -1) {
        mail.style.borderBottom = "2px solid red";
        register = false;
    }
    if (birth.value == null || birth.value == "" || birth.value == " " || date < b) {
        birth.style.borderBottom = "2px solid red";
        register = false;
    }
    if (tariffa.checked) {
        document.querySelector(".tariffa div:nth-child(2)").style.borderBottom = "1px solid red";
        register = false;
    }
    if (pwd.value == null || pwd.value == "" || pwd.value == " ") {
        pwd.style.borderBottom = "2px solid red";
        register = false;
    }
    if (Cpwd.value == null || Cpwd.value == "" || Cpwd.value == " ") {
        register = false;
    }
    if (!different()) {
        register = false;
    }
    if (register) {
        form.submit();
    } else {
        window.scrollTo(0, 0);
        error.className = "error";
        error.innerHTML = "Non tutti i campi sono stati inseriti correttamente";
    }

}

function errorReg() {
    alert("errore nella registrazione");
}