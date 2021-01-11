<?php

    session_start();

    $mysql_connect = mysqli_connect("localhost", "id15829218_shopify", "Shopify@12345", "id15829218_shopifybackend");

    // check if database connected properly
    if(!$mysql_connect){
        die(mysqli_connect_error());
    }

?>
<?php
include 'header.php';
?>

<head>
    <title>Signup Page</title>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="">

</head>

<body>
<div  style = "width:50%; margin: auto; ">
    <form onsbumit method="POST" id = "login_form" name = "login_form" action="#" >
    <table style="width:100%">
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>    
                <label for="userName">Username:
            </td>
            <td>
                 <input type='text' name='username' id='username'>

            </td>
        </tr>
        <tr>
            <td>
            <label for="col">Password: 

            </td>
            <td>
            <input type='password' name='password' id='password' >

            </td>
        </tr>
        <tr>
            <td>
            <label for="col">Confrim New Password: 

            </td>
            <td>
            <input type='password' name='password2' id='password2'>

            </td>
        </tr>
    </table>
        <br>
            <br>
            <button type="submit"  id="create_account" name="create_account">Create Account</button>

    </form>
    <br>
    <br>

</div>
</body>


<?php

// user tried to create new acount
if(isset($_POST['create_account'])){
    // grab user info
    // make username lowercase
    $userName = strtolower($_POST['username']);
    $passWord = $_POST['password'];
    $passWord2 = $_POST['password2'];
 
    $sqlquery = "SELECT * FROM Accounts where username = '$userName'";

    $row = $mysql_connect -> query($sqlquery);
    // check if username taken
    $length = mysqli_num_rows($row);

    // password not matching
    if(strcmp($passWord, $passWord2) !== 0){
        print("Password and confirm password are not the same. Please try again");
    }
    // blank entry
    elseif($userName == "" || $passWord == ""){
        print("You must enter a password and username");

    }
    // username taken 
    elseif($length != 0){
        // can not print anything before header otherwise won't work
        // keep track of username and redirect to main page 
        print("An account already exist with this username");
        echo("<br>");
        print("Please pick another account");
    }   
    else{
        // add to datebase 
        $sqlquery = "INSERT INTO Accounts (username, password) VALUES ('$userName','$passWord')";
        $mysql_connect -> query($sqlquery);
        $_SESSION['login_message'] = "account created successfully";
        // redirect user to login page 
        echo'
        <script>
        window.location.replace("https://shopifybackendchallenge.000webhostapp.com/login_page.php");
        </script>
        ';    }
}

?>


