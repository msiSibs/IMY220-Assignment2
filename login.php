<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	//echo $_GET["loginEmail"];
	//echo $_GET["loginPass"];
	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
	//file upload stuff might have to put at the bottom
	if(isset($_POST['submit'])){

		$target_dir = "gallery/";
		$target_file = $target_dir . basename($_FILES["picToUpload"]["name"]);
		//echo $target_file;
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
		//assignment checks
		if ($_FILES["picToUpload"]["size"] > 1024000) { //1mb filesize
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		if($imageFileType != "jpg"  && $imageFileType != "jpeg") { //check if its jpg/jpeg
		echo "Sorry, only JPG, JPEG files are allowed.";
		$uploadOk = 0;
		}
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		  // if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["picToUpload"]["tmp_name"], $target_file)) {
				//echo "The file ". basename( $_FILES["picToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}

		$id = $_POST["user_id"];
		$filename = basename($_FILES["picToUpload"]["name"]);
		$sql = "INSERT INTO tbgallery ( filename , user_id ) VALUES ( '$filename' , '$id' )";
		$result = mysqli_query($mysqli,$sql);
		
	}
	

?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Name Surname">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					$user_id = $row['user_id'];
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";

							
									
				
					echo 	"<form action='login.php' method='POST' enctype='multipart/form-data'>

						<div class='form-group' >
							<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
							<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
							<input type='hidden' name='loginEmail' id='loginEmail' value= ". $_POST['loginEmail']." />
							<input type='hidden' name='loginPass' id='loginPass' value= ". $_POST['loginPass'] ." />
							<input type='hidden' name='user_id' id='user_id' value= ". $user_id ." />
						</div>
					</form>";

					$fetchQuery = "SELECT * FROM tbgallery WHERE user_id = '$user_id' ";
					$fresult = $mysqli->query($fetchQuery);
					$frows =  mysqli_num_rows($fresult);
		
					echo '<h1>Image Gallery</h1>
						<div class="row imageGallery"> ';
								
						while($frow = mysqli_fetch_array($fresult)){
		
							echo'<div class="col-3" style="background-image: url(gallery/'. $frow["filename"] .')" ></div>'; 
						}
					echo '</div>';

					
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}

			//new stuff for tbgallery

		?>
		

	</div>
		
	<!--<h1>Image Gallery</h1>
	<div class="row imageGallery">
		<div class="col-3" style="background-image: url(gallery/"  ")" ></div>	
	</div>-->


</body>
</html>