from flask import Flask, request
import mysql.connector

app = Flask(__name__)

@app.route('/update_casillero', methods=['POST'])
def update_casillero():
    data = request.json
    conn = mysql.connector.connect(
        host="localhost",
        user="usuario",
        password="contraseña",
        database="base_de_datos"
    )
    cur = conn.cursor()
    cur.execute("UPDATE casilleros SET estado=%s WHERE numero=%s", (data['estado'], data['id']))
    conn.commit()
    conn.close()
    return 'Estado actualizado'

if __name__ == '__main__':
    app.run()
