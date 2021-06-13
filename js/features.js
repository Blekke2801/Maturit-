function ricerca() {
    var ricerca = document.getElementById("search").value;
    location.href = "Home.php?site=stream&page=cerca&ricerca=" + ricerca;
}

function show() {
    var free = document.getElementById("free");
    var show = document.getElementById("showOffer");
    if (free.checked) {
        show.innerHTML = '<span>Potrai vedere tutti i film che vuoi,<br/> ma in una lista limitata</span>';
    } else {
        show.innerHTML = '<span>Potrai vedere tutti i film che vuoi!<br>Senza limitazioni!</span>';
    }
}

function lista(titolo) {
    var lista = document.getElementById("lista");
    if (lista.innerHTML == "Aggiungi alla lista!") {
        location.href = "gestioneLista.php?list=true&film=" + titolo;
    } else if (lista.innerHTML == "Rimuovi dalla lista!") {
        location.href = "gestioneLista.php?list=false&film=" + titolo;
    }
}