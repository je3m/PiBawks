package pibawks;

import java.sql.DriverManager;
import java.sql.SQLException;

import com.mysql.jdbc.Connection;

public final class Database{
	private static final String url = "jdbc:mysql://localhost:3306/",
	    dbName = "jukebawks", 
	    //driver = "com.mysql.jdbc.Driver",
	    userName = "root", 
	    password = "jim";
	
    public static Connection conn;
    
    
    public static Connection getConnection(){
    	if(conn == null){
    		try{
    			conn =  (Connection) DriverManager.getConnection(url+dbName, userName, password);
    			
    		} catch (SQLException e){
    			e.printStackTrace();
    		}
    		
    	} 
    		return conn;
    }
    
    
    
}
