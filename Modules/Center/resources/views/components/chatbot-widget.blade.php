<!-- Chatbot Widget -->
<div id="chatbot-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <!-- Chat Button -->
    <button id="chatbot-toggle" class="btn btn-primary rounded-circle shadow-lg" style="width: 60px; height: 60px;">
        <span style="font-size: 24px;">💬</span>
    </button>

    <!-- Chat Window -->
    <div id="chatbot-window" class="card shadow-lg" style="display: none; width: 350px; height: 500px; position: absolute; bottom: 70px; right: 0;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">مساعد AI</h6>
            <button id="chatbot-close" class="btn btn-sm btn-link text-white">&times;</button>
        </div>
        <div class="card-body d-flex flex-column" style="height: 400px;">
            <div id="chatbot-messages" class="flex-grow-1 overflow-auto mb-3" style="max-height: 350px;">
                <div class="text-center text-muted py-4">
                    <p>{{ __('center::messages.blade_0349') }}</p>
                </div>
            </div>
            <div class="input-group">
                <input type="text" id="chatbot-input" class="form-control" placeholder="{{ __('center::messages.blade_0351') }}">
                <button id="chatbot-send" class="btn btn-primary">{{ __('center::messages.blade_0350') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('chatbot-toggle');
    const window = document.getElementById('chatbot-window');
    const close = document.getElementById('chatbot-close');
    const input = document.getElementById('chatbot-input');
    const send = document.getElementById('chatbot-send');
    const messages = document.getElementById('chatbot-messages');

    // Toggle chat window
    toggle.addEventListener('click', () => {
        window.style.display = window.style.display === 'none' ? 'block' : 'none';
    });

    close.addEventListener('click', () => {
        window.style.display = 'none';
    });

    // Send message
    const sendMessage = async () => {
        const message = input.value.trim();
        if (!message) return;

        // Add user message
        addMessage(message, 'user');
        input.value = '';

        // Show typing indicator
        const typingId = addMessage('...', 'bot');

        try {
            const response = await fetch('{{ route("ai.chatbot", ["tenant" => $tenant->domain]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            
            // Remove typing indicator
            document.getElementById(typingId).remove();
            
            // Add bot response
            if (data.success) {
                addMessage(data.message, 'bot');
            } else {
                addMessage (__('center::messages.blade_0352'), 'bot');
            }
        } catch (error) {
            document.getElementById(typingId).remove();
            addMessage(__('center::messages.blade_0353'), 'bot');
        }
    };

    send.addEventListener('click', sendMessage);
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    // Add message to chat
    function addMessage(text, type) {
        const id = 'msg-' + Date.now();
        const align = type === 'user' ? 'text-end' : 'text-start';
        const bg = type === 'user' ? 'bg-primary text-white' : 'bg-light';
        
        const messageHtml = `
            <div id="${id}" class="${align} mb-2">
                <span class="d-inline-block px-3 py-2 rounded ${bg}" style="max-width: 80%;">
                    ${text}
                </span>
            </div>
        `;
        
        messages.insertAdjacentHTML('beforeend', messageHtml);
        messages.scrollTop = messages.scrollHeight;
        
        return id;
    }
});
</script>
