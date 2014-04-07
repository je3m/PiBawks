<?php
	session_start();

	include 'getID3-1.9.7/getid3/getid3.php';
	include 'database.php';

	Database::connect();

	switch ($_GET["a"]) {
		case "u":
			execute_upload();
			break;
		case "p":
			echo json_encode($_SESSION);
			break;
		default:
			die("lol, troll.");
	}

	function execute_upload() {
		$allowedExts = array("mp3");

		$getid3 = new getid3();

		$msg = "";
		$errorCode = 0;
		if (!isset($_FILES["file"])) {
			$msg .= "Lol, troll.";
			$errorCode = -1; // got trolled (no files)
			die("-1");
		} else {
			for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

				//prep for upload
				$fileName = $_FILES["file"]["name"][$i];
				if ($fileName == "")
					die("-1");

				$temp = explode(".", $fileName);
				$extension = end($temp);
				$newFilePath = UPLOAD_DIR . $fileName;
				$tmpFile = $_FILES["file"]["tmp_name"][$i];

				if(($_FILES["file"]["type"][$i] == "audio/mp3") && in_array($extension, $allowedExts)){
					if ($_FILES["file"]["error"][$i]) {
				    	$msg .= "Error: " . $_FILES["file"]["error"][$i] . "<br/>";
				    	$errorCode = 3; // One or more files is weird
				    	break; // <- TEMPORARY; there to stop upload if error
			    	} else{
						//prep for databasing
						$fileInfo = $getid3->analyze($tmpFile);

						if($fileInfo['tags']['id3v2']['title'][0])
							$songName = $fileInfo['tags']['id3v2']['title'][0];
						else
							$songName = $fileName;


						if($fileInfo['tags']['id3v2']['artist'][0])
							$artistName = $fileInfo['tags']['id3v2']['artist'][0];
						else
							$artistName = "Unknown";


						if($fileInfo['tags']['id3v2']['genre'][0])
							$genre = $fileInfo['tags']['id3v2']['genre'][0];
						else
							$genre = "Unknown";

						$msg .= $songName . " " . $artistName . " " . $genre;
						//databasing it
						if(Database::uploadSong($songName, $artistName, $genre, $newFilePath)){
							move_uploaded_file($_FILES["file"]["tmp_name"][$i], $newFilePath);

							$msg .= "moving... ";			

						} else {
							$errorCode = 2; // One or more files already exist in database
							break; // <- TEMPORARY; there to stop upload if error
						}
			  		
					}
				} else{
					$msg .= "bad file type";
					$errorCode = 1;
					break; // <- TEMPORARY; there to stop upload if error
				}
			}
		}
		echo $msg;
		echo "<br/>";
		echo "\n";
		echo $errorCode;
	}

	Database::disconnect();		
?>