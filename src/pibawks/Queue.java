/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package pibawks;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;


 
    
/**
 *
 * @author Jim
 */
public class Queue {
	
    
     
    private static int currentSongID;
    
    public static int getCurrentSongID(){
    	
    	return currentSongID;
    }
    
    public Queue(){
           
        setCurrentSongID();
    }
        
     /**
     * 
     * @return The songID of the top song in the queue
     */
    public void setCurrentSongID(){
        int songID = -1;
        try {
//            Class.forName(driver).newInstance();
            
            PreparedStatement stmt = Database.getConnection().prepareStatement("SELECT songID FROM queue LIMIT 1;");
            
            ResultSet result = stmt.executeQuery();
            
            if(result.next()){
            	
                songID = result.getInt("songID");
            }
            
            
            
        } catch (Exception e) { 
            e.printStackTrace();
        }
        
        if(songID == -1)
        	songID = randomSong();
        
        
        currentSongID = songID;
        System.out.println("Current song: " + getSongName(currentSongID));
    }

    /**
     * deletes the song off the top of the queue
     */
    public void popOffQueue(int songID){
        try{
            PreparedStatement stmt = Database.getConnection().prepareStatement("DELETE FROM queue WHERE songID =" + songID);
            stmt.execute();
        } catch(SQLException e){
            System.out.println(e.getMessage());
        }
        setCurrentSongID();
        
    }
    
    
    /**
     * 
     * @param songID id of the song to search for
     * @return File path of the song
     */
    public static String getSongFilePath(int songID){
    	String sql = "SELECT file_path FROM songs where id = " + songID;
    	try{
    		
    		PreparedStatement stmt = Database.getConnection().prepareStatement(sql);
    		ResultSet results = stmt.executeQuery();
    		
    		if(results.next()){
    			
    			return results.getString("file_path");
    		} 
    	} catch (SQLException e){
    		System.out.println(e.getMessage());
    		return "Could not get file path for song " + songID;
    	}
    	
    	return "weird stuff " + songID;
    }
    
   
    /**
     * rolls a random song from the songs database
     * @return songID
     */
    public int randomSong(){
    	System.out.println("rolling a random song...");
    	try{
    		PreparedStatement stmt = Database.getConnection().prepareStatement("SELECT id FROM songs ORDER BY RAND() LIMIT 1;");
    		ResultSet results = stmt.executeQuery();
    		
    		if(results.next()){
    			System.out.println("random'd song: " + getSongName(results.getInt("id")));
    			queueSong(results.getInt("id"));
    			return results.getInt("id");
    		}
    	
    	}catch(SQLException e){
    		System.out.println(e.getMessage());
    	}
    	return -1;
    }
    
    /**
     * adds a song to the queue
     * @param songID id of the song to add
     */
    public void queueSong(int songID){
    	try{
    		PreparedStatement stmt = Database.getConnection().prepareStatement("INSERT INTO queue (songID) VALUES(\"" +songID +"\");");
    		stmt.execute();
    		
    	} catch(SQLException e){
    		System.out.println(e.getMessage());
    		System.out.print("on queuing of song");
    	}
    }
    /**
     * closes the sql connection 
     */
    public void closeConnection(){
        try{
        	Database.getConnection().close();
        } catch(SQLException e){
            System.out.println(e.getMessage());
        }
    }
    
    /**
     * 
     * @param songID to search for
     * @return name of the song
     */
    public String getSongName(int songID){
    	try{
    		PreparedStatement stmt = Database.getConnection().prepareStatement("SELECT name FROM songs WHERE id =" + songID);
    		ResultSet results = stmt.executeQuery();
    		if(results.next())
    			return results.getString("name");
    	}catch(SQLException e){
    		System.out.println(e.getMessage());
    		
    	}
    	return "error";
    }
    
    /**
     * wipes the queue
     */
    public static void wipeQueue(){
    	try{
    		PreparedStatement stmt = Database.getConnection().prepareStatement("TRUNCATE queue");
    		stmt.execute();
    		System.out.println("Song queue wiped.");
    	} catch(SQLException e){
    		e.printStackTrace();
    	}
    }
}
