<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include(ROOT_PATH . '/includes/all_functions.php');
	require_once 'includes/all_functions.php'; ?>

<title>MyWebSite | Home </title>

</head>

<body>

	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->

		<!-- content -->
		<div class="content">
			
        <?php
            if (isset($_GET['post-slug'])) {
                $topic_id = $_GET['post-slug'];
            
                $topicPosts = getPublishedPostsByTopic($topic_id);

                if ($topicPosts) {
                    foreach ($topicPosts as $post) {
                    ?>
                        <div class="post">
                            <div class="category"><?php echo $post['topic']; ?></div>
                            <img <?php echo BASE_URL . 'static/images/' . $post['image']; ?> class="post_image" alt="">
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
            }
        ?>	

		</div>
		<!-- // content -->


	</div>
	<!-- // container -->


	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->