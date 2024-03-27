<?php
    // Admin user variables
    $admin_id = 0;
    $isEditingUser = false;
    $username = "";
    $email = "";
    $isEditingUser = false;

    // Topics variables
    $topic_id = 0;
    $isEditingTopic = false;
    $topic_name = "";

    // general variables
    $errors = [];

    /* - - - - - - - - - -
    -
    - Admin users actions
    -
    - - - - - - - - - - -*/

    // if user clicks the create admin button
    if (isset($_POST['create_admin'])) {
        createAdmin($_POST);
    }

    //if user clicks the edit admin button
    if (isset($_GET['edit-admin'])) {
        $isEditingUser = true;
        $admin_id = $_GET['edit-admin'];
        editAdmin($admin_id);
    }

    // if user clicks the update admin button
    if (isset($_POST['update_admin'])) {
        updateAdmin($_POST);
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * - Returns all admin users and their corresponding roles
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    function getAdminUsers(){
        global $conn, $roles;
        $users = array(); 

        $query = "SELECT * FROM users WHERE role='Admin' or role='Author'";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {        
            $users[] = array(
                'id' => $row['id'],
                'username' => $row['username'],
                'email' =>$row['email'],
                'role' => $row['role']
            );
        }

        mysqli_free_result($result);
        return $users;
    }
        
    /* * * * * * * * * * * * * *
    * - Returns all admin roles
    * * * * * * * * * * * * * */
    function getAdminRoles(){
        global $conn;
        $roles = array(); 

        $query = "SELECT * FROM roles";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {        
            $roles[] = array(
                'role' => $row['name'], 
                'id' => $row['id']
            ); 
        }

        mysqli_free_result($result);
        return $roles;
    }
    /* * * * * * * * * * * * * * * * * * * * * * *
    * - Receives new admin data from form
    * - Create new admin user
    * - Returns all admin users with their roles
    * * * * * * * * * * * * * * * * * * * * * * */
    function createAdmin($request_values){
        global $conn;
        global $errors;
        $username = $request_values['username'];
        $email = $request_values['email'];
        $password = $request_values['password'];
        $password2 = $request_values['passwordConfirmation'];
        $role = $request_values['role_id']; 

        if (empty($username)) {
            array_push($errors, "Username required");
        }
        if (empty($email)) {
            array_push($errors, "Email required");
        }
        if (empty($role)) {
            array_push($errors, "Role required");
        }
        if (empty($password)) {
            array_push($errors, "Password required");
        }
        if ($password2 != $password) {
            array_push($errors, "Different passwords");
        }


        $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);
        $existing_user = mysqli_fetch_assoc($check_result);

        if ($existing_user) {
            if ($existing_user['username'] == $username) {
                array_push($errors, "Username already exists");
            }
            if ($existing_user['email'] === $email) {
                array_push($errors, "Email already exists");
            }
        }

        if (empty($errors)) {
            $password = md5($password);
            $sql = "INSERT INTO users (username, email, role, password) VALUES ('$username', '$email', '$role', '$password')";
            $result = mysqli_query($conn, $sql);
            
            if (!$result) {
                array_push($errors, "Error inserting record: " . mysqli_error($conn));
            } else {
                $_SESSION['message'] = "The user have been add";
            }
        }
    }

    /*/* * * * * * * * * * * * * * * * * * * * *
    * - Takes admin id as parameter
    * - Fetches the admin from database
    * - sets admin fields on form for editing
    * * * * * * * * * * * * * * * * * * * * * */
    function editAdmin($admin_id){
        global $conn, $username, $isEditingUser, $admin_id, $email, $password, $role;
        
        $query = "SELECT * FROM users WHERE id='$admin_id' LIMIT 1";
        $result = mysqli_query($conn, $query);
        $admin = mysqli_fetch_assoc($result);
    
        // Remplir les variables globales avec les données récupérées
        $username = $admin['username'];
        $email = $admin['email'];    
    }
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * - Receives admin request from form and updates in database
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    function updateAdmin($request_values){
        global $conn, $errors;
    
        // Récupérer les valeurs mises à jour du formulaire
        $admin_id = $request_values['admin_id'];
        $username = $request_values['username'];
        $email = $request_values['email'];
        $password = $request_values['password'];
        $passwordConfirmation = $request_values['passwordConfirmation'];
        $role = $request_values['role_id'];

        // Validation des données
        if (empty($username)) { array_push($errors, "Username required"); }
        if (empty($email)) { array_push($errors, "Email required"); }
        if ($password != $passwordConfirmation) { array_push($errors, "Passwords differents"); }
        
        // Vérifier l'existence de l'utilisateur à mettre à jour
        $check_query = "SELECT * FROM users WHERE id='$admin_id' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);
        $existing_user = mysqli_fetch_assoc($check_result);
    
        if (!$existing_user) {
            array_push($errors, "User not found");
        }
    
        // S'il n'y a pas d'erreurs, procéder à la mise à jour de l'administrateur
        if (empty($errors)) {
            // Hasher le mot de passe avant de le mettre à jour
            $passwordHash = md5($password);
            // Mettre à jour l'administrateur dans la base de données
            $sql = "UPDATE users SET username='$username', email='$email', password='$passwordHash', role='$role' WHERE id='$admin_id'";
            $result = mysqli_query($conn, $sql);
            
            if ($result) {
                $_SESSION['message'] = "Admin user updated successfully";
            } else {
                array_push($errors, "Error updating record: " . mysqli_error($conn));
            }
        }
    }
    
    
    

    