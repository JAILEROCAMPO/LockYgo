from flask import Flask, jsonify
import mysql.connector

app = Flask(__name__)

@app.route('/get_casilleros', methods=['GET'])
def get_casilleros():
    conn = mysql.connector.connect(
        host="localhost",
        user="usuario",
        password="contraseña",
        database="base_de_datos"
    )
    cur = conn.cursor()
    cur.execute("SELECT numero, estado FROM casilleros")
    casilleros = {row[0]: row[1] for row in cur.fetchall()}
    conn.close()
    return jsonify(casilleros)

if __name__ == '__main__':
    app.run()
