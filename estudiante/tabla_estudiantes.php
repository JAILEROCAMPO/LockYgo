<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock&Go - Gestión de Estudiantes</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../Imagenes/image-removebg-preview.png">
    <script>
        function abrirModal(id, nombre, apellidos, telefono, identificacion, ficha, email, jornada, programa) {
            document.getElementById("id").value = id;
            document.getElementById("nombre").value = nombre;
            document.getElementById("apellidos").value = apellidos;
            document.getElementById("telefono").value = telefono;
            document.getElementById("identificacion").value = identificacion;
            document.getElementById("ficha").value = ficha;
            document.getElementById("email").value = email;
            document.getElementById("jornada").value = jornada;
            document.getElementById("programa").value = programa;
            document.getElementById("modal").style.display = "block";
        }
        function cerrarModal() {
            document.getElementById("modal").style.display = "none";
        }
    </script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }
    </style>
</head>
<body>
    <h2>Lista de Estudiantes</h2>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["nombre"]; ?></td>
            <td><?php echo $row["apellidos"]; ?></td>
            <td><?php echo $row["celular"]; ?></td>
            <td>
                <a href="#" onclick="abrirModal('<?php echo $row['id']; ?>', '<?php echo $row['nombre']; ?>', '<?php echo $row['apellidos']; ?>', '<?php echo $row['celular']; ?>', '<?php echo $row['identificacion']; ?>', '<?php echo $row['ficha']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['jornada']; ?>', '<?php echo $row['programa_formacion']; ?>')">✏ Editar</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div id="modal" class="modal">
        <div class="modal-content">
            <h2>Editar Estudiante</h2>
            <form method="POST">
                <input type="hidden" id="id" name="id">
                <label>Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br>
                <label>Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required><br>
                <label>Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required><br>
                <button type="submit" name="editar">Actualizar</button>
                <button type="button" onclick="cerrarModal()">Cerrar</button>
            </form>
        </div>
    </div>
</body>
</html>

