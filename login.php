<?php 
require_once('database.php');
require_once('add.php');
/*  https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database  */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// initializing variables
$nickname = "";
$nickErr = $pswrErr = "";

// connect to the database
$conn = connect();

// LOGIN USER
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nickname = check_input($_POST['nickname']);
  $password = $_POST['password'];

  /* https://secure.php.net/manual/en/function.password-verify.php */
  $stmt = $conn->prepare("SELECT password FROM person WHERE nickname=:nickname ");//OR email=:email LIMIT 1
  $stmt->bindValue(":nickname", $nickname, PDO::PARAM_STR);
  //$stmt->bindValue(":email", $email, PDO::PARAM_INT);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $hashed_password = $rows[0];

  if (password_verify($password, $hashed_password['password'])) {
    $_SESSION['nickname'] = $nickname;
    $_SESSION['success'] = "Jesteś zalogowany";
    header('location: index.php');
  }else {
    $pswrErr = "Zły login lub hasło";
  }
}

?>


<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Logowanie"); ?>

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
              <!--===================  register start ====================-->
              <div style='color: black; font-size: 50px;'>Logowaie</div>
                <div class="container" style="font-size: 18px; padding: 20px;">
                  <form method="post">
                    <input type="text" class="form-control" id="nickname" placeholder="Nick" name="nickname" required>
                    <!-- <span class="error" style="color: red; font-size: 15px;"><?php echo $nickErr;?></span> -->
                    <input type="password" class="form-control" id="password" placeholder="Hasło" name="password" required>
                    <span class="error" style="color: red; font-size: 15px;"><?php echo $pswrErr;?></span>
                  
                    <div class="form-inline justify-content-end" method="post">
                      <button class="btn-sm btn-dark btn-primary float-xs-right text-white" type="submit" name="insert">Zaloguj</button>
                    </div>
                    <p>
                      Nie masz jeszcze konta? <a style="font-size: 15px;" href="register.php">Zarejestruj się</a>
                    </p>
                  </form>

              </div>
              <!--=================== register end====================-->
            </div>
        </div>
      <!--=================== content body end ====================-->
    </div>
</div>

<?php scripts(); ?>
</body>
</html>