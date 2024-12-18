//Archivo js para el chat bot

// Variables globales
let sessionId = null;
const API_URL = 'http://localhost:5000/api'; // Ajusta según tu configuración
// const API_URL1 = 'https://yesno.wtf/api'; // Ajusta según tu configuración

// Función para alternar la visibilidad del chat
function toggleChat() {     
    const chatModal = document.getElementById('chatModal');
    chatModal.classList.toggle('show');
    
    if (chatModal.classList.contains('show') && !sessionId) {
        // Si es la primera vez que se abre el chat, enviar mensaje inicial
        sendMessage('Hola', true);
    }
}

// Agregar mensaje al chat
function addMessage(message, isUser = false) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
    messageDiv.textContent = message;
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Función para enviar mensajes
async function sendMessage(messageText = null, isInitial = false) {
    const input = document.getElementById('messageInput');
    const message = messageText || input.value.trim();
    
    if (!message) return;
    
    if (!isInitial) {
        addMessage(message, true);
        input.value = '';
    }

    try {
        const response = await fetch(`${API_URL}/chat`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                session_id: sessionId,
                message: message
            })
        });

        const data = await response.json();
        
        if (data.status === 'success') {
            if (data.session_id && !sessionId) {
                sessionId = data.session_id;
            }
            
            addMessage(data.response);
            
            // Si el bot está esperando un dato específico
            if (data.waiting_for) {
                document.getElementById('messageInput').placeholder = 
                    `Por favor, ingresa tu ${data.waiting_for}...`;
            } else {
                document.getElementById('messageInput').placeholder = 
                    'Escribe tu mensaje...';
            }
        } else {
            addMessage('Lo siento, hubo un error. Por favor, intenta nuevamente.');
        }
    } catch (error) {
        console.error('Error:', error);
        addMessage('Error de conexión. Por favor, verifica tu conexión a internet.');
    }
}

// Event Listener para el botón del chat
document.getElementById('chatButton').addEventListener('click', toggleChat);

// Event Listener para enviar mensaje con Enter
document.getElementById('messageInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
