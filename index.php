<?php

session_start();

$mysql_connect = mysqli_connect("localhost", "id15829218_shopify", "Shopify@12345", "id15829218_shopifybackend");

// check if database connected properly
if(!$mysql_connect){
    die(mysqli_connect_error());
}


// see if user login
// if login grab private image of user and every public image
// if not login grab every public image
if(isset($_SESSION['username'])){
$userName = $_SESSION['username'];
$sqlquery = "SELECT * FROM image_gallery where private_image = 0 or username = '$userName'";

$row = $mysql_connect -> query($sqlquery);
}
else{
    $sqlquery = "SELECT * FROM image_gallery where private_image = 0";

$row = $mysql_connect -> query($sqlquery);
}

// make sure page redirect to landing page
$_SESSION['login_redirect'] = $_SERVER['PHP_SELF'];
?>
<?php
include 'header.php';
?>
<head>
    <title>Image Gallery</title>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="">

</head>
<div style = "text-align: center; ">
<h1 style = "margin:0 auto;">Shopify Image Gallery</h1>
</div>
<br>
<br>

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

    // if priavte image display private image
    // else display public image 
    if($image['private_image'] == 1){
        $message = "private image";
    }
    else{
        $message = "public image";
    }

    // user login and is cureent picture publish by user
    if(isset($_SESSION['username']) && strcmp($image['username'],$userName) ==0)
    {
        // image directory of usre
        $userDirectory = $userName;
        // grab image name
        $imageName = explode('.',$image['image_name']);
        $imageName = $imageName[0];
        // print image 
        echo("<img src = './$userName/".$image['image_name']."' width='200' height='200' >");
        echo("<p>Image name: ".$imageName);
        echo '<br>';
        echo("Image uploaded by: ".$image['username']."<br> $message</p>");
    }
    // user login and current picture was not publish by user
    elseif(isset($_SESSION['username']) && strcmp($image['username'],$userName) !=0){
        // image name
        $imageName = explode('.',$image['image_name']);
        $imageName = $imageName[0];
        // print image
        echo("<img src = './upload_image/".$image['image_name']."' width='200' height='200' >");
        echo("<p>Image name: ".$imageName);
        echo '<br>';
        echo("Image uploaded by: ".$image['username']."<br> $message</p>");
    }
    // user not login
    else{
        // image name
        $imageName = explode('.',$image['image_name']);
        $imageName = $imageName[0];

        // print image
        echo("<img src = './upload_image/".$image['image_name']."' width='200' height='200' >");
        echo("<p>Image name: ".$imageName);
        echo '<br>';
        echo("Image uploaded by: ".$image['username']."<br> $message</p>");
    }
    echo '</div>';
    // next column
    $col += 1;
}
echo("</div>");
echo("</div>");

?>
