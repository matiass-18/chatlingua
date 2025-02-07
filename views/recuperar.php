<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatLingua - Recuperar Contraseña</title>
    
    <!-- Estilos -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-96 animate-fade-in">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-2xl text-white font-bold">CL</span>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Recuperar Contraseña</h1>
                <p class="text-gray-600 mt-2">Ingresa tu correo electrónico para recuperar tu cuenta</p>
            </div>

            <!-- Mensajes del sistema -->
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 animate-slide-in">
                    <?php 
                    echo $_SESSION['mensaje'];
                    unset($_SESSION['mensaje']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 animate-slide-in">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="index.php?action=recuperar" method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                        Correo Electrónico
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input focus:ring-2 focus:ring-blue-500 transition-all"
                        required
                        placeholder="tu@email.com"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 
                           transform hover:-translate-y-0.5 transition-all duration-200 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Enviar Enlace de Recuperación
                </button>
            </form>

            <!-- Enlaces -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    ¿Recordaste tu contraseña? 
                    <a href="index.php?action=login" 
                       class="text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                        Iniciar Sesión
                    </a>
                </p>
            </div>
        </div>
    </div>


</body>
</html>