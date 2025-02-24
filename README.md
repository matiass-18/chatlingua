# 📢 Chatlingua

**Chatlingua** es una aplicación web diseñada para mejorar el aprendizaje de idiomas a través de la interacción en un chat en línea.

## 📌 Características

✅ Chat en tiempo real para la práctica de idiomas.  
✅ Sistema de autenticación de usuarios.  
✅ Recuperación de contraseña mediante correo electrónico.  
✅ Implementación con **PHP, JavaScript, HTML, CSS y SQL**.  
✅ Uso de **PHPMailer** para el envío de correos electrónicos.  
✅ Almacenamiento de archivos en la carpeta `uploads/`.  

---

## 📂 Estructura del Proyecto

📦 Chatlingua
 ┣ 📂 assets                # Archivos estáticos (CSS y JS) 
 ┃ ┣ 📂 css                 # Hojas de estilo
 ┃ ┃ ┗ 📜 styles.css        # Archivo de estilos principal
 ┃ ┣ 📂 js                  # Archivos JavaScript
 ┃ ┃ ┗ 📜 chat.js           # Lógica de interacción en el chat
 ┣ 📂 config                # Configuración del proyecto
 ┃ ┣ 📜 Database.php        # Conexión a la base de datos
 ┃ ┣ 📜 Mailer.php          # Configuración de PHPMailer para envío de correos
 ┣ 📂 controllers           # Controladores (manejan la lógica de negocio)
 ┃ ┣ 📜 ChatController.php  # Controlador del chat
 ┃ ┣ 📜 UsuarioController.php  # Controlador de usuarios (registro, login, etc.)
 ┣ 📂 models                # Modelos de la base de datos
 ┃ ┣ 📜 Mensaje.php         # Modelo de mensajes del chat
 ┃ ┣ 📜 Usuario.php         # Modelo de usuarios
 ┣ 📂 uploads               # Carpeta para almacenar archivos subidos por usuarios
 ┣ 📂 vendor                # Dependencias instaladas con Composer
 ┃ ┣ 📂 composer            # Archivos internos de Composer
 ┃ ┣ 📂 phpmailer\phpmailer # Librería PHPMailer para envío de correos
 ┃ ┣ 📜 autoload.php        # Autoload de Composer para cargar dependencias
 ┣ 📂 views                 # Vistas (Interfaz de usuario en PHP)
 ┃ ┣ 📜 chat.php            # Página principal del chat
 ┃ ┣ 📜 login.php           # Página de inicio de sesión
 ┃ ┣ 📜 recuperar.php       # Página para recuperar contraseña
 ┃ ┣ 📜 registro.php        # Página de registro de usuarios
 ┃ ┣ 📜 restablecer.php     # Página para restablecer la contraseña
 ┣ 📜 composer.json         # Archivo de configuración de Composer
 ┣ 📜 composer.lock         # Archivo de control de versiones de dependencias
 ┣ 📜 index.php             # Archivo de inicio del proyecto

🔧 Requisitos previos
Antes de empezar, asegúrate de tener instalados los siguientes programas en tu sistema:

✅ PHP (versión 7.4 o superior)
✅ Composer (para gestionar dependencias)
✅ Servidor web (XAMPP, WAMP o Apache con MySQL)
✅ Base de datos MySQL

📥 1. Instalación del proyecto
🔹 Clonar o descargar el proyecto
Si usas Git, clona el repositorio en tu carpeta de servidor local:
git clone https://github.com/tu-usuario/chatlingua.git

O simplemente descarga el código y colócalo en tu directorio del servidor.

📦 2. Instalación de dependencias
🔹 Entra en la carpeta del proyecto y ejecuta el siguiente comando para instalar las dependencias necesarias (PHPMailer, etc.):
cd chatlingua
composer install

🛠 3. Configuración del proyecto
🔹 📁 Dependencias con Composer
Ejecuta:
composer install

4️⃣ Configurar la Base de Datos
🔹 ¡No es necesario crear una nueva base de datos!
✔️ La base de datos ya está creada y cargada en el repositorio.

📌 Importar la Base de Datos
Si necesitas importar la base de datos manualmente en phpMyAdmin, sigue estos pasos:

1. Abre phpMyAdmin en tu navegador:
http://localhost/phpmyadmin/
2. Selecciona la pestaña Importar.
3. Haz clic en Seleccionar archivo y elige chat.sql (ubicado en el repositorio).
4. Presiona Continuar para completar la importación.
Nota: Si chat.sql no está en el repositorio, verifica con tu equipo o exporta la base de datos desde phpMyAdmin.

5️⃣ Configurar la Conexión a la Base de Datos
Abre el archivo config/Database.php y verifica que los datos coincidan con tu configuración de MySQL
⚠ Si cambiaste el usuario o contraseña de MySQL, actualízalos en este archivo.

6️⃣ Iniciar el Servidor Local
Si usas XAMPP, activa Apache y MySQL, luego abre el navegador y accede a:
http://localhost/chatlingua/index.php?action=login

7️⃣ ¡Listo! 🎉
Tu aplicación ChatLingua está instalada y funcionando.

![image](https://github.com/user-attachments/assets/3ae42c01-1d07-4462-a66f-7ba8c184a64a)
![image](https://github.com/user-attachments/assets/c56cfbaf-90c6-4229-b066-70a77f7a2cbd)
![image](https://github.com/user-attachments/assets/d2cd1fc8-2fd6-4798-8f5f-e661482f4413)
![image](https://github.com/user-attachments/assets/96b9f42f-8800-4e4f-8321-511538330b23)
![image](https://github.com/user-attachments/assets/da01ca2d-c0ba-495b-b86c-9780046d7ea7)
