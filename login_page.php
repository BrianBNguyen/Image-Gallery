<?php

session_start();

$mysql_connect = mysqli_connect("localhost", "id15829218_shopify", "Shopify@12345", "id15829218_shopifybackend");

// check for database connection
if(!$mysql_connect){
    die(mysqli_connect_error());
}
else{
    // user tries to login
    if(isset($_POST['login'])){
        // grab password and username
        // make username lowercase
        $userName = strtolower($_POST['username']);
        $passWord = $_POST['password'];
        $sqlquery = "SELECT * FROM Accounts where username = '$userName' AND password ='$passWord'";

        $row = $mysql_connect -> query($sqlquery);
        // get length of row
        $length = mysqli_num_rows($row);

        // check fi account was found in database if found redirect to mainpage 
        if($length == 1){
            // can not print anything before header otherwise won't work
            // keep track of username and redirect to main page 
            $_SESSION['username'] = $userName;

            // if user tried to add/delete image but was not login redirect to hose page
            if(isset($_SESSION['login_redirect'])){
                $location = $_SESSION['login_redirect'];
                unset($_SESSION['login_redirect']);
                header("Location: $location");
            }
            // if use just tried to login regularly redirect to landing page 
            else{
                header("Location: index.php");
            }
            exit;
        }   
    }
}
?>
<?php
include 'header.php';
?>

<div style = "width:50%; margin: auto; ">
<?php
// user tried to add picture without logging in
// or user created account successfully message 
if(isset($_SESSION['login_message'] ))
{
    print($_SESSION['login_message']);
    unset($_SESSION['login_message']);
}
?>
</div>
<br>
<head>
    <title>Login Page</title>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="">

</head>

<body>
<div  style = "width:50%; margin: 0 auto; ">
    <form id = "login_form" name = "login_form" action="" method="POST" >
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
            <input type='password' name='password' id='password'>

            </td>
        </tr>
    </table>
                <!-- login and redirect to main page -->
                <button type="submit"  id="login" name="login">Login</button>
                <!-- redirect so signup page -->
                <button type="submit" onclick="signup()" id="create_login" name="create_login">Sign Up</button>
    </form>
    <br>
    <br>

</div>
</body>

<?php
// account not in database
if(isset($_POST['login'])){
    // wrong account info 
    if($length != 1){
        print("Incorrect username or password try again");
    }
}
?>

<!-- redirect action of form to signup page  -->
<script>

    // redirect to signup page
    function signup() {
        document.getElementById("login_form").action = "./signup.php";
    }

</script>
