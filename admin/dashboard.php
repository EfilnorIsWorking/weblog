<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>
<title>Admin | Dashboard</title>
</head>

<body>
	<div class="header">
		<div class="logo">
			<a href="<?php echo BASE_URL . 'admin/dashboard.php' ?>">
				<h1>MyWebSite - Admin</h1>
			</a>
		</div>
		<?php if (isset($_SESSION['user'])) : ?>
			<div class="user-info">
				<span><?php echo $_SESSION['user']['username'] ?></span> &nbsp; &nbsp;
				<a href="<?php echo BASE_URL . '/logout.php'; ?>" class="logout-btn">logout</a>
			</div>
		<?php endif ?>
	</div>
	<div class="container dashboard">
		<h1>Welcome</h1>
		<div class="stats">
			<a href="users.php" class="first">
				<span>
					<?php
					 	$query = "SELECT COUNT(*) AS nombre_utilisateurs FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 31 DAY)";
						$result = mysqli_query($conn, $query);
						 
						if ($result) {
							$row = mysqli_fetch_assoc($result);
							$nombre_utilisateurs = $row['nombre_utilisateurs'];
							echo $nombre_utilisateurs;
						} else {
							echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
						} 
					?>
				</span> <br>
				<span>Newly registered users</span>
			</a>
			<a href="posts.php">
				<span>
					<?php
					 	$query = "SELECT COUNT(*) AS published_posts FROM posts WHERE published = 1";
						$result = mysqli_query($conn, $query);
						 
						if ($result) {
							$row = mysqli_fetch_assoc($result);
							$published_posts = $row['published_posts'];
							echo $published_posts;
						} else {
							echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
						} 
					?>
				</span> <br>
				<span>Published posts</span>
			</a>
			<a>
				<span>43</span> <br>
				<span>Published comments</span>
			</a>
		</div>
		<br><br><br>
		<div class="buttons">
			<a href="users.php">Add Users</a>
			<a href="posts.php">Add Posts</a>
		</div>
	</div>
</body>

</html>
