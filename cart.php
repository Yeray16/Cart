<?php
// Inicia una nueva sesión o reanuda una existente
session_start();

// Si el carrito no está inicializado en la sesión, se crea como un arreglo vacío
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Variable para almacenar el total de la compra
$total = 0;

// Comprueba si se recibió un parámetro 'value' con el valor 'increase' y un 'id' en la URL
if (isset($_GET['value']) && $_GET['value'] === 'increase' && isset($_GET['id'])) {
    $id = $_GET['id'];
    // Incrementa la cantidad del producto correspondiente en el carrito
    $_SESSION['cart'][$id]['quantity']++;
}

// Comprueba si se recibió un parámetro 'value' con el valor 'decrease' y un 'id' en la URL
if (isset($_GET['value']) && $_GET['value'] === 'decrease' && isset($_GET['id'])) {
    $id = $_GET['id'];
    // Decrementa la cantidad del producto en el carrito
    $_SESSION['cart'][$id]['quantity']--;
    // Si la cantidad es menor o igual a 0, elimina el producto del carrito
    if ($_SESSION['cart'][$id]['quantity'] <= 0) {
        unset($_SESSION['cart'][$id]);
    }
}

// Comprueba si se ha enviado un formulario con la acción 'clear'
if (isset($_POST['action']) && $_POST['action'] === 'clear') {
    // Vacía el carrito eliminando todos los productos
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compra</title>
    <!-- Enlace al archivo CSS para estilos -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Carrito de compra</h1>
    <h2><a href="home.php">Volver a la tabla de productos</a></h2>
    <!-- Tabla para mostrar los productos en el carrito -->
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
            // Variable para acumular el total de la compra
            $total = 0;

            // Comprueba si el carrito no está vacío
            if (!empty($_SESSION['cart'])) {
                // Recorre los productos del carrito
                foreach ($_SESSION['cart'] as $id => $product) {
                    $name = $product['name']; // Nombre del producto
                    $price = $product['price']; // Precio del producto
                    $amount = $product['quantity']; // Cantidad del producto
                    $subtotal = $price * $amount; // Calcula el subtotal
                    $total += $subtotal; // Acumula el subtotal al total
                    // Imprime una fila en la tabla con los datos del producto
                    printf(
                        '<tr><td>%s</td><td>%.2f</td><td>%d</td><td>%.2f</td><td><a href="cart.php?value=increase&id=%d"><button>+</button></a><a href="cart.php?value=decrease&id=%d"><button>-</button></a></td></tr>',
                        $name,
                        $price,
                        $amount,
                        $subtotal,
                        $id,
                        $id
                    );
                }
            } else {
                // Mensaje para cuando el carrito está vacío
                echo "<tr><td colspan='5'>El carrito está vacío</td></tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <!-- Muestra el total acumulado -->
                <th colspan="3">Total</th>
                <th colspan="2"><?= $total ?></th>
            </tr>
        </tfoot>
    </table>
    <!-- Formulario para vaciar el carrito -->
    <form method="post">
        <button type="submit" name="action" value="clear" id="vaciar">Vaciar carrito</button>
    </form>
</body>

</html>