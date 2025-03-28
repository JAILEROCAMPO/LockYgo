<?php
session_start();

if (!isset($_SESSION["autenticado"])) {
    header("Location: ../login/inicioSesion_usuario.html");
    exit();
}

$nombreUsuario = $_SESSION["nombre"];
$idUsuario = $_SESSION["id_usuario"];
include '../conexion/dbpdo.php';

try {
    $query = "SELECT * FROM casilleros";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $casilleros = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $casilleros[$row['numero']] = $row['estado'];
    }
} catch (PDOException $e) {
    die("Error al obtener casilleros: " . $e->getMessage());
}

$bloques = [
    'A' => range(1, 120),
    'B' => range(121, 269),
    'C' => array_merge(range(270, 284), range(304, 310), range(315, 325), range(330, 340)),
    'D' => array_merge(range(285, 299), range(360, 434)),
    'E' => range(435, 475)
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock&Go - Reserva de Casilleros</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .contenedor-botones { text-align: center; margin-bottom: 20px; }
        .btn { margin: 5px; padding: 10px 15px; cursor: pointer; }
        .contenedor-cuadricula {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .fila {
            display: flex;
            gap: 10px;
        }
        .casillero {
            width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;
            border: 2px solid black; cursor: pointer;
        }
        .libre { background-color: green; }
        .ocupado { background-color: red; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="contenedor-botones">
        <?php foreach ($bloques as $bloque => $numeros) : ?>
            <button class="btn bloque" onclick="mostrarCasilleros('<?php echo $bloque; ?>')">Bloque <?php echo $bloque; ?></button>
        <?php endforeach; ?>
    </div>

    <div class="contenedor-cuadricula">
        <?php foreach ($bloques as $bloque => $numeros) : ?>
            <div id="bloque-<?php echo $bloque; ?>" style="display: none;">
                <?php 
                $contador = 0;
                foreach ($numeros as $num) :
                    if ($contador % 10 == 0) echo '<div class="fila">';
                    $estado = isset($casilleros[$num]) ? $casilleros[$num] : 'libre';
                    $clase = ($estado == 'libre') ? 'libre' : 'ocupado';
                ?>
                    <div class="casillero <?php echo $clase; ?>" data-id="<?php echo $num; ?>" onclick="mostrarVentana(this)">
                        <?php echo $num; ?>
                    </div>
                <?php 
                    $contador++;
                    if ($contador % 10 == 0) echo '</div>';
                endforeach; 
                if ($contador % 10 != 0) echo '</div>'; // Cierra la última fila si no es múltiplo de 10
                ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function mostrarCasilleros(bloque) {
            document.querySelectorAll('.contenedor-cuadricula > div').forEach(div => div.style.display = 'none');
            document.getElementById('bloque-' + bloque).style.display = 'block';
        }
    </script>
</body>
</html>