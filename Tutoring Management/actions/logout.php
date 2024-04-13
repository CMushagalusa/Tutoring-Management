<?php
    session_start();

    if (isset($_SESSION['StudentID'])){
        unset($_SESSION['StudentID']);

        // Destroy all data registered the session
        session_destroy();
        header("Location:../view/login.php");
        
    }