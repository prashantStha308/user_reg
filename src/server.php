<?php  
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // To display errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // // get contents from .env
    // $rootPath = dirname(__DIR__, 1); // Goes up 1 levels from src to root
    // $env = file_get_contents($rootPath."/.env");
    // $lines = explode("\n",$env);
    // // load env variables
    // foreach($lines as $line){
    //   preg_match("/([^#]+)\=(.*)/",$line,$matches);
    //   if(isset($matches[2])){ putenv(trim($line)); }
    // } 

    // constants
    define( "USER" , 'ur_user' );
    define( "PASSWORD" , 'user_registration' );
    define( "HOST" , 'localhost' );
    define( "DB_NAME" , 'user_management' );
    define( "PASSWORD_ATTEMPT_MAX" , 5 );
    define( "PASSWORD_BLOCK_TIMEOUT" , 5 * 60 ); //5 minutes
    define( "SESSION_TIMEOUT" , 30 * 60 ); //30 mins
    define( "ERROR_TIMEOUT" , 5 ); //5 seconds

    //Creating a single database connection for whole project
    try {
        $db = new PDO('mysql:host=' . HOST .';dbname=' . DB_NAME, USER, PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }

    // remove expired errors
    if (isset($_SESSION['error']) && isset($_SESSION['error_time'])) {
        if (time() - $_SESSION['error_time'] > ERROR_TIMEOUT) {
            unset($_SESSION['error']);
            unset($_SESSION['error_time']);
        }
    }
    // remove expire password blockage
    if( isset($_SESSION['password_count']) && isset($_SESSION['password_timestamp']) ){
        if( time() - $_SESSION['password_timestamp'] > PASSWORD_BLOCK_TIMEOUT ){
            unset($_SESSION['password_count']);
            unset($_SESSION['password_timestamp']);
            unset($_SESSION['password_message']);
        }
    }

    // Destrory session after $session_timeout duration if last_activity exists AND if user is logged in
    if ( isset($_SESSION['last_activity']) && isset($_SESSION['username']) ) {
        
        if ( time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
            session_regenerate_id(true);
            session_unset();
            session_destroy();
            header("Location: login.php?timeout=true");
            exit;
        }
    }
    $_SESSION['last_activity'] = time();
?>