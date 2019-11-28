<?php
require_once('database.php');
$conn = connect();

//add_comment(NULL, "Alicja123", "2010-10-10", "Data, źle się dodała");// na DEFAULT nie dizła ;(
//add_picture(NULL, "http://localhost/img/Felix.png", "Felix", "Alicja123", "Animals", 1, "Kotek"); <--dodawać obrazki jakby były urlem
add_picture(NULL, "http://localhost/img/Beauty.png", "Beauty", "Alicja123", NULL, NULL, "Smoczek");
?>