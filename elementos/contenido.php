<?php
// Estructura asociativa: producto => precio
$productos = [
  "Pan de Camas"        => 1.20,
  "Aceitunas aliñadas"  => 2.50,
  "Tortas de aceite"    => 3.00, 
  "Leche chocalatada"   => 1.20
];
?>

<h2 class="text-success text-center mt-4">Productos locales de Camas</h2>

<table class="table table-bordered table-striped w-75 mx-auto mt-4 text-center align-middle">
  <thead class="table-primary">
    <tr>
      <th>Producto</th>
      <th>Precio (€)</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $productos_total = count($productos);
      echo $productos_total;
      print_r(array_keys($productos));
      /*for ($i = 0; $i < count($productos); $i++ ){
        echo "<tr><td>". $productos[$i] ."</td></tr>";
      }*/
    ?>
  </tbody>
</table>
