<?php
require_once('database.php');
require_once('add.php');
$conn = connect();

$stmt = $conn->prepare("SELECT nickname FROM person");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$autors = $rows;

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
        $path = "http://localhost/img/".$image_name;
        //echo $path;//to jest te path jak url, więc go można "wrzucić" do bay danych i bedzie wyświetlał obraz
        /*if(strpos($_FILES["image"]["type"], "image/") !== false){ //Można zostawić dla pewności, ale accept to ogarnia
            echo "Poprawny";
        } else {
            echo "Nie poprawny";
        }*/
        
        add_picture($path, $title, $autor, 0, $description);//najpier dodam z zerową ilością tagów, a póżiej w updejtowaniu tagów, zmienię na jeden, o ile są jakieś xD
        $last_id = $conn->lastInsertId(); //chyba tak je wyciągne
        //echo $autor. "<br />";
        //echo $title. "<br />";
        //echo $description. "<br />";
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

                //przekierowanie na stronę mówiące, że obraz został pomyślnie dodany
            //dorobić dodawanie do bazy danych
            }
        }
    }
}

//echo "<img src='".$row['image']."' />";

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


                            <!-- <span class="btn btn-default btn-file">
                                Browse <input type="file">
                            </span> -->

                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/png, image/jpeg, image/gif" required>
                            <label class="custom-file-label" for="image">Wybierz obraz</label>
                        </div>
                        <br>
                        <br>

                        <!-- https://stackoverflow.com/questions/23738311/uploading-selected-picture-without-page-refresh -->
                        <!-- https://stackoverflow.com/questions/55037474/how-to-uploadand-visualization-images-without-refresh-page-in-php -->
                        <!-- <div class="custom-file">
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/png, image/jpeg, image/gif" required>
                        </div> -->
                        <input type="text" class="form-control" id="title" placeholder="Tytuł" name="title" required>
                        <textarea style="font-size: 18px;" placeholder="Dodaj opis (nie jest wymagany)" class="pb-cmnt-textarea form-control" name="description"><?php echo $description;?></textarea>
                        <!-- === https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/ === -->
                        <div class="form-group">
                            <!-- <label>Tagi</label><br/> -->
                            <!-- https://www.itsolutionstuff.com/post/bootstrap-input-multiple-tags-example-using-tag-manager-jquery-pluginexample.html -->
                            <input type="text" name="tags" placeholder="Tagi" class="tm-input form-control tm-input-info"/>
                        </div>

                        <!-- ten button niżej dodaje, zrobimu go po całej formie xd -->
                        <div class="form-inline justify-content-end" method="post">
                            <button class="btn-sm btn-dark btn-primary float-xs-right text-white" type="submit" name="insert">Dodaj</button>
                        </div>
                    </form>

                    <br>  
                    <br>  
                    <!-- <table class="table table-bordered">  
                         <tr>  
                              <th>Obraz</th>  
                         </tr>  
                    <?php  
                    /*echo "tu powinno wyświetlić obrazek xd" //ale to niby ajax jest potrzebny
                    $query = "SELECT * FROM picture ORDER BY id DESC";  
                    $result = mysqli_query($connect, $query);  
                    while($row = mysqli_fetch_array($result))  
                    {  
                         echo '  
                              <tr>  
                                   <td>  
                                        <img src="data:image/jpeg;base64,'.base64_encode($row['name'] ).'" height="200" width="200" class="img-thumnail" />  
                                   </td>  
                              </tr>  
                         ';  
                    }  */
                    ?>  
                    </table>   -->
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