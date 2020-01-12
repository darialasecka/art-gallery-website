<?php
require_once('database.php');
$conn = connect();
//check if session is sared, if no then start
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//add_comment(NULL, "Alicja123", "2010-10-10", "Data, źle się dodała");// na DEFAULT nie dizła ;(
//add_picture(NULL, "http://localhost/img/Felix.png", "Felix", "Alicja123", "Animals", 1, "Kotek"); <--dodawać obrazki jakby były urlem
//add_picture(NULL, "http://localhost/img/Beauty.png", "Beauty", "Alicja123", NULL, NULL, "Smoczek");
//add_person("Kasia1993", "Kasia", "Kowalska", "kasia@test.com", "17");
//add_picture(NULL, "http://localhost/img/Falling.png", "Falling", "Kasia1993", NULL, NULL, 
				//"Smoczek spada. Daję dłuższy do testów. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ");

//check if person wants to logout
if (isset($_GET['logout'])){
	session_destroy();
  	unset($_SESSION['nickname']);
  	header("location: login.php");
}

function check_file($file){ //checks if there is already file with this name and adds another number if file exists
    $number = 1;
    $filename = pathinfo($file, PATHINFO_FILENAME);
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    $path = 'C:\\xampp\\htdocs\\img\\';
    $new_filename = $filename;
    while(file_exists($path.$new_filename.'.'.$extension)){
        $new_filename = $filename.'_'.$number;
        $number++;
    }
    return $new_filename.'.'.$extension;
}

function head($title){
	echo "<!-- ========== Meta Tags ========== -->
	    <meta charset='UTF-8'>
	    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
	    <!-- ========== Title ========== -->
	    <title>".$title."</title>

	    <!-- ========== STYLESHEETS ========== -->
	    <!-- Bootstrap CSS -->
	    <!-- <link href='assets/css/bootstrap.min.css' rel='stylesheet'> -->
	    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
		<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js' integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'></script>
	    <!-- Fonts Icon CSS -->
	    <link href='assets/css/font-awesome.min.css' rel='stylesheet'>
	    <link href='assets/css/et-line.css' rel='stylesheet'>
	    <link href='assets/css/ionicons.min.css' rel='stylesheet'>
	    <!-- Carousel CSS -->
	    <link href='assets/css/slick.css' rel='stylesheet'>
	    <!-- Magnific-popup -->
	    <link rel='stylesheet' href='assets/css/magnific-popup.css'>
	    <!-- Animate CSS -->
	    <link rel='stylesheet' href='assets/css/animate.min.css'>
	    <!-- Custom styles for this template -->
	    <link href='assets/css/main.css' rel='stylesheet'>";
}

function logo_main_menu_login(){
	echo "<!--logo -->
        <div class='logo_box'>
            <a href='index.php'>
                <h1 style='color: white'><b>Galeria<br>Obrazów</b></h1>
            </a>
        </div>
        <!--logo end-->

        <!--main menu -->
        <div class='side_menu_section'>
            <ul class='menu_nav'>
                <li class='active'>
                    <a href='index.php'>
                        Strona główna
                    </a>
                </li>
                <li>
	                <a href='login.php'>
	                    Zaloguj się
	                </a>
	            </li>
                <li>
                    <a href='register.php'>
                    	Zarejestruj się
                    </a>
                </li>
            </ul>
        </div>
        <!--main menu end -->";
}

