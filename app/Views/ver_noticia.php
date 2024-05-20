<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Noticia</title>  
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
<?php $id_user = session()->get('id'); 
if (!isset($id_user) || $id_user != $noticia['id_usuario'] || $noticia['estado']== '3' || $noticia['estado']== '4' || $noticia['estado']== '5') :?>
  <div class="container my-4">
    <!-- Título de la noticia -->
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4"><?php echo esc($noticia['titulo']); ?></h1>
        </div>
    </div>

    <!-- Descripción de la noticia -->
    <div class="row justify-content-center my-3">
        <div class="col-md-8">
            <p class="lead"><?php echo esc($noticia['descripcion']); ?></p>
        </div>
    </div>

    <!-- Imagen de la noticia -->
    <div class="row justify-content-center my-3">
        <div class="col-md-8">
            <img src="<?php echo $noticia['imagen']; ?>" alt="Imagen de la noticia" class="img-fluid rounded">
        </div>
    </div>

    <!-- Contenido de la noticia -->
    <div class="row justify-content-center my-3">
        <div class="col-md-8">
            <p><?php echo esc($noticia['contenido']); ?></p>
        </div>
    </div>
</div>
<br> 
    <?php if (isset($id_user)):?>
        <?php if ($noticia['estado'] == '2') : ?><!--estado = para validar-->
          <?php
          // Conexión a la base de datos
          $db = \Config\Database::connect();
          $historialQuery = $db->query("SELECT * FROM historial_noticias WHERE id_noticia = ? AND estado = 2", [$noticia['id']]);
          $rowCount = $historialQuery->getNumRows();
            ?>
          <?php if(session()->get('rol') === '1' || session()->get('rol') === '2'): ?>    
            <?php if ($rowCount == 0 ) : ?>
                <a href="<?php echo base_url('descartar_noticia?id=' . $noticia['id']); ?>" class="btn btn-primary">Rechazar</a>
            <?php endif; ?>
            <a href="<?php echo base_url('enviar_paraBorrador?id=' . $noticia['id']); ?>" class="btn btn-primary">Enviar para correción</a>
            <a href="<?php echo base_url('publicar?id=' . $noticia['id']); ?>" class="btn btn-primary">Publicar</a>
          <?php endif; ?>
        <?php endif; ?>
        <?php if ($noticia['estado'] == '3') : ?><!--estado = publico-->
          <?php if(session()->get('rol') === '1' || session()->get('rol') === '2'): ?>
            <a href="<?php echo base_url('descartar_noticia?id=' . $noticia['id']); ?>" class="btn btn-primary">Descartar</a>
          <?php endif; ?>
        <?php endif; ?>
        <?php if ($noticia['estado'] == '4') : ?><!--estado = para autoPublicada-->
          <?php if(session()->get('rol') === '1' || session()->get('rol') === '2'): ?>          
            <a href="<?php echo base_url('enviar_paraBorrador?id=' . $noticia['id']); ?>" class="btn btn-primary">Despublicar</a>
          <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?> 
    
