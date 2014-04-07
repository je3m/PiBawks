<? session_start();?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="indexstylesheet.css">
	<title>Jukebawks</title>
	<script type="text/javascript" src="upload.js"></script>
</head>
<body>

	<form method="POST" enctype="multipart/form-data" name="uploadForm">
		<input type="hidden" value="songUpload" name="<?php echo ini_get('session.upload_progress.name'); ?>"/>
		<table>
			<tr>
				<td>

					<h1>Enter a file to upload</h1>
				</td>
			</tr>
			<tr>
				<td><input type="file" multiple name="file[]" multiple id="file"></td>
			</tr>
			<tr>
				<td><input type="submit" name="submit" value="Submit" id="uploadButton"></td>
			</tr>
		</table>
	</form>
	<form action="queue.php" method="POST">
		<input type="submit" name="submit" value="reload">
	</form>
	<div id="progress-output"></div>
	<!--<style type="text/css">
	@font-face{
		font-family: Adler;
		src: url(Adler.ttf);
	}
	div{
		font-family:myFirstFont;
	}
	</style> -->


</body>
</html>