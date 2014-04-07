<?php

/* K
SO WE GOT SOME NEW STUFF ALL UP IN HERE.
first off, we need some php.ini stuff set.
session.upload_progress.enabled = On
session.upload_progress.cleanup = Off
session.upload_progress.name = PHP_SESSION_UPLOAD_PROGRESS <- should be by default

Second off, look down at that stuff. yeah.
that's called lol.
You're gonna wanna change dat UPLOAD_DIR to wherever you
want yours to be, and make that DIR_SEPARATOR '\\' if you're
in windows, and leave it as-is in anything else.

And that's about it. so yeah and stuff. Obviously, you're gonna need the new
upload.js.
*/
define("DB_ADDR", "127.0.0.1");
define("DB_USER", "root");
define("DB_PASS", "jim"); 
define("DB_NAME", "jukebawks");
define("DIR_SEPARATOR", "\\"); // for exploding the file path in php
define("UPLOAD_DIR", "C:\\Users\\Jim\\Desktop\\New folder\\"); // upload dir!!!!!
?>