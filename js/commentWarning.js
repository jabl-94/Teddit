document.addEventListener('DOMContentLoaded', (event) => {
    // Aggiunge l'EventListener al bottone 
    document.addEventListener('click', (event) => {
        // Verifica se l'elemento cliccato ha la classe 'log-in' 
        if(event.target.matches('#commenta')) {
            // Previene l'azione di default
            event.preventDefault();

            // Mostra il messaggio di errore
            alert('Please log in to comment on this post.');
        }
    });
});