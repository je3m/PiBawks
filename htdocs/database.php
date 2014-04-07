<?php
	include("db_conf.php");
	
	class Database {
		public static $con;
		public static function connect() {
			self::$con = mysqli_connect(DB_ADDR, DB_USER, DB_PASS, DB_NAME);
			if (self::$con->connect_error)
				die("Could not connect to database.");
		}
		public static function disconnect() {
			self::$con->close();
		}

		public static function checkSong($filePath) {
			$filePath = explode(DIR_SEPARATOR, $filePath);
			$fileName = $filePath[count($filePath) - 1];

			$stmt = self::$con->prepare("SELECT * FROM songs WHERE file_path LIKE ?");
			if (!$stmt)
				die("BAD SQL in checkSong.");

			$lol = "%" . $fileName;
			$stmt->bind_param("s", $lol);
			$stmt->execute();
			$stmt->store_result();

			$file_exists = $stmt->num_rows;
			$stmt->close();


			return (!$file_exists); 
		}
		//returns true if file did not exist, false otherwise
		public static function uploadSong($songName, $songArtist, $songGenre, $file_path){
			if (self::checkSong($file_path)){

				$sql = "INSERT INTO songs (name, artist, genre, file_path) VALUES (?, ?, ?, ?);"; 
				$stmt = self::$con->prepare($sql);
				if(!$stmt)
					die("BAD SQL in uploadSong.");
				
				$stmt->bind_param("ssss", $songName, $songArtist, $songGenre, $file_path);
				$stmt->execute();
				$stmt->close();
				return true;
			} else {

				
				echo "File already exists";
				return false;
			}
		}

		//used to insert a song to the queue
		public static function queueSong($songID){
			$stmt = self::$con->prepare("SELECT * FROM queue WHERE songID = ?");
			if (!$stmt)
				die("BAD SQL in queue.");

			
			$stmt->bind_param("i", $songID);
			$stmt->execute();
			$stmt->store_result();

			$file_exists = $stmt->num_rows;
			$stmt->close();

			if($file_exists){
				
				return false;
			} else{
				$sql = "INSERT INTO queue (songID) VALUES (?);"; 
				$stmt = self::$con->prepare($sql);
				if(!$stmt)
					die("BAD SQL in uploadSong.");
				
				$stmt->bind_param("i", $songID);
				$stmt->execute();
				$stmt->close();
				

			}
			return true;
		}

		public static function listSongs(){
			$stmt = self::$con->prepare("SELECT id, name, artist, genre, file_path FROM songs");
			$stmt->execute();
			$stmt->bind_result($song_id, $song_name, $song_artist, $song_genre, $file_path);


			echo '<div class="container">
			    <div class="row">
			        <div class="col-lg-12">
			            <div class="page-header">
			                <h1>JUKEBAWKS</h1>
			            </div>
			          
			        </div>
			    </div>
			    <div class="row">
			        <div class="col-lg-12">
			            <h3>Songs \'n\' st00f</h3>
			        </div>
			    </div>
			    <div class="row">
			        <div class="col-lg-4 col-lg-offset-4">
			            <input type="search" id="search" value="" class="form-control" placeholder="Search">
			        </div>
			    </div>
			    <div class="row">
			        <div class="col-lg-12">
			            <table class="table" id="table">
			                <thead>
			                    <tr>
			                        <th>Song Name</th>
			                        <th>Artist</th>
			                        <th>Genre</th>
			                        <th>add</th>
			                    </tr>
			                </thead>

			                <tbody>';
			                    
			          			while($stmt->fetch()){
			          			echo '

			          			<tr>
									<td>'.$song_name.'</td>
									<td>'.$song_artist.'</td>
									<td>'.$song_genre.'</td>
									<td><form action="" method="POST">
											<button type="submit" name="add" value="'.$song_id.'">add</button>
										</form>
									</td>
									<td><form method="get" action="' . $file_path . '">
											<button type="submit">Download!</button>
										</form>
									</td>
								</tr>
								';
								}

							echo '
			                </tbody>
			            </table>
			            <hr>
			        </div>
			        
			     </div>
			     <script src="//rawgithub.com/stidges/jquery-searchable/master/dist/jquery.searchable-1.0.0.min.js"></script>
		     
			     ';

			//     <div class=\"row\">
			//         <div class=\"col-lg-12\">
			//             <h3>Non-table example</h3>
			//         </div>
			//     </div>
			//     <div class=\"row\">
			//         <div class=\"col-lg-4 col-lg-offset-4\">
			//             <input type=\"search\" id=\"container-search\" value=\"\" class=\"form-control\" placeholder=\"Search...\">
			//         </div>
			//     </div>
			// </div>
			// <div class=\"container\" id=\"searchable-container\">
			//     <div class=\"row row-padding\">
			//         <div class=\"col-xs-4\">Col 1</div>
			//         <div class=\"col-xs-4\">Col 2</div>
			//         <div class=\"col-xs-4\">Col 3</div>
			//     </div>
			//     <div class=\"row row-padding\">
			//         <div class=\"col-xs-4\">Another row</div>
			//         <div class=\"col-xs-4\">With some</div>
			//         <div class=\"col-xs-4\">Other data</div>
			//     </div>
			//     <div class=\"row row-padding\">
			//         <div class=\"col-xs-4\">Lorem</div>
			//         <div class=\"col-xs-4\">Ipsum</div>
			//         <div class=\"col-xs-4\">Dolor</div>
			//     </div>
			//     <div class=\"row row-padding\">
			//         <div class=\"col-xs-4\">Foo</div>
			//         <div class=\"col-xs-4\">Bar</div>
			//         <div class=\"col-xs-4\">Baz</div>
			//     </div>
			// </div>
			// <script src=\"//rawgithub.com/stidges/jquery-searchable/master/dist/jquery.searchable-1.0.0.min.js\"></script>
			// ";

			// echo"
			// <table>
			// <tr>
			// <th>Title</th>
			// <th>Artist</th>
			// <th>Genre</th>
			// <th>Add to Queue</th>
			// </tr>
			// ";
			// while($stmt->fetch()){
				

			// // 	echo'
			// 	<tr>
			// 		<td>'.$song_name.'</td>
			// 		<td>'.$song_artist.'</td>
			// 		<td>'.$song_genre.'</td>
			// 		<td><form action="" method="POST">
			// 				<button type="submit" name="add" value="'.$song_id.'">add</button>
			// 			</form>
			// 		</td>
			// 	</tr>
			// 	';
			// }
			// // 	echo'
			// 	</table>
			// 	';
				$stmt ->close();
		}
		public static function listQueue(){

		}
	}
	
?>





