<?php
session_start();
if (isset($_SESSION['idUsuario'])) {
  header("Location: ../index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  </head>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <body class="bg-light">
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-4">Iniciar Sesión</h3>

                    <?php if (isset($_SESSION['error'])): ?>
                    <script>
                      Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '<?= $_SESSION["error"] ?>',
                        confirmButtonText: 'Entendido'
                      });
                    </script>
                    <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>



                    <form action="proceso_login.php" method="POST">
                        <div class="mb-3">
                            <label>Identificación</label>
                            <input type="text" name="cedula" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Clave</label>
                            <input type="password" name="clave" class="form-control" required>
                        </div>
                        <div class="d-flex flex-column align-items-center gap-3 mt-4">
                          <button class="btn btn-primary w-100">Ingresar</button>
                          <a href="../index.php" class="btn btn-secondary w-100">Volver</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

  </body>
</html>