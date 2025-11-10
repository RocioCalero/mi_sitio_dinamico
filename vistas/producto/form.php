<?php
// Variables: $auth (array|null)
// Si $producto está definido, estamos en modo edición
$rol = $auth['rol'] ?? 'visitante';
$esManager = ($rol === 'manager');

$esEdicion = isset($producto); // Producto
$titulo = $esEdicion ? 'Editar producto' : 'Nuevo producto';
$action = $esEdicion ? 'actualizar' : 'crear';

$valNombre = $esEdicion ? $producto->nombre : '';
$valPrecio = $esEdicion ? (string)$producto->precio : '';
$valId     = $esEdicion ? (int)$producto->getId() : 0;
?>
<h2 class="text-success text-center mt-4"><?= htmlspecialchars($titulo) ?></h2>

<?php if (!$esManager): ?>
  <div class="alert alert-danger w-75 mx-auto">No tienes permisos para <?= $esEdicion ? 'editar' : 'crear' ?> productos.</div>
  <?php return; ?>
<?php endif; ?>

<form class="w-75 mx-auto mt-4" method="post" action="index.php?p=productos&action=<?= htmlspecialchars($action) ?>">
  <?php if ($esEdicion): ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$valId) ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input class="form-control" name="nombre" maxlength="120" required
           value="<?= htmlspecialchars($valNombre) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Precio (€)</label>
    <input class="form-control" name="precio" type="number" step="0.01" min="0" required
           value="<?= htmlspecialchars($valPrecio) ?>">
  </div>

  <div class="d-flex gap-2">
    <button class="btn btn-primary" type="submit"><?= $esEdicion ? 'Guardar cambios' : 'Crear producto' ?></button>
    <a class="btn btn-secondary" href="index.php?p=contenido">Cancelar</a>
  </div>
</form>

<div class="mb-3">
  <label for="stock" class="form-label">Stock</label>
  <input type="number" class="form-control <?= isset($errores['stock']) ? 'is-invalid' : '' ?>"
         id="stock" name="stock" min="0" value="<?= htmlspecialchars($producto->getStock()) ?>">
  <div class="invalid-feedback"><?= $errores['stock'] ?? '' ?></div>
</div>

<div class="mb-3">
  <label for="descripcion" class="form-label">Descripción</label>
  <textarea class="form-control <?= isset($errores['descripcion']) ? 'is-invalid' : '' ?>"
            id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($producto->getDescripcion()) ?></textarea>
  <div class="invalid-feedback"><?= $errores['descripcion'] ?? '' ?></div>
</div>