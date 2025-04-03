<?php
    require_once "config.php";

    function bind_param( &$query , $param , &$value ){
        $query->bindParam( $param , $value );
    }
    function bind_value( &$query , $param , &$value ){
        $query->bindValue( $param , $value );
    }

    function validate_form( $username , $email , $password = "__registered_password" ){
        try{
            if( empty($username) || empty($email) ){
                throw new Exception("Required Fields missing");
            }
            if( $password !== "__registered_password" && empty($password) ){
                throw new Exception("Required Fields missing");
            }
            return true;
        }catch( Exception $e ){
            echo "Error encountered: " . $e->getMessage();
            return false;
        }
    }

    function create_user( $username , $email , $password ){
        try{
            // make a pdo
            $db = new pdo( 'mysql:host=localhost;dbname=user_management' , USER , PASSWORD );
            // enable error handeling
            $db->setAttribute( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );

            $checkQuery = $db->prepare("SELECT email FROM users WHERE email = :email LIMIT 1");
            bind_param( $checkQuery , ":email" , $email );
            $checkQuery->execute();
            $emailExist = $checkQuery->fetchColumn();

            if( $emailExist){
                $_SESSION['error'] = "Email already exists. Please use a different email.";
                return false;
            }

            $hashed_password = password_hash($password , PASSWORD_DEFAULT);

            $query = $db->prepare("INSERT INTO users( username , email , password ) VALUES ( :username , :email , :password )");
            bind_param( $query , ":username" , $username );
            bind_param( $query , ":email" , $email );
            bind_param( $query , ":password" , $hashed_password );
            $query->execute();
            return true;
        }catch( PDOException $e ){
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function get_user($username = null, $limit = 10, $page = 1) {
        global $db;
        try {
            if ($username !== null) {
                $username = trim($username, "'\""); // Remove both single and double quotes
            }
            
            if ($username === null) {
                $offset = ($page - 1) * $limit;
                $query = $db->prepare(" SELECT * FROM users LIMIT {$limit} OFFSET {$offset} ");
                $query->execute();
            } else {                
                $query = $db->prepare("SELECT * FROM users WHERE username = :username");
                bind_param( $query , ":username" , $username );
                $query->execute();
            }
            
            if ($username === null) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $result = $query->fetch(PDO::FETCH_ASSOC);
            }
            
            return $result;
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
            return false;
        }
    }

    function auth_current($username) {
        return isset($_SESSION['username']) && $username && $_SESSION['username'] === $username;
    }

    function update_user($username, $email, $description) {
        global $db;
        try {
            // Prepare the update query
            $query = $db->prepare("UPDATE users SET username = :username, email = :email, description = :description WHERE user_id = :userId");
            
            // Verify user_id exists
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("User ID is not set in session");
            }
            $userId = $_SESSION['user_id'];
            bind_value( $query , ":userId" , $userId );
            bind_value( $query , ":username" , $username );
            bind_value( $query , ":email" , $email );
            bind_value( $query , ":description" , $description );

            
            // Execute the query
            $result = $query->execute();
            $rowCount = $query->rowCount();
            
            if ($result) {
                if ($rowCount > 0) {
                    // Update session with new username
                    $_SESSION['username'] = $username;
                    return true;
                } else {
                    throw new Exception("No records were updated");
                }
            } else {
                throw new Exception("Update query execution failed");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            return false;
        }
    }

    function delete_user( $username ){
        global $db;
        try{
            if( !isset($_SESSION['username']) || $_SESSION['username'] !== $username ){
                throw new Exception("You are not the owner of this account");
            }
            $query = $db->prepare("DELETE FROM users WHERE username = :uName");
            bind_param( $query , ":uName" , $username );
            if( $query->execute() ){
                logout();
                return true;
            }else{
                throw new Exception("Execution failure");
            }
        }catch( Exception $e ){
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function logout(){
        session_regenerate_id(true);
        session_unset();
        session_destroy();
        return true;
    }
    

?>