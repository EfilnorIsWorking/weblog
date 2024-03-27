<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/post_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

<!--//BTW: ideally we need to create a role_user table (users<->role_user<->roles)-->
<!--// role_user(id, user_id,role_id)-->
<?php
// Get all posts from DB
$post = getAllPosts(); // table roles


?>

<title>Admin | Manage posts</title>
</head>

<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>
	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<!-- Display records from DB-->
		<div class="table-div">

			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/public/messages.php') ?>
			<?php if (empty($post)) : ?>
				<h1>No post in the database.</h1>
			<?php else : ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Author</th>
						<th>Title</th>
                        <th>Views</th>
						<th>Publish</th>
                        <th>Edit</th>
                        <th>Delete</th>
					</thead>
					<tbody>
						<?php foreach ($post as $key => $post) : ?>
							<tr>
								<td><?php echo $key + 1; ?></td>
								<td>
									<?php echo $post['author']; ?>
								</td>
								<td>
                                    <a href="/weblog/single_post.php?post-slug=<?php echo $post['slug']?>"> <?php echo $post['title'] ?></a>
                                </td>
                                <td>
                                    <?php echo $post['views'] ?>
                                <td>
                                    <a class="fa fa-pencil btn publish" href="posts.php?unpublish=<?php echo $post['id'] ?>">
                                </td>
								<td>
									<a class="fa fa-pencil btn edit" href="create_post.php?edit-post=<?php echo $post['id'] ?>">
									</a>
								</td>
								<td>
									<a class="fa fa-trash btn delete" href="create_post.php?delete-post=<?php echo $post['id'] ?>">
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Display records from DB -->

	</div>

</body>

</html>