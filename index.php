<?php
require './model/functions.php';

session_start();

use Model\Database;
use Model\GetProducts;

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['category']) {
        case '1':
            $_SESSION['category'] = 'Категория 1';
            break;
        case '2':
            $_SESSION['category'] = 'Категория 2';
            break;
        case '3':
            $_SESSION['category'] = 'Категория 3';
            break;
        case 'delete':
            $_SESSION['category'] = null;
            break;
    }
    $_SESSION['before_price'] = $_POST['before_price'] ?? null;
    $_SESSION['after_price'] = $_POST['after_price'] ?? null;
    $_SESSION['name'] = $_POST['name'] ?? null;

    header("Location: /");
    exit;
}

$products = new GetProducts(
    $_SESSION['category'] ?? null,
    $_SESSION['before_price'] ?? null,
    $_SESSION['after_price'] ?? null,
    $_SESSION['name'] ?? null,
);

$result = $products->result;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фильтры</title>
</head>

<body>

    <form method="post">
        <select name="category">
            <option value="delete">Все</option>
            <option value="1">Категория 1</option>
            <option value="2">Категория 2</option>
            <option value="3">Категория 3</option>
        </select>
        <input name="before_price" placeholder="Цена от">
        <input name="after_price" placeholder="Цена до">
        <input name="name" placeholder="Название">
        <button>Отправить</button>
    </form>

    <?php foreach ($result as $value): ?>
        <h2>
            <?= $value['name'] ?>
        </h2>
        <p>
            <?= $value['category'] ?>
        </p>
        <p>
            <?= $value['price'] ?>
        </p>
    <?php endforeach; ?>

</body>

</html>