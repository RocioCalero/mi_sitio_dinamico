<?php
declare(strict_types=1);

// /nucleo/Datos.php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../config.php';

/**
 * Vacía una tabla de forma segura (MySQL).
 * Nota: TRUNCATE hace commit implícito; por eso debe ejecutarse fuera de una transacción.
 */
function resetTabla(PDO $pdo, string $tabla): void
{
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec("TRUNCATE TABLE `{$tabla}`");
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

/**
 * 🌱 Inserta productos de prueba.
 * - Si $reset = true, vacía la tabla antes de insertar.
 * - Devuelve el número aproximado de filas afectadas.
 */
function semillaProductosDatos(bool $reset = false): int
{
    $pdo = Database::getConnection();
    $afectadas = 0;

    $productos = [
        ['producto' => 'Pan de Camas',                   'precio' => 1.20, 'descripcion' => 'Pan de masa madre',  'stock' => 15],
        ['producto' => 'Aceitunas aliñadas de Camas',    'precio' => 2.50, 'descripcion' => 'Aceitunas negras con aliño',  'stock' => 5],
        ['producto' => 'Tortas de aceite',               'precio' => 3.00, 'descripcion' => 'Tortas de Ines rosales',  'stock' => 15],
        ['producto' => 'Aceite Virgen Extra “Aljarafe”', 'precio' => 6.80, 'descripcion' => 'Aceite exclusivo de la zona de sevilla',  'stock' => 15],
        ['producto' => 'Jamón ibérico de recebo',        'precio' => 12.50, 'descripcion' => 'Jamon de buena calidad',  'stock' => 115],
        ['producto' => 'Queso de cabra payoya',          'precio' => 4.75, 'descripcion' => 'Cabras criadas en libertad',  'stock' => 20],
        ['producto' => 'Miel de azahar del Aljarafe',    'precio' => 5.20, 'descripcion' => 'Miel de Azahar de Gines',  'stock' => 15],
        ['producto' => 'Almendras fritas estilo barra',  'precio' => 3.40, 'descripcion' => 'Almendras de la zona de Granada',  'stock' => 25],
        ['producto' => 'Bollos de anís tradicionales',   'precio' => 2.30, 'descripcion' => 'Hechos en una fabrica familiar de Bollulos',  'stock' => 200],
        ['producto' => 'Paté de aceituna verde',         'precio' => 3.10, 'descripcion' => 'Algo amargo pero muy bueno',  'stock' => 15],
        ['producto' => 'Vino blanco DO “Aljarafe”',      'precio' => 8.50, 'descripcion' => 'Vino de la fabrica mas antigua de España',  'stock' => 150],
        ['producto' => 'Dulce de membrillo artesano',    'precio' => 2.90, 'descripcion' => 'Manjar para dioses',  'stock' => 15],
        ['producto' => 'Anchoas en aceite de oliva',     'precio' => 7.20, 'descripcion' => 'Del cantabrico',  'stock' => 15],
        ['producto' => 'Chorizo casero del Aljarafe',    'precio' => 4.60, 'descripcion' => 'Perfecto para hacer chorizo al infierno',  'stock' => 15],
        ['producto' => 'Flor de sal del Guadalquivir',   'precio' => 2.70, 'descripcion' => 'Utilizado en los mejores restaurantes de la zona',  'stock' => 15],
        ['producto' => 'Mermelada de higo de la zona',   'precio' => 3.30, 'descripcion' => 'Perfecto para untar',  'stock' => 15],
        ['producto' => 'Cervezas artesanas sevillanas',  'precio' => 2.80, 'descripcion' => 'Para los mas cerveceros, de grano integral',  'stock' => 15],
        ['producto' => 'Tomate seco en aceite',          'precio' => 4.20, 'descripcion' => 'Las pequeñas delicateces',  'stock' => 15],
        ['producto' => 'Aceite arbequina 250 ml',        'precio' => 5.60, 'descripcion' => 'Aceitunas negras con aliño',  'stock' => 15],
        ['producto' => 'Picos de pan artesanos',         'precio' => 1.80, 'descripcion' => 'Pan de masa madre',  'stock' => 15],
    ];

    // Si vas a resetear, hazlo SIEMPRE fuera de la transacción
    if ($reset) {
        resetTabla($pdo, 'productos');
    }

    $sql = "INSERT INTO productos (nombre, precio) VALUES (:nombre, :precio)";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();

        foreach ($productos as $p) {
            $stmt->execute([
                ':nombre' => (string)($p['producto'] ?? ''),
                ':precio' => (float)($p['precio'] ?? 0.0),
            ]);
            $afectadas += $stmt->rowCount();
        }

        $pdo->commit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Seed productos error: ' . $e->getMessage());
        return 0;
    }

    return $afectadas;
}

/**
 * 🌱 Inserta usuarios de prueba.
 * - Si $reset = true, vacía la tabla antes de insertar.
 * - Devuelve el número aproximado de filas afectadas.
 */
function seedUsuariosDatos(bool $reset = false): int
{
    $pdo = Database::getConnection();
    $afectadas = 0;

    $usuarios = [
        ['admin',    'admin123', 'Administrador General', 'admin'],
        ['manager1', 'manager1', 'Laura Gestora',         'manager'],
        ['manager2', 'manager2', 'Carlos Supervisor',     'manager'],
        ['user1',    'user1',    'María Compradora',      'usuario'],
        ['user2',    'user2',    'Pedro Cliente',         'usuario'],
        ['user3',    'user3',    'Lucía Compradora',      'usuario'],
        ['user4',    'user4',    'Manuel Perez',          'usuario'],
         ['user5',    'user5',    'Tess test',          'usuario'],
    ];

    if ($reset) {
        resetTabla($pdo, 'usuarios');
    }

    $sql = "INSERT INTO usuarios (usuario, password, nombre, rol)
            VALUES (:usuario, :password, :nombre, :rol)";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();

        foreach ($usuarios as [$usuario, $clave, $nombre, $rol]) {
            $stmt->execute([
                ':usuario'  => $usuario,
                ':password' => password_hash($clave, PASSWORD_DEFAULT),
                ':nombre'   => $nombre,
                ':rol'      => $rol,
            ]);
            $afectadas += $stmt->rowCount();
        }

        $pdo->commit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Seed usuarios error: ' . $e->getMessage());
        return 0;
    }

    return $afectadas;
}
