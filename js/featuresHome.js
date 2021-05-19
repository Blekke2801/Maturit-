function scrollMenu() {
    var y = 141;
    var yb = window.scrollY;
    if (yb > y) {
        document.getElementById("head").style.position = "fixed";
    } else {
        document.getElementById("head").style.position = "relative";
    }
}

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