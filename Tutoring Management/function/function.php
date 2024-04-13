<?php
function check_login($connection){
    // Check if the session variable for pid is set
    if(isset($_SESSION['StudentID'])){
        // Use the pid from the session
        $Student = $_SESSION['StudentID'];

        $query = "SELECT * FROM students WHERE StudentID = '$Student' LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0){
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    else{
        // Redirect to login page if not logged in
        header("Location:../view/login.php");
        die;
    }
}