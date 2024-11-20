<?php
$user = 'root';
$password = '';
$dsn = 'mysql:host=localhost;dbname=tienda';
<<<<<<< HEAD

try {
  $link = new PDO($dsn, $user, $password);
  $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
  die ("Error en la conexión: ". $ex->getMessage());
}
=======
try {
  $link = new PDO($dsn, $user, $password);
  $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  die ('Error en la conexión: ' . $e->getMessage());
}
>>>>>>> 2eb0213 (Hecho hasta las 20:00)
