<?php
    echo "<link rel='stylesheet' href='../output.css'>";
    $current_page = "dashboard";
    require_once "../components/header.php";
    set_model("Login required", "Please login to view this page", "login.php", "Login");
    include "../components/model.php"; 
?>