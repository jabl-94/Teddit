// Ascolta il click del bottone con la classe 'follow-blog' che si trova nel body del document e inizializza le variabili
// per il badge di followers e quella del bottone per aggiornarlo
// Si ascolta il 'body' perche se il contenuto e' generato dinamicamente, i bottoni nuovi non hanno l'event tag che ascolta l'azione
$(document).ready(function(){
    $('body').on('click', '.follow-blog', function(){
        var blogid = $(this).data('blogid'); // Viene creata la variabile 'blogid' che contiene l'idi del blog da seguire
        var button = $(this); // fa riferimento al bottone cliccato
        var followersBadge = $("#" + blogid); // fa riferimento al badge da modificare
        $.ajax({
            url: 'includes/_follow.php',
            type: 'post',
            data: {
                'blogid': blogid
            },
            success: function(response){
                // Aggiorna il testo e la classe del bottone 
                if(button.text() == "Follow blog") {
                    button.text("Following");
                    button.removeClass("btn-primary"); // Rimuove la classe precedente
                    button.addClass("btn-success"); // Aggiunge l'altra classe

                    // Incrementa il numero di followers di 1
                    var num_followers = parseInt(followersBadge.text());
                    followersBadge.text(num_followers + 1 + " followers");
                } else {
                    button.text("Follow blog");
                    button.removeClass("btn-success"); // Rimuove la classe precedente
                    button.addClass("btn-primary"); // Aggiunge l'altra classe

                    // Sotrae 1 dal numero di followers
                    var num_followers = parseInt(followersBadge.text());
                    followersBadge.text(num_followers - 1 + " followers");
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            // Mostra un errore tramite alert se non si e' loggati
            alert("Please log in to be able to follow blogs");
        });
    });
});