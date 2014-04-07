window.onload = initPage;

function initPage() {
	uploadForm = document.forms["uploadForm"];
	uploadButton = document.getElementById("uploadButton");
	uploadButton.onclick = uploadStuff;
}

function requestProgress() {
	//console.log("Initializing progress request...");
	progressRequest = new XMLHttpRequest();
	if (!progressRequest) {
		alert("LOLNOAJAX");
		return;
	}
	progressRequest.open("GET", "upload.php?a=p", true);
	progressRequest.onreadystatechange = function() {
		if (progressRequest.readyState == 4) {
			//console.log("Progress request complete.");
			if (progressRequest.status == 200) {
				//console.log("Error free!");
				//console.log("Response text: " + request.responseText);
				var session = JSON.parse(progressRequest.responseText);
				displayUploadProgress(session);
			}
		}
	}
	progressRequest.send(null);
}

function uploadErrorMessage(code) {
	switch (code) {
		case -1: return "Quit trolling, ya troll!";
		case 0: return "Everythin' good!";
		case 1: return "One or more of your files... ain't what we're looking for.";
		case 2: return "Yeah, about them files... we already have em' (at least one of them).";
		case 3: return "Uh, some weird file error.";
		default: return "LOL, we don't even know what happened!";
	}
}

function displayUploadProgress(session) {
	//console.debug(session);
	var uploadProgress = session.upload_progress_songUpload;
	var contentLength = uploadProgress.content_length;
	var bytesProcessed = uploadProgress.bytes_processed;
	var percentComplete = (bytesProcessed * 100) / contentLength;
	if (percentComplete == 100) {
		clearInterval(rid);
	}
	var text =	'<div class="upload-progress-display">' +
				'<h3>Total Upload Progress</h3>' +
				'<span class="progress-bar-container">' + 
					'<span class="progress-bar" style="width: ' + Math.ceil(percentComplete) * 2 + 'px;">' +
					'</span>' + 
				'</span>' + percentComplete + '%<br/>' +
				bytesProcessed / 1000000 + 'mb / ' + contentLength / 1000000 + 'mb';

	var files = uploadProgress.files;
	text += '<h3>Number of files: ' + files.length + '<br/></h3>';
	for (i = 1; i <= files.length; i++) {
		var file = files[i - 1];
		var fileSpan = '<span>';
		if (file.done) {
			if (file.error)
				fileSpan = '<span class="progress-bad-upload">';
			else
				fileSpan = '<span class="progress-good-upload">';
		}

		text += fileSpan + file.name + '</span><br/>';
	}
	text += '</div>';
	document.getElementById("progress-output").innerHTML = text;
}

function uploadStuff() {
	uploadButton.disabled = true;

	try {
		clearInterval(rid);
		console.log("Restarting progress tracking...");
	} catch (e) {

	}

	rid = setInterval(requestProgress, 500);

	uploadRequest = new XMLHttpRequest();
	if (!uploadRequest) {
		alert("LOLNOAJAX");
		return;
	}
	uploadRequest.open("post", "upload.php?a=u", true);
	uploadRequest.onreadystatechange = function() {
		if (uploadRequest.readyState == 4) {
			console.log("Upload request complete.");
			if (uploadRequest.status == 200) {
				var response = uploadRequest.responseText;

				console.log("Upload request successful.");
				console.log("Response: " + response);

				var lines = response.split("\n");
				var errorCode = parseInt(lines[lines.length - 1]);
				console.log("Error code: " + errorCode);
				console.log("Interpreted status message: " + uploadErrorMessage(errorCode));
				
				if (errorCode) {
					clearInterval(rid);
					document.getElementById("progress-output").innerHTML =
						'<span style="color: red">' + uploadErrorMessage(errorCode) + '</span>';
				}

				resetUploadForm();
			}
		}
	}
	uploadRequest.send(new FormData(uploadForm));

	return false;
}

function resetUploadForm() {
	uploadForm.reset();
	uploadButton.disabled = false;
}