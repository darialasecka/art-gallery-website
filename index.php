<?php
require_once('database.php');
require_once('add.php');
$conn = connect();
$stmt = $conn->prepare("SELECT * FROM picture");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$images = $rows[0];

//echo "<img src='".$row['image']."' />";
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Galeria Obrazów"); ?>
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
        <?php side_menu(true); ?>
        <!--=================== side menu end====================-->

        <!--=================== content body ====================-->
        <div class="col-lg-10 col-md-9 col-12 body_block  align-content-center">
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
        </div>
        <!--=================== content body end ====================-->
    </div>
</div>


<?php scripts(); ?>
</body>
</html>