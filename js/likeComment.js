// La funziona ascolta tutto il body una volta il document e' ready
// Ascolta il click sul bottone con la classe like-comment e inizializza le variabili per i bottone e i badge e il numero di likes da aggiornare
// Si ascolta il 'body' perche se il contenuto e' generato dinamicamente, i bottoni nuovi non hanno l'event tag che ascolta l'azione
$(document).ready(function(){
    $('body').on('click', '.like-comment', function(){
        var commentid = $(this).data('commentid'); 
        var button = $(this);
        var likesBadge = $('.likeCommentBadge[data-commentid="' + commentid + '"] .like-count'); 
        var badge = $('.likeCommentBadge[data-commentid="' + commentid + '"]'); 
        var currentLikes = parseInt(likesBadge.text());
        
        // Fa la chiamata AJAX alla pagina backend perfare l'insert o il delete degli upvote 
        $.ajax({
            url: 'includes/_like_comment.php', 
            type: 'post',
            data: {
                'commentid': commentid
            },
            success: function(response){
                // Se il bottone ha scritto 'upvote' allora lo cambia a 'upvoted', cambia le clasi del bottone e del badge 
                // e aumenta anche di 1 il numero di upvotes
                if(button.text() == "upvote") {
                    button.text("upvoted");
                    button.removeClass("btn-primary");
                    button.addClass("btn-warning");

                    likesBadge.text(currentLikes + 1);

                    badge.removeClass("bg-info-subtle border-info-subtle text-info-emphasis");
                    badge.addClass("text-bg-warning");

                } else {
                    // Se si clicca di nuovo, si rimuove l'upvote nel backend, e vengono cambiati il testo e le classi del bottone,
                    // le classi del badge e sotrae 1 dal numero di upvote
                    button.text("upvote");
                    button.removeClass("btn-warning");
                    button.addClass("btn-primary");

                    likesBadge.text(currentLikes - 1);

                    badge.removeClass("text-bg-warning");
                    badge.addClass("bg-info-subtle border-info-subtle text-info-emphasis");
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            alert("Please log in to be able to upvote comments");
        });
    });
});