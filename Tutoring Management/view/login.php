<?php
   session_start();
   include("../settings/connection.php");
   include("../function/function.php");

   if($_SERVER['REQUEST_METHOD'] == 'POST'){
      
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(!empty($email) && !empty($password)){

            // Reading the information from the database.
            $query = "SELECT * FROM students WHERE Email = ? LIMIT 1";
            $statement = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            mysqli_stmt_execute($statement);

            $result = mysqli_stmt_get_result($statement);

            if($result && mysqli_num_rows($result) > 0){
                $user_data = mysqli_fetch_assoc($result);

                // Validating the password.
                if (password_verify($password, $user_data['Password'])){

                    session_regenerate_id(true);
                    $_SESSION['StudentID'] = $user_data['StudentID'];
                    $_SESSION['Fullname'] = $user_data['FirstName']. ' ' . $user_data['LastName'];
                    $_SESSION['ProfileImage'] = $user_data['Profile'] ?? '../images/pic-1.jpg';
                    
                    header("Location:../view/home.php");
                    die;
                }

                else{
                    echo '<div class="alert alert-danger" role="alert">Invalid Password</div>';
                    echo '<a href="../view/login.php">Login</a>';
                }
            }
        }

        else{
            echo '<div class="alert alert-danger" role="alert">Please fill all the fields</div>';
            echo '<a href="../view/login.php">Login</a>';
        }
   }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login Form</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/loggin.css">

</head>
<body>

<header class="header">
   <section class="flex">
      <p class="logo">Learning Sphere</p>
      <div class="icons">
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>
   </section>
</header>   

<section class="form-container">

    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Login</h3>
      
        <div class="login">
            <div class="form-field">
                <input type="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" placeholder="Email" name="email" id="email" class="box" required>
                <i class='bx bxs-envelope'></i>
            </div>
            
            <div class="form-field">
                <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" placeholder="Password" name="password" id="password" class="box" required>
                <i class='bx bxs-lock-alt' ></i>
            </div>
                    
            <div class="remember-forgot">
                <label><input type="checkbox">Remember Me</label>
                <a href="password.php">Forgot Password</a>
            </div>
    
            <div class="register-link">
                <p>Don't have an account? <a href="../view/registration.php">Register</a></p>
            </div>
            <button type="submit" class="login-button">Login</button>
        </div>
   </form>
</section>

<!-- custom js file link  -->
<script src="../js/registration.js"></script>
</body>
</html>
