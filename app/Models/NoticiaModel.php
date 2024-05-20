<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticiaModel extends Model
{
    protected $table = 'noticias'; // Nombre de la tabla de noticias
    protected $primaryKey = 'id'; // Nombre de la clave primaria
    protected $useAutoIncrement = true; // Indica si la tabla tiene una clave primaria autoincrementable
    protected $allowedFields = ['titulo', 'descripcion', 'contenido', 'estado', 'categoria', 'imagen']; // Campos permitidos para inserción

    public function insertNoticia($data)
    {
        return $this->insert($data);
    }
}
