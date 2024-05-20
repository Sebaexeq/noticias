<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuarios</title>  
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('css del diario.css') ?>">
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <!-- Brand -->
      <a class="navbar-brand" href="<?= site_url() ?>"><img src="<?php echo base_url('logo.png') ?>" alt="logo" height="100px" width="auto"></a>
      <a class="navbar-brand" href="<?= site_url() ?>"><h1 style="color: white;">Diario Tu Noticia</h1></a>

      <!-- Toggler/collapsible Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url('registroUser') ?>" data-target="#registroModal"><h2 style="color: white;">Registrar</h2></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal"><h2 style="color: white;">Iniciar sesión</h2></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>  <br>
  <?php echo form_open('/registroExito'); ?>
    <h2>Registro de Usuario</h2>
    <!-- Formulario de registro de usuario -->
    <div class="form-group">
      <?php echo form_label('Nombre', 'registroNombre'); ?>
      <?php echo form_input(['name' => 'nombre', 'id' => 'registroNombre', 'class' => 'form-control', 'placeholder' => 'Nombre']); ?>
    </div>
    <!-- Mostrar errores del campo nombre -->
    <?php if (session()->has('errors')) : ?>
      <?php $errors = session('errors'); ?>
      <?php if (isset($errors['nombre'])) : ?>
        <p class="text-danger"><?= esc($errors['nombre']) ?></p>
      <?php endif; ?>
    <?php endif; ?>
    <div class="form-group">
      <?php echo form_label('Apellido', 'registroApellido'); ?>
      <?php echo form_input(['name' => 'apellido', 'id' => 'registroApellido', 'class' => 'form-control', 'placeholder' => 'Apellido']); ?>
    </div>
    <!-- Mostrar errores del campo apellido -->
    <?php if (session()->has('errors')) : ?>
      <?php $errors = session('errors'); ?>
      <?php if (isset($errors['apellido'])) : ?>
        <p class="text-danger"><?= esc($errors['apellido']) ?></p>
      <?php endif; ?>
    <?php endif; ?>
    <div class="form-group">
      <?php echo form_label('Correo electrónico', 'registroEmail'); ?>
      <?php echo form_input(['name' => 'email', 'id' => 'registroEmail', 'class' => 'form-control', 'placeholder' => 'Correo electrónico']); ?>
    </div>
    <!-- Mostrar errores del campo email -->
    <?php if (session()->has('errors')) : ?>
      <?php $errors = session('errors'); ?>
      <?php if (isset($errors['email'])) : ?>
        <p class="text-danger"><?= esc($errors['email']) ?></p>
      <?php endif; ?>
    <?php endif; ?>
    <div class="form-group">
      <?php echo form_label('Contraseña', 'registroPassword'); ?>
      <?php echo form_password(['name' => 'password', 'id' => 'registroPassword', 'class' => 'form-control', 'placeholder' => 'Contraseña']); ?>
    </div>
    <!-- Mostrar errores del campo password -->
    <?php if (session()->has('errors')) : ?>
      <?php $errors = session('errors'); ?>
      <?php if (isset($errors['password'])) : ?>
        <p class="text-danger"><?= esc($errors['password']) ?></p>
      <?php endif; ?>
    <?php endif; ?>
    <div class="form-group">
      <?php echo form_label('Confirmar Contraseña', 'registroConfirmPassword'); ?>            
      <?php echo form_password(['name' => 'cPassword', 'id' => 'registroConfirmPassword', 'class' => 'form-control', 'placeholder' => 'Confirmar Contraseña']); ?>
    </div>
    <!-- Mostrar errores del campo cPassword -->
    <?php if (session()->has('errors')) : ?>
      <?php $errors = session('errors'); ?>
      <?php if (isset($errors['cPassword'])) : ?>
        <p class="text-danger"><?= esc($errors['password']) ?></p>
      <?php endif; ?>
    <?php endif; ?>
    <div class="form-group">
      <?php echo form_label('Tipo de Usuario', 'registroUserType'); ?>
      <?php echo form_dropdown('rol', ['' => 'Seleccione tipo de usuario', '0' => 'Editor', '1' => 'Validador', '2' => 'Ambos'], '', ['name' => 'rol','id' => 'registroUserType', 'class' => 'form-control']); ?>
    </div>
    <!-- Mostrar errores del campo rol -->
    <?php if (session()->has('errors')) : ?>
      <?php $errors = session('errors'); ?>
      <?php if (isset($errors['cPassword'])) : ?>
        <p class="text-danger"><?= esc($errors['rol']) ?></p>
      <?php endif; ?>
    <?php endif; ?>
    <input type="submit" class="btn btn-primary" name="registro_submit" value="Registrar"> 
  </div>
</div>
<?= form_close(); ?>

<!-- Bootstrap JS (jQuery is required) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
