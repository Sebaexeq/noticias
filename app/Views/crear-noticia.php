<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>crear noticia</title>  
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('css del diario.css') ?>">
  <?php $errors = session('errors'); ?>
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
<?php endif; ?><br>

<div class="container">
        <h2>Crear Noticia</h2>
        <?php echo form_open_multipart('/crearNoticia'); ?>
            <div class="form-group">
                <?php echo form_label('Título', 'titulo'); ?>
                <?php echo form_input(['name' => 'titulo', 'id' => 'titulo', 'class' => 'form-control', 'placeholder' => 'Título de la noticia']); ?>
                <?php if (isset($errors['titulo'])) : ?>
                <div class="text-danger"><?= esc($errors['titulo']) ?></div>
                <?php endif ?>
              </div>
              <div class="form-group">
                <?php echo form_label('Descripción', 'descripcion'); ?>
                <?php echo form_textarea(['name' => 'descripcion', 'id' => 'descripcion', 'class' => 'form-control', 'placeholder' => 'descripcion de la noticia']); ?>
                <?php if (isset($errors['descripcion'])) : ?>
                <div class="text-danger"><?= esc($errors['descripcion']) ?></div>
                <?php endif ?>
            </div>
            <div class="form-group">
                <?php echo form_label('Contenido', 'contenido'); ?>
                <?php echo form_textarea(['name' => 'contenido', 'id' => 'contenido', 'class' => 'form-control', 'placeholder' => 'Contenido de la noticia']); ?>
                <?php if (isset($errors['contenido'])) : ?>
                <div class="text-danger"><?= esc($errors['contenido']) ?></div>
                <?php endif ?>
            </div>
            <div class="form-group">
                <?php echo form_label('Categoría', 'categoria'); ?>                
                <select class="form-control" id="categoria" name="categoria">
                    <option value="">Seleccione una categoría</option>
                    <option value="Ultimas tendencias">Últimas tendencias</option>
                    <option value="Dispositivos y gadgets">Dispositivos y gadgets</option>
                    <option value="Software y aplicaciones">Software y aplicaciones</option>
                    <option value="Seguridad informatica">Seguridad informática</option>
                    <option value="Innovación e investigación">Innovación e investigación</option>
                    <option value="Evento">Evento</option>
                </select>
                <?php if (isset($errors['categoria'])) : ?>
                <div class="text-danger"><?= esc($errors['categoria']) ?></div>
                <?php endif ?>
            </div>
            <div class="form-group">
            <?php echo form_label('Duración de la noticia', 'duracion'); ?>                
            <select class="form-control" id="duracion" name="duracion">
                <option value="7" selected>1 semana</option>
                <option value="14">2 semanas</option>
                <option value="21">3 semanas</option>
            </select>
        </div>
            <?php echo form_label('Imagen', 'imagen'); ?>            
            <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*">
            <?php if (isset($errors['imagen'])) : ?>
                <div class="text-danger"><?= esc($errors['imagen']) ?></div>
            <?php endif ?>
            <button type="submit" class="btn btn-primary">Crear Noticia</button>
            <?php         
                $message = session('error-limite');
                if ($message !== null) {
                    echo '<script>alert("' . $message . '")</script>';
                }
            ?>
        <?php echo form_close(); ?>
    </div>
  
  

  <!-- Bootstrap JS (jQuery is required) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
