/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package pibawks;


/**
 *This is the main class of the PiBawks project
 * @author Jim
 */
public class PiBawks {
   private static Queue q;
    
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
    	System.out.println("PiBawks on");
        q = new Queue();
        MusicPlayer musicPlayer = new MusicPlayer();
        try{
        	if(args[0] == "fix")
        		Songs.fix();
        }catch(Exception e){
        	System.out.println("not fixing");
        }
        	
        
        Thread commands = new Thread(new CommandListener());
        commands.start();
        
        while(true){
        	musicPlayer.playSong(Queue.getSongFilePath(Queue.getCurrentSongID()));
        	q.popOffQueue(Queue.getCurrentSongID());
        }
    	
        
    }
    
    public static Queue getQueue(){
    	return q;
    }
    
    
}    
    

