<?php
   // Start the session
   session_start();

   // Including the connection file
   include("../settings/connection.php");
   include("../function/function.php");
   include("../function/tutor_function.php");

   // Check if the user is logged in
   if (!isset($_SESSION['StudentID'])) {
      // Redirect to login page if the user is not logged in
      header("Location:../view/login.php");
      die;
   }

   // Initializing an error message variable
   $loginError = '';

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $email = $_POST['email'];
      $password = $_POST['password'];

      if($statement = $connection->prepare('SELECT Tutors.TutorID, Tutors.Password FROM Tutors INNER JOIN Students ON Tutors.StudentID = Students.StudentID WHERE Students.Email = ?')){
         $statement->bind_param('s', $email);
         $statement->execute();
         
         $statement->store_result();

         if($statement->num_rows > 0){
            $statement->bind_result($tutorID, $hashedPassword);
            $statement->fetch();

            if(password_verify($password, $hashedPassword)){
               // Setting the session variables
               $_SESSION['TutorID'] = $tutorID;

               header('Location: ../view/tutor_dashboard.php');
               exit;   
            }
            else{
               $loginError = 'Invalid password';
            }
         }
         else{
            $loginError = 'No tutor found for this email';
         }
         $statement->close();
      }

   }
   $connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutor Login Page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/tutor_login.css">

</head>
<body>

<header class="header">
   
   <section class="flex">

      <p class="logo">Learning Sphere</p>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>
   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img class="image" src="<?php echo htmlspecialchars($_SESSION['ProfileImage']); ?>"  alt="">
      <h3 class="name"><?php echo htmlspecialchars($_SESSION['Fullname']); ?></h3>
      <p class="role">Student</p>
      <a href="../view/profile.php" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="../view/home.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="../view/tutors.php"><i class="fas fa-user"></i><span>Tutors</span></a>
      <a href="../view/courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
      <a href="../view/about.php"><i class="fas fa-question"></i><span>About</span></a>
      <a href="../view/contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
      <a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
   </nav>

</div>

<section class="form-container">
   <h1 class="heading">Log into your tutor account</h1>

   <div class="form-container">
      <form action="" method="POST" enctype="multipart/form-data">
         <h3>Login</h3>
         <div class="notify">
            <?php if ($loginError != ''): ?>
               <p class="error"><?php echo $loginError; ?></p>
            <?php endif; ?>
         </div>
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
               <p>Don't have a tutor account? <a href="../view/tutor_register.php">Register</a></p>
            </div>
            <button type="submit" class="login-button">Login</button>
         </div>
      </form>
   </div>

</section>

<!-- custom js file link  -->
<script src="../js/script.js"></script>

   
</body>
</html>