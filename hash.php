<?php
// Contraseña en texto plano
$password = "Especiales00";

// Generar hash usando bcrypt (por defecto)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Contraseña original: " . $password . PHP_EOL;
echo "Contraseña hasheada: " . $hashedPassword . PHP_EOL;

// Verificar la contraseña
if (password_verify($password, $hashedPassword)) {
    echo "La contraseña coincide";
} else {
    echo "La contraseña no coincide";
}
