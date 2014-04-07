/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package pibawks;

import java.io.FileInputStream;
import java.io.FileNotFoundException;

import javazoom.jl.decoder.JavaLayerException;
import javazoom.jl.player.advanced.AdvancedPlayer;

/**
 *	This class is responsible for playing songs
 * @author Jim
 */
public class MusicPlayer{
	
	
    private static AdvancedPlayer player;
    
    /**
     * Plays a selected song
     * @param filePath
     */
    public void playSong(String filePath){
//    	System.out.println(filePath);
    	try {
			player = new AdvancedPlayer(new FileInputStream(filePath));
			player.play();
			
		} catch (FileNotFoundException e) {
			System.out.println("FILE NOT FOUND");
			e.printStackTrace();
		} catch (JavaLayerException e) {
			
			e.printStackTrace();
		}
    }
    
    public static void skipSong(Queue q){
    	player.close();

    	System.out.println("skipping song...");
    }
}
