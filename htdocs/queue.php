<!DOCTYPE html>
<html>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>


<script src="jquery.js"></script>
<script src="jquery.searchable.js"></script>
<script src="search.js"></script>
</head>
<body>
<?php	
		include 'database.php';
		Database::connect();
		
		error_reporting(true);

		Database::listSongs();

		if($_POST["add"]){
			if(addSong($_POST["add"])){

				echo "<script type='text/javascript'>alert(\"Song added to queue!\");</script>";
			} else{
				echo "<script type='text/javascript'>alert(\"Song already in queue!\");</script>";
			}

		}

		function addSong($songID){
			return Database::queueSong($songID);
		}
?>
</body>
</html>