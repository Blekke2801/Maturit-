function ricerca() {
    var ricerca = document.getElementById("search").value;
    location.href = "Home.php?site=stream&page=cerca&ricerca=" + ricerca;
}

function show() {
    var free = document.getElementById("free");
    var show = document.getElementById("showOffer");
    if (free.checked) {
        show.innerHTML = '<img src="../img/Free.jpg"><br><span>Potrai vedere tutti i film che vuoi,<br/> ma in una lista limitata</span>';
    } else {
        show.innerHTML = '<img src="../img/Premium.jpg"><br><span>Potrai vedere tutti i film che vuoi!<br>Senza limitazioni!</span>';
    }
}

function lista(titolo) {
    var lista = document.getElementById("lista");
    var xhttp = new XMLHttpRequest();
    if (lista.innerHTML == "Aggiungi alla lista!") {
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                lista.innerHTML = "Rimuovi dalla lista!";
            }
        };
        xhttp.open("POST", "lista.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("list=false&film=" + titolo);
    } else if (lista.innerHTML == "Rimuovi dalla lista!") {
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                lista.innerHTML = "Aggiungi alla lista!";
            }
        };
        xhttp.open("POST", "lista.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("list=false&film=" + titolo);
    }
}