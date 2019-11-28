<?php
require_once('database.php');
$conn = connect();
$stmt = $conn->prepare("SELECT * FROM person WHERE nickname=:nickname");
$nick = urldecode($_GET["nickname"]);
$nick = substr($nick, 0, -1);
$stmt->bindValue(":nickname", $nick, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$details = $rows[0];

$stmt = $conn->prepare("SELECT * FROM picture WHERE autor=:nickname");
$stmt->bindValue(":nickname", $details['nickname'], PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$images = $rows[0];

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- ========== Title ========== -->
    <title>Profil</title>
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
                        <a href="/profile_page.php?<?php echo $row['id'] ?>">
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
           <!--=================== person data ====================-->

            <h3>Twoje dane:</h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                      <th>Nick</th>
                      <td><?php echo $details['nickname'];?></td>
                    </tr>
                    <tr>
                      <th>Imie</th>
                      <td><?php echo $details['name'];?></td>
                    </tr>
                    <tr>
                      <th>Nazwisko</th>
                      <td><?php echo $details['lastname'];?></td>
                    </tr>
                    <tr>
                      <th>Wiek</th>
                      <td><?php echo $details['age'];?> </td>
                    </tr>
                </tbody>
            </table>

            <div class="container-fluid">
                <!--=================== main gallery start====================-->
                <div class="grid img-container justify-content-center no-gutters">
                    <?php foreach($rows as $row): ?> <!--pózniej dorobić jakieś skalowanie obrazków-->
                    <a href="/image_details.php?id=<?php echo $row['id'] ?>/">

                    <div class="grid-sizer col-sm-12 col-md-6 col-lg-3"></div>
                        <div class="grid-item animals col-sm-12 col-md-6 col-lg-3">
                            <div class="project_box_one">
                                <?php echo "<img src='".$row['image']."' />"; ?>
                                    <div class="product_info">
                                        <div class="product_info_text">
                                            <div class="product_info_text_inner">
                                                <!--<i class="ion ion-plus"></i>-->
                                                <h4><?php echo $row['name']; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    
                    </a>
                    <?php endforeach; ?>
                </div>
                <!--=================== main gallery end====================-->
            </div>
            <!--=================== content body end ====================-->
        </div>
    </div>
</div>


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