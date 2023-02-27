<?php
	include "config/base_url.php";
	include "config/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Регистрация в систему</title>
	<?php include "views/head.php";?>
</head>
<body>
<?php include "views/header.php";?>
<?php
    $id = $_GET['id'];
    $user = mysqli_query($con, 
    "SELECT * FROM users WHERE id=$id");
    $row = mysqli_fetch_assoc($user);
?>
	<section class="container page">
		<div class="auth-form">
            <h1>Редактирование профиля</h1>
			<form class="form" action="<?=$BASE_URL?>/api/user/update.php?id=<?=$_SESSION['id']?>" method="POST" enctype="multipart/form-data">
                <fieldset class="fieldset">
                    <input class="input" value="<?=$row['email']?>" type="text" name="email" placeholder="Введите email">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" value="<?=$row['full_name']?>"  type="text" name="full_name" placeholder="Полное имя">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="text" value="<?=$row['nickname']?>"  name="nickname" placeholder="Nickname">
                </fieldset>
                <fieldset class="fieldset">
                    <button class="button button-yellow input-file">
                        <input type="file" name="image">	
                        Выберите картинку
                    </button>
                </fieldset>
                <fieldset class="fieldset">
                    <textarea name="about" cols="52" rows="10"></textarea>
                </fieldset>

                <fieldset class="fieldset">
                    <button class="button" type="submit">Сохранить</button>
                </fieldset>
			</form>
		</div>
	</section>

    <?php
        if(isset($_GET['error']) && $_GET['error'] = '1'){
    ?>
        <script>
            alert("Заполните все поля!!!")
        </script>
    <?php
        }
    ?>
</body>
</html>