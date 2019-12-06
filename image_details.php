<?php
require_once('database.php');
require_once('add.php');
$conn = connect();
//tymczasowow
$conn = connect();
$stmt = $conn->prepare("SELECT nickname FROM person");
$stmt->execute();
$autors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM picture WHERE id=:id");
$stmt->bindValue(":id", $_GET["id"], PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$details = $rows[0];

if ($details['tags'] == true) {
    $stmt = $conn->prepare("SELECT * FROM tag_where WHERE where_is='picture' AND where_id=:where_id");
    $stmt->bindValue(":where_id", $details["id"], PDO::PARAM_INT);
    $stmt->execute();
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /*$count_tags = count($tags); <-- na razie chyba zbędne
    $counter = 0;*/
}


if ($details['comments'] == true) {
    $stmt = $conn->prepare("SELECT * FROM comment WHERE where_is='picture' AND where_id=:where_id");
    $stmt->bindValue(":where_id", $details['id'], PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $comments = $rows[0];
}

//do formy dodawania komentarza
$autor = $content = $contentErr ="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $autor = check_input($_POST["autor"]);
    $check = false;

    if (empty($_POST["content"])) {
        $contentErr = "Nie możesz dodać pustego komentarza";
        $check = false;
    } else {
        $content = check_input($_POST["content"]);
        $check = true;
    }

    if ($check) {
        if ($details['comments'] == false) {
            $stmt = $conn->prepare("UPDATE picture SET comments=:comments WHERE id=:id");
            $stmt->bindValue(":comments", true, PDO::PARAM_INT);
            $stmt->bindValue(":id", $details['id'], PDO::PARAM_INT);
            $stmt->execute();
        }
        add_comment(NULL, $autor, $content, 'picture', $details['id']);
        $content = "";
        $check = false;
    }
    //else echo "Nie udało się dodać twojego komnetarza";
    $content = "";

}
/*function check_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}*/

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
                <!-- /*echo '<p style="color: black">".$details['name']."</p>';*/ --> 
                <?php echo "<img src='".$details['image']."' />"; ?> <!-- póżniej zmienszyć rozmier obrazka -->
                <table class="table table-striped" style="padding-top: 10px;">
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
                  </tbody>
                </table>  
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
                <!-- <div>
                    <h5>Dodaj komentarz: </h5>
                    <form method="post">
                        Kto(tymczasowo): <select name="autor" >
                            <?php foreach ($autors as $autor): ?>
                               <option value=<?php echo $autor['nickname']; ?>> <?php echo $autor['nickname']; ?> </option>
                            <?php endforeach; ?>
                        </select><br>
                        Kometarz: <textarea name="content"><?php echo $content;?></textarea><span class="error"><?php echo $contentErr;?></span><br>
                        <input type="submit" class="button" name="submit" value="Dodaj komentarz">  
                    </form>
                </div> -->
                <h5>Dodaj komentarz: </h5>
                <div class="container pb-cmnt-container">
                    <form method="post">
                    Kto(tymczasowo):
                    <select name="autor" >
                        <?php foreach ($autors as $autor): ?>
                           <option value=<?php echo $autor['nickname']; ?>> <?php echo $autor['nickname']; ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- </form> -->
                    <div class="row justify-content-between">
                        <div class="col-md-12 col-md-offset-6">
                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <textarea style="font-size: 18px;" placeholder="Dodaj komentarz" class="pb-cmnt-textarea" name="content"><?php echo $content;?></textarea>
                                    <span class="error" style="color: red;"><?php echo $contentErr;?></span>
                                    <div class="form-inline justify-content-end" method="post">
                                        <button class="btn-sm btn-dark btn-primary float-xs-right text-white" type="submit" name="submit">Dodaj</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
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
                                                  echo $row['added']; //"Data dodania: "
                                                  ?>
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