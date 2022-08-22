<?php
   
   session_start();
    if(!isset($_SESSION["logged"]))
    {
        header("Location: Login.php");
        exit( );
    }
    
    require_once 'Model/Semester.php';
    require_once 'Model/Course.php';
  

    $studentId=$_SESSION['studentId'];
    if(!isResultInRegistration($studentId)){
        
    }
    $tableResult = getRegistratedCourseTableResult($studentId);
    $tableHeader="<table class='table col-md-10  mb-2 mt-2' ><tr class='d-flex table-info'><th class='col-1'>Year</th><th class='col-1'>Term</th><th class='col-1'>Course Code</th><th class='col-2'>Course Title</th><th class='col-1'></th><th class='col-2'>Hours</th><th class='col-1'>Select</th></tr>";
    $tableFoot="</table>";
    $errorCouseSelection=isResultInRegistration($studentId)?"":"You have not registrations";
    if(isset($_POST['btnDelete'])){
        
        validateSelectedCourse($errorCouseSelection);
        if($errorCouseSelection==""){
            require 'PDO.php';
            foreach ($_POST['selectedCourse'] as $c){
                $search= explode(",", $c);
                $sql = "delete from registration where StudentId=? and SemesterCode=? and CourseCode=? ";
                $pdo->prepare($sql)->execute([$studentId, $search[0], $search[1]]);
                
            }
            
            header("Location:CurrentRegistration.php");
        }
    }elseif (isset($_POST['btnCancel'])){ {
//        $_SESSION["semesterSelection"]=$_POST['semesterSelection'];
        header("Location:CurrentRegistration.php");
    }
    include("./common/header.php");
}
    function isResultInRegistration($studentId){
        require 'PDO.php';
        $sql='select CourseCode, SemesterCode from Registration where StudentId=?';
        $resultSet=$pdo->prepare($sql);
        $resultSet->execute([$studentId]);
        if($resultSet->rowCount()>0){
            return true;
        }
        return false;
        
    }
    function getRegistratedCourseTableResult($studentId){
        $tableResult="";
        $totalHours=0;
        require 'PDO.php';
        
        $semesters= getAllSemesters();
        foreach($semesters as $semester){
            $semesterCode=$semester->getSemesterCode();
            $sql='select CourseCode, SemesterCode from Registration where StudentId=? and SemesterCode=? ';
            $resultSet=$pdo->prepare($sql);
            $resultSet->execute([$studentId,$semesterCode]);
            if($resultSet->rowCount()>0){
                foreach ($resultSet as $row) {                
                    $course= getCourse($row['CourseCode']);
                    $tableResult.= getTableRow($semester, $course);
                    $totalHours+=intval($course->getWeeklyHours());
                }
                $tableResult.="<tr class='d-flex'><td class='col-1'></td><td class='col-1'></td><td class='col-1'></td><td class='col-2'></td><td class='col-2'>Total Weekly Hours </td><td class='col-1 ' >$totalHours</td><td class='col-1'></td></tr>";
                $totalHours=0;
            }
            
            
        }
        return $tableResult;
    }
    function getAllSemesters(){
        $semesters=array();
        require 'PDO.php';
        $sql='select SemesterCode,Term,Year from Semester';
        $resultSet=$pdo->query($sql);
        
        foreach($resultSet as $row){
            $semester=new Semester($row['SemesterCode'],$row['Term'],$row['Year']);
            $semesters[]=$semester;
        }
        return $semesters;
    }
    function getSemester($semesterCode){
        
        require 'PDO.php';
        $sql='select SemesterCode,Term,Year from Semester where Semester=?';
        $resultSet=$pdo->prepare($sql);
        $resultSet->execute([$semesterCode]);
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        if($row){
            return Semester($row['SemesterCode'],$row['Term'],$row['Year']);
        } else {
            return null;
        }

        
    }
    function getCourse($courseCode){
        
        require 'PDO.php';
        $sql='select CourseCode,Title,WeeklyHours from course where CourseCode=?';
        $resultSet=$pdo->prepare($sql);
        $resultSet->execute([$courseCode]);
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        if($row){
            return new Course($row['CourseCode'], $row['Title'], $row['WeeklyHours']);
            
        }else{
        return null;
        }
    }
    function getTableRow(&$semester,&$course){
        $search=array($semester->getSemesterCode(),$course->getCourseCode());
        $value=implode(",",$search);
        $year=$semester->getYear();
        $term=$semester->getTerm();
        $courseCode=$course->getCourseCode();
        $title=$course->getTitle();
        $weeklyHours=$course->getWeeklyHours();
        $tableRow= "<tr class='d-flex'><td class='col-1'>$year</td><td class='col-1'>$term</td><td class='col-1'>$courseCode</td><td class='col-2'>$title</td><td class='col-2'></td><td class='col-1 '>$weeklyHours</td><td class='col-1'><input class='form-check-input' type = 'checkbox' id=$courseCode name = 'selectedCourse[]' value = $value /></td></tr>";
        return $tableRow;
    }
    
    
    function validateSelectedCourse(&$errorCouseSelection){
        
        if(!isset($_POST['selectedCourse'])){
            $errorCouseSelection="You need select at least one course to delete!";
        }
        
    }
    function calculateCourseHourse(){
        $totalHours=0;
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

    include("./common/header.php"); 
?>

<div class="container col-md-offset-2 col-md-10 mt-4 mb-2">
    <h2 class="col-md-8">Course Registrations</h2><br>
   
    <p>Hello <?php echo $_SESSION['name']?> !(not you? change user <a href="Login.php">here</a>), the followings are your current registrations</p>
    <form  method="post">
        <div>
         <label class="col-md-3 col-form-label text-danger" id="errorCouseSelection" ><?php echo $errorCouseSelection ?><label>
        </div>
        <?php echo $tableHeader ?>
        <?php echo $tableResult ?>
        <?php echo $tableFoot ?>
         <div class="col-md-4 col-md-offset-6">
             <input type ="submit" class=" me-3 btn btn-success " name='btnDelete' id="btnDelete" value="DeleteSelected">
             <input type ="submit" class=" btn btn-primary " name="btnCancel" id="btnCancel" value="Clear">
             <input type ="submit" hidden class=" btn btn-primary" name="btnSubmit" id="btnSubmit" >
        </div>
        
    </form>
    
</div>
<script>
  
    
    $(".form-check-input").change(function(){
        $("#errorCouseSelection").hide();
    });
    $("#btnDelete").click(function(){
        confirm("Theselected registrations will be delected!")
//        {
//            $("#btnSubmit").click();
//        }else{
//             $("#btnCancel").click();
//        }
    });
  

</script>

<?php include('./common/footer.php'); ?>