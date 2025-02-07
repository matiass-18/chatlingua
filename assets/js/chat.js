document.addEventListener('DOMContentLoaded', function() {
    const chatArea = document.getElementById('chat-area');
    const mensajeForm = document.getElementById('mensaje-form');
    const formChat = document.getElementById('form-chat');
    const searchInput = document.getElementById('buscar-usuario');
    const usuariosList = document.getElementById('lista-usuarios');
    const usuariosItems = document.querySelectorAll('.usuario-chat');
    let usuarioActual = null;
    let intervalActualizacion = null;

    // Función de búsqueda
    function buscarUsuarios(searchTerm) {
        searchTerm = searchTerm.toLowerCase();
        
        usuariosItems.forEach(usuario => {
            const nombreUsuario = usuario.querySelector('.nombre-usuario').textContent.toLowerCase();
            if (nombreUsuario.includes(searchTerm)) {
                usuario.style.display = 'block';
            } else {
                usuario.style.display = 'none';
            }
        });

        // Mostrar mensaje si no hay resultados
        const usuariosVisibles = Array.from(usuariosItems).filter(u => u.style.display !== 'none');
        const mensajeNoResultados = usuariosList.querySelector('.no-resultados');
        
        if (usuariosVisibles.length === 0) {
            if (!mensajeNoResultados) {
                const mensaje = document.createElement('div');
                mensaje.className = 'p-4 text-center text-gray-500 no-resultados';
                mensaje.textContent = 'No se encontraron usuarios';
                usuariosList.appendChild(mensaje);
            }
        } else if (mensajeNoResultados) {
            mensajeNoResultados.remove();
        }
    }

    // Evento de búsqueda
    searchInput.addEventListener('input', function(e) {
        buscarUsuarios(e.target.value);
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            buscarUsuarios('');
            this.blur();
        }
    });

    // Cargar mensajes
    async function cargarMensajes(usuarioId) {
        if (!usuarioId) return;
        
        try {
            const response = await fetch(`index.php?action=obtener-mensajes&receptor_id=${usuarioId}`);
            const mensajes = await response.json();
            
            if (mensajes && Array.isArray(mensajes)) {
                mostrarMensajes(mensajes);
            } else {
                chatArea.innerHTML = '<div class="text-center text-gray-500">No hay mensajes aún</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            chatArea.innerHTML = '<div class="text-center text-red-500">Error al cargar mensajes</div>';
        }
    }

    // Mostrar mensajes
    function mostrarMensajes(mensajes) {
        const miId = document.querySelector('meta[name="usuario-id"]').content;
        chatArea.innerHTML = mensajes.map(mensaje => {
            const esEmisor = mensaje.emisor_id == miId;
            return `
                <div class="mb-4 ${esEmisor ? 'text-right' : 'text-left'}">
                    <div class="flex ${esEmisor ? 'justify-end' : 'items-start'} mb-1">
                        <img src="uploads/${mensaje.emisor_foto}" 
                             alt="Foto de perfil" 
                             class="w-8 h-8 rounded-full ${esEmisor ? 'order-2 ml-2' : 'mr-2'}"
                             onerror="this.src='uploads/default.jpg'"
                        >
                        <div class="${esEmisor ? 'bg-blue-500 text-white' : 'bg-gray-200'} 
                                    rounded-lg px-4 py-2 max-w-xs">
                            ${mensaje.contenido}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        chatArea.scrollTop = chatArea.scrollHeight;
    }

    // Click en usuario
    document.querySelectorAll('.usuario-chat').forEach(usuario => {
        usuario.addEventListener('click', function() {
            const usuarioId = this.dataset.usuarioId;
            if (!usuarioId) return;

            // Limpiar intervalo anterior
            if (intervalActualizacion) {
                clearInterval(intervalActualizacion);
            }

            // Actualizar UI
            document.querySelectorAll('.usuario-chat').forEach(u => 
                u.classList.remove('bg-blue-100')
            );
            this.classList.add('bg-blue-100');
            
            // Mostrar formulario
            mensajeForm.style.display = 'block';
            document.getElementById('receptor_id').value = usuarioId;
            usuarioActual = usuarioId;
            
            // Cargar mensajes
            cargarMensajes(usuarioId);
            
            // Configurar actualización automática
            intervalActualizacion = setInterval(() => cargarMensajes(usuarioId), 3000);
        });
    });

    // Enviar mensaje
    formChat.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!usuarioActual) return;

        const mensajeInput = document.getElementById('mensaje');
        const mensaje = mensajeInput.value.trim();
        
        if (!mensaje) return;

        try {
            const formData = new FormData();
            formData.append('receptor_id', usuarioActual);
            formData.append('mensaje', mensaje);

            const response = await fetch('index.php?action=enviar-mensaje', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();
            if (resultado.success) {
                mensajeInput.value = '';
                await cargarMensajes(usuarioActual);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Limpiar al cerrar
    window.addEventListener('beforeunload', function() {
        if (intervalActualizacion) {
            clearInterval(intervalActualizacion);
        }
    });
});