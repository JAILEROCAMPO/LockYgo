# importamos Flask desde flask para usar el frameword
from flask import Flask
# importamos SQLAlchemy para hacer uso de la base de datos
from flask_sqlalchemy import SQLAlchemy

# creamos una instancia sin asociar a ninguna aplicacion flash todavia para utilizar la conexion en otros archivos
db = SQLAlchemy()

# creamos la funcion con la conexion
def crear_app_db():
    # creamos una instancia de la aplicacion flask
    aplicaciondb = Flask(__name__)
    # accedemos a la configuracion de la aplicaciondb y usamos las credenciales de la base de datos
    aplicaciondb.config['SQLALCHEMY_DATABASE_URI']='mysql+pymysql://root:@localhost/casilleros20256'
    # desactivamos las notificaciones de la base de datos para tener un mejor rendimiento y evitar advertencias
    aplicaciondb.config['SQLALCHEMY_TRACK_MODIFICATIONS']=False
    # conectamos la base de datos con la aplicacion db y pasamos la instancia a conexion 
    db.init_app(aplicaciondb)
    # retornamos la funcion 
    return aplicaciondb
