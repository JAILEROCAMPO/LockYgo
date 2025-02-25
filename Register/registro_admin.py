# importamos para obtener los datos enviados desde el formulario "request" redirigir al usuario a otra pagina despues de insertar datos "redirect"
# generar url de ruta flask "url_for" cargar archivos html "render_template"
from flask import Flask, request, redirect, url_for
#importamos la instancia de la base de datos desde el archivo db.py
from db import aplicaciondb
#usamos la instancia ya creada
app = aplicaciondb

#definimos la tabla creada en la base de datos

class usuario(aplicaciondb.Model):
    __tablename__ = 'Usuarios'

    