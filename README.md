# php
Programación Orientada a Objetos | UNAM CERT | Prácticas

En la carpeta practicas se encuentran las siguientes carpetas:

jsonParser:

	En esta carpeta se encuentra el archivo jsonParser.php que
	es el codigo que se encarga de parsear los dos archivos de
	ejemplo que se encuentran en la misma carpeta (example.json,
	example2.json y example3.json).

logins:

	En esta carpeta se encuentran dos carpetas:

	login:

		Aqui se encuentran todos los archivos del login
		construido con paradigma orientado a objetos.

	loginPro:

		Aqui se encuentran todos los archivos del login
		construido con paradigma orientado a objetos y
		tambien se incorpora la funcionalidad para poder
		autenticarse utilizando una cuenta de gmail.

crawler:

	Esta carpeta se encuentra el archivo crawler.php y dos archivos
	de ejemplo que fueron el resultado de ejecutar el crawler
	a la pagina web de la unam con niveles de profundidad 1 y 2
	no se intento el nivel de profundidad 3 por que el nivel 2 tomo
	mucho tiempo en ejecutarse.

	El programa descarta urls que no tengan el mismo dominio de la
	primera que se pasa.

	Para que el programa funcione es necesario ejecutar los siguientes
	comandos en nuestra terminal linux

		sudo apt-get install php-dom
		sudo systemctl restart apache2.service
	
	En nuestro navegador se mostraran un monton de warnings que deben
	ser ignorados, el resultado de la ejecucion se encontrará hasta abajo
	en el navegador despues de todos los warnings.

