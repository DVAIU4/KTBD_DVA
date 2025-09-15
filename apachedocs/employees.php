
<?
require_once('init.php');
check_auth();

// Добавление
if (isset($_POST['add_employee'])) {
    $sql = "INSERT INTO employees (empl_name, empl_surn, empl_job, empl_pass) 
            VALUES (:name, :surname, :job, :pass)";
    ora_query($sql, array(
        ':surname' => $_POST['surname'],
        ':name' => $_POST['name'],
        ':job' => $_POST['job'],
        ':pass' => $_POST['password']
    ));
    ora_query("COMMIT");
    header("Location: employees.php");
    exit;
}

// Удаление
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM employees WHERE empl_id = :id";
    ora_query($sql, array(':id' => (int)$_GET['delete']));
    ora_query("COMMIT");
    header("Location: employees.php");
    exit;
}

// Получение данных
$sql = "SELECT empl_id, empl_surn, empl_name, empl_job FROM employees";
$stid = ora_query($sql);
$employees = ora_fetch_all($stid);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Сотрудники</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #c4c9d9;
            color: #333;
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
            display: flex;
            justify-content: space-between; /* Размещаем таблицу и форму по краям */
            align-items: flex-start; /* Выравниваем по верхнему краю */
            min-height: 80vh;
        }

        /* Form Container */
        .form-container {
            background-color: #e0e5ef;
            padding: 20px;
            border: 2px solid #222;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px; /* Adjust as needed */
        }

        .form-container h2 {
            color: #000;
            margin-bottom: 20px;
            font-size: 1.5em;
            text-align: left;
        }

        .form-container .form-group {
            margin-bottom: 15px;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            color: #000;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container select {
            width: 95%;
            padding: 10px;
            border: 1px solid #000;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 5px;
        }

        .form-container select {
            appearance: none; /* Remove default arrow in some browsers */
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 15px;
        }

        .form-container input[type="text"]:focus,
        .form-container select:focus {
            border-color: #000;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .form-container .form-submit-button {
            background-color: #e0e5ef;
            color: #000;
            padding: 12px 20px;
            border: 2px solid #000;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .form-container .form-submit-button:hover {
            background-color: #d2d8e5;
        }

        /* Data Table */
        .data-table {
            width: 60%; /* Уменьшаем ширину таблицы */
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-right: 20px; /* Добавляем отступ справа */
        }

        .data-table th,
        .data-table td {
            padding: 8px 10px; /* Уменьшаем отступы в ячейках */
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em; /* Уменьшаем размер шрифта */
        }

        .data-table th {
            background-color: #0056b3;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Добавлены стили для чередования строк */
        .data-table tr.odd {
            background-color: #f2f2f2; /* Светлый оттенок серого */
        }

        .data-table tr.even {
            background-color: #e0e0e0; /* Темный оттенок серого */
        }

        .data-table tr:hover {
            background-color: #d0d0d0; /* Еще темнее при наведении */
        }

        .data-table a {
            color: #0056b3;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .data-table a:hover {
            color: #003d82;
        }

        /* Footer */
        .footer {
            background-color: #000;
            color: #fff;
            text-align: left;
            padding: 10px 20px;  /* Добавлен отступ слева и справа */
            font-size: 14px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-area {
                flex-direction: column;
                align-items: center;
            }

            .data-table {
                width: 100%;
                margin-right: 0;
            }

            .form-container {
                width: 90%;
            }

            .form-container input[type="text"],
            .form-container select {
                width: 100%;
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
<div class="user-info"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
</div>

<div class="content-area">
    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>ФИО</th>
            <th>Логин</th>
            <th>Должность</th>
            <?php if (strtolower($_SESSION['role']) == 'admin') echo "<th>Действия</th>"; ?>
        </tr>
        </thead>
        <tbody>
        <?php $i = 0; foreach ($employees as $emp): ?>
            <tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd'; ?>">
                <td><?php echo htmlspecialchars($emp['EMPL_ID']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_SURN']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_NAME']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_JOB']); ?></td>
                <?php if (strtolower($_SESSION['role']) == 'admin'): ?>
                    <td>
                        <a href="?delete=<?php echo $emp['EMPL_ID']; ?>"
                           onclick="return confirm('Удалить сотрудника?')">Удалить</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php $i++; endforeach; ?>
        </tbody>
    </table>

    <?php if (strtolower($_SESSION['role']) == 'admin'): ?>
        <div class="form-container">
            <h2>Добавить сотрудника</h2>
            <form method="post">
                <div class="form-group">
                    <label for="surname">ФИО:</label>
                    <input type="text" id="surname" name="surname" required>
                </div>
                <div class="form-group">
                    <label for="name">Логин:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="job">Должность:</label>
                    <select id="job" name="job" required>
                        <option value="admin">Администратор</option>
                        <option value="student">Студент</option>
                        <option value="ing_prog">Инженер - программист</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="text" id="password" name="password" required>
                </div>
                <button type="submit" name="add_employee" class="form-submit-button">Добавить</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<div class="footer">
	    ОАО "ААААААААААААУЧ"
</div>

</body>
</html>
