<?php
  session_start();
  require 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Tabla de productos</h1>
  <?php
    try {
      
      if (isset($_GET['order'])) {
        setcookie('order', $_GET['order'], time() + 3600, "/");
        header("Location: home.php");
      }

      $order = isset($_COOKIE['order']) ? $_COOKIE['order'] : 'name';   
      $stmtProducts = $link->prepare("SELECT id, name, price, amount FROM products ORDER BY $order");
      $stmtProducts->execute();
      $products = $stmtProducts->fetchAll(PDO::FETCH_OBJ);

      if(isset($_GET['id'])){
        $productId = $_GET['id'];
        if(isset($_SESSION['cart'][$productId])){
          $_SESSION['cart'][$productId]['quantity']++;
        } else{
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

        $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

      
  ?>
  <h2>
    Productos dentro del carrito:
    <?php echo $cartCount; }?>
  </h2>
  <h2><a href="cart.php">Ver carrito</a></h2>
  <table>
    <thead>
      <tr>
        <th><a href="home.php?order=name">Nombre</a></th>
        <th><a href="home.php?order=price">Precio</a></th>
        <th><a href="home.php?order=amount">Cantidad</a></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach($products as $product) {
          printf('<tr><td>%s</td><td>%.2f</td><td>%d</td><td><a href=home.php?id=%d><button>Añadir al carrito</button></a></td>',
                  $product->name, $product->price, $product->amount, $product->id);
        }
      ?>
    </tbody>
  </table>
  <?php
    } catch(Exception $e) {
      die('Error: '.$e->getMessage());
    } 
  ?>
</body>
</html>