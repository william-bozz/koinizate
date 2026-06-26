<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Koinízate</title></head>
<body>
  <h1>Κοινίζατε — funcionando ✓</h1>
  <?php if ($user): ?>
    <p>Hola, <?= htmlspecialchars($user['nombre']) ?>. <a href="/logout">Salir</a></p>
  <?php else: ?>
    <p><a href="/login">Iniciar sesión</a> · <a href="/registro">Registrarse</a></p>
  <?php endif; ?>
</body>
</html>
