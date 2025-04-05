<?php
    // ----------------------mis----------------------
    function bind_param( &$query , $param , &$value ){
        $query->bindParam( $param , $value );
    }
    function bind_value( &$query , $param , &$value ){
        $query->bindValue( $param , $value );
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