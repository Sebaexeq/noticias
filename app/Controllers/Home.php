<?php

namespace App\Controllers;
use App\Models\UsuarioModel;
use App\Models\NoticiaModel;

class Home extends BaseController
{
    public function __construct(){
        helper('form');  
        helper('url');     
        $session = \Config\Services::session();        
        }
    public function index(): string{  
        $this->autoFinalizar();
        $this->autoPublicar();              
        return view('inicio');
    }    
    public function logout() {
        // Eliminar todas las variables de sesión
        session()->destroy();    
        // Redirigir al usuario a la página de inicio o a donde desees
        return redirect()->to(base_url())->with('success', '¡Sesión cerrada correctamente!');
    }        
    public function registroUser(){
        return view('registroUser');
    }
    public function inicio(){
        $this->autoFinalizar();
        $this->autoPublicar();
        return view('inicio');
    }
    public function crear(){
        return view('crear-noticia');
    }
    public function misNoticias(){
        return view('mis-noticias');
    }
    public function validarNoticias(){
        return view('validar-noticias');
    }
    public function revisarPublicaciones(){
        return view('revisar-publicaciones');
    }
    public function verNoticia(){
        // Conexión a la base de datos
        $db = \Config\Database::connect();
        $id = $this->request->getGet('id');
        // Lógica para obtener los detalles de la noticia usando el ID
        $sql = "SELECT * FROM noticias WHERE id = ?";
        $query = $db->query($sql, [$id]);
        $datosDeLaNoticia = $query->getRowArray();
    
        // Luego pasas los detalles de la noticia a la vista correspondiente
        return view('ver_noticia', ['noticia' => $datosDeLaNoticia]);
    }
    
    public function registroExito(){   
        // Lógica para el formulario de inicio de sesión
        $validation = service('validation');
        $validation->setRules([
            'nombre' => 'required|min_length[2]|max_length[20]|alpha',
            'apellido' => 'required|min_length[2]|max_length[20]|alpha',
            'email' => 'required|valid_email|is_unique[usuarios.email]', // Agregar la regla 'is_unique'
            'password' => 'required|min_length[8]|matches[cPassword]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/]',
            'cPassword'=> 'required',
            'rol'=> 'required',
        ],
        [
            'nombre' => [
                'required' => 'El campo nombre es obligatorio',
                'min_length' => 'El nombre debe tener al menos 2 caracteres',
                'max_length' => 'El nombre no puede tener más de 20 caracteres',
                'alpha' => 'El nombre solo puede contener caracteres alfabéticos',
            ],
            'apellido' => [
                'required' => 'El campo apellido es obligatorio',
                'min_length' => 'El apellido debe tener al menos 2 caracteres',
                'max_length' => 'El apellido no puede tener más de 20 caracteres',
                'alpha' => 'El apellido solo puede contener caracteres alfabéticos',
            ],
            'email' => [
                'required' => 'El campo email es obligatorio',                
                'valid_email' => 'El email no es valido',
                'is_unique' => 'El correo electrónico ya está registrado', // Mensaje para la regla is_unique
            ],
            'password' => [
                'required' => 'El campo contraseña es obligatorio',
                'min_length' => 'La longitud mínima es 8',
                'matches' => 'Las contraseñas no coinciden',
                'regex_match' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un número',
            ],
            'cPassword' => [
                'required' => 'El campo es obligatorio',                        
            ],
            'rol' => [
                'required' => 'Seleccione un rol'                        
            ]
        ]);
        

        // Verificar la validación de los datos del formulario
        if (!$validation->withRequest($this->request)->run()) {
            // Si la validación falla, redirige de vuelta al formulario con los errores
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        } else {
            // Si la validación es exitosa, guarda los datos en la base de datos
            $nombre = $this->request->getPost('nombre');
            $apellido = $this->request->getPost('apellido');
            $email = $this->request->getPost('email');
            $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            $rol = $this->request->getPost('rol');
        
            // Conexión a la base de datos
            $db = \Config\Database::connect();
        
            // Consulta SQL para insertar el nuevo usuario
            $sql = "INSERT INTO usuarios (nombre, apellido, email, contraseña, rol) VALUES ('$nombre', '$apellido', '$email', '$password', '$rol')";
            $db->query($sql);
        
            // Obtener el ID del nuevo usuario
            $newUserId = $db->insertID();

            // Guardar los datos del nuevo usuario en la sesión
            $session = session();
            $session->set('id', $newUserId);
            $session->set('nombre', $nombre);
            $session->set('apellido', $apellido);
            $session->set('email', $email);
            $session->set('rol', $rol);
        
            // Luego, redirige al usuario a una página de éxito
            return redirect()->to(base_url('inicio'))->with('success', '¡Registro exitoso!');
        }
        
    }

