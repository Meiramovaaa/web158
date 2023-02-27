<?php
    include "../../config/db.php";
    include "../../config/base_url.php";

    if(isset($_POST['title'],
    $_POST['category_id'],
    $_POST['description'],
    $_GET['id']) &&
    strlen($_POST['title']) > 0 &&
    strlen($_POST['description']) > 0 &&
    intval($_POST['category_id']) &&
    intval($_GET['id'])){
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $blog_id = $_GET['id'];
        $cat_id = $_POST['category_id'];
        session_start();
        $author_id = $_SESSION['id'];

        if(isset($_FILES['image'], $_FILES['image']['name']) &&
        strlen($_FILES['image']['name']) > 0){
            $query_img = mysqli_query($con, 
            "SELECT img FROM blogs WHERE id=$blog_id");
            if(mysqli_num_rows($query_img) > 0){
                $row = mysqli_fetch_assoc($query_img);
                // $row['img'] ===> "images/blogs/234567890.png" 
                // __DIR__  ==> localhost/web158/api/blog/update.php../../images/blogs/234567890.png
                $old_path = __DIR__."../../".$row['img'];
                if(file_exists($old_path)){
                    unlink($old_path);
                }
            }
            $ext = end(explode(".", $_FILES['image']['name'])); //"2345678""png"
            $image_name = time().".".$ext;
            move_uploaded_file($_FILES['image']['tmp_name'],
            "../../images/blogs/$image_name");
            $path = "images/blogs/$image_name";

            $prep = mysqli_prepare($con, 
            "UPDATE blogs SET title=?, description=?, category_id=?, img=? 
            WHERE id=? AND author_id=?");
            mysqli_stmt_bind_param($prep, "ssisii", $title, $desc, $cat_id,
            $path, $blog_id, $author_id);
            mysqli_stmt_execute($prep);

        }else{
            $prep = mysqli_prepare($con, 
            "UPDATE blogs SET title=?, description=?, category_id=? WHERE 
            id=? AND author_id=?");
            mysqli_stmt_bind_param($prep, "ssiii", $title, $desc, $cat_id,
            $blog_id, $author_id);
            mysqli_stmt_execute($prep);
        }

        $nickname = $_SESSION['nickname'];
        header("Location:$BASE_URL/profile.php?nickname=$nickname");
    }else{
        header("Location:$BASE_URL/editblog.php?error=1");
    }
?>