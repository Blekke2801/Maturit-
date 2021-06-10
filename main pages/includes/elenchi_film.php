<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
//controllo per vedere se l'utente è entrato in questa pagina senza home
if (strpos($url, 'Home.php') === false) {
    header("Location:../Home.php");
}
if (isset($_GET["ricerca"])) {
    $films = research($_GET["ricerca"]);
} else {
    $films = take_film_stream();
}
?>
<div>
    <?php
    if (isset($_GET["ricerca"])) {
        echo "<h3> Ecco i risultati per la ricerca: " . $_GET["ricerca"] . "</h3><hr>";
    } else {
        echo "<h3> Ecco i Film disponibili nel nostro sito</h3><hr>";
    }
    if (sizeof($films) > 0) {
        foreach ($films as $single) {
            $percorsoFilm = "../films/stream/" . $single;
            $Titolo = $single;
            $cartella = $Titolo;
            $Titolo[0] = strtoupper($Titolo[0]);
            $img = $percorsoFilm . "/horizontal.jpg";
            $Percorsotrama = $percorsoFilm . "/trama.txt";
            $myfile = fopen($Percorsotrama, "r") or die("Unable to open file!");
            $trama = "";
            for ($i = 0; $i < 200; $i++) {
                $trama = $trama . fgetc($myfile);
            }
            fclose($myfile);
            $trama = $trama . "...";
            echo "<a href='Home.php?NomeFilm=$cartella' class='film'><img class='locandinaElenco' src='$img'><p class='tramaIntro'>$trama</p><h1 class='text'>$Titolo</h1></a>";
        }
        echo "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam at fermentum purus. Ut hendrerit ligula ut risus tristique laoreet. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin nec congue quam, vel maximus turpis. Mauris consequat elementum ornare. Nam quis risus nisl. Suspendisse potenti. Donec quis pharetra est. Duis risus nisl, posuere eget sagittis non, ultrices porta neque. Suspendisse lacinia arcu et accumsan blandit. Fusce suscipit lorem ac lacus tempus tempus. Pellentesque ac luctus orci, eget bibendum nibh. Quisque lacus diam, scelerisque eleifend nisl a, cursus lacinia felis. Ut eleifend dolor vel consequat dapibus. Maecenas gravida ligula vel tempor convallis. Aenean eget nisl tincidunt, imperdiet nisl non, condimentum justo.

        Maecenas congue mauris quis metus gravida finibus. In ac mauris eu est ornare tempus. Phasellus non quam ex. Sed eget tempus urna, nec ornare nibh. Vivamus euismod vitae velit quis dignissim. Phasellus placerat ligula dolor. Aliquam efficitur lobortis blandit. Sed quis eros consectetur ipsum efficitur egestas vel ut nisi.
        
        Duis rutrum, nibh ac tempus dapibus, erat eros aliquam quam, ac ullamcorper justo eros non diam. Vivamus quis commodo ex. Sed pharetra pulvinar mi, a mattis tellus finibus scelerisque. Fusce tincidunt, turpis vel rhoncus condimentum, enim ipsum vulputate quam, non congue tellus lacus in arcu. Donec accumsan porttitor lectus, in mattis enim dignissim et. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur scelerisque tempus sapien in pharetra.
        
        Donec vitae nisi sagittis, dictum sapien a, malesuada lacus. Proin neque felis, lobortis ac quam non, accumsan vestibulum dui. Suspendisse vehicula erat non iaculis sagittis. Quisque nec elementum urna, vitae iaculis elit. Curabitur in laoreet augue. Mauris non mauris et neque auctor convallis sed vel lorem. Phasellus nec ultricies lectus, ut ultricies quam. Cras nec imperdiet tortor. Morbi velit lacus, ornare vel eros rutrum, dictum fermentum libero. Duis sed enim interdum ex ultrices luctus. Suspendisse hendrerit ullamcorper turpis, non ultricies metus volutpat pretium. Mauris vitae sem purus. Duis sed suscipit orci. Sed turpis ipsum, ultricies sit amet elit vitae, rhoncus dapibus leo. Quisque hendrerit elit eget nisl congue efficitur.
        
        Donec vel lectus gravida, feugiat felis eget, auctor odio. Aliquam euismod justo quam, ut posuere neque porta vitae. Nulla sit amet felis molestie, vestibulum libero non, sollicitudin orci. Etiam vitae aliquet elit. Aenean ultrices ante eu leo efficitur accumsan. Etiam ultrices erat ante, eget cursus sapien aliquet non. Sed a velit eget diam ultricies tincidunt. Proin iaculis mollis tortor vel commodo. Sed posuere a sem id pellentesque. Quisque luctus finibus dolor eu porta. Maecenas mollis, ipsum vel placerat semper, diam orci lacinia ex, ut posuere enim sem in mi. Phasellus vitae arcu sed leo dapibus volutpat nec non mauris. Vivamus mattis risus eros, sit amet eleifend felis ornare et.
        
        Sed efficitur lobortis justo, sit amet facilisis ligula congue ac. Nullam nisl nunc, porttitor quis nisl ac, suscipit lobortis lectus. Nulla sodales volutpat molestie. Curabitur lacinia urna vehicula, interdum nibh vel, semper eros. Ut libero metus, ornare at dignissim non, pulvinar vitae leo. Curabitur auctor, risus eu porta mollis, lectus felis dapibus ante, sit amet semper ligula quam quis dui. Sed feugiat diam accumsan lorem condimentum, quis posuere sapien tristique. Maecenas nec nibh velit. Donec vel nibh in eros dignissim fermentum in at nibh.
        
        Fusce sagittis nisl at urna ultrices finibus. Pellentesque convallis elit sit amet metus pharetra, id bibendum tellus ultricies. Curabitur finibus, tortor id facilisis aliquet, lacus lorem aliquam purus, vitae tincidunt purus nisi quis quam. Fusce lobortis semper ante, sit amet gravida tortor molestie non. Integer a ligula erat. In hac habitasse platea dictumst. Nam lacus velit, vehicula vitae erat et, aliquam finibus nibh. Aenean rutrum sollicitudin erat in congue. Praesent gravida odio vitae purus viverra, eget varius nulla elementum. Sed ligula libero, vehicula sit amet arcu tincidunt, feugiat congue justo. Phasellus semper, sem fermentum tincidunt sodales, libero mi auctor nisi, ut fermentum sem purus volutpat tellus. Curabitur feugiat justo nisl, quis blandit justo faucibus et.
        
        Maecenas accumsan arcu risus, et hendrerit velit fermentum vel. Mauris non felis purus. Nulla et ex id lacus posuere commodo ut nec odio. Quisque interdum condimentum eros, at fermentum nulla pellentesque nec. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Aenean ut dui ac nulla sollicitudin placerat at at eros. Vivamus lacinia ex quis semper blandit. Maecenas non cursus turpis, sed sagittis enim.
        
        Pellentesque vitae finibus nulla. Praesent pellentesque metus mi, in commodo elit tristique sed. Duis a posuere massa. Vivamus eros ante, mattis nec justo eget, malesuada aliquam metus. Ut iaculis euismod suscipit. Cras mattis non tortor eget volutpat. Sed tempor a tortor ultricies vestibulum. Aliquam tempor, dolor at accumsan rhoncus, urna quam euismod mi, nec iaculis sem massa vitae quam. Vivamus ut turpis ac dui rhoncus accumsan. Integer auctor enim arcu, ut luctus leo semper vitae. Sed iaculis feugiat erat nec accumsan. Aenean fermentum est enim, a imperdiet ante pharetra in.
        
        Aliquam rutrum sapien tincidunt, placerat turpis id, efficitur nulla. Mauris eget sapien augue. Cras turpis libero, consequat in est eget, porta pulvinar lectus. Nulla facilisi. Nulla dictum eget nisl cursus semper. Suspendisse potenti. Sed consequat condimentum bibendum.
        
        Fusce a risus dui. Nunc gravida nisl id metus pulvinar, sed volutpat sapien porttitor. Etiam placerat sollicitudin vehicula. Duis consequat sapien ex, tincidunt facilisis metus auctor eget. Proin at interdum nulla. Phasellus ut pretium dolor, nec finibus ligula. Proin convallis fringilla tortor. Donec ut tincidunt magna, at suscipit diam. Cras convallis ultricies magna, ac porta magna ultrices et. Aenean scelerisque libero purus, ac tincidunt sem eleifend id. Nullam egestas mi vel tortor hendrerit, eu pharetra enim imperdiet.
        
        Curabitur nibh tortor, iaculis nec hendrerit eget, placerat quis diam. Aenean vulputate volutpat elementum. Ut vel lorem eget nisl consequat congue tristique eu tortor. Mauris finibus leo sed lectus varius elementum. Nullam a massa odio. Mauris porttitor, mauris id pharetra tempor, risus urna accumsan ligula, non vehicula neque dui vel mauris. Fusce eleifend mi mollis, gravida nunc quis, vestibulum urna. Nam nulla ipsum, mattis sit amet purus id, pretium molestie turpis. Vivamus tincidunt fermentum neque, eu accumsan elit. Sed velit lacus, ornare eget enim eu, semper scelerisque nulla. Integer ultricies ipsum at neque pellentesque gravida. Vestibulum tempus purus a maximus lacinia. Integer laoreet iaculis arcu, eget volutpat sapien.
        
        Mauris magna dui, aliquam eget sapien at, laoreet consequat arcu. Etiam nec diam ac odio efficitur hendrerit sed a augue. Nulla ac semper justo. Cras sapien tellus, viverra ut odio sit amet, tincidunt accumsan neque. Vivamus quis semper ex, sit amet volutpat quam. Quisque vel iaculis sapien, et dapibus sem. Sed augue justo, aliquam ut tempus nec, fringilla non quam. In et nunc nisi. Ut posuere, leo eget malesuada accumsan, odio purus consectetur metus, ut auctor risus arcu nec erat. Aliquam id ultrices mauris, vitae dignissim augue. Sed consectetur sed ligula consequat convallis. Cras a nulla id tortor laoreet feugiat et quis magna.
        
        Vestibulum eu sodales diam. Mauris faucibus posuere justo, eu malesuada risus convallis quis. Etiam fermentum diam id tellus ultrices venenatis. Quisque cursus, leo blandit tincidunt dapibus, lacus ante sagittis nunc, nec vehicula lorem massa a tortor. Sed massa massa, dapibus nec fringilla ut, porta eu urna. Maecenas rhoncus nunc aliquam, dapibus nisi quis, pellentesque dui. Nulla tempor quis justo eu dictum. Maecenas convallis auctor viverra. Mauris imperdiet vel felis sed tincidunt. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum non rutrum erat. Ut magna augue, vestibulum sit amet dignissim ut, hendrerit eu mauris. Proin urna enim, fringilla ut est sed, rutrum vestibulum felis.
        
        Etiam varius ornare lacus, id pharetra nisi maximus ac. Phasellus lobortis, nunc vitae cursus tincidunt, ex elit tincidunt mauris, eget faucibus odio dui ut ligula. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec et varius nulla, nec commodo ligula. Aenean elementum quis ante at malesuada. Nam vitae rhoncus ligula. Nulla nec erat in ligula tempor faucibus. Donec quis bibendum lorem, et tristique leo. Duis id nisi a mauris feugiat luctus. Ut aliquam arcu non commodo pretium. Duis at molestie odio. Integer accumsan lorem vel gravida placerat. Donec interdum ullamcorper tempor. Duis erat nulla, condimentum non magna a, porta fringilla metus.
        
        Nulla sollicitudin rutrum tincidunt. Etiam sed dolor et arcu ultrices suscipit. Sed semper lectus sed turpis placerat, id tempor ex molestie. Nunc cursus nisl sit amet erat pulvinar fermentum. In condimentum orci non semper maximus. Cras placerat tempus elit. Curabitur vulputate facilisis metus bibendum lacinia. Nam eros lorem, aliquam in odio sit amet, faucibus volutpat justo. Maecenas ut sem ac ligula congue ornare. Sed euismod laoreet dui, a posuere sapien cursus et.
        
        Mauris posuere est id augue cursus scelerisque a eu lectus. Cras ut velit ligula. In sodales iaculis dui, sit amet pellentesque lorem semper ac. Aliquam in purus lacus. Cras mattis rhoncus turpis, eu viverra eros vehicula et. Phasellus id magna non tortor vulputate fringilla non eu est. Cras elit lectus, suscipit vel metus id, luctus mollis mauris. Aliquam tempor lectus et nulla posuere blandit. Mauris porta eros turpis. Nam ac sem ut orci elementum cursus. Sed et ante non lorem viverra blandit non nec ligula. Pellentesque pharetra malesuada magna, in cursus nulla fermentum ut. Fusce ullamcorper, nisl ac mattis viverra, neque sapien consectetur ante, in ullamcorper nisl nisi ut lorem. Cras lorem dolor, vestibulum vitae gravida et, ultricies nec sem. Quisque sed libero a mi dictum facilisis. Aenean aliquam nisi ac congue porttitor.
        
        Nunc dui nisi, aliquet at sapien ut, tempor dapibus ipsum. Ut nec imperdiet nisi. Sed scelerisque erat nec arcu iaculis porttitor. Donec cursus eros convallis suscipit congue. Morbi lacinia consectetur tellus, at dictum odio hendrerit eget. Fusce nec vulputate mi, eget consectetur orci. Proin vehicula dolor at dui gravida, sit amet semper nisi faucibus. Aenean scelerisque nisl vitae turpis rutrum lobortis.
        
        Mauris ultrices tellus vitae magna cursus blandit. Aenean elementum pellentesque commodo. Fusce porta eros erat, ac mattis ante rutrum sit amet. Duis mollis ante at fringilla ultricies. Nunc mollis diam et mauris vehicula, nec tincidunt ante lobortis. Fusce ac urna urna. Cras eget nisi id urna rhoncus elementum. Mauris sodales metus leo, et dignissim sapien sagittis at. Phasellus eu pharetra eros.
        
        Fusce odio massa, viverra vel scelerisque fringilla, dapibus id ante. Cras malesuada ligula ac eleifend tincidunt. Ut convallis, ex vel pretium lobortis, sapien nisi suscipit mi, vitae hendrerit tortor lorem ut lacus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc vulputate imperdiet arcu sed euismod. Pellentesque vehicula diam quis rutrum ultrices. Ut ornare rutrum nisi et efficitur.";
    } else {
        echo "<h4>Nessun risultato trovato</h4>";
    }
    ?>
</div>