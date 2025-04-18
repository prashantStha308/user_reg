<?php
    require_once "../server.php";
    require_once "utility.php";

    // ----------------------auth and validation----------------------
    function auth_user( $key , $value) {
        try {
            if( !isset($_SESSION['user_id']) ){
                throw new Exception("NO user login detected");
            }
            if ($value === null || $value === ''){
                throw new Exception("Value is not set");
            }
            $res = get_user_data($key , $value);
            if ( !$res['success'] || !isset($res['data']['user_id']) ) {
                throw new Exception($res['message']);
            }
            $userId = $res['data']['user_id'];
            
            if ( $_SESSION['user_id'] !== $userId) {
                throw new Exception("You are not the owner of this account");
            }
            
            return true;
        } catch (Exception $e) {
            set_error( 'AuthenticationError' , $e );
            return false;
        }
    }
    

    function validate_form( $username , $email , $password = null ){
        // regexes:
        $pReg = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/'; //password
        $uReg = '/^[a-zA-Z0-9 _-]{3,50}$/'; //username
        $username = trim($username);
        $email = trim($email);
        try{
            if( empty($username) || empty($email) ){
                throw new Exception("Required Fields missing");
            }
            // Only check for password if it's provided
            if (isset($password)) {
                if (empty($password)) {
                    throw new Exception("Required Fields missing");
                }
                // validate password
                if( !preg_match( $pReg , $password ) ){
                    throw new Exception("Password should be an alphanumeric value containing at least 1 lowercase letter, 1 uppercase letter, and 1 digit");
                }
            }
    
            // Validate email
            if( !filter_var( $email , FILTER_VALIDATE_EMAIL ) ){
                throw new Exception('Invalid email');
            }
            // validate username
            if( !preg_match( $uReg , $username ) ){
                throw new Exception("Username should only contain alphanumeric values and should be of length 3 to 50");
            }
    
            return true;
        }catch( Exception $e ){
            set_error( 'ValidationError' , $e );
            return false;
        }
    }
    
    // ----------------------Project essentials----------------------
    function login( $email , $password ){
        global $db;
        try{
            if( isset($_SESSION['user_id']) ){
                throw new Exception("You are already logged in.");
            }
            $query = $db->prepare("SELECT * FROM users WHERE email = :email ");
            $query->bindValue( ":email" , $email );
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);
            
            // Check if user exists and verify password
            if( $user ){
                if ( password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    return true;
                } else {
                    $_SESSION['password_count'] = ($_SESSION['password_count'] ?? 0) + 1;
                    if( $_SESSION['password_count'] >= PASSWORD_ATTEMPT_MAX ){
                        $_SESSION['pass_blocked'] = true;
                        $_SESSION['password_message'] = "Too many failed attemps. You have been blocked for 5 minutes";
                    }
                    $_SESSION['password_timestamp'] = time();
                    throw new Exception("Invalid password. Please try again");
                }
            }else{
                throw new Exception("Unregistered email. Try again");
            }
        }catch( Exception $e ){
            set_error( 'LoginError' , $e );
            return false;
        }
    }

    function create_user( $username , $email , $password  ){
        global $db;
        $username = trim($username);
        $email = strtolower(trim($username));
        try{
            $getUsername = get_user_data("username", $username);
            $getEmail = get_user_data("email", $email);
            if ($getEmail['success']) {
                throw new Exception("Email already in use. Please use a different email.");
            }
            if ($getUsername['success']) {
                throw new Exception("Username already in use. Please use a different username");
            }
            $hashed_password = password_hash($password , PASSWORD_DEFAULT);
            $query = $db->prepare("INSERT INTO users( username , email , password ) VALUES ( :username , :email , :password )");
            $query->bindParam(':username', $username);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $hashed_password);
            if( $query->execute() ){
                session_regenerate_id(true);
                return true;
            }else{
                throw new Exception("Database error: Failed to create user" . var_export($query->errorInfo() , true) );
            }
            
        }catch( Exception $e ){
            set_error( 'UserCreationError' , $e );
            return false;
        }
    }

    function update_user($username, $email) {
        global $db;
        try {
            if( !isset($_SESSION['user_id']) ){
                throw new Exception("No user logged in.");
            }
            // Check if the username or email already exists in the database
            $existingUsername = get_user_data('username', $username);
            $existingEmail = get_user_data('email', $email);

            if ($existingUsername['success'] && $existingUsername['data']['user_id'] != $_SESSION['user_id']) {
                throw new Exception("Username already taken. Please choose a different username.");
            }

            if ($existingEmail['success'] && $existingEmail['data']['user_id'] != $_SESSION['user_id']) {
                throw new Exception("Email already registered. Please choose a different email.");
            }

            $query = $db->prepare("UPDATE users SET username = :username, email = :email WHERE user_id = :user_id");
            $query->bindValue(":user_id", $_SESSION['user_id']);
            $query->bindValue(":username", $username);
            $query->bindValue(":email", $email);
    
            if ($query->execute() && $query->rowCount() > 0) {
                $_SESSION['username'] = $username;
                return true;
            }else{
                throw new Exception("No records were updated");
            }
        } catch (Exception $e) {
            set_error( 'UserUpdateError' , $e );
            return false;
        }
    }
    

    function delete_user( $username ){
        global $db;
        try{
            if( !isset($_SESSION['user_id']) || !auth_user( 'username' , $username) ){
                throw new Exception("You are not the owner of this account");
            }

            $query = $db->prepare("DELETE FROM users WHERE user_id = :user_id");
            $user_id = $_SESSION['user_id'];
            $query->bindValue(":user_id" , $user_id);
            $query->execute();
            session_unset();
            session_destroy();
            return true;
        }catch( Exception $e ){
            set_error( 'UserDeletionError' , $e );
            return false;
        }
    }

    function logout($username){
        try{
            if( !isset($_SESSION['user_id']) ){
                throw new Exception("No user logged in.");
            }

            if(auth_user('username' , $username)){
                unset_errors();
                session_unset();
                session_destroy();
                return true;
            }
        }catch(Exception $e){
            set_error( 'LogoutError' , $e );
            return false;
        }
    }
    
?>