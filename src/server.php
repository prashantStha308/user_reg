<?php
    if( session_status() !== PHP_SESSION_ACTIVE ) session_start();

    $devMode = true;
    // Enable debugginng on devMode
    if($devMode){
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    // get contents from .env
    $rootPath = dirname(__DIR__, 1); // Goes up 1 levels from src to root
    $env = file_get_contents($rootPath."/.env");
    $lines = explode("\n",$env);
    // load env variables
    foreach($lines as $line){
      preg_match("/([^#]+)\=(.*)/",$line,$matches);
      if(isset($matches[2])){ putenv(trim($line)); }
    } 

    // constants
    define( "SESSION_TIMEOUT" , 1080 );

    // functions
    function logout(){
        session_regenerate_id(true);
        session_unset();
        session_destroy();
        return true;
    }

    // Making a single pdo objects for entire project
    try {
        $db = new PDO('mysql:host=' . getenv('HOST') .';dbname=' . getenv('DB_NAME'), getenv('USER'), getenv('PASSWORD'));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }

    // Destrory session after $session_timeout duration if last_activity exists AND if user is logged in
    if ( isset($_SESSION['last_activity']) && isset($_SESSION['username']) ) {
        $inactive_time = time() - $_SESSION['last_activity'];
        
        if ($inactive_time > SESSION_TIMEOUT) {
            // logout
            logout();
            // start new session
            session_start();
            $_SESSION['timeout'] = true;
            header("Location: login.php?timeout=true");
            exit;
        }
    }
    $_SESSION['last_activity'] = time();
?>