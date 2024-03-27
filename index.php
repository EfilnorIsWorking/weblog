<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include('includes/public/registration_login.php'); ?>
<title>MyWebSite | Home </title>

</head>

<body>

	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->

		<!-- Banner -->
		<?php include(ROOT_PATH . '/includes/public/banner.php'); ?>
		<!-- // Banner -->

		<!-- Messages -->
		
		<!-- // Messages -->

		<!-- content -->
		<div class="content">
			<h2 class="content-title">Recent Articles</h2>
			<hr>
			<?php
			include(ROOT_PATH . '/includes/all_functions.php');
			require_once 'includes/all_functions.php';

			$publishedPosts = getPublishedPosts();

			if ($publishedPosts) {
				foreach ($publishedPosts as $post) {
					?>
					<div class="post">
						<div class="category"><?php echo $post['topic']; ?></div>
						<img src="<?php echo BASE_URL . 'static/images/' . $post['image']; ?>" class="post_image" alt="">
						<div class="post_info">
							<h2 class="content-title"><?php echo $post['title']; ?></h2>
							<p class="post_info"><span><?php echo $post['date']; ?></span></p>
							<p class="post_info"><span class="read_more"><a href="single_post.php?post-slug=<?php echo $post['slug']; ?>"> Read more </a></span></p>
						</div>
					</div> 
				<?php
				}
			} else {
				echo "Aucun post publié trouvé.";
			}
			?>

			



		</div>
		<!-- // content -->


	</div>
	<!-- // container -->


	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->