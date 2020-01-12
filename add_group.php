<?php
require_once('database.php');
require_once('add.php');
$conn = connect();

$stmt = $conn->prepare("SELECT slug FROM tag");
$stmt->execute();
$db_tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = $description = $tags = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = check_input($_POST["title"]);
    $description = check_input($_POST["description"]);
    $tags = $_POST["hidden-tags"];

    $last_id = add_group($title, $_SESSION['nickname'], $description);
    if($tags != NULL){
        $db_list_of_tags = [];
        foreach ($db_tags as $tag) {
            array_push($db_list_of_tags, $tag['slug']);
        }
        $list_of_tags = explode(",",$tags);
        foreach ($list_of_tags as $tag) {
            $tag = check_input($tag);
            if(!in_array($tag, $db_list_of_tags)){
                add_tag($tag);
            } 
            update_tag_where($tag, "group", $last_id);//zupdejtować informacje o tagach dla gelerii
            $stmt = $conn->prepare("UPDATE group_info SET tags=:tags WHERE id=:id");
            $stmt->bindValue(":tags", true, PDO::PARAM_INT);
            $stmt->bindValue(":id", $last_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    header("location: group_details.php?id=$last_id");
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Stwórz grupę"); ?>
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
                <div style='color: black; font-size: 50px;'>Stwórz grupę</div>
                <div class="container" style="font-size: 18px; padding: 20px;">

                    <form method="post">
                        <input type="text" class="form-control" id="title" placeholder="Nazwa" name="title" required>
                        <textarea style="font-size: 18px;" placeholder="Opis (nie jest wymagany)" class="pb-cmnt-textarea form-control" name="description"><?php echo $description;?></textarea>
                        <div class="form-group">
                            <input type="text" name="tags" placeholder="Tagi" class="tm-input form-control tm-input-info"/>
                        </div>

                        <div class="form-inline justify-content-end" method="post">
                            <button class="btn-sm btn-dark btn-primary float-xs-right text-white" type="submit" name="insert">Stwórz</button>
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