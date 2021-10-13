<?php

$mysql_connect = mysqli_connect("localhost", "id15829218_shopify", "Shopify@12345", "id15829218_shopifybackend");
session_start();

// check if database connected properly
if(!$mysql_connect){
    die(mysqli_connect_error());
}
else{

    // if user did not login redirect to login page
    // once user login redirect back to add page
    if(!isset($_SESSION['username']))
    {
        $msg = "please login first";
        $_SESSION['login_redirect'] = $_SERVER['PHP_SELF'];
        $_SESSION['login_message'] = "Please log in first before adding an image";
        header("Location: login_page.php");
    }
   
?>
<?php
include 'header.php';
?>

<head>
    <title>Add Images</title>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="">
</head>

<body >
<div style = "width:50%; margin: auto; ">
    <form id = "add_form" name = "add_form" action="" method="POST" enctype = 'multipart/form-data'>
    <table style="width:100%">
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td >    
                <label for="images[]">Select Images:
            </td>
            <td>
                <input type='file' name='images[]' id='images[]' multiple>
            </td>
        </tr>
        <tr>
            <td>
                 <label for="private_image">Check this box to make your uploaded images private:

            </td>
            <td>
                 <input type = "checkbox" name = "private_image" id = "private_image" value = "private">
            </td>
        </tr>
    </table>
        <br>
        <button type="submit"  id="upload" name="upload">Upload Images</button>
    </form>

</div>
<div style = "width:50%; margin: auto; ">
<?php
    // check if user is login 
    if(isset($_SESSION['username'])){
        // user tries to upload images
        if(isset($_POST['upload'])){

            $file = $_FILES['images'];
            //print_r($file);
            // let us know if image was uploaded succesfully 
            $image_upload_success = False;

            // let us know if image uploaded were private 
            // 0 will represent images that are not private
            // 1 will represent images that are private 
            $private_bit = 0;
        
            // check if user made the images private
            if(isset($_POST['private_image'])){
                $private_bit = 1;
            }
        
            // go through each image user selected 
            foreach ($_FILES['images']['name'] as $index => $images){
            // print($_FILES['images']['name'][$index]." this is the key".$index);
            // grab image information 
            // tmp_name is the user image full directory
                $image_name = $_FILES['images']['name'][$index];
                $temp_image_name = $_FILES['images']['tmp_name'][$index];
                $image_size = $_FILES['images']['size'][$index];
                $image_error = $_FILES['images']['error'][$index];
        
                // if the user does not have a personal folder to upload picture create one
                $directoryName = $_SESSION['username'];
                if(!is_dir("./".$directoryName)){
                    // folder does not exist, so create it
                    mkdir("./".$directoryName, 0755, true);
                }
        
                // upload image to user folder and public folder
                if($private_bit == 0){
                    if ($upload_result = move_uploaded_file($temp_image_name,'./'.$directoryName.'/'.$image_name)) {
                        $upload2 = copy('./'.$directoryName.'/'.$image_name, './upload_image/'.$image_name);
                    }
                }
                // upload image to user folder only
                else{
                    $upload_result = move_uploaded_file($temp_image_name,'./'.$directoryName.'/'.$image_name);
                    $upload2 =True;
                }
        
                // check for upload errors 
                if(!$upload_result || $image_error != 0 || !$upload2 ){
                    print($image_name." was not uploaded properly");
                    echo '<br>';
                }
                else{
                    print($image_name." was uploaded properly");
                    $image_upload_success = True;
                    echo '<br>';
                }
                
                // add to database 
                if($image_upload_success){
                    $userName = $_SESSION['username'];
                    $image_id = $userName.$image_name;
                    $sqlquery = "INSERT INTO image_gallery (image_id,username, image_name, private_image) VALUES ('$image_id','$userName','$image_name',$private_bit)";
                    $mysql_connect -> query($sqlquery);
                }
            
            }// end of iterate through each image for loop 
        
        }// end of if upload isset
    }// end of if username isset
}// end of else databse connected else statement

?>
</div>

</body>

