<?php
/*--------------------
post_functions.php
------------------------*/
if (isset($_GET['unpublish'])) {
    $post_id = $_GET['unpublish'];
    UnpublishingPost($post_id);
}

if (isset($_GET['publish'])) {
    $post_id = $_GET['publish'];
    PublishingPost($post_id);
}
/* - - - - - - - - - -
- Post functions
- - - - - - - - - - -*/
// get all posts from WEBLOG DATABSE
function getAllPosts() {
    global $conn ;

    $posts = array() ;

    $sql = "SELECT * FROM posts" ;
    $result = mysqli_query($conn, $sql) ;
    
    while ($row = mysqli_fetch_array($result)) {
        $posts[] = array(
            'id' => $row['id'],
            'author' => getPostAuthorById($row['user_id']),
            'title' => $row['title'],
            'slug' => $row['slug'],
            'views' => $row['views'],
            'published' => $row['published']
        );
    }

    return $posts ;
}

// get the author/username of a post
//cette fonction est dans post_functions.php
function getPostAuthorById($user_id){
    global $conn ;
    $sql = "SELECT username FROM users WHERE id=$user_id" ;
    $result = mysqli_query($conn, $sql) ;

    if ($result) {
    // return username
        return mysqli_fetch_assoc($result)['username'] ;
    } else {
        return null ;
    }
}

function PublishingPost($post_id){
    global $conn ;
    $sql = "UPDATE posts SET published=1 WHERE id=$post_id" ;
    $result = mysqli_query($conn, $sql) ;

    if ($result) {
        $_SESSION['message'] = "Post published state changed!" ;
        header("location: posts.php") ;
        exit(0) ;
    }
}

function UnpublishingPost($post_id){
    global $conn ;
    $sql = "UPDATE posts SET published=0 WHERE id=$post_id" ;
    $result = mysqli_query($conn, $sql) ;

    if ($result) {
        $_SESSION['message'] = "Post published state changed!" ;
        header("location: posts.php") ;
        exit(0) ;
    }
}