    public function inicioExito(){   
        // Lógica para el formulario de inicio de sesión
        $validation = service('validation');
        $validation->setRules([
            'iEmail' => 'required|valid_email',
            'iPassword' => 'required|min_length[8]',               
        ],
        [        
            'iEmail' => [
                'required' => 'El campo email es obligatorio',                
                'valid_email' => 'El email no es valido',
            ],    
            'iPassword' => [
                'required' => 'El campo contraseña es obligatorio',
                'min_length' => 'La longitud mínima es 8'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Si la validación falla, redirigir al formulario de inicio de sesión con los errores
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Obtener el correo electrónico y la contraseña del formulario
        $email = $this->request->getPost('iEmail');
        $password = $this->request->getPost('iPassword');

        // Buscar el usuario en la base de datos por correo electrónico
        $usuarioModel = new UsuarioModel(); // Instancia del modelo de usuario
        $user = $usuarioModel->where('email', $email)->first();
        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && password_verify($password, $user['contraseña'])) {
            // Iniciar sesión y redirigir a la página de inicio
            $session = \Config\Services::session();
            $session->set('nombre', $user['nombre']);
            $session->set('apellido', $user['apellido']);
            $session->set('email', $user['email']);
            $session->set('rol', $user['rol']);
            $session->set('id', $user['id']);
            return redirect()->to(base_url('inicio'));
        } else {
            // Si el usuario no existe o la contraseña es incorrecta, redirigir de vuelta al formulario de inicio de sesión con un mensaje de error
            return redirect()->back()->withInput()->with('error', 'Correo electrónico o contraseña incorrectos.');
        }
    }

    public function crearNoticia(){
        $validation = service('validation');
        $validation->setRules([
            'titulo' => 'required|max_length[100]',
            'descripcion' => 'required|max_length[500]',
            'contenido' => 'required',
            'categoria' => 'required',
            'imagen' => 'max_size[imagen,1024]|is_image[imagen]|permit_empty',
            'duracion' => 'required|numeric' // Asegúrate de que la duración también esté validada
        ],
        [
            'titulo' => [
                'required' => 'El título es obligatorio',
                'max_length' => 'El título no puede tener más de 100 caracteres',
            ],
            'descripcion' => [
                'required' => 'La descripción es obligatoria',
                'max_length' => 'La descripción no puede tener más de 500 caracteres',
            ],
            'contenido' => [
                'required' => 'El contenido es obligatorio',
            ],
            'categoria' => [
                'required' => 'La categoría es obligatoria',
            ],
            'imagen' => [
                'max_size' => 'La imagen no debe superar 1 MB',
                'is_image' => 'Por favor, seleccione un archivo de imagen válido (JPEG, PNG, JPG, GIF)',
            ],
            'duracion' => [
                'required' => 'La duración es obligatoria',
                'numeric' => 'La duración debe ser un número válido',
            ]
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        } else {
            $session = \Config\Services::session();
            $id_user = $session->get('id');
    
            // Conexión a la base de datos
            $db = \Config\Database::connect();
    
            // Verificar si el usuario ya tiene 3 noticias en estado 'borrador' (estado = 3) o 'para validar' (estado = 4)
            $builder = $db->table('noticias');
            $builder->where('id_usuario', $id_user);
            $builder->whereIn('estado', [1, 2]);
            $count = $builder->countAllResults();
    
            if ($count >= 3) {
                return redirect()->back()->withInput()->with('error-limite', 'No puede tener más de 3 noticias en estado borrador o para validar.');
            }
    
            $titulo = $this->request->getPost('titulo');
            $descripcion = $this->request->getPost('descripcion');
            $contenido = $this->request->getPost('contenido');
            $categoria = $this->request->getPost('categoria');
            $duracion = $this->request->getPost('duracion'); // Obtener la duración de la noticia
            $imagen = $this->request->getFile('imagen'); // Obtener la imagen subida
    
            // Procesar y guardar la imagen si se proporciona
            $imagenPath = null;
            if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
                $newName = $imagen->getRandomName();
                $imagen->move(ROOTPATH . 'public/uploads', $newName);
                $imagenPath = base_url('public/uploads/' . $newName);
            }
    
            // Insertar la noticia en la base de datos
            $sql = "INSERT INTO noticias (titulo, descripcion, contenido, categoria, imagen, id_usuario, duracion, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?)";
            $db->query($sql, [$titulo, $descripcion, $contenido, $categoria, $imagenPath, $id_user, $duracion, date('Y-m-d H:i:s')]);
            $idNoticia = $db->insertID();
    
            return redirect()->to(base_url('ver_noticia?id=' . $idNoticia))->with('success', '¡La noticia se ha creado exitosamente!');
        }
    }
        
    public function editarNoticia(){
        // Lógica para el formulario de inicio de sesión
        $validation = service('validation');
        $validation->setRules([
            'titulo' => 'required|max_length[100]',
            'descripcion' => 'required|max_length[500]',
            'contenido' => 'required',
            'categoria' => 'required',
        ],
        [
            'titulo' => [
                'required' => 'El título es obligatorio',
                'max_length' => 'El título no puede tener más de 100 caracteres',
            ],
            'descripcion' => [
                'required' => 'La descripción es obligatoria',
                'max_length' => 'La descripción no puede tener más de 500 caracteres',
            ],
            'contenido' => [
                'required' => 'El contenido es obligatorio',
            ],
            'categoria' => [
                'required' => 'La categoría es obligatoria',
            ],
        ]);
    
        // Verificar la validación de los datos del formulario
        if (!$validation->withRequest($this->request)->run()) {
            // Si la validación falla, redirige de vuelta al formulario con los errores
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        } else {
            $session = \Config\Services::session();
            $titulo = $this->request->getPost('titulo');
            $descripcion = $this->request->getPost('descripcion');
            $contenido = $this->request->getPost('contenido');                
            $categoria = $this->request->getPost('categoria');
            $duracion = $this->request->getPost('duracion');
            $imagen = $this->request->getPost('imagen');
            $id_user = $session->get('id');

            // Conexión a la base de datos
            $db = \Config\Database::connect();
            $noticia_id = $this->request->getGet('id');            

            // Obtener los datos originales de la noticia antes de la edición
            $noticiaOriginal = $db->table('noticias')->where('id', $noticia_id)->get()->getRowArray();

            // Guardar los datos originales en el historial
            $sqlHistorial = "INSERT INTO historial_noticias (id_noticia, id_usuario, titulo, descripcion, contenido, categoria, imagen, duracion, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $db->query($sqlHistorial, [$noticia_id, $id_user, $noticiaOriginal['titulo'], $noticiaOriginal['descripcion'],
            $noticiaOriginal['contenido'], $noticiaOriginal['categoria'], $noticiaOriginal['imagen'], $noticiaOriginal['duracion'],
            $noticiaOriginal['estado'],$noticiaOriginal['fecha_creacion']]);
            
            // Datos a ser actualizados
            $data = [
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'contenido' => $contenido,
                'categoria' => $categoria,
                'duracion' => $duracion,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]; 

            // Verificar si se ha subido una nueva imagen
            if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
                $newName = $imagen->getRandomName();
                $imagen->move(ROOTPATH . 'public/uploads', $newName);
                $data['imagen'] = base_url('public/uploads/' . $newName);
            }    
    
            // Actualiza la noticia existente
            $db->table('noticias')->where('id', $noticia_id)->update($data);
            
            // Redirige al usuario a una página de éxito
            return redirect()->to(base_url('ver_noticia?id=' . $noticia_id))->with('success', '¡La noticia se ha creado exitosamente!');
        }
    }

    public function enviar_paraValidar() {
        // Conexión a la base de datos
        $db = \Config\Database::connect();
        $session = \Config\Services::session();
        $noticia_id = $this->request->getGet('id');  
        $id_user = $session->get('id');  
        
        // Obtener los datos originales de la noticia antes de la edición
        $noticiaOriginal = $db->table('noticias')->where('id', $noticia_id)->get()->getRowArray();

        // Guardar los datos originales en el historial
        $sqlHistorial = "INSERT INTO historial_noticias (id_noticia, id_usuario, titulo, descripcion, contenido, categoria, imagen, duracion, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $db->query($sqlHistorial, [$noticia_id, $id_user, $noticiaOriginal['titulo'], $noticiaOriginal['descripcion'],
        $noticiaOriginal['contenido'], $noticiaOriginal['categoria'], $noticiaOriginal['imagen'], $noticiaOriginal['duracion'],
        $noticiaOriginal['estado'],$noticiaOriginal['fecha_creacion']]);
        
        
        // Actualizar el estado de la noticia a 'para validar' (estado = 2)
        $data = ['estado' => 2,
        'fecha_creacion' => date('Y-m-d H:i:s')// Obtiene la fecha y hora actual
        ];
        $db->table('noticias')->where('id', $noticia_id)->update($data);
        
        // Redirigir a la página de la noticia
        return redirect()->to(base_url('ver_noticia?id=' . $noticia_id))->with('success', '¡La noticia se ha enviado para revisión correctamente!');
    }

    public function enviar_paraBorrador() {
        // Conexión a la base de datos
        $db = \Config\Database::connect();
        $session = \Config\Services::session();
        $noticia_id = $this->request->getGet('id');        
        $id_user = $session->get('id');  
        
        // Obtener los datos originales de la noticia antes de la edición
        $noticiaOriginal = $db->table('noticias')->where('id', $noticia_id)->get()->getRowArray();

        // Guardar los datos originales en el historial
        $sqlHistorial = "INSERT INTO historial_noticias (id_noticia, id_usuario, titulo, descripcion, contenido, categoria, imagen, duracion, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $db->query($sqlHistorial, [$noticia_id, $id_user, $noticiaOriginal['titulo'], $noticiaOriginal['descripcion'],
        $noticiaOriginal['contenido'], $noticiaOriginal['categoria'], $noticiaOriginal['imagen'], $noticiaOriginal['duracion'],
        $noticiaOriginal['estado'],$noticiaOriginal['fecha_creacion']]);          
        
        // Actualizar el estado de la noticia a 'borrador' (estado = 1)
        $data = ['estado' => 1,
        'fecha_creacion' => date('Y-m-d H:i:s')];
        $db->table('noticias')->where('id', $noticia_id)->update($data);
        
        // Redirigir a la página de la noticia
        return redirect()->to(base_url('ver_noticia?id=' . $noticia_id))->with('success', '¡La noticia se ha enviado para revisión correctamente!');
    }

    public function descartar_noticia() {
        // Conexión a la base de datos
        $db = \Config\Database::connect();
        $noticia_id = $this->request->getGet('id');
        
        // Borrar todas las entradas relacionadas con esta noticia en la tabla historial_noticias
        $db->table('historial_noticias')->where('id_noticia', $noticia_id)->delete();
        
        // Borrar la noticia de la tabla noticias
        $db->table('noticias')->where('id', $noticia_id)->delete();
        
        // Redirigir a alguna página, quizás al inicio o a una lista de noticias
        return redirect()->to(base_url('inicio'))->with('success', '¡La noticia se ha descartado correctamente!');
    }    
    
    public function publicar() {
        // Conexión a la base de datos
        $db = \Config\Database::connect();
        $session = \Config\Services::session();
        $noticia_id = $this->request->getGet('id');
        $id_user = $session->get('id');  
        
        // Obtener los datos originales de la noticia antes de la edición
        $noticiaOriginal = $db->table('noticias')->where('id', $noticia_id)->get()->getRowArray();

        // Guardar los datos originales en el historial
        $sqlHistorial = "INSERT INTO historial_noticias (id_noticia, id_usuario, titulo, descripcion, contenido, categoria, imagen, duracion, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $db->query($sqlHistorial, [$noticia_id, $id_user, $noticiaOriginal['titulo'], $noticiaOriginal['descripcion'],
        $noticiaOriginal['contenido'], $noticiaOriginal['categoria'], $noticiaOriginal['imagen'], $noticiaOriginal['duracion'],
        $noticiaOriginal['estado'],$noticiaOriginal['fecha_creacion']]);
        
        // Actualizar el estado de la noticia a 'publicada' (estado = 3)
        $data = [
            'estado' => 3,
            'fecha_creacion' => date('Y-m-d H:i:s') // Obtiene la fecha y hora actual
        ];
        $db->table('noticias')->where('id', $noticia_id)->update($data);
        
        // Redirigir a la página de la noticia
        return redirect()->to(base_url('ver_noticia?id=' . $noticia_id))->with('success', '¡La noticia se ha publicado correctamente!');
    }

    public function deshacer(){
        // Conexión a la base de datos
        $db = \Config\Database::connect();
        $noticia_id = $this->request->getGet('id');
    
        // Obtener todas las noticias del historial para la noticia especificada, ordenadas por fecha de creación descendente
        $query = $db->table('historial_noticias')->where('id_noticia', $noticia_id)->orderBy('fecha_creacion', 'DESC')->get();
    
        // Verificar si hay entradas en el historial
        if ($query->getResult()) {
            // Obtener la primera fila del historial
            $primeraFila = $query->getRow();
    
            // Eliminar la primera fila del historial
            $db->table('historial_noticias')->where('id', $primeraFila->id)->delete();
    
            // Sobreescribir la noticia con los valores de la primera fila del historial
            $data = [
                'titulo' => $primeraFila->titulo,
                'descripcion' => $primeraFila->descripcion,
                'contenido' => $primeraFila->contenido,
                'categoria' => $primeraFila->categoria,
                'imagen' => $primeraFila->imagen,
            ];
    
            $db->table('noticias')->where('id', $noticia_id)->update($data);
    
            // Retornar true indicando que se ha deshecho la acción correctamente
            return redirect()->to(base_url('ver_noticia?id=' . $noticia_id))->with('message_deshacer', '¡La acción se deshizo exitosamente!');
        } else {
            // No hay entradas en el historial para deshacer
            return redirect()->to(base_url('ver_noticia?id=' . $noticia_id))->with('message_deshacer', '¡Ya no se puede deshacer mas!');
        }
    }
    

    public function autoPublicar() {
        // Conexión a la base de datos
        $db = \Config\Database::connect();
        
        // Obtener todas las noticias con estado 2 (para validar) y fecha_creacion mayor o igual a 5 días
        $noticias = $db->table('noticias')->where('estado', 2)
                                           ->where('fecha_creacion <=', date('Y-m-d H:i:s', strtotime('-5 days')))
                                           ->get()
                                           ->getResultArray();
    
        // Verificar cada noticia
        foreach ($noticias as $noticia) {
            // Actualizar el estado de la noticia a 4 (auto publicada) y la fecha_creacion a la actual
            $data = [
                'estado' => 4,
                'fecha_creacion' => date('Y-m-d H:i:s') // Obtiene la fecha y hora actual
            ];
            $db->table('noticias')->where('id', $noticia['id'])->update($data);
        }    
        
    }

    public function autoFinalizar(){
        // Conexión a la base de datos
        $db = \Config\Database::connect();

        // Obtener todas las noticias con estado 3 y 4 cuya fecha de creación sea mayor o igual a 5 días
        $noticias = $db->table('noticias')
            ->where('estado', 3)
            ->orWhere('estado', 4)
            ->where('fecha_creacion <=', date('Y-m-d H:i:s', strtotime('-5 days')))
            ->get()
            ->getResultArray();

        foreach ($noticias as $noticia) {
            $fechaCreacion = new \DateTime($noticia['fecha_creacion']);
            $fechaActual = new \DateTime();
            $diferenciaDias = $fechaActual->diff($fechaCreacion)->days;

            if ($diferenciaDias >= $noticia['duracion']) {
                $data = [
                    'estado' => 5,
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $db->table('noticias')->where('id', $noticia['id'])->update($data);
            }
        }
    }
    
    public function historial() {
        // Conexión a la base de datos
        $db = \Config\Database::connect();
    
        // Obtener el ID de la noticia desde la URL
        $idNoticia = $this->request->getGet('id');
    
        // Obtener la noticia principal
        $sqlNoticia = "SELECT * FROM noticias WHERE id = ?";
        $queryNoticia = $db->query($sqlNoticia, [$idNoticia]);
        $noticiaPrincipal = $queryNoticia->getRowArray();
    
        // Obtener el historial de la noticia
        $sqlHistorial = "SELECT * FROM historial_noticias WHERE id_noticia = ? ORDER BY fecha_creacion DESC";
        $queryHistorial = $db->query($sqlHistorial, [$idNoticia]);
        $historial = $queryHistorial->getResultArray();
    
        // Pasar los datos a la vista
        return view('historial', ['noticia' => $noticiaPrincipal, 'historial' => $historial]);
    }
    
    
    
 
    
}
