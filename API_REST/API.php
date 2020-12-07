<?php 
/*
	Autor: Jorge Eduardo Martínez Mohedano
	Fecha: 05/12/2020
	Descripción: Mi primera API REST FULL conectada a una base de datos en MYSQL con el método de conexión PDO.
*/
include 'Configuracion.php';
include 'utilidades.php';
$dbConexion = conectar($db);
//Métodos para seleccionar un elemento o todos los elementos de la base de datos.
//por medio de la condición IF verifica si se invocado por el método GET, si esto es
//correcto, verifica si el llamado recibe parámetro, para realizar la acción correspondiente. 
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if(isset($_GET['Id_Disco'])){
			//Por medio de la variable, se concatena el valor recibido, para preprar la consulta
			$Consulta = $dbConexion->prepare("SELECT * FROM Disco WHERE Id_Disco=:Id_Disco");
			//Se agrega la variable a la consulta
			$Consulta->bindValue(':Id_Disco', $_GET['Id_Disco']);
			//Se ejecuta la consulta guardando el resultado en la variable
			$Consulta->execute();
			//Redirección
			header("HTTP/1.1 200 OK");
			//JSONEncode para poder obtenerlo en este formato
			echo json_encode($Consulta->fetch(PDO::FETCH_ASSOC));
			//Termina la consulta.
			exit();
		}
		else{
			//Prepara la consulta
			$sql = $dbConexion->prepare("SELECT * FROM Disco");
			//Se ejecuta la consulta guardando el resultado en la variable
		    $sql->execute();
		    //PDO Se encarga de formatear el resultado de en la variable.
		    $sql->setFetchMode(PDO::FETCH_ASSOC);
		    //Redirección
		    header("HTTP/1.1 200 OK");
		    //JSONEncode para poder obtenerlo en este formato
		    echo json_encode($sql->fetchAll());
		    //Termina la consulta.
		    exit();
		}
	}
//Por medio del siguiente IF, y en caso de que haya sido invocado por medio del método POST
//realizará el insert, los parametros necesarios para este método no viajan a travez de la barra
//de direcciones.
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//En caso de que sea este método, se revisan todas las variables que viajan para ver si están completas.
		if(isset($_POST['N_Disco'],$_POST['Artista'],$_POST['Descripcion'],$_POST['Cantidad'])){
			//Asignación de valores recibidos a variables en ejecución
			$Nombre_Disco = $_POST['N_Disco'];
			$Artista = $_POST['Artista'];
			$Descripcion = $_POST['Descripcion'];
			$Cantidad = $_POST['Cantidad'];
			// Preparación de la consulta, la especificación de campos puede ser de esta forma.
			$resultado = $dbConexion->prepare("INSERT INTO Disco (N_Disco,Artista,Descrip,Cantidad) 
			VALUES(:N_Disco,:Artista,:Descripcion,:Cantidad)");
			//A la variable que se le asignó la preparación de la ejecución de la consulta, se le anexan
			//las variables con el formato que fueron descritas en la consulta.
			$resultado->bindParam(':N_Disco', $Nombre_Disco);
			$resultado->bindParam(':Artista', $Artista);
			$resultado->bindParam(':Descripcion', $Descripcion);
			$resultado->bindParam(':Cantidad', $Cantidad);
			//Se ejecuta la consulta.
			$resultado->execute();
			//Fin de la consulta.
			exit();

		}
	}
//Método de delete, identifica el delete y se encarga de borrar, este método recibe los parámetros
//por medio de la barra de direcciones.
	if($_SERVER['REQUEST_METHOD']== 'DELETE'){
		if(isset($_GET['Id_Disco'])){
		$Id_Disco = $_GET['Id_Disco'];
		$Consulta = $dbConexion->prepare("DELETE FROM Disco WHERE Id_Disco=:Id_Disco");
		$Consulta->bindValue(':Id_Disco',$Id_Disco);
		$Consulta->execute();
		header("HTTP/1.1 200 OK");
		exit();
		}
	}
//Método encargado de la actualización de los campos de una registro en la base de datos.
//estos datos viajan al igual que get por la barra de direcciones.
	if($_SERVER['REQUEST_METHOD']== 'PUT'){
		//En caso de que sea este método, se verifican las variables recibidas.
		if(isset($_GET['Id_Disco'],$_GET['N_Disco'],$_GET['Artista'],$_GET['Descripcion'],$_GET['Cantidad'])){
			//Asignación de valores recibidos a variables en ejecución
			$Id_Disco = $_GET['Id_Disco'];
			$Nombre_Disco = $_GET['N_Disco'];
			$Artista = $_GET['Artista'];
			$Descripcion = $_GET['Descripcion'];
			$Cantidad = $_GET['Cantidad'];
			// Preparación de la cadena de la consulta, la especificación de campos puede ser de esta forma.
			$Consulta = "UPDATE Disco SET N_Disco = :N_Disco, Artista = :Artista, Descrip = :Descripcion, Cantidad = :Cantidad WHERE Id_Disco= :Id_Disco";
			// Preparación de la consulta
			$resultado = $dbConexion->prepare($Consulta);
			//A la variable que se le asignó la preparación de la ejecución de la consulta, se le anexan
			//las variables con el formato que fueron descritas en la consulta.
			$resultado->bindValue(':Id_Disco', $Id_Disco);
			$resultado->bindValue(':N_Disco' , $Nombre_Disco);
			$resultado->bindValue(':Artista', $Artista);
			$resultado->bindValue(':Descripcion', $Descripcion);
			$resultado->bindValue(':Cantidad', $Cantidad);
			//Ejecución de la consulta.
			$resultado->execute();
			//Redirección
			header("HTTP/1.1 200 OK");
	    	//Fin de la consulta.
	    	exit();

		}
	}
//EN CASO DE QUE NO COINCIDA CON NINGUNO DE LOS ANTERIOS, SE REDIRIGIRA A UNA PAGINA DE ERROR.
//	
	header("HTTP/1.1 400 Bad Request");
?>