package pibawks;

import java.util.Scanner;

public class CommandListener implements Runnable{
	private Scanner scan = new Scanner(System.in);
	
	public void run() {
		while(true){
			System.out.println("Enter a command if you wanna.");
			switch (scan.next()){
			
			case "fix_songs":
				Songs.fix();
				break;
				
			case "wipe_queue":
				Queue.wipeQueue();
				break;
				
			case "skip_song":
				MusicPlayer.skipSong(PiBawks.getQueue());
				break;
				
			case "?":
				System.out.println("possible commands: \n" +
						"fix_songs \t wipes the mySQL database and sync all songs back into it \n" +
						"wipe_queue \t empties the queue + \n" +
						"skip_song \t Skips the current song");
				break;
				default:
					System.out.println("That is not a valid command");
					
			}
		}
	}
	
	
}
