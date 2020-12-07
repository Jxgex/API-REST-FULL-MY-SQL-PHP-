<?php
/*
	Autor: Jorge Eduardo Martínez Mohedano
	Fecha: 05/12/2020
	Descripcion: Archivo encargado de encapsular la cadena de conexión con la base de datos.
*/
	function conectar($db){
		try {
			//Los parámetros que recibe son invocados con el arreglo de el archivo de Configuración.
			$conexion = new PDO("mysql:host={$db['host']};dbname={$db['db']}",$db['user_name'],$db['password']);

			$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conexion;
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
?>