<?php
$pdo = null;
function connect() { //działa
	global $pdo;
	$servername = "localhost";
	$username = "root";
	$password = "";
	if ($pdo == null) {
		try {
			$pdo = new PDO("mysql:host=$servername;dbname=picturegallery", $username, $password);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "Tak";
		} catch (Exception $e) {
			trigger_error("Could not connect to database via PDO: ".$e->getMessage(), E_USER_ERROR);
		}
	}
	return $pdo;
}

function add_tag($slug){ //działa
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO tag (slug)
								VALUES (:slug)");
	$sql->bindValue(':slug', $slug);
	$sql->execute();
	echo "Tag został dodany";
}

function add_comment($id, $autor, $added, $content){ //z tego co widziałam w bazie danych, to też działa
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO comment (id, autor, added, content)
								VALUES (:id, :autor, :added, :content)");
	$sql->bindValue(':id', $id);
	$sql->bindValue(':autor', $autor);
	$sql->bindValue(':added', $added);
	$sql->bindValue(':content', $content);
	$sql->execute();
	echo "Komentarz został dodany";
}

function add_person($nickname, $name, $lastname, $email, $age){ //działa
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO person (nickname, name, lastname, email, age)
								VALUES (:nickname, :name, :lastname, :email, :age)");
	$sql->bindValue(':nickname', $nickname);
	$sql->bindValue(':name', $name);
	$sql->bindValue(':lastname', $lastname);
	$sql->bindValue(':email', $email);
	$sql->bindValue(':age', $age);
	$sql->execute();
	echo "Osoba została dodana";
}

function add_picture($id, $image, $name, $autor, $tags, $comments, $description){ //działa, ale ścieżkę trzeba śmiesznie dodawać xd
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO picture (id, image, name, autor, tags, comments, description )
								VALUES (:id, :image, :name, :autor, :tags, :comments, :description)");
	$sql->bindValue(':id', $id);
	$sql->bindValue(':image', $image);
	$sql->bindValue(':name', $name);
	$sql->bindValue(':autor', $autor);
	$sql->bindValue(':tags', $tags);
	$sql->bindValue(':comments', $comments);
	$sql->bindValue(':description', $description);
	$sql->execute();
	echo "Obraz został dodany";
}

function add_gallery($id, $name, $person, $pictures, $created, $latest_update, $tags, $comments, $description){
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO gallery (id, name, person, pictures, created, latest_update, tags, comments, description)
							VALUES (:id, :name, :person, :pictures, :created, :latest_update, :tags, :comments, :description)");
	$sql->bindValue(':id', $id);
	$sql->bindValue(':name', $name);
	$sql->bindValue(':person', $person);
	$sql->bindValue(':pictures', $pictures);
	$sql->bindValue(':created', $created);
	$sql->bindValue(':latest_update', $latest_update);
	$sql->bindValue(':tags', $tags);
	$sql->bindValue(':comments', $comments);
	$sql->bindValue(':description', $description);
	$sql->execute();
	echo "Galleria została stworzoa pomyślnie";
}
?>
