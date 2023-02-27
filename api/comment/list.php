<?php
    include "../../config/db.php";
    include "../../config/base_url.php";

    if(!isset($_GET['id']) || !intval($_GET['id'])){
        exit();
    }

    $id = $_GET['id'];

    $query = mysqli_query($con, 
    "SELECT c.*, u.full_name FROM comments c
    LEFT OUTER JOIN users u 
    ON c.author_id=u.id 
    WHERE c.blog_id=$id");

    $comments = array();
    // php => js   json_encode()
    // js = > json_decode()
    if(mysqli_num_rows($query) == 0){
        echo json_encode($comments);
        exit();
    }

    while($com = mysqli_fetch_assoc($query)){
        $comments[] = $com;
    }

    echo json_encode($comments);
?>