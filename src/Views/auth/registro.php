<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Registro — Koinízate</title></head>
<body>
  <h1>Crear cuenta</h1>
  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <form method="POST" action="/registro">
    <input type="text" name="nombre" placeholder="Nombre" required><br>
    <input type="text" name="apellido" placeholder="Apellido" required><br>
    <input type="email" name="email" placeholder="Correo" required><br>
    <input type="password" name="password" placeholder="Contraseña (mín. 8 chars)" required><br>
    <button type="submit">Registrarse</button>
  </form>
</body>
</html>
