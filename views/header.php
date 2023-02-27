<header class="header container">
	<div class="header-logo">
	    <a href="index.php">Decode Blog</a>	
	</div>
	<form method="GET" class="header-search">
		<input name="q" type="text" class="input-search" placeholder="Поиск по блогам">
		<button type="submit" class="button button-search">
			<img src="images/search.svg" alt="">	
			Найти
		</button>
	</form>
	<div>
		
		<?php
		if(isset($_SESSION['nickname'])){
			$id = $_SESSION['id'];
			$user = mysqli_query($con, 
			"SELECT * FROM users WHERE id=$id");
			$user_info = mysqli_fetch_assoc($user);
		

	if(isset($user_info['img'])){?>
		<a href="<?=$BASE_URL?>/profile.php?nickname=<?=$_SESSION['nickname']?>">
			<img class="avatar"  src="<?=$BASE_URL?>/<?=$user_info['img']?>" alt="Avatar">
		</a>
		<?php
		}else{
		?>
		<a href="<?=$BASE_URL?>/profile.php?nickname=<?=$_SESSION['nickname']?>">
			<img class="avatar"  src="<?=$BASE_URL?>/images/incognito.jpg" alt="Avatar">
		</a>
		<?php
		}
		?>
		<?php
	}else{
		?>
			<div class="button-group">
				<a href="<?=$BASE_URL?>/register.php" class="button">Регистрация</a>
				<a href="<?=$BASE_URL?>/login.php" class="button">Вход</a>
			</div>
		<?php
		}
		?>
		
	</div>
</header>