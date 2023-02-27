<?php
include "../../config/base_url.php";
include "../../config/db.php";
if(isset($_GET['id'], $_POST['nickname'],  $_POST['full_name'],  $_POST['email']) && 
intval($_GET['id']) && 
strlen($_POST['nickname']) > 0 && 
strlen($_POST['full_name']) > 0 && 
strlen($_POST['email']) > 0){
    $id = $_GET['id'];
    $nickname = $_POST['nickname'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];

    if(isset($_POST['about'], $_FILES['image'], $_FILES['image']['name']) && 
    strlen($_POST['about']) > 0 && 
    strlen($_FILES['image']['name']) > 0){

        $query_img = mysqli_query($con, 
        "SELECT img FROM users WHERE id=$id");
        if(mysqli_num_rows($query_img) > 0){
            $row = mysqli_fetch_assoc($query_img);
            $old_path = __DIR__."../../".$row['img'];
            if(file_exists($old_path)){
            unlink($old_path);
            }
        }
        $ext = end(explode(".", $_FILES['image']['name']));
        $image_name = time().".".$ext;

        move_uploaded_file($_FILES['image']['tmp_name'],
        "../../images/users/$image_name");
        $path = "images/users/$image_name";
        
        $prep = mysqli_prepare($con, 
        "UPDATE users SET nickname=?, full_name=?, email=?, about=?, img=? WHERE id=?");
        mysqli_stmt_bind_param($prep, "sssssi", $nickname, $full_name, 
        $email, $_POST['about'], $path, $id);
        mysqli_stmt_execute($prep);
        }
    else{ 
        $prep = mysqli_prepare($con, 
        "UPDATE users SET nickname=?, full_name=?, email=? WHERE id=?");
        mysqli_stmt_bind_param($prep, "sssi", $nickname, $full_name, $email, $id);
        mysqli_stmt_execute($prep);    
    }
    session_start();
    $_SESSION['nickname'] = $nickname;
    $nick = $_SESSION['nickname'];
    header("Location:$BASE_URL/profile.php?nickname=$nick");
}
else
{
    header("Location:$BASE_URL/editindex.php?error=1");
}
?>