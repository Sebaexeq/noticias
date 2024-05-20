<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios'; // Nombre de la tabla de la base de datos    
    protected $primaryKey = 'id'; // Nombre de la clave primaria
    protected $useAutoIncrement = true; // Indica si la tabla tiene una clave primaria autoincrementable    
    protected $allowedFields = ['email', 'contraseña','rol','nombre','apellido']; // Campos permitidos para inserción

    // Método para insertar un nuevo usuario
    public function insertUsuario($data)
    {
        return $this->insert($data);
    }
}
