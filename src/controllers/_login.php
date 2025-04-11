<?php
    require_once "auth.php";
    if( isset($_SESSION['username']) ){
        $_SESSION['error'] = "User logged in. Can't login in this state";
        $_SESSION['error_time'] = time();
        header("Location:dashboard.php");
    }
    // handle login
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if( login( $email , $password ) ){
            unset_errors();
            header("Location: dashboard.php?username=" . urlencode($_SESSION['username']));
            exit;
        }else{
            $_SESSION['error'] = "Invalid email or password";
            $_SESSION['error_time'] = time();
        }
    }

    function setReadonly(){
        if( isset($_SESSION['password_blocked']) && $_SESSION['password_blocked'] ){
            return "readonly";
        }else{
            return "";
        }
    }
?>