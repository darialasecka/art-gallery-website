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
//security function xd
function check_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}



//adding to database functions
function add_tag($slug){ //działa
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO tag (slug)
								VALUES (:slug)");
	$sql->bindValue(':slug', $slug);
	$sql->execute();
	//echo "Tag został dodany";
}

function add_comment($autor, $content, $where_is, $where_id){ //działa
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO comment (autor, content, where_is, where_id)
								VALUES (:autor, :content, :where_is, :where_id)");
	$sql->bindValue(':autor', $autor);
	$sql->bindValue(':content', $content);
	$sql->bindValue(':where_is', $where_is);
	$sql->bindValue(':where_id', $where_id);
	$sql->execute();
	//echo "Komentarz został dodany";
}

function add_person($nickname, $name, $lastname, $email, $age, $password){ //działa
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO person (nickname, name, lastname, email, age, password)
								VALUES (:nickname, :name, :lastname, :email, :age, :password)");
	$sql->bindValue(':nickname', $nickname);
	$sql->bindValue(':name', $name);
	$sql->bindValue(':lastname', $lastname);
	$sql->bindValue(':email', $email);
	$sql->bindValue(':age', $age);
	$sql->bindValue(':password', $password);
	$sql->execute();
	echo "Osoba została dodana";
}

function add_picture($image, $name, $autor, $tags, $description){ //działa, ale ścieżkę trzeba śmiesznie dodawać xd
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO picture (image, name, autor, tags, description )
								VALUES (:image, :name, :autor, :tags, :description)");
	$sql->bindValue(':image', $image);
	$sql->bindValue(':name', $name);
	$sql->bindValue(':autor', $autor);
	$sql->bindValue(':tags', $tags);
	$sql->bindValue(':description', $description);
	$sql->execute();
	//echo "Obraz został dodany";
}

function add_gallery($name, $person, $description){ //jeszcze nie testowane
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO gallery (name, person, description)
							VALUES (:name, :person, :description)");
	$sql->bindValue(':name', $name);
	$sql->bindValue(':person', $person);
	$sql->bindValue(':description', $description);
	$sql->execute();
	echo "Galleria została stworzoa pomyślnie";
}

function update_tag_where($tag_slug, $where_is, $where_id){ //jeszcze nie testowane
	$conn = connect();
	$sql = $conn->prepare("INSERT INTO tag_where (tag_slug, where_is, where_id)
							VALUES (:tag_slug, :where_is, :where_id)");
	$sql->bindValue(':tag_slug', $tag_slug);
	$sql->bindValue(':where_is', $where_is);
	$sql->bindValue(':where_id', $where_id);
	$sql->execute();
	//echo "Zupdejtowano informacje w tag_where";
}
?>
