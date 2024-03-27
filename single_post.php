<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include(ROOT_PATH . '/includes/all_functions.php');
require_once 'includes/all_functions.php'; ?>

<title>  <?php echo $post['title'] ?> | MyWebSite</title>

</head>

<body>

<div class="container">
    <!-- Navbar -->
        <?php include( ROOT_PATH . '/includes/public/navbar.php'); ?>
    <!-- // Navbar -->
    <div class="content" >
        <!-- Page wrapper -->
        <div class="post-wrapper">
            <!-- full post div -->
            <div class="full-post-div">
                <?php
                if (isset($_GET['post-slug'])) {
                    $post_slug = $_GET['post-slug'];
                    global $conn;
                
                    $query = "SELECT * FROM posts WHERE slug = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "s", $post_slug);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if ($post = mysqli_fetch_assoc($result)) {
                ?>

                <h2 class="post-title"><?php echo $post['title']; ?></h2>
                <div class="post-body-div"><?php echo $post['body']; ?></div>
                
                <?php
                    } else {
                        echo "Article non trouvé.";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "Identifiant de l'article manquant dans l'URL.";
                }
                ?>
                </div>
            </div>
            <!-- // full post div -->
        </div>
        <!-- // Page wrapper -->

        <!-- post sidebar -->
        <div class="post-sidebar">
            <div class="card">
                <div class="card-header">
                <h2>Topics</h2>
                </div>
                <div class="card-content">
                <?php 
                $topics = getAllTopics();
                foreach($topics as $topic){ 
                    ?>
                    <a href="filtered_posts.php?post-slug=<?php echo $topic['id']; ?>"> <?php echo $topic['name']?> </a> 
                    <?php
                }
                ?>
                </div>
            </div>
        </div>
        <!-- // post sidebar -->
    </div>
</div>
<!-- // content -->
<?php include( ROOT_PATH . '/includes/public/footer.php'); ?>