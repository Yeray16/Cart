<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total = 0;

if (!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['amount'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $subtotal = $price * $amount;
    $total = $subtotal + $total;

if (isset($_POST['action']) && $_POST['action'] === 'increase' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $_SESSION['cart'][$name]['amount']++;
}

if (isset($_POST['action']) && $_POST['action'] === 'decrease' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $_SESSION['cart'][$name]['amount']--;
    if ($_SESSION['cart'][$name]['amount'] <= 0) {
        unset($_SESSION['cart'][$name]); 
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'clear') {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compra</title>
    <style>
        table {border-collapse: collapse; width: 100%;}
        td, th {border: 1px solid black; padding: 8px; text-align: center;}
        button {padding: 5px 10px; margin: 2px;}
    </style>
</head>
<body>
    <h1>Carrito de compra</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;

            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $name => $product) {
                    $price = $product['price'];
                    $amount = $product['amount'];
                    $subtotal = $price * $amount;
                    $total += $subtotal;
                    printf('<tr><td>%s</td><td>%.2f</td><td>%d</td><td>%.2f</td><td><form method="post"><button type="submit" name="action" value="increase">+</button><td><button type="submit" name="action" value="decrease">-</button></form></tr>', 
                    $name, $price, $amount, $subtotal);
                  }
                }
            } else {
                echo "<tr><td colspan='5'>El carrito está vacío</td></tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th colspan="2"><?= $total ?></th>
            </tr>
        </tfoot>
    </table>
    <form method="post">
        <button type="submit" name="action" value="clear">Vaciar carrito</button>
    </form>
</body>
</html>
