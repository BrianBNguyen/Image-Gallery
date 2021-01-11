<?php

session_start();

$mysql_connect = mysqli_connect("localhost", "id15829218_shopify", "Shopify@12345", "id15829218_shopifybackend");

// check if database connected properly
if(!$mysql_connect){
    die(mysqli_connect_error());
}

// see if user login
// grab all image user uploaded 
if(isset($_SESSION['username'])){
$userName = $_SESSION['username'];
$sqlquery = "SELECT * FROM image_gallery where username = '$userName'";

$row = $mysql_connect -> query($sqlquery);
}
// redirect user to login page. Once user login redirect back to delete page 
else{
    $msg = "please login first";
    $_SESSION['login_redirect'] = $_SERVER['PHP_SELF'];
    $_SESSION['login_message'] = "Please log in first before deleting an image";
    header("Location: login_page.php");
}

?>
<?php
include 'header.php';
?>
<head>
    <title>Delete Image</title>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="">

</head>
<div style = "text-align: center; ">
<h1 style = "margin:0 auto;">Your Images</h1>
</div>
<br>
<br>
<form id = "delete_form" name = "delete_form" action="" method="POST" enctype = 'multipart/form-data'>
    <?php
    // new row every 4 columns
    $col = 0;
    echo('<div class="container">');
    echo '<div class = "row" style = "text-align: center; ">';

    // go through each image
    foreach( $row as $image){
        // new row
        if ($col%4 == 0) {
            echo '</div>';
            echo '<div class = "row">';

            echo("<div  class='col-md-3'>");
        } 
        else{
            echo("<div  class='col-md-3'>");
        }

        // if priavte image display private image text
        // else display public image text
        if($image['private_image'] == 1){
            $message = "private image";
        }
        else{
            $message = "public image";
        }

        // check if image is publish by user 
        if(isset($_SESSION['username']) && strcmp($image['username'],$userName) ==0)
        {
            // get directory of user images
            $userDirectory = $userName;
            // grab image name
            $imageName = explode('.',$image['image_name']);
            $imageName = $imageName[0];
            $imageid = $image['image_id'];

            // print image 
            echo("<img src = './$userName/".$image['image_name']."' width='200' height='200' >");
            echo("<p>Image name: ".$imageName);
            echo '<br>';
            echo("Image uploaded by: ".$image['username']."<br> $message<br> 
            Select image: <input type = 'checkbox' name = 'delete_image[]' value = '$imageid' ></p>");
        }
        // 
        echo '</div>';
        // next column
        $col += 1;

    }
    echo("</div>");
    echo("</div>");
    ?>
    <div style = "text-align: center; ">
    <button type="submit"  id="delete" name="delete">Delete Images</button>
</div>
</form>

<?php
    // user tried to delete images
    if(isset($_POST['delete_image'])){
        $delete_images = $_POST['delete_image'];
       // go through each image user trying to delete
        foreach($delete_images as $delete){
            $directory = $userName;
            $sqlquery = "SELECT * FROM image_gallery WHERE image_id='".$delete."'";
            $rows = $mysql_connect -> query($sqlquery);
            foreach($rows as $row){

                // check if it is a private or public image
                // if public delete in user directory and public directory
                if($row['private_image'] == 1){
                    $image_delete = unlink("./".$directory."/".$row['image_name']);
                    $image_delete2 = True;
                }
                else{
                    $image_delete = unlink("./".$directory."/".$row['image_name']);
                    $image_delete2 = unlink("./upload_image/".$row['image_name']);
                }
                // check if file deleted properly 
                if(!$image_delete || !$image_delete2)
                {
                    print($row['image_name']." was not deleted properly try again");
                    echo '<br>';
                }
                // delete image from database 
                else{
                    print($row['image_name']." was deleted properly");
                    $sqlquery = "DELETE FROM image_gallery WHERE image_id='".$delete."'";
                    $mysql_connect -> query($sqlquery);
                    echo '<br>';
                }
            }// end of row loop
        } // end of deleted image loop

    } // end of delete image post request if statemeent 

?>
