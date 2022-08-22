<?php
    
    session_start(); 	
    
    if(isset($_SESSION["studentId"])){
        $studentId=$_SESSION["studentId"];
    }
    
    $password=isset($_SESSION["password"])?$_SESSION["password"]:'';
    
    $errorPassword="";$errorStudentId="";$errorData="";
    if (isset($_POST["btnSubmit"]))
    {
        extract($_POST);
        $_SESSION["studentId"]=$studentId;
        ValidateId($studentId, $errorStudentId);

        ValidatePassword($password,$errorPassword);
        if($errorPassword==""&&$errorStudentId==""){
            ValidateCorrectMatch($studentId, $password, $errorData);
        }

        




        if($errorData==""&&$errorPassword==""&& $errorStudentId==""){
            $_SESSION["studentId"]=$studentId;

            $_SESSION["password"]=$password;
            $_SESSION["logged"]=true;
    //        echo '<script>';
     //       echo 'window.open("")';
     //       echo '</script>';
            header("Location: CourseSelection.php");
            exit();
        }
    }
    if (isset($_POST["btnClear"]))
    {
        $_SESSION["studentId"]="";
        
        $_SESSION["password"]="";
        
        
        
        header("Location: Login.php");
    }
    $nstudentId=isset($_SESSION["studentId"]) ?$_SESSION["studentId"]: '';
   
    $npassword=isset($_SESSION["password"]) ?$_SESSION["password"]: '';
    
    
    
    
    function ValidateId(&$studentId,&$errorStudentId){
            if(!trim($studentId)){
                $errorStudentId="Student Id can not be blank";
            }
        }
   
    function ValidatePassword(&$password,&$errorPassword){

        if(!trim($password)){
            $errorPassword="Password can not be blank";

        }
    }
    function ValidateCorrectMatch(&$studentId,&$password,&$errorData){
        require_once 'PDO.php';
        $hashPassword= password_hash($password, PASSWORD_BCRYPT);
        $sql=$pdo->prepare('select Password,Name from Student where StudentId=?');
        $sql->execute(array($studentId));
 //       $resultSet=$pdo->query($sql);
        $row = $sql ->fetch(PDO::FETCH_ASSOC);
        if(!$row){
            $errorData="Incorrect student ID!";
        }else if(!password_verify($password, $row["Password"])){
            $errorData="Incorrect password!";
        }else{
            $_SESSION["name"]=$row[Name];
        }
    }
       
include("./common/header.php"); 
?>
<div class="container col-md-offset-2 col-md-10 mt-4">
    <h1 class="col-md-8">Log In </h1><br>
   
    
     <form  method="post">  
         <span class="col-md-9 col-form-label text-danger" id="errorData" ><?php echo $errorData ?></span>
        <div class="row mb-2 fw-bold">
            
          <label class="col-md-2 col-form-label" for="studentId">Student Id: </label>
          <div class="col-md-3">
              <input class="form-control" type = "text" id="studentId" name = "studentId" value="<?php echo $nstudentId ?>" />
          </div>
          <label class="col-md-3 col-form-label text-danger" id="errorStudentId" ><?php echo $errorStudentId ?></label>
        </div>

        <div class="row mb-2 fw-bold">
            <label class="col-md-2 col-form-label" for="password">Password: </label>
            <div class="col-md-3">
                <input class="form-control" type = "password" id="password" name = "password" value="<?php echo $npassword?>"/>
            </div>

            <label class="col-md-3 col-form-label text-danger " id="errorPassword" ><?php echo $errorPassword?><label>       

        </div>

        <div >
        <input type = "submit" class="col-md-1 me-3 btn btn-primary me-4" name="btnSubmit" value = "Submit" />
        <input type ="submit" class="col-md-1 btn btn-success ms-4" name="btnClear" value="Clear">
        </div>
    </form>
</div>
<script>
  
    $("#studentId").change(function(){
        $("#errorStudentId").hide();
  });
  $("#studentId").focus(function(){
        $("#errorData").hide();
  });
  
  $("#password").focus(function(){
        $("#errorPassword").hide();
        $("#errorData").hide();
  });
  

</script>
<?php include('./common/footer.php'); ?>
