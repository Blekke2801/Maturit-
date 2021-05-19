function carouselPlus() {
    var posizione = document.getElementById("carousel");
    if (posizione == 5)
        posizione = 1;
    else
        posizione++;
}

function carouselMinus(a) {

    var posizione = document.getElementById("carousel").position;
    if (posizione == 1)
        posizione = 5;
    else
        posizione--;
}

function ricerca() {
    var ricerca = document.getElementById("search").value;
    location.href = "Home.php?page=cerca&ricerca=" + ricerca;
}