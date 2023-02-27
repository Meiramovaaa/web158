<?php
	include "config/base_url.php";
	include "config/db.php";
	if(isset($_GET['id'])){
		$id = $_GET['id'];
		$query = mysqli_query($con, 
		"SELECT * FROM blogs WHERE id=$id");
		if(mysqli_num_rows($query) > 0){
			$blog = mysqli_fetch_assoc($query);
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Редактировать блог</title>
	<?php include "views/head.php";?>
</head>

<?php include "views/header.php";?>

	<section class="container page">
		<div class="page-block">

			<div class="page-header">
				<h2>Редактировать блог</h2>
			</div>
			<form class="form" action="<?=$BASE_URL?>/api/blog/update.php?id=<?=$id?>" method="POST" enctype="multipart/form-data">
				
				<fieldset class="fieldset">
					<input class="input" type="text" name="title" placeholder="Заголовок" value="<?=$blog['title']?>">
				</fieldset>

				<fieldset class="fieldset">
				<select name="category_id" id="" class="input">
					<?php
						$categories = mysqli_query($con, "SELECT * FROM categories");
						while($cat = mysqli_fetch_assoc($categories)){
							if($blog['category_id'] == $cat['id']){
					?>
						<option selected value="<?=$cat['id']?>"><?= $cat['name']?></option>
					<?php
						}else{
					?>
						<option value="<?=$cat['id']?>"><?= $cat['name']?></option>
					<?php
					}
				}
					?>
				</select>
			</fieldset class="fieldset">

				<img width="300" src="<?=$BASE_URL?>/<?=$blog['img']?>" alt="">
				<fieldset class="fieldset">
					<button class="button button-yellow input-file">
						<input type="file" name="image">	
						Выберите картинку
					</button>
				</fieldset>
					
				<fieldset class="fieldset">
					<textarea class="input input-textarea" name="description" id="" cols="30" rows="10" placeholder="Описание"><?=$blog['description']?></textarea>
				</fieldset>
				<fieldset class="fieldset">
					<button class="button" type="submit">Сохранить</button>
				</fieldset>
			</form>

			
				<!-- <p class="text-danger"> Заголовок и Описание не могут быть пустыми!</p> -->



		</div>

	</section>
	
	
<?php
}
	}else{
		session_start();
		$nickname = $_SESSION['nickname'];
		header("Location:$BASE_URL/profile.php?nickname=$nickname&error=1");
	}
?>
	
</body>
</html>
