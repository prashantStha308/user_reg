<?php
    // getters
    function get_user_data( $key , $value ){
        global $db;
        $validKey = ['username' , 'user_id' , 'email'];
        try{
            if( !in_array($key , $validKey) ){
                throw new Exception("Invalid key.");
            }
            $query = $db->prepare("SELECT user_id , username , email , description FROM users WHERE {$key} = :value ");
            $query->bindParam( ":value" , $value );
            if( $query->execute()){
                if( $query->rowCount() > 0 ){
                    $res = $query->fetch(PDO::FETCH_ASSOC);
                    $data = [
                        'success' => true,
                        'data' => $res,
                        'message' => 'User found'
                    ];
                    return $data;
                }else{
                throw new Exception("User not found");
                }
            }else{
                throw new Exception("Database error, failed to execute SQL statement");
            }
        }catch( Exception $e ){
            $data = [
                'success' => false,
                'data' => null,
                'message' => 'Error: ' . $e->getMessage()
            ];
            return $data;
        }
    }

    function get_users( $limit = 15, $page = 1 ) {
        global $db;
        try {
                $offset = ($page - 1) * $limit;
                $query = $db->prepare(" SELECT * FROM users LIMIT {$limit} OFFSET {$offset} ");
                if( $query->execute() ){
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    return $res;
                }else{
                    throw new Exception("Unexpected error occured while executing SQL statement");
                }
        } catch (Exception $e) {
            global $error;
            $error = "GetUserError: " . $e->getMessage();
            return false;
        }
    }

    // ----------------------setters----------------------
    function unset_errors(){
        global $model;
        unset($model);
        unset($_SESSION['error']);
        unset($_SESSION['error_time']);
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