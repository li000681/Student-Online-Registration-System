<?php
    session_start();	
    if(!isset($_SESSION["logged"]))
    {
        header("Location: Login.php");
        exit( );
    }

    include("./common/header.php");
    $path="uploads/".$_SESSION["studentId"].DIRECTORY_SEPARATOR;
    if(!file_exists ( $path ) ){
        mkdir($path);
    }
    $target_dir = $path;
    
    $errorMessage="";
    $finalMessage="";
    $thumbImageLink="";
    $targetWidth=160;
   
    if(isset($_POST["Submit"])) {
        $file=$_FILES["fileToUpload"];
        
        $count=count($file["name"]);
        
        for($i=0;$i<$count;$i++){
            if($file["error"][$i]==4){
            $errorMessage="<P class='text-danger'> You must select files to upload!<p> ";
            break;
            }
            
            $uploadOk = 1;
            $target_file = $target_dir.basename($file["name"][$i]); 
               
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $check = getimagesize($file["tmp_name"][$i]);
            validate($uploadOk, $file["name"][$i],$imageFileType, $target_file, $check,$file["size"][$i], $errorMessage);
            if ($uploadOk == 0) {
            $errorMessage.="<P class='text-danger'> Sorry, ". htmlspecialchars( basename( $file["name"][$i])). " was not uploaded!<p>";
          
            } else {
            if (move_uploaded_file($file["tmp_name"][$i], $target_file)) {
              $finalMessage.="<P class='text-success'> The file ". htmlspecialchars( basename( $file["name"][$i])). " has been uploaded.<p>";
              if(createImage($target_file,$imageFileType,$check, $file["name"][$i], $file["size"][$i], $targetWidth)){
                  
                  $thumbImageSource=$target_dir."thumb_".$file["name"][$i];
                  $thumbImageName='thumb_'.$file["name"][$i];
                  $thumbImageLink.="<a href='$target_file' target='_blank'><img src='$thumbImageSource' alt='$thumbImageName'></a> ";
              }
                } else {
                  $finalMessage.="<P class='text-danger'> Sorry, there was an error uploading your ".htmlspecialchars( basename( $file["name"][$i]))."!<p>";
                }
            }
        }
        $directorDetail= scandir($$targetHeight);
    }
    function validate(&$uploadOk, $fileName,$imageFileType,$target_file,$check,$fileSize, &$errorMessage){
        
        // Check if file is an image
        if($check == false) {
          
          $errorMessage.="<P class='text-danger'> ".$fileName." is not an image!";
          $uploadOk = 0;
        }
        // Check if file already exists
        if (file_exists($target_file)) {
          $errorMessage.="<P class='text-danger'> Sorry, ".$fileName." already exists.<p>";
          $uploadOk = 0;
        }

        // Check file size
        if ($fileSize > 5000000) {
          $errorMessage.="<P class='text-danger'> Sorry, ".$fileName." is too large.<p>";
          $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          $errorMessage.="<P class='text-danger'> Sorry, only JPG, JPEG, PNG & GIF files are allowed.<p>";
          $uploadOk = 0;
        }
    }
    function createImage($target_file,$imageFileType,$check,$fileName,$fileSize,$targetWidth,$targetHeight = null){
           
         
        $pathInfo= pathinfo($target_file);
        
        if($imageFileType == "png"){
            $image= imagecreatefrompng($target_file);
        }elseif ($imageFileType == "gif") {
            $image= imagecreatefromgif($target_file);
        }elseif ($imageFileType == "jpeg"||$imageFileType == "jpg") {
            $image= imagecreatefromjpeg($target_file);
        }elseif ($imageFileType == "webp") {
            $image= imagecreatefromwebp($target_file);
        }
        
         if ($targetHeight == null) {

        // get width to height ratio
        $ratio=$check[0]/$check[1];

        // if is portrait
        // use ratio to scale height to fit in square
        if ($check[0] > $check[1]) {
            $targetHeight = floor($targetWidth / $ratio);
        }
        // if is landscape
        // use ratio to scale width to fit in square
        else {
            $targetHeight = $targetWidth;
            $targetWidth = floor($targetWidth * $ratio);
        }
        // create duplicate image based on calculated target size
        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
         if ($imageFileType == "png"|| $imageFileType == "gif") {

        // make image transparent
        imagecolortransparent(
            $thumbnail,
            imagecolorallocate($thumbnail, 0, 0, 0)
        );
        }
        // additional settings for PNGs
        if ($imageFileType == "png") {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
        }
        // copy entire source image to duplicate image and resize
        imagecopyresampled(
            $thumbnail,
            $image,
            0, 0, 0, 0,
            $targetWidth, $targetHeight,
            $check[0], $check[1]
        );
        if($imageFileType == "png"){
            $i=imagepng($thumbnail,$pathInfo['dirname']. DIRECTORY_SEPARATOR ."thumb_".$fileName,0);
            imagedestroy($thumbnail);
            imagedestroy($image);
            return $i;
        }elseif ($imageFileType == "gif") {
            $i=imagegif($thumbnail,$pathInfo['dirname']. DIRECTORY_SEPARATOR ."thumb_".$fileName);
            imagedestroy($thumbnail);
            imagedestroy($image);
            return $i;
        }elseif ($imageFileType == "jpeg"||$imageFileType == "jpg") {
            $i=imagejpeg($thumbnail,$pathInfo['dirname']. DIRECTORY_SEPARATOR ."thumb_".$fileName,100);
            imagedestroy($thumbnail);
            imagedestroy($image);
            return $i;
        }elseif ($imageFileType == "webp") {
            $i=imagewebp($thumbnail,$pathInfo['dirname']. DIRECTORY_SEPARATOR ."thumb_".$fileName,80);
            imagedestroy($thumbnail);
            imagedestroy($image);
            return $i;
        }
    }
    

    }
?>
<div class="container col-md-offset-2 col-md-10 mt-4">
    <h1 class="col-md-8">Create Your Profile </h1><br>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
	 <div class="row mb-2 fw-bold">
            <label class="col-md-2 col-form-label" for="fileToUpload">File to Upload: </label>
            <div class="col-md-3"> 
                <input class="form-control" type="file"  accept="image/*" id="fileToUpload" name="fileToUpload[]" multiple/>             </div>
                      
                   
                    
         </div>
	<input class="col-md-1  btn btn-primary me-4" type="submit" name="Submit" />
        <?php echo $errorMessage ?>
        <?php echo $finalMessage ?>
</form>
<h4>Your uploaded images are:</h4>
<?php echo $thumbImageLink ?>
<!--<script>
  
    $("#fileToUpload").change(function(){
        $("#errorStudentId").hide();
  });
  
</script>-->
<?php include('./common/footer.php'); ?>
