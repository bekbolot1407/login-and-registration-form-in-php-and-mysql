<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <link rel="stylesheet" href="style.css">
        <title>Login Form</title>
    </head>

    <body>
        <div class="container">
            <?php
                if(isset($_POST["login"])) {
                $email = $_POST["email"];
                $password = $_POST["password"];
                    require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);  // Выполняем заврос на БД
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    if($user) {
                        if(password_verify($password, $user["password"])) {
                            session_start();
                            $_SESSION["user"] = "yes";
                            header("Location: index.php");  // Если email и пароль верные то переводим его на другую страницу(в index.php).
                        die(); 
                        }
                        else {
                            echo "<div class='alert alert-danger'>Incorrect email address or password</div>";  // Выодим ошибку, введенные данные не верные(password).
                        }
                    }
                    else {
                        echo "<div class='alert alert-danger'>Incorrect email address or password</div>";  // Выодим ошибку, введенные данные не верные (email).
                    }
                
                }
            ?>

            <form action="login.php" method="post">
                <h4 class="title">Hello, i know you ?</h4>
                <div class="form-group">
                    <input type="email" placeholder="Enter Email:" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Enter Password:" name="password" class="form-control">
                </div>
                <div class="form-btn">
                    <input type="submit" value="login" name="login" class="btn btn-primary">
                </div>
            </form>
            <div>
                <p class="footer">Not registered yet? <a href="registration.php">Register Here</a></p>
            </div>
        </div>
    </body>

</html>