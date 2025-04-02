<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['last_activity'] = time();
    // To display errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // constants
    define( "USER" , 'root' );
    define( "PASSWORD" , '0308' );

    // Making a single pdo objects for entire project
    try {
        $db = new PDO('mysql:host=localhost;dbname=user_management', 'root', '0308');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }

    $session_timeout = 1500;
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