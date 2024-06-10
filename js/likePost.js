// Si ascolta il click del bottone con la classe like-post e poi inizializza le variabili
// postid con l'id del post da upvotare, il bottone da modificare, il badge e il suo testo da modificare per mostrare che e' stato upvotato
// Si ascolta il 'body' perche se il contenuto e' generato dinamicamente, i bottoni nuovi non hanno l'event tag che ascolta l'azione
$(document).ready(function(){
    $('body').on('click', '.like-post', function(){
        var postid = $(this).data('postid');
        var button = $(this); // fa riferimento al bottone dell'upvote
        var likesBadge = $('.like-count-badge[data-postid="' + postid + '"] .like-count'); // fa riferimento allo span dove c'e' il testo di quanti upvote ci sono
        var badge = $('.like-count-badge[data-postid="' + postid + '"]'); // fa riferimento al badge da aggiornare
        var currentLikes = parseInt(likesBadge.text()); // il numero di upvote che sono attualmente mostrati nello schermo

        // Chiamata AJAX per gestire l'upvote del post
        $.ajax({
            url: 'includes/_like_post.php',
            type: 'post',
            data: {
                'postid': postid
            },
            success: function(response){
                // Aggiorna il bottone se il testo era 'upvote' a 'upvoted'
                if(button.text() == "upvote") {
                    button.text("upvoted");
                    button.removeClass("btn-primary"); // rimuove la classe prescedente
                    button.addClass("btn-warning"); // agginge la nuova classe

                    // Aumenta il numero di upvote nel badge di 1
                    likesBadge.text(currentLikes + 1);

                    // Toglie le classi di upvote e aggiunge quelle di upvoted
                    badge.removeClass("bg-info-subtle border-info-subtle text-info-emphasis");
                    badge.addClass("text-bg-warning");

                } else {
                    button.text("upvote");
                    button.removeClass("btn-warning"); // rimuove la classe prescedente
                    button.addClass("btn-primary"); // agginge la nuova classe

                    // Sotrae 1 dal numero di upvote nel badge
                    likesBadge.text(currentLikes - 1);

                    // Toglie le classi di upvoted e aggiunge quelle di upvote
                    badge.removeClass("text-bg-warning");
                    badge.addClass("bg-info-subtle border-info-subtle text-info-emphasis");
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            // Gestisce l'errore tramite alert se l'utente non e' loggato
            alert("Please log in to be able to upvote posts");
        });
    });
});
