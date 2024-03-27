<?php
function getPublishedPosts() {
    global $conn;
    $publishedPosts = array(); 

    $query = "SELECT * FROM posts WHERE published = true";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $topic = getPostTopic($row['id']);
        
        $publishedPosts[] = array(
            'post_id' => $row['id'],
            'title' => $row['title'],
            'date' =>$row['created_at'],
            'topic' => $topic, 
            'image' => $row['image'], 
            'slug' => $row['slug']
        );
    }

    mysqli_free_result($result);

    return $publishedPosts;
}

function getPostTopic($post_id) {
    global $conn;

    // Requête MySQL paramétrée pour sélectionner le nom du topic associé à l'ID de post spécifié
    $query = "SELECT topics.name
              FROM post_topic
              JOIN topics ON post_topic.topic_id = topics.id
              WHERE post_topic.post_id = ?";
    
    // Préparation de la requête
    $stmt = mysqli_prepare($conn, $query);

    // Liaison de la You are now registered and logged invariable post_id à la requête
    mysqli_stmt_bind_param($stmt, "i", $post_id);

    // Exécution de la requête
    mysqli_stmt_execute($stmt);

    // Récupération du résultat
    mysqli_stmt_bind_result($stmt, $topic_name);
    mysqli_stmt_fetch($stmt);

    // Fermeture du statement
    mysqli_stmt_close($stmt);

    // Retourne le nom du topic
    return $topic_name;
}

function getAllTopics(){
    global $conn;
    $allTopics = array(); 

    $query = "SELECT * FROM topics";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {        
        $allTopics[] = array(
            'id' => $row['id'], 
            'name' => $row['name']
        ); 
    }

    mysqli_free_result($result);

    return $allTopics;
}

function getPublishedPostsByTopic($topic_id) {
    global $conn;
    // Préparez votre requête SQL avec une jointure pour récupérer les articles et leurs sujets correspondants
    $sql = "SELECT posts.*
            FROM post_topic
            JOIN posts ON post_topic.post_id = posts.id
            WHERE post_topic.topic_id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $topic_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $final_posts = array();
    while ($post = mysqli_fetch_assoc($result)) {
        $topic = getPostTopic($post['id']);
        
        $final_posts[] = array(
            'post_id' => $post['id'],
            'title' => $post['title'],
            'date' =>$post['created_at'],
            'topic' => $topic, 
            'image' => $post['image'], 
            'slug' => $post['slug']
        );
    }
    return $final_posts;
}


