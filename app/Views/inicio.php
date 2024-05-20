<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>  
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('css del diario.css') ?>">
</head>
<body>
<?php if (session()->has('nombre')) : ?>
  <nav class="navbar navbar-expand-lg ">
  <div class="container">
    <!-- Marca y nombre del diario -->
    <a class="navbar-brand" href="<?= base_url('inicio') ?>"><img src="<?php echo base_url('logo.png') ?>" alt="logo" height="100px" width="auto"></a>
    <a class="navbar-brand" href="<?= base_url('inicio') ?>"><h1 style="color: white;">Diario Tu Noticia</h1></a>

    <!-- Contenido del menú -->
    <div class="collapse navbar-collapse" id="navbarToggleExternalContent">
    <ul class="navbar-nav ml-auto">   
      <li class="nav-item dropdown">
        
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Bienvenido, <?= session()->get('nombre') ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php if (session()->get('rol') === '0' || session()->get('rol') === '2'): ?>
            <a class="dropdown-item" href="<?= base_url('crear-noticia') ?>">Crear noticia</a>            
            <a class="dropdown-item" href="<?= base_url('mis-noticias') ?>">Mis noticias</a>
          <?php endif; ?>
          <?php if (session()->get('rol') === '1' || session()->get('rol') === '2'): ?>
            <a class="dropdown-item" href="<?= base_url('validar-noticias') ?>">Validar noticias</a>
            <a class="dropdown-item" href="<?= base_url('revisar-publicaciones') ?>">Revisiar noticias auto-publicadas</a>
          <?php endif; ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?= base_url('logout') ?>">Cerrar sesión</a>
        </div>
      </li>
    </ul>
    </div>

    <!-- Botón desplegable -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>
<?php else : ?>
  <nav class="navbar navbar-expand-lg navbar-light ">
    <div class="container">
      <!-- Brand -->
      <a class="navbar-brand" href="<?= site_url() ?>"><img src="logo.png" alt="logo" height="100px" width="auto"></a>
      <a class="navbar-brand" href="<?= site_url() ?>"><h1 style="color: white;">Diario Tu Noticia</h1></a>

      <!-- Toggler/collapsible Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
          <a class="nav-link" href="<?= site_url('registroUser') ?>"><h2 style="color: white;">Registrar</h2></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal"><h2 style="color: white;">Iniciar sesión</h2></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<?php endif; ?>


  
<?php 
// Conexión a la base de datos
$db = \Config\Database::connect();
    
// Consulta SQL para recuperar todas las noticias con estado 3 y 4, ordenadas de la más reciente a la más antigua
$query = $db->query("SELECT * FROM noticias WHERE estado IN (3, 4) ORDER BY fecha_creacion DESC");

// Obtener los resultados de la consulta
$noticias = $query->getResultArray();
?>
<div class="container">
    <h1 class="mt-4 mb-4">Lista de Noticias Publicadas</h1>
    <div class="row">
        <?php foreach ($noticias as $noticia) : ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?php echo $noticia['imagen']; ?>" class="card-img-top" alt="Imagen de la noticia">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $noticia['titulo']; ?></h5>
                        <p class="card-text"><?php echo $noticia['descripcion']; ?></p>
                        <a href="<?= base_url('ver_noticia?id=' . $noticia['id']) ?>" class="btn btn-primary">Leer más</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


    <!-- Formulario de Iniciar Sesión -->
<?php echo form_open('/inicioExito'); ?>
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Iniciar sesión</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Formulario de inicio de sesión -->
          <div class="form-group">
            <?= form_label('Correo electrónico', 'inputEmail'); ?>
            <?= form_input(['name' => 'iEmail', 'id' => 'inputEmail', 'class' => 'form-control', 'placeholder' => 'Correo electrónico']); ?>
          </div>
          <!-- Mostrar errores del campo email -->
          <?php if (session()->has('errors')) : ?>
              <?php $errors = session('errors'); ?>
              <?php if (isset($errors['iEmail'])) : ?>
                  <p class="text-danger"><?= esc($errors['iEmail']) ?></p>
              <?php endif; ?>
          <?php endif; ?>
          <div class="form-group">
            <?= form_label('Contraseña', 'inputPassword'); ?>
            <?= form_password(['name' => 'iPassword', 'id' => 'inputPassword', 'class' => 'form-control', 'placeholder' => 'Contraseña']); ?>
          </div>
          <!-- Mostrar errores del campo Password -->
          <?php if (session()->has('errors')) : ?>
              <?php $errors = session('errors'); ?>
              <?php if (isset($errors['iPassword'])) : ?>
                  <p class="text-danger"><?= esc($errors['iPassword']) ?></p>
              <?php endif; ?>
          <?php endif; ?>
          <input type="submit" class="btn btn-primary" name="inicio_submit" value="Iniciar">
        </div>
      </div>
    </div>
  </div>
<?= form_close(); ?>
  

  <!-- Bootstrap JS (jQuery is required) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
