<?php

require_once("../../config.php");
	$database="if15_jaagsii";
	$mysqli=new mysqli($servername, $username, $password, $database);
	
	$email_error = "";
	$password_error = "";
	$username_error = "";
	$create_email_error = "";
	$create_password_error = "";
	$create_username_error = "";
	
	$email = "";
	$password = "";
	$username = "";
	$create_email = "";
	$create_password = "";
	$create_username = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST["login"])){

			if ( empty($_POST["username"]) ) {
				$username_error = "See väli on kohustuslik";
			}else{
				$username = cleanInput($_POST["username"]);
			}

			if ( empty($_POST["password"]) ) {
				$password_error = "See väli on kohustuslik";
			}else{
				$password = cleanInput($_POST["password"]);
			}
			if($password_error == "" && $username_error == ""){
				echo "Võib sisse logida! Kasutajanimi on ".$username." ja parool on ".$password;
			}
			$password_hash=hash("sha512", $password);
			
			$stmt=$mysqli->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
				$stmt->bind_param("ss", $email, $password_hash);
				
				$stmt->bind_result($id_from_db, $email_from_db);
				$stmt->execute();
				
				if($stmt->fetch()){
					echo "kasutaja id=".$id_from_db;
				}else{
					echo "wrong email or password";
				}
				$stmt->close();
			}

		}
    if(isset($_POST["create"])){
			if ( empty($_POST["create_username"]) ) {
				$create_username_error = "See väli on kohustuslik";
			}else{
				$create_username = cleanInput($_POST["create_username"]);
			}

			if ( empty($_POST["create_email"]) ) {
				$create_email_error = "See väli on kohustuslik";
			}else{
				$create_email = cleanInput($_POST["create_email"]);
			}

			if ( empty($_POST["create_password"]) ) {
				$create_password_error = "See väli on kohustuslik";
			} else {
				if(strlen($_POST["create_password"]) < 8) {
					$create_password_error = "Peab olema vähemalt 8 tähemärki pikk!";
				}else{
					$create_password = cleanInput($_POST["create_password"]);
				}
			}

			if(	$create_username_error == "" && $create_password_error == "" &&	$create_email_error == ""){
				echo "Võib kasutajat luua! Kasutajanimi on ".$create_username, "E-post on" .$create_email;
				
				$password_hash=hash("sha512", $create_password);
				echo"<br>";
				echo $password_hash;
				
				$stmt=$mysqli->prepare("INSERT INTO user_sample (username, email, password)VALUE(?, ?, ?)");
				
				echo $mysqli->error;
				echo $stmt->error;

				$stmt->bind_param("sss", $create_email, $create_username, $password_hash);
				$stmt->execute();
				$stmt->close();
      }
      }
	  
	  function cleanInput($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
	  }
	$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
?>
<?php
	$page_title = "Login leht";
	$file_name = "login.php";
?>
<?php require_once("../header.php"); ?>
	<h2>Login</h2>
	 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
  	<input name="username" type="username" placeholder="Kasutajanimi" value="<?php echo $username; ?>"> <?php echo $username_error; ?><br><br>
  	<input name="password" type="password" placeholder="Parool" value="<?php echo $password; ?>"> <?php echo $password_error; ?><br><br>
  	<input type="submit" name="login" value="Log in">
  </form>
	
	
	<h2>Create user</h2>
	 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input name="create_username" type="username" placeholder="Kasutajanimi" value="<?php echo $create_username; ?>"> <?php echo $create_username_error; ?><br><br>
	<input name="create_email" type="email" placeholder="E-post" value="<?php echo $create_email; ?>"> <?php echo $create_email_error; ?><br><br>
  	<input name="create_password" type="password" placeholder="Parool"> <?php echo $create_password_error; ?> <br><br>
	<input type="submit" name="create" value="Create user">
  </form>
<body>
</html>
	 
<?php require_once("../footer.php"); ?>