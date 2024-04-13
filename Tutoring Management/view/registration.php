<?php
   session_start();
   include("../settings/connection.php");
   include("../function/function.php");

   if($_SERVER['REQUEST_METHOD'] == 'POST'){

      // Collection data from the POST request.
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $email = $_POST['email'];
      $gender = $_POST['gender'];
      $class = $_POST['class'];
      $major = $_POST['major'];
      $phone = $_POST['phone'];
      $password = $_POST['password'];
      $profile = isset($_FILES['profile']) ? $_FILES['profile']['name'] : '';
      $uploadDirectory = "../uploads/profiles/";
      $uploadedFile = $profile ? $uploadDirectory . basename($_FILES['profile']['name']) : '';
      $registrationDate = date("Y-m-d");

      if(!empty($firstName) && !empty($lastName) && !empty($email) && !empty($gender) && !empty($class) && !empty($major) && !empty($phone) && !empty($password) && !empty($profile)){

         // Checking if the passwords match.
         if($password !== $_POST['confirmPassword']){
            echo "<script>alert('Passwords do not match');</script>";
            echo "<script>window.location.href='../view/registration.php';</script>";
         }

         else{

            // Hashing the password.
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Defining the path for the uploaded profile image.
            $profilePath = $profile ? $uploadDirectory . basename($_FILES['profile']['name']) : '';

            // Uploading the profile image.
            if($profile !== ''){
               if(move_uploaded_file($_FILES['profile']['tmp_name'], $uploadedFile)){
                  $profilePath = $uploadedFile;
               }

               else{
                  echo "<script>alert('Failed to upload profile image');</script>";
                  echo "<script>window.location.href='../view/registration.php';</script>";
               }
            }

            else{
               $profilePath = ''; // No File Uploaded.
            }

            // Inserting the information into the database, students table.
            $query = "INSERT INTO students (FirstName, LastName, Email, Gender, Class, MajorID, Phone, Password, Profile, RegistrationDate) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($statement, "ssssiissss", $firstName, $lastName, $email, $gender, $class, $major, $phone, $password, $profilePath, $registrationDate);

            if(mysqli_stmt_execute($statement)){
               echo "<script>alert('Registration successful');</script>";
               echo "<script>window.location.href='../view/login.php';</script>";
            }
            
            else{
               echo "<script>alert('Registration unsuccessful');</script>";
               echo "<script>window.location.href='../view/registration.php';</script>";
            }
         }
      }

      else{
         echo "<script>alert('All fields are required');</script>";
         echo "<script>window.location.href='../view/registration.php';</script>";
      }
   }

   // Fetch majors from database for user registration
   $majorsQuery = "SELECT MajorID, MajorName FROM majors";
   $majorsResult = mysqli_query($connection, $majorsQuery);
   $majors = mysqli_fetch_all($majorsResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registration Form</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/registration.css">

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
      <h3>Register</h3>
      
      <div class="register">
         <div class="form-field">
            <input type="text" pattern="[A-Za-z\s]+" placeholder="First Name" name="firstName" id="firstName" class="box" required>
            <i class='bx bxs-user'></i>
         </div>
         <div class="form-field">
            <input type="text" pattern="[A-Za-z\s]+" placeholder="Last Name" name="lastName" id="lastName" class="box" required>
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
               <option value="">Select Major</option>
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
            <p>Already have an account? <a href="../view/login.php">Log in</a></p>
         </div>
         <button type="submit" class="register-button">Register</button>
      </div>
   </form>

</section>

<!-- custom js file link  -->
<script src="../js/registration.js"></script>
</body>
</html>
