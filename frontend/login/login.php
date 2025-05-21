<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script
    src="https://kit.fontawesome.com/81448e9ee5.js"
    crossorigin="anonymous"></script>
  <title>Tractores Clavijo</title>
  <link rel="stylesheet" href="login.css" />
</head>

<body>
  <header>
    <h1>Tractores Clavijo</h1>
    <nav class="navegacion" id="navegacion"> <a href="../main/main.php">Inicio</a>
      <a href="../about/about.php">Informacion</a>
      <a href="../tractors/tractors.php">Tractores</a>
      <a href="../contact/contact.php">Contactos</a>
      <a href="../merch/merch.php">Merch</a>
      <button class="btn" id="loginBtn">Iniciar Sesión</button>
      <button class="btn" id="cartBtn"><i class="fa-solid fa-cart-shopping"></i></button>
    </nav>
    <div class="menu-toggle" id="menuToggle"> <i class="fa-solid fa-bars"></i>
    </div>
  </header>

  <div class="fondo" id="loginFormContainer"> <span class="icono-cerrar" id="closeIcon"><i class="fa-solid fa-xmark"></i></span> <div class="contenedor-form login" id="loginForm"> <h2>Iniciar sesión</h2>
    <div class="contenedor-input">
      <input type="text" required>
      <label>Usuario</label>
      <span class="icono"><i class="fa-solid fa-user"></i></span>
    </div>
    <div class="contenedor-input">
      <input type="password" required>
      <label>Contraseña</label>
      <span class="icono"><i class="fa-solid fa-lock"></i></span>
    </div>
    <button class="btn">Entrar</button>
    <div class="registrar">
      <p>¿No tienes una cuenta? <a href="#" class="registrar-link" id="registerLink">Regístrate</a></p> </div>
  </div>

  <div class="contenedor-form registrar" id="registerForm"> <h2>Registrarse</h2>
      <form action="#">
          
        <div class="contenedor-input">
          <span class="icono"><i class="fa-solid fa-user"></i></span>
          <input type="text" required>
          <label for="#">Nombre de Usuario</label>
        </div> 

        <div class="contenedor-input">
          <span class="icono"><i class="fa-solid fa-envelope"></i></span>
          <input type="email" required>
          <label for="#">Email</label>
        </div>

        <div class="contenedor-input">
          <span class="icono"><i class="fa-solid fa-lock"></i></span>
          <input type="password" required>
          <label for="#">Contraseña</label>
        </div>

        <div class="recordar">
          <label for="#"><input type="checkbox">Acepto los términos y condiciones</label>
        </div>

        <button type="submit" class="btn">Registrarme</button>

        <div class="registrar">
          <p>¿Tienes una cuenta? <a href="#" class="login-link" id="loginLink">Iniciar Sesión</a></p> </div>
      </form>
    </div>
    </div>

    <script src="login.js"></script>
</body>

</html>