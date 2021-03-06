<?php
require_once('database.php');
require_once('add.php');
$conn = connect();

$stmt = $conn->prepare("SELECT slug FROM tag");
$stmt->execute();
$db_tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = $description = $tags = "";

//do formy dodawania obrazu
/* https://github.com/niczak/PHP-Sanitize-Post/blob/master/sanitize.php */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $autor = $_SESSION['nickname'];
    $title = check_input($_POST["title"]);
    $description = check_input($_POST["description"]);
    $tags = $_POST["hidden-tags"];
    $image_name = $_FILES["image"]["name"];


    if ($_FILES["image"]["error"] <= 0) { //wątpię czy może być mniejsze od zera ale na razie niech tak zostanie
        $image_path = $_FILES["image"]["tmp_name"];
        //sprawdzić czy nazwa już jest i zmienić ją z dopiskiem 1
        $image_name = check_file($image_name);
        move_uploaded_file($image_path, "img/$image_name"); //działa

        $path = "http://localhost/img/".$image_name;

        add_picture($path, $title, $autor, 0, $description);//najpier dodam z zerową ilością tagów, a póżiej w updejtowaniu tagów, zmienię na jeden, o ile są jakieś xD
        $last_id = $conn->lastInsertId();
        if($tags != NULL){
            $db_list_of_tags = [];
            foreach ($db_tags as $tag) {
                //echo $tag['slug'];
                array_push($db_list_of_tags, $tag['slug']);
            }
            //echo "Tagi:".$tags. "<br />";
            $list_of_tags = explode(",",$tags);
            foreach ($list_of_tags as $tag) {
                $tag = check_input($tag);
                if(!in_array($tag, $db_list_of_tags)){
                    //echo "nie ma";//jeślni nie to dodać do tags
                    add_tag($tag);
                } 
                //else echo "jest";
                update_tag_where($tag, "picture", $last_id);//zupdejtować informacje o tagach dla obrazu
                $stmt = $conn->prepare("UPDATE picture SET tags=:tags WHERE id=:id");
                $stmt->bindValue(":tags", true, PDO::PARAM_INT);
                $stmt->bindValue(":id", $last_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        header("location: image_details.php?id=$last_id");
        //przekierowanie na stronę "mówiące", że obraz został pomyślnie dodany
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Dodaj obraz"); ?>
    <!-- Tags -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.js"></script>
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
    </style>
</head>
<body>
<?php if (!isset($_SESSION['nickname'])): header("location: login.php");
    else: ?>
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
                <!--===================  add image form start ====================-->
                <div style='color: black; font-size: 50px;'>Dodaj obraz</div>
                <div class="container" style="font-size: 18px; padding: 20px;">

                    
                    <form method="post" enctype="multipart/form-data">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/png, image/jpeg, image/gif" required>
                            <label class="custom-file-label" for="image">Wybierz obraz</label>
                        </div>
                        <br>
                        <br>
                        <input type="text" class="form-control" id="title" placeholder="Tytuł" name="title" required>
                        <textarea style="font-size: 18px;" placeholder="Opis (nie jest wymagany)" class="pb-cmnt-textarea form-control" name="description"><?php echo $description;?></textarea>
                        <div class="form-group">
                            <input type="text" name="tags" placeholder="Tagi" class="tm-input form-control tm-input-info"/>
                        </div>

                        <div class="form-inline justify-content-end" method="post">
                            <button class="btn-sm btn-dark btn-primary float-xs-right text-white" type="submit" name="insert">Dodaj</button>
                        </div>
                    </form>
                </div>  
                <!--=================== add image form end====================-->
            </div>
        </div>
        <!--=================== content body end ====================-->
    </div>
</div>
<?php endif; ?>
<!-- Tags -->
<script type="text/javascript">
    $(".tm-input").tagsManager();

     $('.custom-file input').change(function (e) {
        $(this).next('.custom-file-label').html(e.target.files[0].name);
    });
</script>
<?php scripts(); ?>
</body>
</html>