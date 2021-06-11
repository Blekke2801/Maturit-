<?php
require "../utility/Functions.php";
sec_session_start();
//pagina in cui vengono effettuate effettivamente le operazioni dell'admin
if (!login_check() || $_SESSION["ruolo"] != 0) {
    header("Location:Home.php");
} else {
    if (isset($_POST["op"])) {
        switch ($_POST["op"]) {
            case "1":
                if (new_film()) {
                    echo "Film aggiunto con successo!";
                    header("Refresh:1; url= AdminPage.php");
                } else {
                    echo "Errore nell'aggiunta del film";
                    header("Refresh:1; url= AdminPage.php");
                }
                break;
            case "2":

                if (isset($_POST["id_film"])) {
                    if (new_hour()) {
                        echo "Orario aggiunto con successo!";
                        header("Refresh:1; url= AdminPage.php");
                    } else {
                        echo "Errore nell'aggiunta dell'orario";
                        header("Refresh:1; url= AdminPage.php");
                    }
                } else {
?>
                    <form action="<?php htmlentities($_SERVER["PHP_SELF"]); ?>" method="post" style="display:flex;flex-direction:column">
                        <fieldset>
                            <legend>Selezionare il film:</legend>
                            <input type="radio" name="data" value="<?php echo $_POST["data"]; ?>" checked hidden>
                            <input type="radio" name="ora" value="<?php echo $_POST["ora"]; ?>" checked hidden>
                            <input type="radio" name="sala" value="<?php echo $_POST["sala"]; ?>" checked hidden>
                            <select name="id_film">
                                <?php
                                foreach (take_film_prenota() as $film) {
                                    echo "<option value=" . $film["ID_Film"] . ">" . $film["Titolo"] . "</option>";
                                } ?>
                            </select>
                            <button type="submit" name="op" value="2">Conferma</button>
                        </fieldset>
                    </form>
<?php
                }

                break;
            default:
                header("AdminPage.php");
                break;
        }
    } else {
        header("AdminPage.php");
    }
}
