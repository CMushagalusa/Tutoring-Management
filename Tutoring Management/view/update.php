<?php
   // Start the session
   session_start();

   // Including the connection file
   include("../settings/connection.php");
   include("../function/function.php");

   // Check if the user is logged in
   if (!isset($_SESSION['StudentID'])) {
      // Redirect to login page if the user is not logged in
      header("Location:../view/login.php");
      die;
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile Form</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/update.css">

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

      <div class="profile">
         <img class="image" src="<?php echo htmlspecialchars($_SESSION['ProfileImage']); ?>"  alt="">
         <h5 class="name"><?php echo htmlspecialchars($_SESSION['Fullname']); ?></h5>
         <p class="role">Student</p>
         <a href="../view/profile.php" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="../view/tutor_login.php" class="option-btn">Login</a>
            <a href="../view/tutor_register.php" class="option-btn">Be Tutor</a>
         </div>
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

<section class="update">
   <h1 class="heading">Update Your Profile</h1>

   <div class="form-container">
      <form action="" method="POST" enctype="multipart/form-data">
         <h3>Update</h3>
         
         <div class="register">
            <div class="form-field">
               <input type="text" pattern="[A-Za-z\\s]+" placeholder="First Name" name="firstName" id="firstName" class="box" required>
               <i class='bx bxs-user'></i>
            </div>
            <div class="form-field">
               <input type="text" pattern="[A-Za-z\\s]+" placeholder="Last Name" name="lastName" id="lastName" class="box" required>
               <i class='bx bxs-user'></i>
            </div>
                        
            <div class="form-field">
               <input type="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" placeholder="Email" name="email" id="email" class="box" required>
               <i class='bx bxs-envelope'></i>
            </div>
            <div class="form-field">
               <select name="gender" class="choose" required>
                  <option value="">Select Gender</option>
                  <option value="Female">Female</option>
                  <option value="Male">Male</option>
                  <option value="Other">Other</option>               
               </select>
            </div>
            
            <div class="form-field">
               <select name="class" class="choose" required>
                  <option value="">Select Class</option>
                  <option value="2024">2024</option>
                  <option value="2025">2025</option>
                  <option value="2026">2026</option>
                  <option value="2027">2027</option>
               </select>
            </div>
            <div class="form-field">
               <select name="major" class="choose" required>
                  <option value="">Select Course</option>
                  <option value="1">Business Administration</option>
                  <option value="2">Computer Engineering</option>
                  <option value="3">Computer Science</option>
                  <option value="4">Economics</option>
                  <option value="5">Electrical and Electronic Engineering</option>
                  <option value="6">Management Information Systems</option>
                  <option value="7">Machanical Engineering</option>
                  <option value="8">Mechatronics Engineering</option>
               </select>     
            </div>
            <div class="form-field">
               <input type="tel" placeholder="Phone Number" name="phone" id="phone" pattern="\+[0-9]{1,3}[0-9]{4,14}(?:x.+)?$" title="Include the country code" class="box" required>
               <i class="bx bxs-phone"></i>
            </div>
            <div class="form-field">
               <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" placeholder="Password" name="password" id="password" class="box" required>
               <i class='bx bxs-lock-alt' ></i>
            </div>
            <div class="form-field">
               <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword" class="box" required>
               <i class='bx bxs-lock-alt' ></i>
            </div>
            <div class="form-field">
               <input type="file" accept="image/*" name="profile" class="box">
            </div>
            <div class="login-link">
               <p>Already have a tutor account? <a href="../view/login.php">Log in</a></p>
            </div>
            <button type="submit" class="register-button">Update Profile</button>
         </div>
      </form> 
   </div>

</section>


<!-- custom js file link  -->
<script src="../js/script.js"></script>
   
</body>
</html>