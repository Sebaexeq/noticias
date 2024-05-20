<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Revisar publicaciones</title>  
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
            <a class="dropdown-item" href="<?= base_url('revisar-punlicaciones') ?>">Revisiar noticias auto-publicadas</a>
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

<div class="container">
  <h2>Mis Noticias</h2>
  <table class="table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Categoría</th>
            <th>Fecha de Creación</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Conexión a la base de datos
        $db = \Config\Database::connect();
        
        $sql = "SELECT * FROM noticias WHERE estado = '4'"; //estado "Auto publicada"
        $query = $db->query($sql);
        $noticias = $query->getResultArray();

        // Mostrar las noticias en la tabla
        foreach ($noticias as $noticia): ?>
            <tr>
                <td><a href="<?= base_url('ver_noticia?id=' . $noticia['id']) ?>"><?= $noticia['titulo'] ?></a></td>
                <td><?= $noticia['descripcion'] ?></td>
                <td>
                    <?php
                    // Consulta SQL para obtener el nombre del estado
                    $estadoId = $noticia['estado'];
                    $sqlEstado = "SELECT nombre FROM estados WHERE id = ?";
                    $queryEstado = $db->query($sqlEstado, [$estadoId]);
                    $estado = $queryEstado->getRow()->nombre;
                    echo $estado;
                    ?>
                </td>
                <td><?= $noticia['categoria'] ?></td>
                <td><?= $noticia['fecha_creacion'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<!-- Bootstrap JS (jQuery is required) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
