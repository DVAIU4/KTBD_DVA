
<?
header('Content-Type: text/html; charset=utf-8');
ob_start();

require_once('init.php');
require('fpdf.php');
check_auth();

// Получение списка продуктов
$sql_products = "SELECT prds_id, prds_name, prds_tp_type FROM products";
$stid_products = ora_query($sql_products);
$products = ora_fetch_all($stid_products);

// Генерация PDF
if (isset($_GET['product_id'])) {
    // Очистка буфера
    while (ob_get_level()) {
        ob_end_clean();
    }

    $product_id = (int)$_GET['product_id'];
    $sql = "SELECT p.*, eq.eqpt_name, d.docs_name
            FROM products p
            LEFT JOIN equipment eq ON p.prds_eqpt_id = eq.eqpt_id
            LEFT JOIN documents d ON p.prds_docs_id = d.docs_id
            WHERE p.prds_id = :id";
    $stid = ora_query($sql, array(':id' => $product_id));
    $result = ora_fetch_all($stid);
    $product = isset($result[0]) ? $result[0] : array();

    if (empty($product)) {
        die("Изделие не найдено.");
    }

    // Генерация PDF
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->Image('a.png', 0, 0, 297, 210);
    $pdf->SetFont('Arial', '', 12);

    $izdelie_text = isset($product['PRDS_NAME']) ? $product['PRDS_NAME'] : '';
    $tp_type_text = isset($product['PRDS_TP_TYPE']) ? $product['PRDS_TP_TYPE'] : '';

    $pdf->SetXY(40, 30);
    $pdf->Cell(50, 7, 'IZDELIE: ' . $izdelie_text, 0, 0);

    $pdf->SetXY(185, 57);
    $pdf->Cell(40, 7, 'TP_type: ' . $tp_type_text, 0, 0);

    $stages = array(
        array('text' => '1. Podgotovka komponentov', 'x' => 18, 'y' => 88), 
        array('text' => '2. Cborka (' . (isset($product['PRDS_TP_TYPE']) ? $product['PRDS_TP_TYPE'] : '') . ')', 'x' => 18, 'y' => 95), 
        array('text' => '3. Testirovanie', 'x' => 18, 'y' => 102), 
        array('text' => '4. Ypakovka', 'x' => 18, 'y' => 109)  
    );
    foreach ($stages as $stage) {
        $pdf->SetXY($stage['x'], $stage['y']);
        $pdf->Cell(0, 7, $stage['text'], 0, 1);
    }

    $pdf->Output('marshrut.pdf', 'D');
    exit;
}
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Маршрутная карта</title>
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
            text-align: center; /* Центрируем форму */
            min-height: 80vh;
        }

        .content-area h2 {
            color: #000;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .content-area form {
            display: inline-block; /* Чтобы форма занимала только необходимую ширину */
        }

        .content-area select {
            padding: 10px;
            border: 1px solid #000;
            border-radius: 4px;
            box-sizing: border-box;
            margin-right: 10px; /* Отступ между select и button */
            appearance: none;
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 15px;
        }

        .content-area select:focus {
            border-color: #000;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .content-area input[type="submit"] {
            background-color: #e0e5ef;
            color: #000;
            padding: 12px 20px;
            border: 2px solid #000;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .content-area input[type="submit"]:hover {
            background-color: #d2d8e5;
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
                padding: 10px;
            }

            .content-area form {
                display: block;
            }

            .content-area select {
                width: 100%;
                margin-bottom: 10px;
                margin-right: 0;
            }

            .content-area input[type="submit"] {
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
    <h2>Выберите изделие</h2>
    <form method="get">
        <select name="product_id" required>
            <?php foreach ($products as $prod) { ?>
                <option value="<?php echo $prod['PRDS_ID']; ?>"
                        data-tp-type="<?php echo htmlspecialchars($prod['PRDS_TP_TYPE']); ?>">
                    <?php echo htmlspecialchars($prod['PRDS_NAME']); ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" value="Сформировать PDF">
    </form>
</div>

<div class="footer">
    ОАО "ААААААААААААУЧ"
</div>

</body>
</html>