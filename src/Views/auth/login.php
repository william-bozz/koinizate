<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login — Koinízate</title></head>
<body>
  <h1>Iniciar sesión</h1>
  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <form method="POST" action="/login">
    <input type="email" name="email" placeholder="Correo" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button type="submit">Entrar</button>
  </form>
  <p><a href="/registro">¿No tienes cuenta?</a></p>
</body>
</html>
