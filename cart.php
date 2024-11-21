<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total = 0;

if (isset($_GET['value']) && $_GET['value'] === 'increase' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['cart'][$id]['quantity']++;
}

if (isset($_GET['value']) && $_GET['value'] === 'decrease' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['cart'][$id]['quantity']--;
    if ($_SESSION['cart'][$id]['quantity'] <= 0) {
        unset($_SESSION['cart'][$id]); 
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Carrito de compra</h1>
    <h2><a href="home.php">Volver a la tabla de productos</a></h2>
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
                foreach ($_SESSION['cart'] as $id => $product) {
                    $name = $product['name'];
                    $price = $product['price'];
                    $amount = $product['quantity'];
                    $subtotal = $price * $amount;
                    $total += $subtotal;
                    printf('<tr><td>%s</td><td>%.2f</td><td>%d</td><td>%.2f</td><td><a href="cart.php?value=increase&id=%d"><button>+</button></a><a href="cart.php?value=decrease&id=%d"><button>-</button></a></td></tr>', 
                    $name, $price, $amount, $subtotal, $id, $id);
                  }
                }
             else {
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
        <button type="submit" name="action" value="clear" id="vaciar">Vaciar carrito</button>
    </form>
</body>
</html>
