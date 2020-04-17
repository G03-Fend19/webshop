<?php 
    $imageArray = [];
    $error_notImage = ' is not an image';
    $failedUploads = [];
   


if(isset($_POST['submit'])){
 

 // Count total files
 $countfiles = count($_FILES['file']['name']);
  
 // Looping all files

 for($i=0;$i<$countfiles;$i++){
  $uploadOk = 1;

  $failedUploads = [];
  $filename = $_FILES['file']['name'][$i];
  
  $imageFileType = strtolower(pathinfo('../media/product_images/'.$filename, PATHINFO_EXTENSION));

  // check if current file is a real image

  $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
    if ($check !== false) {
    } else {
        $failedUploads[$filename][] =   " is not an image. ";
       
        $uploadOk = 0;
    }
    // check if correct file-type is image
   if($imageFileType == 'jpg' ||$imageFileType == 'png' ||$imageFileType == 'jpeg' || $imageFileType =='gif'|| $imageFileType =='svg'){
    } else {
         $failedUploads[$filename][] =   ' file not supported, try jpg, jpeg, png or gif instead. ';

       $uploadOk = 0;
    }
    // check if file exists
    if (file_exists('../media/product_images/'.$filename)) {
        $failedUploads[$filename][] =   " file already exists.";
         $uploadOk = 0;
        }
   
    // check filesize
    if (filesize($_FILES['file']['tmp_name'][$i]) > 2000000) {
     $failedUploads[$filename][] =   " file is too big.";
     $uploadOk = 0;
     }


     // upload if nothing happened to uploadOk
    if($uploadOk == 1) {
         $imageArray[] = $filename; 
        move_uploaded_file($_FILES['file']['tmp_name'][$i],'../media/product_images/'.$filename);
    }else {
    count($failedUploads) !== 0 ? print_r($failedUploads) : null;
    }
}
 
} 
?>

<form id="dragme"class="upload-form hidden"method='post' action='' enctype='multipart/form-data' draggable="true">
<div class="upload-form__border">   <button class="cancel-upload" type="button">X</button> </div>
 <input type="file" name="file[]" id="file" multiple>
 <input type='submit' name='submit' value='Upload'>

</form>


<script>
function drag_start(event) {
    var style = window.getComputedStyle(event.target, null);
    event.dataTransfer.setData("text/plain",
    (parseInt(style.getPropertyValue("left"),10) - event.clientX) + ',' + (parseInt(style.getPropertyValue("top"),10) - event.clientY));
} 
function drag_over(event) { 
    event.preventDefault(); 
    return false; 
} 
function drop(event) { 
    var offset = event.dataTransfer.getData("text/plain").split(',');
    var dm = document.getElementById('dragme');
    dm.style.left = (event.clientX + parseInt(offset[0],10)) + 'px';
    dm.style.top = (event.clientY + parseInt(offset[1],10)) + 'px';
    event.preventDefault();
    return false;
} 
var dm = document.getElementById('dragme'); 
dm.addEventListener('dragstart',drag_start,false); 
document.body.addEventListener('dragover',drag_over,false); 
document.body.addEventListener('drop',drop,false); 
</script>
