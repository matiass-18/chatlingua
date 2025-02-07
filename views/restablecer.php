<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatLingua - Restablecer Contraseña</title>
    
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
                <h1 class="text-3xl font-bold text-gray-800">Nueva Contraseña</h1>
                <p class="text-gray-600 mt-2">Introduce tu nueva contraseña</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 animate-slide-in">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=restablecer&token=<?php echo htmlspecialchars($token); ?>" method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                        Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input pr-10"
                            required
                            minlength="6"
                        >
                        <button 
                            type="button"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                            onclick="togglePassword('password')"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password_confirm">
                        Confirmar Contraseña
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirm" 
                            name="password_confirm" 
                            class="form-input pr-10"
                            required
                            minlength="6"
                        >
                        <button 
                            type="button"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                            onclick="togglePassword('password_confirm')"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    <ul class="list-disc list-inside space-y-1">
                        <li>La contraseña debe tener al menos 6 caracteres</li>
                        <li>Debe incluir números y letras</li>
                        <li>Las contraseñas deben coincidir</li>
                    </ul>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 
                           transform hover:-translate-y-0.5 transition-all duration-200"
                >
                    Guardar Nueva Contraseña
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>