<?php
    require_once "../controllers/auth.php";
    if( isset($_SESSION['username']) ){
        $_SESSION['error'] = "User logged in. Can't create user in this state";
        $_SESSION['error_time'] = time();
        header("Location:dashboard.php");
    }
    // handle user creation
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $username = htmlspecialchars($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        if( validate_form( $username , $email , $password ) ){
            if( create_user( $username , $email , $password ) ){
                unset_errors();
                header("Location:login.php");
                exit();
            }
        }
    }
?>