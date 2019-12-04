<?php
require_once('database.php');
require_once('add.php');
$conn = connect();
$stmt = $conn->prepare("SELECT * FROM picture");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$images = $rows[0];

//https://www.php.net/manual/en/function.copy.php <- kopiowanie obrazu

if(isset($_POST["insert"]))  
 {  
      $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));   
      echo '<script>alert("Image Inserted into Database")</script>';  
 }  

$title = $description = ""
//do formy dodawania komentarza, przerobić na dodawanie obrazu
/*$autor = $content = $contentErr ="";
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
    else echo "Nie udało się dodać twojego komnetarza";
    $content = "";

}
function check_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}*/

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
            padding-top: 10px;
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
                <!--===================  add image form start ====================-->
                <div class="container" style="font-size: 18px; padding: 20px;">  
                    <h2 align="center">Dodaj obraz</h2><br>  
                    
                    <form method="post" enctype="multipart/form-data">  
                        <!-- <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="image">
                            <label class="custom-file-label" for="customFile">Wybierz obraz</label>
                        </div> -->
                        <!-- https://stackoverflow.com/questions/23738311/uploading-selected-picture-without-page-refresh -->
                        <!-- https://stackoverflow.com/questions/55037474/how-to-uploadand-visualization-images-without-refresh-page-in-php -->
                        <div class="custom-file">
                            <input type="file" class="form-control-file" id="image" name="image" required>
                        </div>
                        <input type="title" class="form-control" id="title" placeholder="Tytuł" required value=<?php echo $title ?> >
                        <textarea style="font-size: 18px;" placeholder="Dodaj opis (nie jest wymagany)" class="pb-cmnt-textarea form-control" name="description"><?php echo $description;?></textarea>
                        <!-- === https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/ === -->
                        <!-- === https://stackoverflow.com/questions/52454626/bootstrap-4-tags-input-add-tags-only-from-the-predefined-list === -->
                        <!-- <div class="form-row">
                            <div class="col-md-1 mb-2">
                                <label style="padding: 10px; padding-left: 20px;">Tagi</label>
                            </div>
                            <div class="col-md-11 mb-2">
                              <input type="tags" class="form-control" id="tags" data-role="tagsinput" value="Tagi nie chcą działać ;(">
                            </div>
                        </div> -->
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
                    <table class="table table-bordered">  
                         <tr>  
                              <th>Obraz</th>  
                         </tr>  
                    <?php  
                    echo "tu powinno wyświetlić obrazek xd"
                    /*$query = "SELECT * FROM picture ORDER BY id DESC";  
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
                    </table>  
                </div>  
                <!--=================== add image form end====================-->
            </div>
        </div>
        <!--=================== content body end ====================-->
    </div>
</div>

<!-- Tags -->
<script type="text/javascript">
    $(".tm-input").tagsManager();
</script>
<?php scripts(); ?>
</body>
</html>
 <!-- skrypt do dodawania obrazków / wyskakujące okienko z wyborem -->
<!-- <script>  
$(document).ready(function() {  
    $('#insert').click(function() {  
        var image_name = $('#image').val();  
        if(image_name == '') {  
            alert("Nie wybrałeś żadnego obrazu");  
            return false;  
        } else {  
            var extension = $('#image').val().split('.').pop().toLowerCase();  
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1) {  
                 alert('Nieprawidłowy format obrazu');  
                 $('#image').val('');  
                 return false;  
            }  
        }  
    });  
});  

$('#addrun').click(function(){

     $('#addrun').before(
         '<input type="text" '+
         'value="Amsterdam,Washington,Sydney,Beijing" '+
         'data-role="tagsinput" />'
     ); 

     //Now run bootstrap.js to re-search
     //new data-* elements
     $('input').tagsinput('refresh');   
});
</script> -->