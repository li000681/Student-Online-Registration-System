<?php
    
     session_start();	
    if(!isset($_SESSION["logged"]))
    {
        header("Location: Login.php");
        exit( );
    }else{
        
        header("Location: Logout.php");
        exit( );
    }
    
?>

    

