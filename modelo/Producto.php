<?php
/**
 * https://www.w3schools.com/charsets/ref_utf_symbols.asp
 */
//activar el modo estricto de tipos
declare(strict_types=1);

require_once __DIR__ . '/Entidad.php';

/**
 * ========================================================
 * 🍞 Clase Producto 
 * Representa una fila de la tabla 'productos'
 * Hereda de Entidad (que contiene el id y utilidades comunes)
 * ========================================================
 */
class Producto extends Entidad
{
  
    public function __construct(
    public string $nombre,
    public float $precio,
    public int $stock,
    public string $descripcion
  ) {}
  
    public static function vacio(): self
    {
        return new self("", 0.0);
    }
  
    /**
     * Convierte el objeto en un array (útil para debug o JSON).
     */
    public function toArray(): array
    {
        return [
            'id'      => $this->getId(),
            'nombre'  => $this->nombre,
            'precio'     => $this->precio,
            'descripcion'  => $this->descripcion,
            'stock'   => $this->stock
        ];
    }

    /*Get Y Set de Stock*/
    public function getStock():int {
        return $this-> stock;
    }
    public function setStock(int $stock): void{
        if ($stock < 0){
            throw new InvalidArgumentException("El stock no puede ser negativo");
        }
        $this->stock = $stock;
    }

    /*Get y Set de Descripcion*/
       public function getDescripcion():string {
        return $this-> descripcion;
    }
    public function setDescripcion(string $descripcion): void{
        if (strlen($descripcion) > 500){
            throw new InvalidArgumentException("La descripción no puede superar los 500 caracteres");
        }
        $this->descripcion = $descripcion;
    }
}
