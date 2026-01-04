

 <!-- Welcome to
  
     |  ___|  __ \  |   |  _ \   _ \   
     | |      |   | |   | |   | |   |  
 \   | |      |   | |   | __ <  |   |  
\___/ \____| ____/ \___/ _| \_\\___/   
                                       
  ___|  _ \  __ \  ____|    _ )   _ _| __ \  ____|    \     ___|  
 |     |   | |   | __|     _ \ \   |  |   | __|     _ \  \___ \  
 |     |   | |   | |      ( `  <   |  |   | |      ___ \       | 
\____|\___/ ____/ _____| \___/\/ ___|____/ _____|_/    _\_____/  

  https://jcduro.bexartideas.com/index.php | 2026 | JC Duro Code & Ideas

------------------------------------------------------------------------------- -->



<?php
// Incluir tu conexión PDO
require_once __DIR__ . '/ruta/a/db.php'; // AJUSTA ESTA RUTA

// Traer colores de la BD
$stmt = $pdo->query("SELECT id_color, colores, rgb_color FROM colores ORDER BY colores ASC");
$colores = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Selector de Colores - Camiseta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Font Awesome para iconos (si no lo tienes ya en el layout) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>


</head>
<body>

<div class="tshirt-module">
  <h1>Selector de Color</h1>

  <!-- Select de colores desde la BD -->
  <select id="colorPicker" class="neon-select">
    <?php foreach ($colores as $color): ?>
      <option value="<?= htmlspecialchars($color['rgb_color']) ?>">
        <?= htmlspecialchars($color['colores']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <div class="tshirt-wrapper">
    <canvas id="tshirtCanvas" width="300" height="300"></canvas>
  </div>
</div>

<script>
// ---- UTILIDADES ----
function hexToRgb(hex) {
  if (!hex) return '0,0,0';
  hex = hex.replace('#', '');
  if (hex.length === 3) {
    hex = hex.split('').map(c => c + c).join('');
  }
  const num = parseInt(hex, 16);
  const r = (num >> 16) & 255;
  const g = (num >> 8) & 255;
  const b = num & 255;
  return `${r}, ${g}, ${b}`;
}

// ---- CANVAS ----
const canvas = document.getElementById('tshirtCanvas');
const ctx = canvas.getContext('2d');
const img = new Image();
// Imagen base de la camiseta (gris/blanca)
img.src = '/proyectos/dashjc/assets/images/tshirt_base.png'; // AJUSTA RUTA

function drawBase() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
}

function drawTshirtFromHex(hex) {
  const rgb = hexToRgb(hex);
  drawTshirt(rgb);
}

function drawTshirt(rgb) {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // 1. dibuja la camiseta base (PNG con fondo transparente)
  ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

  // 2. aplica color SOLO sobre la camiseta
  ctx.globalCompositeOperation = 'source-atop';
  ctx.fillStyle = `rgba(${rgb}, 0.6)`;
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  // 3. reset
  ctx.globalCompositeOperation = 'source-over';
}


// Cargar imagen base
img.onload = () => {
  // Dibujar camiseta blanca tal cual
  drawBase();

  // Si existe al menos un color, aplicar el primero (si no es negro puro puedes dejarlo)
  const select = document.getElementById('colorPicker');
  if (select && select.value) {
    // Si quieres que arranque SIN color, comenta la siguiente línea:
    // drawTshirtFromHex(select.value);
  }
};

// Cambio de color desde el select
document.getElementById('colorPicker').addEventListener('change', function () {
  const hex = this.value; // ej: "#ff0000"
  drawTshirtFromHex(hex);
});
</script>

</body>
</html>

