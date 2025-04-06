<?php
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