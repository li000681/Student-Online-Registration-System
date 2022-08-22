<!DOCTYPE html>
<?php 

$Log= !(isset($_SESSION["logged"]))?"Log In":"Log Out";

?>
<html lang="en" style="position: relative; min-height: 100%;">
<head>
	<title>Online Course Registration</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body style="padding-top: 5rem; margin-bottom: 70px;">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top " >
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        
            <a class="navbar-brand"  href="http://www.algonquincollege.com">
              <img src="Common/img/AC.png" 
                   alt="Algonquin College" style="max-width:100%; max-height:100%;"/>
          </a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
            
          </button>
              
     
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="active nav-link" href="Index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="CourseSelection.php">Course Selection</a></li>
            <li class="nav-item"><a class="nav-link" href="CurrentRegistration.php">Current Registration</a></li>
            <li class="nav-item"><a class="nav-link" href="Profile.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="Complete.php"><?php echo $Log?> </a></li>          
          </ul>
        </div>
      </div>  
    </nav>
