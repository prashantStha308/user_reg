<?php
    require_once "config.php";

    function bind_param( &$query , $param , &$value ){
        $query->bindParam( $param , $value );
    }
    function bind_value( &$query , $param , &$value ){
        $query->bindValue( $param , $value );
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

    function auth_current( $username ){
        try{
            if( isset($_SESSION['username']) && $_SESSION['username'] == $username  ){
                return true;
            }else{
                throw new Exception("You are not the owner");
            }
            
        }catch( Exception $e){
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function update_user( $key , $value ){
        global $db;
        try{
            // Validate the column name to prevent SQL injection
            $allowed_columns = ['username', 'email', 'password'];
            if (!in_array($key, $allowed_columns)) {
                throw new Exception("Invalid column name.");
            }

            $query = $db->prepare("UPDATE user SET {$key} :updateValue WHERE username = :uName");
            bind_param( $query , ":updateValue" , $value );
            bind_param( $query , ":uName" , $_SESSION['username'] );
            
            if( $query->execute() ){
                return true;
            }else{
                throw new Exception("Execution Failed!!");
            }
        }catch (Exception $e) {
            echo "Error: " . $e->getMessage();
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
        session_unset();
        session_destroy();
        return true;
    }
    

?>