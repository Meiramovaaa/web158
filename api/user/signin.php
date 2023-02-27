<?php
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(isset(
        $_POST['email'], 
        $_POST['password']) &&
        strlen($_POST['email']) > 0 &&
        strlen($_POST['password']) > 0){
            $email = $_POST['email'];
            $password = $_POST['password'];
            $hash = sha1($password);

            $prep = mysqli_prepare($con, 
            "SELECT * FROM users WHERE email=? AND password=?");
            mysqli_stmt_bind_param($prep, "ss", $email, $hash);
            mysqli_stmt_execute($prep);
            $user = mysqli_stmt_get_result($prep);

            if(mysqli_num_rows($user) == 0){
                header("Location:$BASE_URL/login.php?error=2");
                exit();
            }

            $row = mysqli_fetch_assoc($user);
            session_start();
            $_SESSION['nickname'] = $row['nickname'];
            $_SESSION['id'] = $row['id'];
            $nickname = $row['nickname'];

            header("Location:$BASE_URL/profile.php?nickname=$nickname");

    }else{
        header("Location:$BASE_URL/login.php?error=1");
    }
