<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
echo form_open('form/exito');
echo form_label('Nombre: ','nombre');
echo form_input(array('name'=>'nombre','value'=>old('nombre'))).'<br>';
echo '<p style="color:red">'.session('errors.nombre').'</p>';
echo form_label('Password: ','password');
echo form_password(array('name'=>'password', 'value'=>old('password'))).'<br>';
echo '<p style="color:red">'.session('errors.password').'</p>';
echo form_label('Confirmar Password: ','c_password');
echo form_password('c_password').'<br>';
echo form_label('Correo electrónico: ','email');
echo form_input(array('name'=>'email','type'=>'email')).'<br>';
echo form_submit('enviar','Enviar');
echo form_reset('reset','Limpiar Formulario');
echo form_close();
?>
</body>
</html>