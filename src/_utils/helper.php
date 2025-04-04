<?php
    require_once "config.php";

    // ----------------------mis----------------------
    function bind_param( &$query , $param , &$value ){
        $query->bindParam( $param , $value );
    }
    function bind_value( &$query , $param , &$value ){
        $query->bindValue( $param , $value );
    }

    // getters
    function get_data_by_id($user_id, $key) {
        global $db;
        $query = $db->prepare("SELECT {$key} FROM users WHERE user_id = :user_id LIMIT 1");
        bind_param( $query , ":user_id" , $user_id );
        $query->execute();
    
        if ($query->rowCount() > 0) {
            $res = $query->fetch(PDO::FETCH_ASSOC);
            // $res is an associative array
            return $res[$key];
        } else {
            echo "No data found for user_id: {$user_id}";
            return false;
        }
    }

    function get_data_by_username( $username , $key ){
        global $db;
        $query = $db->prepare("SELECT {$key} FROM users WHERE username = :username LIMIT 1");
        bind_param( $query , ":username" , $username );
        $query->execute();
    
        if ($query->rowCount() > 0) {
            $res = $query->fetch(PDO::FETCH_ASSOC);
            // $res is an associative array
            return $res[$key];
        } else {
            echo "No data found for username: {$username}";
            return false;
        }
    }

    // ----------------------auth and validation----------------------
    function auth_user($username){
        try{
            $getId = get_data_by_username( $username , 'user_id' );
            if ( !isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $getId ) {
                throw new Exception("You are not the owner of this account");
            }
            return true;
        }catch( Exception $e ){
            $_SESSION['error'] = 'Error:' . $e->getMessage();
            return false;
        }
    }

    function validate_form( $username , $email , $password = "__registered_password__" ){
        $_SESSION['last_activity'] = time();
        try{
            if( $username === "__ristricted_username__" ){
                throw new Exception("Invalid username, please choose a new one");
            }
            if( empty($username) || empty($email) ){
                throw new Exception("Required Fields missing");
            }
            if( $password !== "__registered_password__" && empty($password) ){
                throw new Exception("Required Fields missing");
            }
            return true;
        }catch( Exception $e ){
            $_SESSION['error'] = "Error encountered: " . $e->getMessage();
            return false;
        }
    }

    // ----------------------Project essentials----------------------
    function login( $email , $password ){
        global $db;
        try{
            if( isset($_SESSION['password_count']) && $_SESSION['password_count'] > PASSWORD_ATTEMPT_MAX ){
                throw new Exception("Max attempt reached. You have been blocked");
            }
            $query = $db->prepare("SELECT * FROM users WHERE email = :email ");
            $query->bindParam( ":email" , $email );
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);
            
            // Check if user exists and verify password
            if( $user ){
                if ( password_verify($password, $user['password'])) {
                    // start session if login successful
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['last_activity'] = time();
                    return true;
                } else {
                    if(!isset($_SESSION['password_count'])){
                        $_SESSION['password_count'] = 1;
                    }else{
                        $_SESSION['password_count'] += 1;
                    }
                    throw new Exception("Invalid password. Please try again");
                }
            }else{
                throw new Exception("Unregistered email. Try again");
            }
        }catch( Exception $e ){
            $_SESSION['error'] = "Error: " . $e->getMessage();
            return false;
        }
    }

    function logout(){
        session_regenerate_id(true);
        session_unset();
        session_destroy();
        return true;
    }

    function create_user( $username , $email , $password ){
        global $db;
        try{
            if( get_data_by_username( $username , 'email' ) ){
                throw new Exception("Email already exists. Please use a different email.");
            }
            if( validate_form( $username , $email , $password ) ){
                $hashed_password = password_hash($password , PASSWORD_DEFAULT);
                $query = $db->prepare("INSERT INTO users( username , email , password ) VALUES ( :username , :email , :password )");
                bind_param( $query , ":username" , $username );
                bind_param( $query , ":email" , $email );
                bind_param( $query , ":password" , $hashed_password );
                $query->execute();
            }
            return true;
        }catch( Exception $e ){
            $_SESSION['error'] = "Error: " . $e->getMessage();
            return false;
        }
    }

    function get_user($username = "__ristricted_username__", $limit = 15, $page = 1) {
        global $db;
        try {
            
            if ($username === "__ristricted_username__") {
                $offset = ($page - 1) * $limit;
                $query = $db->prepare(" SELECT * FROM users LIMIT {$limit} OFFSET {$offset} ");
                $query->execute();
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
            } else {                
                $query = $db->prepare("SELECT * FROM users WHERE username = :username");
                bind_param( $query , ":username" , $username );
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                
            }
            return $result;
        } catch (Exception $e) {
            $_SESSION['error'] ="Error: " . $e->getMessage();
            return false;
        }
    }

    function update_user($username, $email, $description) {
        global $db;
        try {
            if( !isset($_SESSION['user_id']) ){
                throw new Exception("No user_id in session. Cannot update without proper user_id.");
            }
            if( get_data_by_id( $_SESSION['user_id'] , 'username' ) && validate_form( $username , $email ) ){
                $query = $db->prepare("UPDATE users SET username = :username, email = :email, description = :description WHERE user_id = :user_id");
                $userId = $_SESSION['user_id'];
                bind_value( $query , ":user_id" , $userId );
                bind_value( $query , ":username" , $username );
                bind_value( $query , ":email" , $email );
                bind_value( $query , ":description" , $description );
                $result = $query->execute();
                $rowCount = $query->rowCount();
                
                if ($result) {
                    if ($rowCount > 0) {
                        $_SESSION['username'] = $username;
                        return true;
                    } else {
                        throw new Exception("No records were updated");
                    }
                } else {
                    throw new Exception("Update query execution failed");
                }
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            return false;
        }
    }

    function delete_user( $username ){
        global $db;
        try{
            if( !auth_user($username) ){
                throw new Exception("You are not the owner of this account");
            }

            $query = $db->prepare("DELETE FROM users WHERE user_id = :user_id");
            $user_id = $_SESSION['user_id'];
            bind_param( $query , ":user_id" , $user_id );
            if( $query->execute() ){
                logout();
                unset_errors();
                return true;
            }else{
                throw new Exception("Execution failure");
            }
        }catch( Exception $e ){
            $_SESSION['error'] = "Error: " . $e->getMessage();
            return false;
        }
    }

    // ----------------------setters----------------------
    function unset_errors(){
        global $model;
        unset($model);
        unset($_SESSION['error']);
    }

    function set_model( $title = "Error: " , $message= "Error Message." , $href = null , $btnText = null ){
        global $model;
        $model = [
            'status' => true,
            'title' => $title,
            'message' => $message,
            'href' => $href,
            'btnText' => $btnText
          ];
    }
    

?>