<?php
if (session_id()) {
    session_destroy();
}
session_start();
require_once('oracle.php');

if (isset($_POST['login'])) {
    $sql = "SELECT empl_id, empl_name, empl_job, empl_pass FROM employees 
            WHERE empl_name = :name AND empl_pass = :pass";

    $stid = ora_query($sql, array(
        ':name' => $_POST['username'],
        ':pass' => $_POST['password']
    ));

    if (OCIFetchInto($stid, $row, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $_SESSION['user_id'] = $row['EMPL_ID'];
        $_SESSION['username'] = $row['EMPL_NAME'];
        $_SESSION['role'] = $row['EMPL_JOB'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Авторизация</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #c4c9d9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: #e0e5ef;
            padding: 30px;
            border: 2px solid #222;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-container h2 {
            color: #000;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .login-container .form-group {
            margin-bottom: 15px;
        }

        .login-container label {
            display: block;
            margin-bottom: 5px;
            color: #000;
            font-weight: bold;
            text-align: left;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 95%;
            padding: 10px;
            border: 1px solid #000;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 5px;
        }

        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border-color: #000;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .login-container .login-button {
            background-color: #e0e5ef;
            color: #000;
            padding: 12px 20px;
            border: 2px solid #000;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .login-container .login-button:hover {
            background-color: #d2d8e5;
        }

        .login-container .error-message {
            color: red;
            margin-top: 10px;
        }

        .footer {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            font-size: 14px;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Авторизация</h2>
    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="username">Логин:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login" class="login-button">Войти</button>
    </form>
</div>

<div class="footer">
    ОАО "ААААААААААААУЧ" © <?= date('Y') ?>
</div>

</body>
</html>
