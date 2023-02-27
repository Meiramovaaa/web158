<?php
	include "config/base_url.php";
	include "config/db.php";
	include "config/time.php";

	$sql = "SELECT b.*, u.nickname, c.name FROM blogs b
	LEFT OUTER JOIN users u ON b.author_id=u.id
	LEFT OUTER JOIN categories c ON b.category_id=c.id";
	$limit = 3;

	$sql_count = "SELECT CEIL(COUNT(*)/$limit) as total_page FROM blogs b
	LEFT OUTER JOIN users u ON b.author_id=u.id 
	LEFT OUTER JOIN categories c ON b.category_id=c.id";
	$category = '';
	$q = '';
	$page = 1;

	if(isset($_GET['cat_id'])){
		$category = $_GET['cat_id'];
		$sql .= " WHERE b.category_id=$category";
		$sql_count .= " WHERE b.category_id=$category";
	}

	if(isset($_GET['q'])){
		$q = strtolower($_GET['q']); // "aa"
		$sql .= " WHERE LOWER(b.title) LIKE ? OR 
		LOWER(b.description) LIKE ? OR 
		LOWER(u.nickname) LIKE ? OR 
		LOWER(c.name) LIKE ?";

		$sql_count .=  " WHERE LOWER(b.title) LIKE ? OR 
		LOWER(b.description) LIKE ? OR 
		LOWER(u.nickname) LIKE ? OR 
		LOWER(c.name) LIKE ?";
	}

	if(isset($_GET['page']) && intval($_GET['page'])){
		// LIMIT skip, limit page = 1 ==> 0, 3 // skip = page - 1 * limit
		// LIMIT skip, limit page = 2 ==> 3, 3
		$skip = ($_GET['page'] - 1) * $limit;
		$sql .= " LIMIT $skip, $limit";
		$page = $_GET['page'];
	}else{
		$sql .= " LIMIT $limit";
	}

	if($q){
		$param = "%$q%"; // "%aa%"
		$prep = mysqli_prepare($con, $sql);
		mysqli_stmt_bind_param($prep, "ssss", $param, $param, $param, $param);
		mysqli_stmt_execute($prep);
		$blogs = mysqli_stmt_get_result($prep);

		$prep_count = mysqli_prepare($con, $sql_count);
		mysqli_stmt_bind_param($prep_count, "ssss", $param, $param, $param, $param);
		mysqli_stmt_execute($prep_count);
		$count_arr = mysqli_stmt_get_result($prep_count);
		$count = mysqli_fetch_assoc($count_arr);
	}else{
		$blogs = mysqli_query($con, $sql);
		$count_arr = mysqli_query($con, $sql_count);
		$count = mysqli_fetch_assoc($count_arr);
	}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Главная</title>
    <?php include "views/head.php";?>
</head>
<body>
<?php include "views/header.php";?>


<section class="container page">
	<div class="page-content">
			<h2 class="page-title">Блоги по программированию</h2>
			<p class="page-desc">Популярные и лучшие публикации по программированию для начинающих
 и профессиональных программистов и IT-специалистов.</p>
	
		<div class="blogs">
			<?php
				if(mysqli_num_rows($blogs) > 0){
					while($row = mysqli_fetch_assoc($blogs)){	
			?>
				<div class="blog-item">
					<img class="blog-item--img" src="<?=$BASE_URL?>/<?=$row['img']?>" alt="">
					<div class="blog-header">
						<a href="<?=$BASE_URL?>/blog-details.php?id=<?=$row['id']?>"><h3> <?=$row['title']?></h3></a>
					</div>
					<p class="blog-desc"><?=$row['description']?></p>

					<div class="blog-info">
						<span class="link">
							<img src="images/date.svg" alt="">
							<?=time_elapsed_string(strtotime($row['date']))?>
						</span>
						<span class="link">
							<img src="images/visibility.svg" alt="">
							21
						</span>
						<a class="link">
							<img src="images/message.svg" alt="">
							4
						</a>
						<span class="link">
							<img src="images/forums.svg" alt="">
							<?=$row['name']?>
						</span>
						<a class="link" href="<?=$BASE_URL?>/profile.php?nickname=<?=$row['nickname']?>">
							<img src="images/person.svg" alt="">
							<?=$row['nickname']?>
						</a>
					</div>
				</div>
			<?php	
				}
			}else{
			?>
				<h3>0 blogs</h3> 
			<?php
			}
			?>
			
		</div>
		
		<div class="pagination-blog">
			<?php
				$cat_str = '';
				if($category){
					$cat_str = "&cat_id=".$category;
				}
				$q_str = '';
				if($q){
					$q_str = '&q='.$q;
				}
				if($page != 1){
			?>
				<a href="?page=<?=$page-1?><?=$q_str?><?=$cat_str?>">Prev</a>
			<?php
				}
				for($i = 1; $i <= $count['total_page']; $i++){
			?>
				<a href="?page=<?=$i?><?=$cat_str?><?=$q_str?>"><?=$i?></a>
			<?php
				}
				if($page != $count['total_page']){
			?>
				<a href="?page=<?=$page+1?><?=$q_str?><?=$cat_str?>">Next</a>
			<?php
				}
			?>
		</div>
	</div>
	<?php
		include "views/categories.php";
	?>
</section>	
</body>
</html>