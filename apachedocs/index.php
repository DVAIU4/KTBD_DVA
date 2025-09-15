<?php

require_once('init.php');
check_auth();

if (strtolower($_SESSION['role']) == 'admin') {
    $panelTitle = "Панель администратора";
} else {
    $panelTitle = "Панель работника";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $panelTitle; ?></title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #c4c9d9;
            color: #333;
            padding-top: 24px;
        }

        /* Header */
        .header {
            background-color: #222;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .header li {
            margin-right: 20px;
        }

        .header a {
            color: #fff;
            text-decoration: none;
        }

        .header .user-info {
            font-size: 14px;
        }

        /* Content Area */
        .content-area {
            padding: 20px;
            min-height: 80vh;
            text-align: center; /* Центрируем заголовок */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .content-area h1 {
            color: #0056b3; /* Цвет заголовка */
            margin-bottom: 20px;
        }

        .content-area p {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .content-area .username {
            font-weight: bold;
            color: #007bff; /* Цвет имени пользователя */
            text-decoration: none; /* Убираем подчеркивание */
        }

        /* Footer */
        .footer {
            background-color: #000;
            color: #fff;
            text-align: left;
            padding:10px 20px;
            font-size: 14px;
        }

        /* Responsive adjustments (можно настроить при необходимости) */
        @media (max-width: 768px) {
            .content-area {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <ul>
        <li><a href="employees.php">Сотрудники</a></li>
        <li><a href="products.php">Продукция</a></li>
        <li><a href="equipment.php">Оборудование</a></li>
        <li><a href="components.php">Компоненты</a></li>
        <li><a href="defect.php">Брак</a></li>
        <li><a href="documents.php">Документы</a></li>
        <li><a href="report.php">Отчет</a></li>
        <li><a href="logout.php">Выход</a></li>
    </ul>
    <div class="user-info"><?php echo htmlspecialchars($_SESSION['username']); ?></div> <!--  Имя пользователя из сессии -->
</div>

<div class="content-area">
    <h1><?php echo $panelTitle; ?></h1>
    <p>Добро пожаловать!</p>
	<p>Личный кабинет пользователя 
       <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
    </p>
	<p>Доступные вкладки:</p>
	<a href="employees.php">Сотрудники</a>
	<a href="products.php">Продукция</a>
	<a href="equipment.php">Оборудование</a>
	<a href="components.php">Компоненты</a>
	<a href="defect.php">Брак</a>
	<a href="report.php">Отчет</a>
	<a href="logout.php">Выход</a>
</div>

<div class="footer">
    ОАО "ААААААААААААУЧ"
</div>
</body>
</html>
