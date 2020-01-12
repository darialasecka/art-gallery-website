<?php
require_once('database.php');
require_once('add.php');
$conn = connect();
$stmt = $conn->prepare("SELECT * FROM group_info");
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($groups as $group) { // tu liczy cłonków grypu
    $stmt = $conn->prepare("SELECT * FROM person_group WHERE which_group=:id");
    $stmt->bindValue(":id", $group['id'], PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $counter = 0;
    foreach($rows as $row) $counter += 1;
    $members_counter[$group['id']] = $counter;
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Grupy"); ?>
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
            <h3>Grupy: </h3>
            <?php if($groups): ?>
                <table class="table table-striped">
                    <tbody> 
                        <tr>
                          <th>Grupa</th>
                          <th>Ilość członków</th>
                        </tr>
                        <?php foreach($groups as $group): ?>
                            <tr>
                              <td><a style="font-size: 18px;" href="/group_details.php?id=<?php echo $group['id'] ?>/"><?php echo $group['name']; ?></a></th>
                              <td><?php echo $members_counter[$group['id']];?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <h4>Nie ma żadnych grup.</h4>
                <?php if(isset($_SESSION['nickname'])): ?>
                    <a style="font-size: 18px;" href="add_group.php">Stwórz swoją pierwszą grupę.</a>
                <?php endif; ?>
            <?php endif; ?>
            
            <!--=================== content body end ====================-->
        </div>
    </div>
</div>


<?php scripts(); ?>
</body>
</html>