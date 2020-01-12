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
$person = $rows[0];

$stmt = $conn->prepare("SELECT * FROM gallery WHERE person=:nickname");
$stmt->bindValue(":nickname", $person['nickname'], PDO::PARAM_STR);
$stmt->execute();
$galleries = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($galleries){
    foreach ($galleries as $gallery) {
        if ($gallery['pictures'] == true) {
            $stmt = $conn->prepare("SELECT * FROM picture_where WHERE where_is='gallery' AND where_id=:id");
            $stmt->bindValue(":id", $gallery['id'], PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $counter = 0;
            foreach($rows as $row) $counter += 1;
            $img_count[$gallery['id']] = $counter;
        } else {
            $img_count[$gallery['id']] = 0;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Galerie"); ?>
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
                <h3>Twoje galerie:</h3>
            <?php else: ?>
                <h3>Galerie: </h3>
            <?php endif; ?>

            <?php if($galleries): ?>
                <table class="table table-striped">
                    <tbody> 
                        <tr>
                          <th>Galeria</th>
                          <th>Ilość obrazów</th>
                        </tr>
                        <?php foreach($galleries as $gallery): ?>
                            <tr>
                              <td><a style="font-size: 18px;" href="/gallery_details.php?id=<?php echo $gallery['id'] ?>/"><?php echo $gallery['name']; ?></a></th><!-- tu link do galerii -->
                              <td><?php echo $img_count[$gallery['id']];?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <h4>Nie ma żadnych galerii.</h4>
                <?php if($_SESSION['nickname'] == $nick): ?>
                    <a style="font-size: 18px;" href="add_gallery.php">Stwórz swoją pierwszą galerię.</a>
                <?php endif; ?>
            <?php endif; ?>
            
            <!--=================== content body end ====================-->
        </div>
    </div>
</div>


<?php scripts(); ?>
</body>
</html>