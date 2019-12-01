<?php
require_once('database.php');
$conn = connect();
$stmt = $conn->prepare("SELECT * FROM picture WHERE id=:id");
$stmt->bindValue(":id", $_GET["id"], PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$details = $rows[0];

$stmt = $conn->prepare("SELECT * FROM comment WHERE id=:id");
$stmt->bindValue(":id", $details['comments'], PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$comments = $rows[0];

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- ========== Title ========== -->
    <title>Obraz</title>
    <!-- ========== STYLESHEETS ========== -->
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts Icon CSS -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/et-line.css" rel="stylesheet">
    <link href="assets/css/ionicons.min.css" rel="stylesheet">
    <!-- Carousel CSS -->
    <link href="assets/css/slick.css" rel="stylesheet">
    <!-- Magnific-popup -->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body>
<div class="loader">
    <div class="loader-outter"></div>
    <div class="loader-inner"></div>
</div>

<div class="body-container container-fluid">
    <a class="menu-btn" href="javascript:void(0)">
        <i class="ion ion-grid"></i>
    </a>
    <div class="row justify-content-center">
        <!--=================== side menu ====================-->
        <div class="col-lg-2 col-md-3 col-12 menu_block">

            <!--logo -->
            <div class="logo_box">
                <a href="#">
                    <h1 style="color: white"><b>Galeria<br />Obrazów</b></h1>
                </a>
            </div>
            <!--logo end-->

            <!--main menu -->
            <div class="side_menu_section">
                <ul class="menu_nav">
                    <li class="active">
                        <a href="index.php">
                            Strona główna
                        </a>
                    </li>
                    <li>
                        <a href="portfolio.html">
                            Twój profil
                        </a>
                    </li>
                    <li>
                        <a href="about.html">
                            O nas
                        </a>
                    </li>
                    <li>
                        <a href="contact.html">
                            Kontakt
                        </a>
                    </li>
                </ul>
            </div>
            <!--main menu end -->

            <!--social and copyright -->
            <div class="side_menu_bottom">
                <div class="side_menu_bottom_inner">
                    <ul class="social_menu">
                        <li>
                            <a href="#"> <i class="ion ion-social-pinterest"></i> </a>
                        </li>
                        <li>
                            <a href="#"> <i class="ion ion-social-facebook"></i> </a>
                        </li>
                        <li>
                            <a href="#"> <i class="ion ion-social-twitter"></i> </a>
                        </li>
                        <li>
                            <a href="#"> <i class="ion ion-social-dribbble"></i> </a>
                        </li>
                    </ul>
                    <div class="copy_right">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p class="copyright">Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a></p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div>
            <!--social and copyright end -->

        </div>
        <!--=================== side menu end====================-->

        <!--=================== content body ====================-->
        <div class="col-lg-10 col-md-9 col-12 body_block  align-content-center">
            <div style='color: black; font-size: 50px; font-weight: bold;'><?php echo $details['name'];?> </div>
            <!-- /*echo '<p style="color: black">".$details['name']."</p>';*/ --> 
            <?php echo "<img src='".$details['image']."' />"; ?> <!-- póżniej zmienszyć rozmier obrazka -->
            <table class="table table-striped">
              <tbody>
                <tr>
                  <th>Autor</th>
                    <td>
                        <a  style="font-size: 19px;" href="/profile_page.php?nickname=<?php echo urlencode($details['autor']) ?>/"><?php echo $details['autor']; //powiększyć czcionkę później?></a>
                    <!-- <?php 
                    echo '<a href="/profile_page.php?nickname='.urlencode($details['autor']).'/">'.$details['autor'].'</a>';
                    //echo '<a href="/profile_page.php?nickname='.$details['autor'].'/>Nic</a>'; 
                    ?> -->

                    <!-- <?php echo $details['autor'];?> -->
                      
                  </td>
                </tr>
                <tr>
                  <th>Opis</th>
                  <td><?php echo $details['description'];?></td>
                </tr>
                <?php if(!is_null($details['tags'])): ?>
                    <tr>
                      <th>Tagi</th>
                      <td><?php echo $details['tags'];?> <!-- to w pętli bo będzie więcej, na razie jest tylko jeden --></td>
                    </tr>
                <?php endif; ?>

                <?php if(is_null($details['comments'])): ?>
                <tr> 
                    <td colspan="2">Brak komentarzy</td>
                </tr>
                <?php else: ?>
                <tr>
                  <th>Komentarze</th>
                  <!-- <td>
                    <table>
                        <tbody>
                            <td>
                                <?php echo $comments['autor'];
                                      echo "       ";
                                      echo $comments['added']; //tego czcionkę zmienić pewnie będzie lepiej
                                ?>
                            </td>
                            <td>
                                <?php echo $comments['content'];?>
                            </td>
                        </tbody>
                    </table>
                  </td> -->
                  <td><?php echo $comments['autor']; //zmienjszyć czcionkę tego i daty dodania
                            echo "&emsp;&emsp;"; // "/t" tylko działające
                            echo "Data dodania: ".$comments['added'];
                            echo "<br>";
                            echo $comments['content'];?> <!-- to w pętli wyswietlać komentarze, może zrobić dla nich osobną tabelę, albo jeszcze coś innego --></td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>  
        </div>
        <!-- ================= comments ==================== -->
        <div>
            <!-- No to tak,
            tutaj miejsce na napisanie komentarza,
            i jakiś przycisk submit, i po tym, 
            albo strona się odświeża, 
            albo jakaś "animacja" dodawania,
            w każdym razie coś ogarnąć...
            -->
        </div>
<!--=================== content body end ====================-->


<!-- jquery -->
<script src="assets/js/jquery.min.js"></script>
<!-- bootstrap -->
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/waypoints.min.js"></script>
<!--slick carousel -->
<script src="assets/js/slick.min.js"></script>
<!--Portfolio Filter-->
<script src="assets/js/imgloaded.js"></script>
<script src="assets/js/isotope.js"></script>
<!--Counter-->
<script src="assets/js/jquery.counterup.min.js"></script>
<!-- WOW JS -->
<script src="assets/js/wow.min.js"></script>
<!-- Custom js -->
<script src="assets/js/main.js"></script>
</body>
</html>