function logo_main_menu_logout(){
	echo "<!--logo -->
        <div class='logo_box'>
            <a href='index.php'>
                <h1 style='color: white'><b>Galeria<br>Obrazów</b></h1>
            </a>
        </div>
        <!--logo end-->

        <!--main menu -->
        <div class='side_menu_section'>
            <ul class='menu_nav'>
                <li class='active'>
                    <a href='index.php'>
                        Strona główna
                    </a>
                </li>
                <li>
                    <a href='profile_page.php?nickname=".$_SESSION['nickname']."/'>
                        Twój profil
                    </a>
                </li>
                <li>
                    <a href='add_image.php'>
                        Dodaj obraz
                    </a>
                </li>
                <li>
                    <a href='add_gallery.php'>
                        Stwórz galerię
                    </a>
                </li>
                <li>
                    <a href='gallery_list.php?nickname=".$_SESSION['nickname']."/'>
                        Twoje galerie
                    </a>
                </li>
                <li>
                    <a href='all_groups.php'>
                        Wszystkie grupy
                    </a>
                </li>
                <li>
                    <a href='group_list.php?nickname=".$_SESSION['nickname']."/'>
                        Moje grupy
                    </a>
                </li>
                <li>
                    <a href='add_group.php'>
                        Stwórz grupę
                    </a>
                </li>
                <li>
	                <a href='index.php?logout'>
	                    Wyloguj się
	                </a>
	            </li>
            </ul>
        </div>
        <!--main menu end -->";
}

function filter(){
	echo "<!--filter menu -->
        <div class='side_menu_section'>
            <h4 class='side_title'>Filtruj:</h4>
            <ul  id='filtr-container'  class='filter_nav'>
                <li  data-filter='*' class='active'><a href='javascript:void(0)' >all</a></li>
                <li data-filter='.branding'> <a href='javascript:void(0)'>branding</a></li>
                <li data-filter='.design'><a href='javascript:void(0)'>design</a></li>
                <li data-filter='.photography'><a href='javascript:void(0)'>photography</a></li>
                <li data-filter='.animals'><a href='javascript:void(0)'>animals</a></li>
                <li data-filter='.animals'> <a href='javascript:void(0)'>animals</a></li> <!-- z jakiegoś powodu nie działa xd -->
            </ul>
        </div>
        <!--filter menu end -->";
}

function social_copy(){
	echo "<!--social and copyright -->
        <div class='side_menu_bottom'>
            <div class='side_menu_bottom_inner'>
                <ul class='social_menu'>
                    <li>
                        <a href='#'> <i class='ion ion-social-pinterest'></i> </a>
                    </li>
                    <li>
                        <a href='#'> <i class='ion ion-social-facebook'></i> </a>
                    </li>
                    <li>
                        <a href='#'> <i class='ion ion-social-twitter'></i> </a>
                    </li>
                    <li>
                        <a href='https://github.com/darialasecka/'> <i class='ion ion-social-github'></i> </a>
                    </li>
                </ul>
                <div class='copy_right'>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    <p class='copyright'>Copyright &copy;<script>document.write(new Date().getFullYear());</script><br> All rights reserved <br> This template is made by <a href='https://colorlib.com' target='_blank'>Colorlib</a></p>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </div>
            </div>
        </div>
        <!--social and copyright end -->";
}

function side_menu($filter){
	echo "<div class='col-lg-2 col-md-3 col-12 menu_block'>";
	if (!isset($_SESSION['nickname'])) logo_main_menu_login();
	else logo_main_menu_logout();
	if ($filter == true) filter();
	social_copy();
	echo "</div>";
}

function scripts(){
	echo "<!-- jquery -->
		<script src='assets/js/jquery.min.js'></script>
		<!-- bootstrap -->
		<script src='assets/js/popper.js'></script>
		<script src='assets/js/bootstrap.min.js'></script>
		<script src='assets/js/waypoints.min.js'></script>
		<!--slick carousel -->
		<script src='assets/js/slick.min.js'></script>
		<!--Portfolio Filter-->
		<script src='assets/js/imgloaded.js'></script>
		<script src='assets/js/isotope.js'></script>
		<!-- Magnific-popup 
		<script src='assets/js/jquery.magnific-popup.min.js'></script>-->
		<!--Counter-->
		<script src='assets/js/jquery.counterup.min.js'></script>
		<!-- WOW JS -->
		<script src='assets/js/wow.min.js'></script>
		<!-- Custom js -->
		<script src='assets/js/main.js'></script>";
}

//C:\xampp\htdocs\assets\bootstrap-tagsinput-latest
?>