<?php
$imageArray = [];
$error_notImage = ' is not an image';
$failedUploads = [];

if (isset($_POST['submit'])) {

    // Count total files
    $countfiles = count($_FILES['file']['name']);

    // Looping all files

    for ($i = 0; $i < $countfiles; $i++) {
        $uploadOk = 1;

        $failedUploads = [];
        $filename = $_FILES['file']['name'][$i];

        $imageFileType = strtolower(pathinfo('../media/product_images/' . $filename, PATHINFO_EXTENSION));

        // check if current file is a real image

        $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
        if ($check !== false) {
        } else {
            $failedUploads[$filename][] = " is not an image. ";

            $uploadOk = 0;
        }
        // check if correct file-type is image
        if ($imageFileType == 'jpg' || $imageFileType == 'png' || $imageFileType == 'jpeg' || $imageFileType == 'gif' || $imageFileType == 'svg') {
        } else {
            $failedUploads[$filename][] = ' file not supported, try jpg, jpeg, png or gif instead. ';

            $uploadOk = 0;
        }
        // check if file exists
        if (file_exists('../media/product_images/' . $filename)) {
            $failedUploads[$filename][] = " file already exists.";
            $uploadOk = 0;
        }

        // check filesize
        if (filesize($_FILES['file']['tmp_name'][$i]) > 2000000) {
            $failedUploads[$filename][] = " file is too big.";
            $uploadOk = 0;
        }

        // upload if nothing happened to uploadOk
        if ($uploadOk == 1) {
            $imageArray[] = $filename;
            move_uploaded_file($_FILES['file']['tmp_name'][$i], '../media/product_images/' . $filename);

        } else {
            count($failedUploads) !== 0 ? print_r($failedUploads) : null;
        }
    }

}
?>




<script>
        console.log('upload image script running')
        // get images from php
        let imagesFromPHP = <?php echo json_encode($imageArray); ?> ; 
      // get images from localstorage
        let imagesFromLocalStorage = JSON.parse(localStorage.getItem("images"))

        // if there are no images yet uploaded, do nothing
        if(imagesFromPHP.length  === 0 ){
        
       
        } else {
             if(!imagesFromLocalStorage) {
                imagesFromLocalStorage = []
             } else {

             }
             imagesFromPHP.forEach(image => {
                imagesFromLocalStorage.push({
                    img: image,
                    feature:  0
                });
             });
             localStorage.setItem("images", JSON.stringify(imagesFromLocalStorage));
        }
     
    

</script>