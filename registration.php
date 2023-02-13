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
        <title>Registration Form</title>
    </head>

    <body>
        <div class="container">
            <form action="registration.php" method="post">
                <?php
                // Определяем переменные
                    if(isset($_POST["submit"])) {
                    $name = $_POST["name"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $repeat_password = $_POST["repeat_password"];
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $errors = array();
                    // Определяем ошибки
                    if(empty($name) OR empty($email) OR empty($password) OR empty($repeat_password)) {
                        array_push($errors, "All feilds are reqiured");
                    }
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        array_push($errors, "Email is not valide");
                    }
                    if(strlen($password) < 6 ) {
                        array_push($errors, "Password must be at least 8 charactes long");
                    }
                    if($password !== $repeat_password) {
                        array_push($errors, "Password does not match");
                    }
                    
                    require_once "database.php"; // Читаем файл в котором у нас подключение к MySQL
                    // Проверяем есть введенный email при регистрации в базе и если есть то выдаем ошибку "такая почта уже существует" 
                    $sql = "SELECT * FROM users WHERE email = '$email'";
                    $result = mysqli_query($conn, $sql);
                    $rowCount = mysqli_num_rows($result);
                    if($rowCount > 0) {
                        array_push($errors, "Email already exists");
                    }
                    
                    // Выводим ошибки(если они есть) в окно для пользователя.
                    if(count($errors) > 0 ) {
                        foreach($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                        }
                    }
                    // Если ошибок нет, то добавляем нового пользователя в базу данных
                    else {
                        // Грузим нового пользователя в БД
                        $sql = "INSERT INTO users(name, email, password) VALUES(?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                        if($prepareStmt) {
                            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $passwordHash);
                            mysqli_stmt_execute($stmt);
                            echo "<div class='alert alert-success'>You have successfully registered</div>";
                        }
                        else {
                            die("Something went wrong");
                        }
                    }
                }
                ?>
                <h4 class="title">Hello Stranger</h4>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Name:">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email:">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password:">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="repeat_password" placeholder="Reapeat password:">
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </form>
            <div>
                <p class="footer">Already registered? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </body>

</html>