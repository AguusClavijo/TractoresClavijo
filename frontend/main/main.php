<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/81448e9ee5.js" crossorigin="anonymous"></script>
  <title>Tractores Clavijo - Inicio</title>
  <link rel="stylesheet" href="main.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
  <header class="bg-success">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
      <div class="container">
        <a class="navbar-brand logo" href="#">Tractores Clavijo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="../main/main.php">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../about/about.php">Sobre Nosotros</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../tractors/tractors.php">Tractores</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../contact/contact.php">Contacto</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-outline-light nav-link" href="../login/login.php">Iniciar Sesión</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <div id="carouselExampleFade" class="carousel slide carousel-fade mt-0">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="img/tractor1.png" class="d-block w-100" alt="Tractor 1">
        </div>
        <div class="carousel-item">
          <img src="img/tractor2.png" class="d-block w-100" alt="Tractor 2">
        </div>
        <div class="carousel-item">
          <img src="img/tractor3.png" class="d-block w-100" alt="Tractor 3">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </main>

  <footer class="text-light bg-dark">
    <div class="container py-4">
      <div class="row text-center text-md-start align-items-start">
        <div class="col-md-4 mb-3">
          <h5 style="color: #4caf50;">Tractores Clavijo</h5>
          <p>Conecte con nosotros:</p>
          <a href="https://www.instagram.com/tractoresclavijo_" target="_blank" class="d-block text-light text-decoration-none mb-1">
            <i class="bi bi-instagram"></i> @tractoresclavijo_
          </a>
          <a href="https://www.facebook.com/profile.php?id=100089567695602" target="_blank" class="d-block text-light text-decoration-none">
            <i class="bi bi-facebook"></i> Tractores Clavijo
          </a>
        </div>
        <div class="col-md-4 mb-3">
          <h5 style="color: #4caf50;">Ubicación</h5>
          <address class="text-light">
            Carril Santos Lugares Ingeniero Giagnoni<br>
            Mendoza - ARGENTINA<br>
            <span>Teléfono: </span>0263 452-6714
          </address>
        </div>
        <div class="col-md-4 mb-3">
          <h5 style="color: #4caf50;">Enlaces</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-light text-decoration-none">Inicio</a></li>
            <li><a href="#" class="text-light text-decoration-none">Sobre Nosotros</a></li>
            <li><a href="#" class="text-light text-decoration-none">Tractores</a></li>
            <li><a href="#" class="text-light text-decoration-none">Repuestos</a></li>
            <li><a href="#" class="text-light text-decoration-none">Contacto</a></li>
          </ul>
        </div>
      </div>
      <div class="text-center border-top pt-3 mt-3">
        <small>&copy; 2024 Tractores Clavijo - Todos los derechos reservados</small>
      </div>
    </div>
  </footer>
</body>

</html>