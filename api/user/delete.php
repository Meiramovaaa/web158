<?php
    include "../../config/db.php";
    include "../../config/base_url.php";
    session_start();
    if(isset($_GET['id']) && intval($_GET['id'])){
        $id = $_GET['id'];
        mysqli_query($con, 
        "DELETE FROM users WHERE id=$id");
        session_destroy();
        header("Location:$BASE_URL/index.php");
    }
?>