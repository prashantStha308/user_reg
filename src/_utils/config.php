<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['last_activity'] = time();

    // To display errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // constants
    define( "USER" , 'root' );
    define( "PASSWORD" , '0308' );
    define( "HOST" , 'localhost' );
    define( "DB_NAME" , 'user_management' );
    define( "PASSWORD_ATTEMPT_MAX" , 5 );

    // session variables
    if (!isset($_SESSION['password_count'])) {
        $_SESSION['password_count'] = 0;
    }

    // Making a single pdo objects for entire project
    try {
        $db = new PDO('mysql:host=' . HOST .';dbname=' . DB_NAME, USER, PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }

    $session_timeout = 1000;
    // Check if "last activity" timestamp exists
    if (isset($_SESSION['last_activity'])) {
        $inactive_time = time() - $_SESSION['last_activity'];
        
        if ($inactive_time > $session_timeout) {
            // Destroy the session
            session_unset();
            session_destroy();
            header("Location: login.php?timeout=true");
            exit;
        }
    }
?>