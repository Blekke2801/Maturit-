<div class="main">
    <h1 style="color:#fff">Benvenuto nel nostro sito!</h1>
    <p style="color:#fff">Esegui il login o registrati per utilizzare il nostro sito al massimo!</p>
    <div class="regLog">
        <a class="btn" href="Login.php">LOGIN</a>
        <a class="btn" href="register.php">REGISTRATI</a>
    </div>
    <div class="carousel">
        <main id="carousel">
            <?php
            $allFilms = take_film_stream($mysqli);

            for ($i = 0; $i < 6; $i++) {
                $randIndex = rand(0, sizeof($allFilms) - 1);
                $percorso = "../films/" . $allFilms[$randIndex] . "/horizontal.jpg";
                echo '<div name="item" id="' . $i . '" class="item"><img src="' . $percorso . '" class="horizontal"></label></div>';
            }
            ?>
            <main>

    </div>

</div>
</div>
</div>
</div>