<?php
// Inicia una nueva sesión o reanuda una sesión existente
session_start();
// Incluye el archivo que gestiona la conexión a la base de datos
require 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <!-- Enlace a un archivo CSS para estilos -->
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <h1>Tabla de productos</h1>
  <?php
  try {
    // Comprueba si se ha recibido un parámetro 'order' por URL
    if (isset($_GET['order'])) {
      // Establece una cookie con el valor del orden y redirige a la misma página
      setcookie('order', $_GET['order'], time() + 3600, "/");
      header("Location: home.php");
    }

    // Recupera el orden desde la cookie, por defecto 'name' si no existe
    $order = isset($_COOKIE['order']) ? $_COOKIE['order'] : 'name';

    // Prepara una consulta SQL para obtener los productos ordenados
    $stmtProducts = $link->prepare("SELECT id, name, price, amount FROM products ORDER BY $order");
    $stmtProducts->execute();
    // Recupera todos los resultados como objetos
    $products = $stmtProducts->fetchAll(PDO::FETCH_OBJ);

    // Comprueba si se ha recibido un parámetro 'id' por URL
    if (isset($_GET['id'])) {
      $productId = $_GET['id'];

      // Si el producto ya está en el carrito, incrementa su cantidad
      if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']++;
      } else {
        // Si no está en el carrito, lo agrega con cantidad inicial 1
        $stmtAddCart = $link->prepare('SELECT id, name, price FROM products WHERE id=:id');
        $stmtAddCart->bindParam(':id', $productId);
        $stmtAddCart->execute();
        $product = $stmtAddCart->fetch(PDO::FETCH_OBJ);
        $_SESSION['cart'][$product->id] = [
          "name" => $product->name,
          "price" => $product->price,
          "quantity" => 1
        ];
      }

      // Cuenta la cantidad de productos en el carrito
      $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
  ?>
      <h2>
        <!-- Muestra la cantidad de productos en el carrito -->
        Productos dentro del carrito:
      <?php echo $cartCount;
    } ?>
      </h2>
      <h2><a href="cart.php">Ver carrito</a></h2>
      <!-- Tabla para mostrar los productos -->
      <table>
        <thead>
          <tr>
            <!-- Enlaces para ordenar por diferentes columnas -->
            <th><a href="home.php?order=name">Nombre</a></th>
            <th><a href="home.php?order=price">Precio</a></th>
            <th><a href="home.php?order=amount">Cantidad</a></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Recorre los productos obtenidos y los imprime en la tabla
          foreach ($products as $product) {
            printf(
              '<tr><td>%s</td><td>%.2f</td><td>%d</td><td><a href=home.php?id=%d><button>Añadir al carrito</button></a></td>',
              $product->name,
              $product->price,
              $product->amount,
              $product->id
            );
          }
          ?>
        </tbody>
      </table>
    <?php
  } catch (Exception $e) {
    // En caso de error, muestra un mensaje y termina la ejecución
    die('Error: ' . $e->getMessage());
  }
    ?>
</body>

</html>