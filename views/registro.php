<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatLingua - Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md animate-fade-in">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-2xl text-white font-bold">CL</span>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Únete a ChatLingua</h1>
                <p class="text-gray-600 mt-2">Crea tu cuenta y comienza a aprender</p>
            </div>

            <!-- Mensajes de error -->
            <?php if (!empty($errores)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 animate-slide-in">
                    <ul class="list-disc list-inside">
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="index.php?action=registro" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="nombre">
                            Nombre
                        </label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            class="form-input"
                            required
                            value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>"
                        >
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="apellido">
                            Apellido
                        </label>
                        <input 
                            type="text" 
                            id="apellido" 
                            name="apellido" 
                            class="form-input"
                            required
                            value="<?php echo isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : ''; ?>"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                        Correo Electrónico
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input"
                        required
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="idioma_aprender">
                        Idioma que deseas aprender
                    </label>
                    <select 
                        id="idioma_aprender" 
                        name="idioma_aprender" 
                        class="form-input"
                        required
                    >
                        <option value="">Selecciona un idioma</option>
                        <option value="ingles" <?php echo (isset($_POST['idioma_aprender']) && $_POST['idioma_aprender'] === 'ingles') ? 'selected' : ''; ?>>Inglés</option>
                        <option value="espanol" <?php echo (isset($_POST['idioma_aprender']) && $_POST['idioma_aprender'] === 'espanol') ? 'selected' : ''; ?>>Español</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="foto_perfil">
                        Foto de Perfil
                    </label>
                    <input 
                        type="file" 
                        id="foto_perfil" 
                        name="foto_perfil" 
                        class="form-input"
                        accept="image/*"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                        Contraseña (mínimo 8 caracteres y un número)
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input"
                        required
                        minlength="8"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password_confirm">
                        Confirmar Contraseña
                    </label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        class="form-input"
                        required
                        minlength="8"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 
                           transform hover:-translate-y-0.5 transition-all duration-200 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Crear Cuenta
                </button>
            </form>

            <!-- Enlaces -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    ¿Ya tienes una cuenta? 
                    <a href="index.php?action=login" 
                       class="text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                        Inicia Sesión
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const passwordInput = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');

        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            
            if(password.length < 8 || !/\d/.test(password)) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres y contener al menos un número');
                return;
            }
            
            if(password !== passwordConfirm.value) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return;
            }
        });
    });
    </script>
</body>
</html>