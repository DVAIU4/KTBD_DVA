
<?
require_once('init.php');
check_auth();

// Добавление документа
if (isset($_POST['add_document'])) {
    $sql = "INSERT INTO documents (docs_name, docs_type, docs_date, docs_auth) 
            VALUES (:name, :doc_type, TO_DATE(:doc_date, 'DD.MM.YY'), :auth)";

    ora_query($sql, array(
        ':name' => $_POST['name'],
        ':doc_type' => $_POST['doc_type'],
        ':doc_date' => $_POST['doc_date'],
        ':auth' => $_POST['auth']
    ));
    ora_query("COMMIT");
    header("Location: documents.php");
    exit;
}

// Удаление документа
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM documents WHERE docs_id = :id";
    ora_query($sql, array(':id' => (int)$_GET['delete']));
    ora_query("COMMIT");
    header("Location: documents.php");
    exit;
}

// Получение списка документов
$sql = "SELECT docs_id, docs_name, docs_type, docs_auth, 
               TO_CHAR(docs_date, 'DD.MM.YY') as docs_date 
        FROM documents";
$stid = ora_query($sql);
$documents = ora_fetch_all($stid);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Документы</title>
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

         .data-table tr:nth-child(odd) {
            background-color: #f2f2f2; /* Белый для нечетных строк */
        }

        .data-table tr:nth-child(even) {
            background-color: #ffffff; /* Светло-серый для четных строк */
        }

        .data-table tr:hover {
            background-color: #e9ecef;
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
            padding: 10px 20px;
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
            <th>Название</th>
            <th>Тип</th>
            <th>Автор</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($documents as $document): ?>
            <tr>
                <td><?= htmlspecialchars($document['DOCS_ID']); ?></td>
                <td><?= htmlspecialchars($document['DOCS_NAME']); ?></td>
                <td><?= htmlspecialchars($document['DOCS_TYPE']); ?></td>
                <td><?= htmlspecialchars($document['DOCS_AUTH']); ?></td>
                <td><?= htmlspecialchars($document['DOCS_DATE']); ?></td>
                <td>
                    <a href="?delete=<?= $document['DOCS_ID'] ?>"
                       onclick="return confirm('Удалить документ?')">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="form-container">
        <h2>Добавить документ</h2>
        <form method="post">
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="doc_type">Тип:</label>
                <select id="doc_type" name="doc_type" required>
                    <option value="Manual">Manual</option>
                    <option value="Report">Report</option>
                    <option value="Specification">Specification</option>
                </select>
            </div>
            <div class="form-group">
                <label for="auth">Автор:</label>
                <input type="text" id="auth" name="auth" required>
            </div>
            <div class="form-group">
                <label for="doc_date">Дата (ДД.ММ.ГГ):</label>
                <input type="text" id="doc_date" name="doc_date" required
                       placeholder="ДД.ММ.ГГ"
                       pattern="\d{2}\.\d{2}\.\d{2}"
                       title="Введите дату в формате ДД.ММ.ГГ">
            </div>
            <button type="submit" name="add_document" class="form-submit-button">Добавить</button>
        </form>
    </div>
</div>

<div class="footer">
    ОАО "ААААААААААААУЧ"
</div>
</body>
</html>
