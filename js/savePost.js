// Si ascola l'evento click del pulsante con l'id save-post e inizializza le variabili con 
// l'id del post da salvare e il bottone che e' stato cliccato
// Si ascolta il 'body' perche se il contenuto e' generato dinamicamente, i bottoni nuovi non hanno l'event tag che ascolta l'azione
$(document).ready(function(){
    $('body').on('click', '#save-post', function(){
        var postid = $(this).data('postid');
        var button = $(this); // fa riferimento al bottone cliccato
        // Chiamata AJAX alla pagina backend per la gestione del save
        $.ajax({
            url: 'includes/_save.php',
            type: 'post',
            data: {
                'postid': postid
            },
            success: function(response){
                // A seconda della risposta:
                // Se il testo del bottone e'save post' allora cambia le classi per indicare che e' stato salvato
                if(button.text() == "Save post") {
                    button.text("Saved");
                    button.removeClass("btn-primary"); // rimuove la classe vecchia
                    button.addClass("btn-success"); // aggiunge la classe nuova
                } else {
                    button.text("Save post");
                    button.removeClass("btn-success"); // rimuove la classe vecchia
                    button.addClass("btn-primary"); // aggiunge la classe nuova
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            // Gestisce l'errore tramite alert se l'utente clicca ma non e' loggato
            alert("Please log in to be able to save posts");
        });
    });
});