<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="usuario-id" content="<?php echo $_SESSION['usuario']['id']; ?>">
    <title>ChatLingua - Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 h-screen flex flex-col">
        <!-- Barra superior -->
        <div class="bg-white rounded-lg shadow-lg p-4 flex justify-between items-center mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-xl text-white font-bold">CL</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">ChatLingua</h1>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="font-medium text-gray-800">
                        <?php echo htmlspecialchars($_SESSION['usuario']['nombre'] . ' ' . $_SESSION['usuario']['apellido']); ?>
                    </p>
                    <p class="text-sm text-gray-500">
                        Aprendiendo: <?php echo htmlspecialchars($_SESSION['usuario']['idioma_aprender']); ?>
                    </p>
                </div>
                <div class="relative">
                    <img 
                        src="uploads/<?php echo htmlspecialchars($_SESSION['usuario']['foto_perfil']); ?>" 
                        alt="Foto de perfil" 
                        class="w-12 h-12 rounded-full object-cover border-2 border-blue-500"
                        onerror="this.src='uploads/default.jpg'"
                    >
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                </div>
                <a href="index.php?action=logout" 
                   class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200">
                    Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Área principal del chat -->
        <div class="flex-1 flex space-x-4">
            <!-- Lista de usuarios -->
            <div class="w-1/4 bg-white rounded-lg shadow-lg flex flex-col">
                <div class="p-4 border-b border-gray-200">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Buscar usuarios..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            id="buscar-usuario"
                        >
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                    </div>
                </div>
                
                <div class="flex-1 overflow-y-auto" id="lista-usuarios">
                    <?php if (empty($usuarios_activos)): ?>
                        <div class="p-4 text-center text-gray-500">
                            No hay usuarios disponibles
                        </div>
                    <?php else: ?>
                        <?php foreach ($usuarios_activos as $usuario): ?>
                            <div class="usuario-chat hover:bg-gray-50 cursor-pointer transition-colors duration-200" 
                                 data-usuario-id="<?php echo $usuario['id']; ?>">
                                <div class="p-4 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="relative">
                                            <img 
                                                src="uploads/<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" 
                                                alt="Foto de <?php echo htmlspecialchars($usuario['nombre']); ?>" 
                                                class="w-12 h-12 rounded-full object-cover"
                                                onerror="this.src='uploads/default.jpg'"
                                            >
                                            <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white
                                                       <?php echo $usuario['estado'] === 'conectado' ? 'bg-green-500' : 'bg-gray-400'; ?>">
                                            </span>
                                            <?php if ($usuario['mensajes_no_leidos'] > 0): ?>
                                                <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">
                                                    <?php echo $usuario['mensajes_no_leidos']; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate nombre-usuario">
                                                <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">
                                                Aprendiendo: <?php echo htmlspecialchars($usuario['idioma_aprender']); ?>
                                            </p>
                                            <span class="text-xs <?php echo $usuario['estado'] === 'conectado' ? 'text-green-500' : 'text-gray-500'; ?>">
                                                <?php echo $usuario['estado'] === 'conectado' ? 'En línea' : 'Desconectado'; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Área de chat -->
            <div class="flex-1 bg-white rounded-lg shadow-lg flex flex-col">
                <!-- Cabecera del chat -->
                <div class="p-4 border-b border-gray-200" id="chat-header">
                    <div class="flex items-center justify-center h-full text-gray-500">
                        Selecciona un usuario para comenzar a chatear
                    </div>
                </div>

                <!-- Área de mensajes -->
                <div id="chat-area" class="flex-1 p-4 overflow-y-auto">
                    <div class="flex items-center justify-center h-full text-gray-500">
                        Selecciona un usuario para comenzar a chatear
                    </div>
                </div>

                <!-- Formulario de mensaje -->
                <div id="mensaje-form" class="p-4 border-t border-gray-200" style="display: none;">
                    <form id="form-chat" class="flex items-center space-x-4">
                        <input type="hidden" id="receptor_id" name="receptor_id">
                        <input 
                            type="text" 
                            id="mensaje" 
                            name="mensaje" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500"
                            placeholder="Escribe tu mensaje..."
                        >
                        <button 
                            type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors duration-200"
                        >
                            Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/chat.js"></script>
</body>
</html>