<?php else: ?>
  <?= form_open_multipart('/editarNoticia?id=' . $noticia['id'], ['class' => 'needs-validation', 'novalidate' => '']); ?>
  <div class="mb-3">
    <?= form_label('Título', 'titulo', ['class' => 'form-label']); ?>
    <?= form_input([
        'name' => 'titulo', 
        'id' => 'titulo', 
        'class' => 'form-control', 
        'placeholder' => 'Título de la noticia', 
        'value' => esc($noticia['titulo']),
        'required' => 'required'
    ]); ?>
    <?php if (isset($errors['titulo'])) : ?>
      <div class="invalid-feedback d-block"><?= esc($errors['titulo']) ?></div>
    <?php endif ?>
  </div>

  <div class="mb-3">
    <?= form_label('Descripción', 'descripcion', ['class' => 'form-label']); ?>
    <?= form_textarea([
        'name' => 'descripcion', 
        'id' => 'descripcion', 
        'class' => 'form-control', 
        'placeholder' => 'Descripción de la noticia', 
        'value' => esc($noticia['descripcion']),
        'rows' => 3,
        'required' => 'required'
    ]); ?>
    <?php if (isset($errors['descripcion'])) : ?>
      <div class="invalid-feedback d-block"><?= esc($errors['descripcion']) ?></div>
    <?php endif ?>
  </div>

  <div class="mb-3">
    <?= form_label('Contenido', 'contenido', ['class' => 'form-label']); ?>
    <?= form_textarea([
        'name' => 'contenido', 
        'id' => 'contenido', 
        'class' => 'form-control', 
        'placeholder' => 'Contenido de la noticia', 
        'value' => esc($noticia['contenido']),
        'rows' => 5,
        'required' => 'required'
    ]); ?>
    <?php if (isset($errors['contenido'])) : ?>
      <div class="invalid-feedback d-block"><?= esc($errors['contenido']) ?></div>
    <?php endif ?>
  </div>

  <div class="mb-3">
    <?= form_label('Categoría', 'categoria', ['class' => 'form-label']); ?>
    <select class="form-select" id="categoria" name="categoria" required>
      <option value="">Seleccione una categoría</option>
      <option value="Ultimas tendencias" <?= set_select('categoria', 'Ultimas tendencias', $noticia['categoria'] == 'Ultimas tendencias'); ?>>Últimas tendencias</option>
      <option value="Dispositivos y gadgets" <?= set_select('categoria', 'Dispositivos y gadgets', $noticia['categoria'] == 'Dispositivos y gadgets'); ?>>Dispositivos y gadgets</option>
      <option value="Software y aplicaciones" <?= set_select('categoria', 'Software y aplicaciones', $noticia['categoria'] == 'Software y aplicaciones'); ?>>Software y aplicaciones</option>
      <option value="Seguridad informatica" <?= set_select('categoria', 'Seguridad informatica', $noticia['categoria'] == 'Seguridad informatica'); ?>>Seguridad informática</option>
      <option value="Innovación e investigación" <?= set_select('categoria', 'Innovación e investigación', $noticia['categoria'] == 'Innovación e investigación'); ?>>Innovación e investigación</option>
      <option value="Evento" <?= set_select('categoria', 'Evento', $noticia['categoria'] == 'Evento'); ?>>Evento</option>
    </select>
    <?php if (isset($errors['categoria'])) : ?>
      <div class="invalid-feedback d-block"><?= esc($errors['categoria']) ?></div>
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

  <div class="mb-3">
    <?= form_label('Imagen', 'imagen', ['class' => 'form-label']); ?>
    <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*">
  </div>
  <!-- Verifica el estado de la noticia y muestra opciones según corresponda -->
  <?php if ($noticia['estado'] == '1') : ?> 
    <button type="submit" class="btn btn-primary">Editar Noticia</button>
    <a href="<?php echo base_url('descartar_noticia?id=' . $noticia['id']); ?>" class="btn btn-primary">Descartar</a>
    <a href="<?php echo base_url('enviar_paraValidar?id=' . $noticia['id']); ?>" class="btn btn-primary">Enviar para revisión</a>
    <a href="<?php echo base_url('deshacer?id=' . $noticia['id']); ?>" class="btn btn-primary">Deshacer</a>
    <?php         
        $message = session('message_deshacer');
        if ($message !== null) {
            echo '<script>alert("' . $message . '")</script>';
        }
    ?>   
<?php endif; ?>

  <?php if ($noticia['estado'] == '2') : ?><!--estado = para validar-->
    <?php if (session()->get('rol') === '2'): ?>    
      <a href="<?php echo base_url('enviar_paraBorrador?id=' . $noticia['id']); ?>" class="btn btn-primary">Enviar para correción</a>
      <a href="<?php echo base_url('publicar?id=' . $noticia['id']); ?>" class="btn btn-primary">publicar</a>
    <?php endif; ?>
  <?php endif; ?>
  <?php if ($noticia['estado'] == '4') : ?><!--estado = para autoPublicada-->
      <?php if (session()->get('rol') === '2'): ?>            
        <a href="<?php echo base_url('enviar_paraBorrador?id=' . $noticia['id']); ?>" class="btn btn-primary">despublicar</a>
      <?php endif; ?>
  <?php endif; ?> 
  <?= form_close(); ?>  
<?php endif; ?>

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
