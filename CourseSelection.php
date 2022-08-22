<?php
    
    session_start();	
    if(!isset($_SESSION["logged"]))
    {
        header("Location: Login.php");
        exit( );
    }
    include("./common/header.php"); 
    
    
        
    $semesterCode=(isset($_SESSION["semesterSelection"]))? $_SESSION["semesterSelection"]: getFirstOption();
    $studentId=$_SESSION['studentId'];
    $userName=$_SESSION['name'];
    $checked="";
    $registeredHours=calculateCourseHoursInRegistration($studentId,$semesterCode);
    $leftHours=16-$registeredHours;
    $semesterOption= getSemesterOption();
    $tableResult = getCourseListTable($semesterCode, $studentId,$checked);
    $tableHeader="<table class='table col-md-10  mb-2 mt-2' ><tr class='d-flex table-info'><th class='col-1'>Code</th><th class='col-3'>Course Title</th><th class='col-2'>Hours</th><th class='col-2'>Select</th></tr>";
    $tableFoot="</table>";
    $errorCouseSelection="";
    if(isset($_POST['btnSubmit'])){
        $_SESSION["semesterSelection"]=$semesterCode;
        validateSelectedCourse($errorCouseSelection, $registeredHours);
        if($errorCouseSelection==""){
            require 'PDO.php';
            foreach ($_POST['selectedCourse'] as $c){
                $sql = "INSERT INTO registration (StudentId, CourseCode, SemesterCode) VALUES (?,?,?)";
                $pdo->prepare($sql)->execute([$studentId, $c, $semesterCode]);
                
            }
            
            header("Location:CourseSelection.php");
        }
    }elseif (isset($_POST['btnCancel'])){ {
        $_SESSION["semesterSelection"]=$_POST['semesterSelection'];
        header("Location:CourseSelection.php");
    }
}
    function getFirstOption(){
        require 'PDO.php';
        $sql='select SemesterCode from Semester';
        $resultSet=$pdo->query($sql);
        $row=$resultSet ->fetch();
        return $row['SemesterCode'];
    }
    function getSemesterOption(){
        $semesterOption="";
        require 'PDO.php';
        $sql='select SemesterCode,Term,Year from Semester';
        $resultSet=$pdo->query($sql);       
        Foreach($resultSet as $row){
            $semesterOption.="<option value=".$row['SemesterCode']. ( isset($_SESSION['semesterSelection'])&&$_SESSION['semesterSelection'] == $row['SemesterCode'] ? " selected='selected'" : "" ).">".$row['Year']." ". $row['Term']."</option>";
        }
        return $semesterOption;
    }
    function getCourseListTable(&$semesterCode,&$studentId,$checked){
        
        $tableResult="";
        require 'PDO.php';

        $sql="SELECT course.CourseCode,course.Title, course.WeeklyHours,courseoffer.SemesterCode FROM course, cst8257.courseoffer where courseoffer.SemesterCode='$semesterCode' and course.CourseCode=courseoffer.CourseCode and course.CourseCode not in (select registration.CourseCode from registration where registration.SemesterCode='$semesterCode' and registration.StudentId='$studentId')";


        $resultSet=$pdo->query($sql);
//        $resultSet ->fetch(PDO::FETCH_ASSOC);

        Foreach($resultSet as $row){
            if(isset($_POST['selectedCourse'])){
            $checked= in_array($row['CourseCode'], $_POST['selectedCourse'])?"checked":"";
            }
            $tableResult.= "<tr class='d-flex'><td class='col-1'>$row[CourseCode]</td><td class='col-3'>$row[Title]</td><td class='col-2'>$row[WeeklyHours]</td><td class='col-2'><input class='form-check-input' type = 'checkbox' id=$row[CourseCode] name = 'selectedCourse[]' $checked value = $row[CourseCode] /></td></tr>";
                     }
        return $tableResult;
    }
    function validateSelectedCourse(&$errorCouseSelection,&$registeredHours){
        $totalHours= calculateCourseHourse();
        if($totalHours==0){
            $errorCouseSelection="You need select at least one course!";
        }elseif ($totalHours>(16-$registeredHours)) {
            $errorCouseSelection="Your select exceed the max weekly hours!";
    }
        
    }
    function calculateCourseHourse(){
        $totalHours=calculateCourseHoursInRegistration($studentId,$semesterCode);
        require 'PDO.php';
        
       
        if(!empty($_POST['selectedCourse'])){
            foreach ($_POST['selectedCourse'] as $c){
                $sql="select WeeklyHours from course where CourseCode='$c'";
                $resultSet=$pdo->query($sql);
                $row=$resultSet ->fetch();
                $totalHours+=intval($row['WeeklyHours']);
            }
        }
        return $totalHours;
    }
    function calculateCourseHoursInRegistration(&$studentId,&$semesterCode){
        $totalHours=0;
        require 'PDO.php';
        $sql="select CourseCode from registration where StudentId='$studentId' and SemesterCode='$semesterCode'";
        $resultSet=$pdo->query($sql);
        if($resultSet->rowCount()>0){
            foreach ($resultSet as $c){
                $sql="select WeeklyHours from course where CourseCode='$c[CourseCode]'";
                $resultSet=$pdo->query($sql);
                $row=$resultSet->fetch();
                $totalHours+=intval($row['WeeklyHours']);
            }
        }
        return $totalHours;
    }

    include("./common/header.php"); 
?>

<div class="container col-md-offset-2 col-md-10 mt-4 mb-2">
    <h2 class="col-md-8">Course Selection</h4><br>
   
    <p>Welcome <?php echo $_SESSION['name']?> !(not you? change user <a href="Login.php">here</a>)</p>
    <p>You have registered <?php echo $registeredHours?> hours for the selected semester.</p>
    <p>You can register <?php echo $leftHours?> more hours of course(s) for the semester.</p>
    <p>Please note that the courses you have registered will not be displayed in the list</p>
    
     <form  method="post">
         <div class="row col-md-2 col-md-offset-8 ">
             
         <select class="form-select "  aria-label="select semester" name="semesterSelection" id="semesterSelection">
            <?php echo $semesterOption?>
          </select>
         </div>
         <div>
         <label class="col-md-3 col-form-label text-danger" id="errorCouseSelection" ><?php echo $errorCouseSelection ?><label>
         </div>
       <?php echo $tableHeader ?>
    <?php echo $tableResult ?>
    <?php echo $tableFoot ?>
         <div class="col-md-4 col-md-offset-6">
             <input type ="submit" class=" me-3 btn btn-success" name='btnSubmit' id="btnSubmit" value="Submit">
             <input type ="submit" class=" btn btn-primary" name="btnCancel" id="btnCancel" value="Cancel">
        </div>
        
    </form>
    
</div>
<script>
//    $("#semesterSelection").change(function(){
//        var formStr = '<form action="course_select.php"  >';
//        formStr += '<input type="hidden" name="SemesterSelected" value="';
//        formStr += this.value;
//        formStr += '"></form>';
//        $(formStr).appendTo($('body')).submit();
//        });
    $("#semesterSelection").change(function(){
        $("#btnCancel").click();
  });
    $(".form-check-input").change(function(){
        $("#errorCouseSelection").hide();
    });
  

</script>

<?php include('./common/footer.php'); ?>

