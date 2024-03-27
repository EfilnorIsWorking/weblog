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

$isEditingPost = false;

if (isset($_GET['edit-post'])) {
    $isEditingPost = true;
    $post_id = $_GET['edit-post'];
    editPost($post_id);
}

if (isset($_POST['create_post'])) {
    createPost($_POST);
}

if (isset($_POST['update_post'])) {
    updatePost($_POST);
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
function getAllTopics(){
    global $conn;
    $topics = array(); 

    $query = "SELECT * FROM topics";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {        
        $topics[] = array(
            'name' => $row['name'], 
            'id' => $row['id']
        ); 
    }

    mysqli_free_result($result);
    return $topics;
}

function editPost($post_id) {
    global $conn, $title, $post_slug, $body, $isEditingPost, $post_id;

    $query = "SELECT * FROM posts WHERE id='$post_id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);
    
    // Remplir les variables globales avec les données récupérées
    $title = $admin['title'];
    $body = $admin['body'];    
}

function updatePost($request_values){
    global $conn, $title, $post_slug, $body, $isEditingPost, $post_id, $errors;

    // Récupérer les valeurs mises à jour du formulaire
    $post_id = $request_values['post_id'];
    $title = $request_values['title'];
    $body = $request_values['body'];
    $topic_id = $request_values['topic_id'];

    if (empty($title)) { array_push($errors, "Title required"); }
    if (empty($body)) { array_push($errors, "Body required"); }
    if (empty($topic_id)) { array_push($errors, "Topic required"); }

    $check_query = "SELECT * FROM posts WHERE id='$post_id' LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);
    $existing_post = mysqli_fetch_assoc($check_result);

    $post_slug = strtolower(str_replace(' ', '-', $title));

    if (!$existing_post) {
        array_push($errors, "Post not found");
    }

    if (empty($errors)) {
        // Vérification de l'existence du topic
        $check_topic_query = "SELECT * FROM topics WHERE id='$topic_id' LIMIT 1";
        $check_topic_result = mysqli_query($conn, $check_topic_query);
        if (mysqli_num_rows($check_topic_result) == 0) {
            array_push($errors, "Invalid topic");
        }

        if (empty($errors)) {
            $updated_at = date('Y-m-d H:i:s');
            // Mettre à jour le post dans la base de données
            $sql = "UPDATE posts SET title='$title', body='$body', slug='$post_slug', updated_at='$updated_at'  WHERE id='$post_id'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                // Mettre à jour le lien entre le post et le topic dans la table post_topic
                $update_post_topic_sql = "UPDATE post_topic SET topic_id='$topic_id' WHERE post_id='$post_id'";
                $update_post_topic_result = mysqli_query($conn, $update_post_topic_sql);

                if (!$update_post_topic_result) {
                    array_push($errors, "Error updating post topic: " . mysqli_error($conn));
                } else {
                    $_SESSION['message'] = "Post updated successfully";
                }
            } else {
                array_push($errors, "Error updating record: " . mysqli_error($conn));
            }
        }
    }
}


// delete blog post
function deletePost($post_id){
    global $conn;
    $sql = "DELETE FROM posts WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Post successfully deleted";
        header("location: posts.php");
        exit(0);
    }
}
// delete blog post
function togglePublishPost($post_id, $message){
    global $conn;
    $sql = "UPDATE posts SET published=!published WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = $message;
        header("location: posts.php");
        exit(0);
    }
}

function createPost($request_values){
    global $conn;
    global $errors;

    $title = $request_values['title'];
    $body = $request_values['body'];
    $topic_id = $request_values['topic_id'];

    // Création du slug à partir du titre en minuscules avec des tirets
    $slug = strtolower(str_replace(' ', '-', $title));

    if (empty($title)) { array_push($errors, "Title required"); }
    if (empty($body)) { array_push($errors, "Body required"); }
    if (empty($topic_id)) { array_push($errors, "Topic required"); }

    // Vérification de l'existence du topic
    $check_topic_query = "SELECT * FROM topics WHERE id='$topic_id' LIMIT 1";
    $check_topic_result = mysqli_query($conn, $check_topic_query);
    if (mysqli_num_rows($check_topic_result) == 0) {
        array_push($errors, "Invalid topic");
    }

    if (empty($errors)) {
        // Date de création actuelle
        $created_at = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO posts ('user_id', title, body, created_at, slug) VALUES ('1', '$title', '$body', '$created_at', '$slug')";
        $result = mysqli_query($conn, $sql);
            
        if ($result) {
            // Récupération de l'ID du post inséré
            $post_id = mysqli_insert_id($conn);

            // Insertion du lien entre le post et le topic dans la table post_topic
            $post_topic_sql = "INSERT INTO post_topic (post_id, topic_id) VALUES ('$post_id', '$topic_id')";
            $post_topic_result = mysqli_query($conn, $post_topic_sql);

            if (!$post_topic_result) {
                array_push($errors, "Error linking post to topic: " . mysqli_error($conn));
            } else {
                $_SESSION['message'] = "The post has been created";
            }
        } else {
            array_push($errors, "Error inserting record: " . mysqli_error($conn));
        }
     }
}

