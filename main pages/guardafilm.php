<?php
require("../utility/Functions.php");
sec_session_start();
?>
<!doctype html>
<html>

<head>
  <link rel="icon" href="../img/favicon.ico" />
  <link href="http://vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
  <script src="http://vjs.zencdn.net/4.12/video.js"></script>
  <style type="text/css">
    html,
    body {
      padding: 0;
      margin: 0;
      width: 100%;
      height: 100%;
    }

    video {
      width: 100%;
      height: 100%;
      border: none;
      outline: none;
      z-index: 1;
      background-color: black;
    }

    .returnhome {
      position: absolute;
      width: 50px;
      height: 50px;
      z-index: 10;
      margin-top: 100px;
      margin-left: 50px;
      opacity: 0;
    }

    .arrow {
      border: solid white;
      border-width: 0 3px 3px 0;
      display: inline-block;
      padding: 3px;
      transform: rotate(135deg);
      -webkit-transform: rotate(135deg);
      background-color: transparent;
    }

    .returnhome a,
    .returnhome a img {
      width: 100%;
      height: 100%;
    }

    .returnhome:hover {
      opacity: 100% !important;
    }
  </style>
</head>

<body>
  <?php
  if (!isset($_GET["film"]) && sizeof(take_film_stream($_GET["film"])) <= 0 || !isset($_SESSION["tariffa"])) {
    header("Location: Home.php");
  } else if (take_film_stream($_GET["film"])[4] == $_SESSION["tariffa"] || $_SESSION["tariffa"]) {
    $cartella = "../films/stream/" . $_GET["film"] . "/";
    $file = searchFile($cartella . "film/it");
    if ($file == false) {
      header("Location: Home.php");
    } else {
      $estensione = explode(".", $file);
  ?>
      <div id="return" class="returnhome"><a class="arrow" href='<?php echo "Home.php?NomeFilm=" . $_GET["film"]; ?>'></a></div>
      <video controls name="media">
        <source src='<?php echo $file; ?>' type='<?php echo "video/" . $estensione[3]; ?>'>
      </video>

  <?php
    }
  } else {
    header("Location: Home.php");
  }
  ?>
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    var moveTimer;
    var indietro = $("#return");
    $(document).mousemove(function(event) {

      clearTimeout(moveTimer);
      moveTimer = setTimeout(function() {
        indietro.css("opacity", "0%");
        indietro.css("transition", "opacity 1s linear");
      }, 800);

      indietro.css("opacity", "100%");
    });

  });
</script>