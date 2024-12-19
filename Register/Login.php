<?php
include "Connect.php";

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT userID, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            header("location: main.php");
                            exit();
                        } else {
                            $password_err = "The password you entered is incorrect.";
                        }
                    }
                } else {
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #67b26f, #4ca2cd); 
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background: #ffffff;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
    width: 100%;
    max-width: 380px;
    transition: all 0.3s ease;
}

.container:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

h2 {
    font-size: 28px;
    color: #333;
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.form-control {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    outline: none;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #67b26f;
    box-shadow: 0 0 8px rgba(103, 178, 111, 0.5);
}

.text-danger {
    font-size: 14px;
    color: #d9534f;
    margin-top: 5px;
}

.btn-success {
    background-color: #67b26f; 
    border: none;
    color: #fff;
    padding: 12px 20px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    width: 100%;
}

.btn-success:hover {
    background-color: #4ca2cd; 
    transform: translateY(-3px); 
}

.btn-success:active {
    background-color: #4ca2cd; 
    transform: translateY(1px); 
}

p {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #333;
}

p a {
    color: #67b26f;
    text-decoration: none;
    font-weight: 600;
}

p a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" id="username" value="<?php echo $username; ?>">
                <span class="text-danger"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" id="password">
                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>
            <button type="submit" class="btn btn-success">Login</button>
            <p>Don't have an account? <a href="dangky.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>
