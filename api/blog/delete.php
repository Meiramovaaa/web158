<?php
    include "../../config/db.php";
    include "../../config/base_url.php";

    $id = $_GET['id'];
    session_start();
    $nickname = $_SESSION['nickname'];
    mysqli_query($con, "DELETE FROM blogs WHERE id=$id");
    header("Location:$BASE_URL/profile.php?nickname=$nickname");
?>