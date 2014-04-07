package pibawks;

import java.io.File;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import org.farng.mp3.MP3File;
import org.farng.mp3.id3.ID3v1;

public final class Songs {
	private static File filePath = new File("/media/songs/");
	
	
	/**
	 * syncs the database with the directory
	 */
	public static void fix(){
		setFilePath();
		int i = 0; //counts # of songs
		
		//wiping database
		try{
			PreparedStatement stmt = Database.getConnection().prepareStatement("TRUNCATE songs");
			stmt.execute();
		} catch (SQLException e){
			e.printStackTrace();
		}
		
		//add them back
		for(File f: filePath.listFiles()){
			ID3v1 mp3;
			try {
				mp3 = new MP3File(f).getID3v1Tag();
				
				String name = mp3.getTitle(),
				artist = mp3.getArtist(),
				file_path = f.toString(),
				genre = Genre.getGenreByByteId((mp3.getGenre())).toString();
		
				System.out.println("Name: "+ name + "\n" +
						"artist: " + artist + "\n" +
						"genre: " + genre +"\n" + 
						"path: " + file_path.replace("\\", "\\\\") + "\n");
				
				String sql = "INSERT INTO songs (name, artist, genre, file_path) VALUES (?, ?, ?, ?);";
				
				
				System.out.println(sql);
				PreparedStatement stmt = Database.getConnection().prepareStatement(sql);
				stmt.setString(1, name);
				stmt.setString(2, artist);
				stmt.setString(3, genre);
				stmt.setString(4, file_path);
				
				stmt.execute();
				i++;
				
				
			} catch (SQLException e){
				e.printStackTrace();
			} catch (Exception e) {
				
			}
			
			
		}
		System.out.println(i + " songs synced with the database!");
		
	}
	
	
	private static void setFilePath(){
		try {
			
			//getting the filepath
			PreparedStatement stmt = Database.getConnection().prepareStatement("SELECT file_path FROM songs where id =1"); 
			ResultSet results = stmt.executeQuery();
			if(results.next()){
				filePath = new File(results.getString("file_path")).getParentFile();//TODO: 
				
			
			System.out.print("Filepath set to: " + filePath.toString());
			
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
