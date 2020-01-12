<?php
require_once('database.php');
require_once('add.php');
$conn = connect();
//tymczasowow
$conn = connect();
$stmt = $conn->prepare("SELECT nickname FROM person");
$stmt->execute();
$autors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM gallery WHERE id=:id");
$stmt->bindValue(":id", $_GET["id"], PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$details = $rows[0];

if ($details['tags'] == true) {
    $stmt = $conn->prepare("SELECT * FROM tag_where WHERE where_is='gallery' AND where_id=:where_id");
    $stmt->bindValue(":where_id", $details["id"], PDO::PARAM_INT);
    $stmt->execute();
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /*$count_tags = count($tags); <-- na razie chyba zbędne
    $counter = 0;*/
}
//SELECT * FROM `picture` JOIN `picture_where` ON `picture`.`id`=`picture_where`.`picture_id` WHERE `where_is`='gallery' AND `where_id`=1 
if ($details['pictures'] == true) {
    $stmt = $conn->prepare("SELECT * FROM picture JOIN picture_where ON picture.id = picture_where.picture_id WHERE where_is='gallery' AND where_id=:where_id");
    $stmt->bindValue(":where_id", $details["id"], PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($details['comments'] == true) {
    $stmt = $conn->prepare("SELECT * FROM comment WHERE where_is='gallery' AND where_id=:where_id");
    $stmt->bindValue(":where_id", $details['id'], PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $comments = $rows[0];
}

//do formy dodawania komentarza
$content = $contentErr ="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $autor = $_SESSION['nickname'];

    if (empty($_POST["content"])) {
        $contentErr = "Nie możesz dodać pustego komentarza";
    } else {
        $content = $_POST["content"];
        if ($details['comments'] == false) {
            $stmt = $conn->prepare("UPDATE gallery SET comments=:comments WHERE id=:id");
            $stmt->bindValue(":comments", true, PDO::PARAM_INT);
            $stmt->bindValue(":id", $details['id'], PDO::PARAM_INT);
            $stmt->execute();
        }
        add_comment($autor, $content, 'gallery', $details['id']);
        $content = "";
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Obraz"); ?>

    <style>
        .pb-cmnt-textarea {
            resize: none;
            padding: 10px;
            width: 100%;
            border: 1px solid #F2F2F2;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #1f1f1f!important; 
        }
        .pb-cmnt-container{
            margin-bottom: 20px;
        }
        .table-striped{
            margin-top: 10px;
        }

    </style>
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
            <div class="container-fluid">
                <!--===================  image details start ====================-->
                <div style='color: black; font-size: 50px;'><?php echo $details['name'];?> </div>
                <table class="table table-striped" style="padding-top: 10px;">
                    <tbody>
                        <tr>
                            <th>Galleria utworzona przez</th>
                            <td><a  style="font-size: 19px;" href="/profile_page.php?nickname=<?php echo urlencode($details['person']) ?>/"><?php echo $details['person']; //powiększyć czcionkę później?></a></td>
                        </tr>
                        <tr>
                            <th>Data stworzenia</th>
                            <td><?php echo $details['created'];?></td>
                        </tr>
                        <tr>
                            <th>Data ostatniej aktualizacji</th>
                            <td><?php echo $details['latest_update'];?></td>
                        </tr>
                        <tr>
                            <th>Opis</th>
                            <td><?php echo $details['description'];?></td>
                        </tr>
                    </tbody>
                </table>  

                <h4>Obrazy w galerii: </h4>
                <div class="container-fluid">
                <?php if($details['pictures'] == true): ?>
                    <div class="grid img-container justify-content-center no-gutters">
                        <?php foreach($images as $img): ?> <!--pózniej dorobić jakieś skalowanie obrazków-->
                        <a href="/image_details.php?id=<?php echo $row['id'] ?>/">

                        <div class="grid-sizer col-sm-12 col-md-6 col-lg-3"></div>
                            <div class="grid-item animals col-sm-12 col-md-6 col-lg-3">
                                <div class="project_box_one">
                                    <?php echo "<img src='".$img['image']."' />"; ?>
                                        <div class="product_info">
                                            <div class="product_info_text">
                                                <div class="product_info_text_inner">
                                                    <!--<i class="ion ion-plus"></i>-->
                                                    <h4><?php echo $img['name']; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Brak obrazów w galerii. <?php if($_SESSION['nickname'] == $details['person']): ?><a style="font-size: 15px;" href="index.php">Dodaj pierwszy obraz.</a><?php endif; ?></p>
                <?php endif; ?>
                <!-- ================== tags ================== -->
                <?php if($details['tags'] == false): ?>
                    <p>Brak tagów</p>
                <?php else: ?>
                    <h5>Tagi</h5>
                    <div class="container pb-cmnt-container">
                        <p style="font-size: 15px; text-align: left;">
                            <?php 
                            foreach($tags as $tag){
                                echo $tag['tag_slug']."&emsp;"; 
                                /*$counter++; <-- chyba zbędne, bo "p" samo powinno ogarąć, że powinna być nowa linia
                                if ($counter >= 20){
                                    echo "<br>";
                                    $counter = 0;    
                                }*/
                            } ?>
                        </p>
                    </div>    
                <?php endif; ?>
                <!-- ================= adding comments ==================== -->
                <?php if (!isset($_SESSION['nickname'])): ?>
                    <p>Aby dodać komentarz musisz się najpierw <a style="font-size: 15px;" href='login.php'>zalogować</a>.</p>
                <?php else: ?>
                    <h5>Dodaj komentarz: </h5>
                    <div class="container pb-cmnt-container">
                        <form method="post">
                        <div class="row justify-content-between">
                            <div class="col-md-12 col-md-offset-6">
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <textarea style="font-size: 18px;" placeholder="Dodaj komentarz" class="pb-cmnt-textarea" name="content"><?php echo $content;?></textarea>
                                        <span class="error" style="color: red;"><?php echo $contentErr;?></span>
                                        <div class="form-inline justify-content-end" method="post">
                                            <button class="btn-sm btn-dark btn-primary float-xs-right text-white" type="submit" name="comment" value='comment'>Dodaj</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                <?php endif; ?>
                <!-- ================= adding comments end / comments ==================== -->
                <?php if($details['comments'] == false): ?>
                    <p>Brak komentarzy</p>
                <?php else: ?>
                    <h5>Komentarze:</h5>
                    <div class="container pb-cmnt-container">
                        <table class="table table-striped">
                            <tbody>
                                <?php foreach($rows as $row): ?>
                                    <tr>
                                        <td style="font-size: 12px;">
                                            <a style="font-size: 18px;" href="/profile_page.php?nickname=<?php echo urlencode($row['autor']) ?>/"><?php echo $row['autor']; //powiększyć czcionkę później?></a>
                                            <?php echo "<br>";
                                                  echo $row['added']; //"Data dodania: "?>
                                        </td>
                                        <td style="padding-left: 35px;">
                                            <?php echo $row['content'];?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            <!-- ===================== commets end ====================== -->
            </div>
        </div>
    <!--=================== content body end ====================-->
    </div>
</div>

<?php scripts(); ?>
</body>
</html>