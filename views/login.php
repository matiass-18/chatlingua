<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatLingua - Iniciar Sesión</title>
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
                <h1 class="text-3xl font-bold text-gray-800">ChatLingua</h1>
                <p class="text-gray-600 mt-2">Aprende idiomas chateando</p>
            </div>

            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                    <?php 
                    echo $_SESSION['mensaje'];
                    unset($_SESSION['mensaje']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=login" method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                        Correo Electrónico
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200"
                        required
                        placeholder="tu@email.com"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                        Contraseña
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200"
                        required
                        placeholder="••••••••"
                    >
                </div>

                <div class="text-right">
                    <a href="index.php?action=recuperar" class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Iniciar Sesión
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    ¿No tienes una cuenta? 
                    <a href="index.php?action=registro" 
                       class="text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                        Regístrate
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>