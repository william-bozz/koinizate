<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Dashboard — Koinízate</title></head>
<body>
  <h1>¡Bienvenido, <?= htmlspecialchars($user['nombre']) ?>!</h1>
  <p>Tu cuenta fue creada correctamente.</p>
  <ul>
    <li>Email: <?= htmlspecialchars($user['email']) ?></li>
    <li>Plan: <?= $user['plan'] ?></li>
    <li>Idioma: <?= $user['idioma'] ?></li>
  </ul>
  <a href="/logout">Cerrar sesión</a>
</body>
</html>
