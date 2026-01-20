<?php  
 session_start();
 require_once 'config.php';

 if(isset($_POST['register'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role=$_POST['role'];

    $checkEmail=$conn->prepare("SELECT email FROM users WHERE email=?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkResult=$checkEmail->get_result();
    
    if($checkResult->num_rows > 0){
        $_SESSION['register_error']="Email is already registered!";
        $_SESSION['active_form']="register";
    } else{
        $stmt=$conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        
        if($stmt->execute()){
            $_SESSION['register_success']="Registration successful! You can now login.";
            $_SESSION['active_form']="login";
        } else{
            $_SESSION['register_error']="Registration failed! Error: " . $stmt->error;
            $_SESSION['active_form']="register";
        }
        $stmt->close();
    }
    $checkEmail->close();

    header("Location: index.php");
    exit();
 }

 if(isset($_POST['login'])){
    $email=$_POST['email'];
    $password=$_POST['password'];

    $result=$conn->query("SELECT * FROM users WHERE email='$email'");
    if($result->num_rows > 0){
        $user=$result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['name']=$user['name'];
            $_SESSION['email']=$user['email'];

            if($user['role']==='admin'){
                header("Location: admin_page.php");
            } else{
                header("Location: user_page.php"); 
            }
            exit();
        } else {
            $_SESSION['login_error']="Incorrect email or password!";
            $_SESSION['active_form']="login";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['login_error']="Incorrect email or password!";
        $_SESSION['active_form']="login";
        header("Location: index.php");
        exit();
    }
 }
?>
