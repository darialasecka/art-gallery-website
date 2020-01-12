<?php
require_once('database.php');
require_once('add.php');
$conn = connect();
$stmt = $conn->prepare("SELECT * FROM person WHERE nickname=:nickname");
$nick = urldecode($_GET["nickname"]);
$nick = substr($nick, 0, -1);
$stmt->bindValue(":nickname", $nick, PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$details = $rows[0];

$stmt = $conn->prepare("SELECT * FROM picture WHERE autor=:nickname");
$stmt->bindValue(":nickname", $details['nickname'], PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if($rows) $images = $rows[0];

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Profil"); ?>
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
        <?php side_menu(false); ?>
        <!--=================== side menu end====================-->

        <!--=================== content body ====================-->
        <div class="col-lg-10 col-md-9 col-12 body_block  align-content-center">
           <!--=================== person data ====================-->
            <?php if($_SESSION['nickname'] == $nick): ?>
                <h3>Twoje dane:</h3>
            <?php else: ?>
                <h3>Dane: </h3>
            <?php endif; ?>
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
            <!-- =========== check gallery list ============== -->
            <a href="/gallery_list.php?nickname=<?php echo urlencode($details['nickname']) ?>/"><h4>Tu możesz zobaczyć listę galerii.</h4></a>
            

            <a href="/group_list.php?nickname=<?php echo urlencode($details['nickname']) ?>/"><h4>Tu możesz zobaczyć listę grup, do których należy.</h4></a>
                                           
            <!-- <?php if($_SESSION['nickname'] == $nick): ?>
                <a href="add_gallery.php"><i class='ion ion-plus'></i></a>
            <?php endif ?> -->
            <?php if($_SESSION['nickname'] == $nick): ?>
                <h4>Twoje obrazy:</h4>
            <?php else: ?>
                <h4>Obrazy: </h4>
            <?php endif; ?>
            <div class="container-fluid">
                <!--=================== images gallery start ====================-->
                <?php if($rows): ?>
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
                <?php else: ?>
                    <p>Brak obrazów. <?php if($_SESSION['nickname'] == $nick): ?><a style="font-size: 15px;" href="add_image.php">Dodaj swój pierwszy obraz.</a><?php endif; ?></p>
                <?php endif; ?>
                <!--=================== images gallery end====================-->
            </div>
            <!--=================== content body end ====================-->
        </div>
    </div>
</div>


<?php scripts(); ?>
</body>
</html>