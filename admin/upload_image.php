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
/* let imagesFromPHP = <?php echo json_encode($imageArray); ?> ;


        let imagesFromPHP = <?php echo json_encode($imageArray); ?> ; */


//     const uploadBtn = document.querySelector('.upload-btn');



//   imagesLS = JSON.parse(localStorage.getItem("images"));
//   if(imagesLS){
//       imagesLS.forEach(image => {
//           imagesFromPHP.push(image)
//       })
//   }
//   localStorage.setItem("images", JSON.stringify(imagesFromPHP))

//   localStorage.clear();

//   imagesLS = JSON.parse(localStorage.getItem("images"));
//   console.log('heloo')

// images = JSON.parse(localStorage.getItem("images"));
// uploadBtn.addEventListener('click', () => {
//   images = JSON.parse(localStorage.getItem("images"));

// })
</script>