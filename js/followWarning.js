document.addEventListener('DOMContentLoaded', (event) => {
    // Aggiunge l'EventListener al document quando questo e' ready
    document.addEventListener('click', (event) => {
        // Verifica se l'elemento cliccato ha la classe .log-in
        if(event.target.matches('.log-in')) {
            // Previene l'azione del bottone
            event.preventDefault();

            // Trova lo span del messaggio di errore nel documento
            let messageSpan = event.target.parentNode.querySelector('.login-message');

            // Se il messaggio non esiste, viene creato
            if (!messageSpan) {
                messageSpan = document.createElement('span');
                messageSpan.classList.add('login-message');
                event.target.parentNode.appendChild(messageSpan);
            }

            // Mostra il messaggio
            messageSpan.textContent = 'Please log in to follow this blog.';
        }
    });
});
