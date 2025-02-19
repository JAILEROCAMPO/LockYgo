import mysql.connector
from flask import Flask, request, jsonify

app = Flask(__name__)

# Configuración de conexión a la base de datos
def conectar_db():
    return mysql.connector.connect(
        host="localhost",
        user="tu_usuario",
        password="tu_contraseña",
        database="lockers_db"
    )

@app.route('/registrar', methods=['POST'])
def registrar_usuario():
    try:
        # Obtener datos del formulario
        datos = request.json
        nombre = datos.get('nombre')
        apellido = datos.get('apellido')
        telefono = datos.get('telefono')
        identificacion = datos.get('identificacion')
        ficha = datos.get('ficha')
        correo = datos.get('correo')
        jornada = datos.get('jornada')
        contrasena = datos.get('contrasena')

        if not all([nombre, apellido, telefono, identificacion, ficha, correo, jornada, contrasena]):
            return jsonify({"error": "Todos los campos son obligatorios"}), 400

        # Conectar a la base de datos
        conexion = conectar_db()
        cursor = conexion.cursor()

        # Insertar en la base de datos
        consulta = """
            INSERT INTO usuarios (nombre, apellido, telefono, identificacion, ficha, correo, jornada, contrasena)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        valores = (nombre, apellido, telefono, identificacion, ficha, correo, jornada, contrasena)
        cursor.execute(consulta, valores)
        conexion.commit()

        return jsonify({"mensaje": "Registro exitoso"}), 201

    except Exception as e:
        return jsonify({"error": str(e)}), 500

    finally:
        cursor.close()
        conexion.close()

if __name__ == '__main__':
    app.run(debug=True)
