<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
//pagina di benvenuto, viene controllato se l'utente è entrato direttamente in questa pagina o è passato tramite home, se passato tramite home vengono mostrati: un messaggio di benvenuto, richiesta di login o regitrazione con appositi pulsanti e 5 film di esempio per vedere cosa è disponibile vedere nel sito 
if (strpos($url, 'Home.php') === false) {
    require "../../utility/Functions.php";
    header("Location:../Home.php");
} else {
?>
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
                $allFilms = take_film_stream();
                $take = array();
                for ($i = 0; $i < 5; $i++) {
                    if (sizeof($take) > 0) {
                        echo "ses";
                        while (array_search($randIndex, $take, true) !== false) {
                            $randIndex = rand(0, sizeof($allFilms) - 1);
                        }
                    } else
                        $randIndex = rand(0, sizeof($allFilms) - 1);
                    $percorso = "../films/stream/" . $allFilms[$randIndex] . "/horizontal.jpg";
                    echo '<div name="item" id="' . $i . '" class="item"><img src="' . $percorso . '" class="horizontal"></label></div>';
                    $take[$i] = $randIndex;
                }
                ?>
            </main>

        </div>

    </div>
    </div>
    </div>
    </div>
<?php } ?>