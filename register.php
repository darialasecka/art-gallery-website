<?php 
require_once('database.php');
require_once('add.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// initializing variables
$nickname = $name = $lastname = $age = $email = "";
$nickErr = $emailErr = $pswr1Err = $pswr2Err = "";

// connect to the database
$conn = connect();

// REGISTER USER
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $check = true;
  // receive all input values from the form
  $nickname = check_input($_POST['nickname']);
  $name = check_input($_POST['name']);
  $lastname = check_input($_POST['lastname']);
  $email = check_input($_POST['email']);
  $age = check_input($_POST['age']);
  $password_1 = $_POST['password_1'];//nie sprawdzm inputów, bo znaki specjalne powinny móc być w bazie danych, a jeśli nie to później ogarnę
  $password_2 = $_POST['password_2'];

  if ($password_1 != $password_2){
    $pswr2Err = "Hasła sie nie zgadzają";
    $check = false;
  }

  // first check the database to make sure a user does not already exist with the same nickname and/or email
  $stmt = $conn->prepare("SELECT * FROM person WHERE nickname=:nickname OR email=:email LIMIT 1");
  $stmt->bindValue(":nickname", $nickname, PDO::PARAM_STR);
  $stmt->bindValue(":email", $email, PDO::PARAM_STR);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $user = $rows;
  
  if ($user) { // if user exists
    if ($user[0]['nickname'] === $nickname) {
      $nickErr = "Nick jest już zajęty";
      $check = false;
    }
    if ($user[0]['email'] === $email) {
      $emailErr = "Email jest już zajęty";
      $check = false;
    }
  }

  // Finally, register user if there are no errors in the form
  if ($check) {
    $password = password_hash($password_1, PASSWORD_DEFAULT); //encrypt the password before saving in the database
    //add_person($nickname, $name, $lastname, $email, $age, $password){ //działa
    add_person($nickname, $name, $lastname, $email, $age, $password);
    /*$query = "INSERT INTO users (nickname, email, password) 
          VALUES('$nickname', '$email', '$password')";
    mysqli_query($db, $query);*/ //a razie nie chcemy wrzucać tego do bazy danych xd
    $_SESSION['nickname'] = $nickname;
    $_SESSION['success'] = "Jesteś zalogowany";
    $check = false;
    header('location: index.php');//przekierowanie a stronę główną
  }
}
?>


<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php head("Rejestracja"); ?>

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
              <div style='color: black; font-size: 50px;'>Rejestracja</div>
            <div class="container" style="font-size: 18px; padding: 20px;">
                    
                
                
                <form method="post">
                  <!-- https://www.w3schools.com/bootstrap/bootstrap_forms_inputs.asp -->
                  <input type="text" class="form-control" id="nickname" placeholder="Nick" name="nickname" required>
                  <span class="error" style="color: red; font-size: 15px;"><?php echo $nickErr;?></span>
                  <input type="text" class="form-control" id="name" placeholder="Imię" name="name" required>
                  <input type="text" class="form-control" id="lastname" placeholder="Nazwisko" name="lastname" required>
                  <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                  <span class="error" style="color: red; font-size: 15px;"><?php echo $emailErr;?></span>
                  <!-- wiek przerobimy na datę urodzenia, i w bazie danych, alo będziemy trzymać tą datę, albo przeliczymy wiek xd -->
                  <input type="number" class="form-control" id="age" placeholder="Wiek (nie jest wymagany)" name="age"> 
                  <input type="password" class="form-control" id="password_1" placeholder="Hasło" name="password_1" required>
                  <span class="error" style="color: red; font-size: 15px;"><?php echo $pswr1Err;?></span>
                  <input type="password" class="form-control" id="password_2" placeholder="Powtórz hasło" name="password_2" required>
                  <span class="error" style="color: red; font-size: 15px;"><?php echo $pswr2Err;?></span>
                  
                  <div class="form-inline justify-content-end" method="post">
                    <button class="btn-sm btn-dark btn-primary float-xs-right text-white" type="submit" name="insert">Załóż konto</button>
                  </div>
                  <p>
                    Masz już konto? <a style="font-size: 15px;" href="login.php">Zaloguj się</a>
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