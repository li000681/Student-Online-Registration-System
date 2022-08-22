<?php
   
     
    session_start();
    extract($_POST);
    
    $errorName="";$errorPassword="";$errorPasswordR="";$errorPhoneNumber="";$errorStudentId="";
    if (isset($_POST["btnSubmit"]))
    {
    ValidateId($studentId, $errorStudentId);
    ValidateName($name,$errorName);
    ValidatePassword($password,$errorPassword);
    ValidatePasswordR($passwordR,$password,$errorPasswordR);
    ValidatePhoneNumber($phoneNumber, $errorPhoneNumber);
    
    $_SESSION["studentId"]=$studentId;
    $_SESSION["name"]=$name;
    $_SESSION["password"]=$password;
    $_SESSION["passwordR"]=$passwordR;
    $_SESSION["phoneNumber"]=$phoneNumber;
    
    
    if($errorName==""&&$errorPassword==""&&$errorPhoneNumber==""&&$errorPasswordR==""&& $errorStudentId==""){
        $_SESSION["signup"]=true;
        $hashPassword= password_hash($password, PASSWORD_BCRYPT);
        require 'PDO.php';
        $sql=$pdo->prepare('insert into Student(StudentId,Name,Phone,Password)values(?,?,?,?)');
        $sql->execute(array($studentId,$name,$phoneNumber,$hashPassword));
//        $resultSet=$pdo->query($sql);
        $_SESSION["logged"]=true;
        header("Location: CourseSelection.php");
        exit();
        
    }
    }
    if (isset($_POST["btnClear"]))
    {
        $_SESSION["studentId"]="";
        $_SESSION["name"]="";
        $_SESSION["password"]="";
        $_SESSION["passwordR"]="";
        $_SESSION["phoneNumber"]="";
        
        header("Location: NewUser.php");
    }
    $nstudentId=isset($_SESSION["studentId"]) ?$_SESSION["studentId"]: '';
    $nname=isset($_SESSION["name"]) ?$_SESSION["name"]: '';
    $npassword=isset($_SESSION["password"]) ?$_SESSION["password"]: '';
    $nphoneNumber=isset($_SESSION["phoneNumber"]) ?$_SESSION["phoneNumber"]: '';
    $npasswordR=isset($_SESSION["passwordR"]) ?$_SESSION["passwordR"]: '';
    
    
    
    function ValidateId(&$studentId,&$errorStudentId){
            if(!trim($studentId)){
                $errorStudentId="Student Id can not be blank";
            }else{
                require_once 'PDO.php';
                $sql=$pdo->prepare('select StudentId from Student where StudentId=?');
                $sql->execute(array($studentId));
//                $resultSet=$pdo->query($sql);
                
                $row = $sql->fetch(PDO::FETCH_ASSOC);
                if($row){
                    $errorStudentId="A student with this Id has already signed up";
                }
                    
                
                

            }
        }
    function ValidateName(&$name,&$errorName){
            if(!trim($name)){
                $errorName="Name can not be blank";
            }
        }
        
        function ValidatePhoneNumber(&$phoneNumber,&$errorPhoneNumber){
            $phoneNumberRegex = "/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/";

            if(!trim($phoneNumber)){
                $errorPhoneNumber="Phone Number can not be blank";
            }elseif (!preg_match($phoneNumberRegex, $phoneNumber))
		{
                    $errorPhoneNumber="incorrect Phone Number ";
		}
        }
        function ValidatePassword(&$password,&$errorPassword){
            $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{6,}$/";

            if(!trim($password)){
                $errorPassword="Password can not be blank";
                
            }elseif (!preg_match($passwordRegex, $password))
		{
                    $errorPassword="incorrect Password";
                    
		}
        }
        function ValidatePasswordR(&$passwordR,&$password,&$errorPasswordR){
 //           $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";

            if(!trim($passwordR)){
                $errorPasswordR="Repeate Password can not be blank";
                
            }elseif ($passwordR!=$password)
		{
                    $errorPasswordR="Repeate password must be equal wtih password";
                    
		}
        }
include("./common/header.php"); 
?>
<div class="container col-md-offset-2 col-md-10 mt-4">
    <h1 class="col-md-8">Sign Up </h1><br>
   
    
     <form  method="post">  
                    <div class="row mb-2 fw-bold">
                      <label class="col-md-2 col-form-label" for="studentId">Student Id: </label>
                      <div class="col-md-3">
                          <input class="form-control" type = "text" id="studentId" name = "studentId" value="<?php echo $nstudentId ?>" />
                      </div>
                      <label class="col-md-6 col-form-label text-danger" id="errorStudentId" ><?php echo $errorStudentId ?><label>
                    </div>
                    <div class="row mb-2 fw-bold">
                      <label class="col-md-2 col-form-label" for="name">Student Name: </label>
                      <div class="col-md-3">
                          <input class="form-control" type = "text" id="name" name = "name" value="<?php echo $nname ?>" />
                      </div>
                      <label class="col-md-3 col-form-label text-danger" id="errorName"><?php echo $errorName ?><label>
                  </div>
                 
        
        
                  <div class="row mb-2 fw-bold">
                      <label class="col-md-2 col-form-label" for="phoneNumber">Phone Number:<br/>nnn-nnn-nnnn </label>
                      <div class="col-md-3">
                          <input class="form-control" type = "text" id="phoneNumber" name = "phoneNumber" value="<?php echo $nphoneNumber ?>"/>
                      </div>
        
                      <label class="col-md-3 col-form-label text-danger" id="errorPhoneNumber" ><?php echo $errorPhoneNumber?><label>       
                    
                  </div>
                  <div class="row mb-2 fw-bold">
                      <label class="col-md-2 col-form-label" for="password">Password: </label>
                      <div class="col-md-3">
                          <input class="form-control" type = "password" id="password" name = "password" value="<?php echo $npassword?>"/>
                      </div>
                      
                      <label class="col-md-3 col-form-label text-danger " id="errorPassword" ><?php echo $errorPassword?><label>       
                    
                  </div>
                  <div class="row mb-2 fw-bold">
                      <label class="col-md-2 col-form-label" for="passwordR">Password: </label>
                      <div class="col-md-3">
                          <input class="form-control" type = "password" id="passwordR" name = "passwordR" value="<?php echo $npasswordR?>"/>
                      </div>
                      
                      <label class="col-md-3 col-form-label text-danger " id="errorPasswordR" ><?php echo $errorPasswordR?><label>       
                    
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
  $("#name").change(function(){
        $("#errorName").hide();
  });
  $("#phoneNumber").change(function(){
        $("#errorPhoneNumber").hide();
  });
  $("#password").change(function(){
        $("#errorPassword").hide();
  });
  $("#passwordR").focus(function(){
        $("#errorPasswordR").hide();
  });

</script>
<?php include('./common/footer.php'); ?>
