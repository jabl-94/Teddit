<?php 
  // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php"; 
?>

<body class="bg-light">
<div class="col-10">
    <style>
        label.error {
            color: red;
        }
    </style>
    
    <div class="col-md-10 col-12">
        <div class="container-sm text-start px-md-5 px-3 mx-1 py-5 bg-light">
            <h2>Create a blog</h2>
            
            <!-- Form per creare il blog -->
            <form action="includes/_createblog.php" method="POST" enctype="multipart/form-data" id="form">
                <div>
                    <label for="titolo">Title:</label><br>
                    <input class="form-control" type="text" id="titolo" name="titolo" aria-label="enter the tittle of the blog">
                </div>
                <br>
                <div >
                    <select class="form-select" id="categoria" name="categoria" aria-label="choose category">
                        <option disabled selected value>Category:</option>
                        <?php
                        // Query per ottenere le categorie principali
                        $result = $conn_db->query("SELECT category.name FROM category WHERE id_parent IS NULL");

                        // Controlla se la query ha avuto successo
                        if ($result === false) {
                            die('Errore nella query: ' . $conn_db->error);
                        }

                        // Itera sui risultati e crea un'opzione per ciascuna categoria madre
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                        }
                        $result->free();
                        ?>
                    </select><br>
                </div>
                <br>
                <div id="sottocategorie_container" style="display:none;">
                    <select class="form-select" id="sottocategoria" name="sottocategoria" aria-label="choose Subcategory (optional)">
                    </select>
                </div>
                <br>
                <div>
                    <label for="descrizione">Description:</label><br>
                    <textarea class="form-control" rows="3" id="descrizione" name="descrizione" aria-label="enter the blog's description"></textarea>
                </div>
                <br>
                <div>
                    <label for="immagine">Image:</label><br>
                    <input class="form-control" type="file" id="immagine" name="immagine" aria-label="upload an image for the blog (optional)">
                    <span id="fileError" style="color: red;"></span><br>
                </div>
                <br>
                <div class="text-end">
                    <button class="btn btn-success py-2" type="submit">Create</button> 
                </div>
            </form>
        </div>
    </div>


<!-- Script per mostrare le sottocategorie della categoria madre selezionata -->
<script>
$(document).ready(function(){
$("#categoria").change(function(){
    var categoria = $(this).val();
    $.ajax({
        url: 'includes/_getsubcategories.php',
        type: "GET",
        data: {categoria: categoria},
        dataType: "json",
        success: function(result) {
            if (result.hasSubcategories) {
                $("#sottocategoria").empty();
                $("#sottocategoria").append('<option value="" disabled selected>Subcategory</option>'); 
                $("#sottocategoria").append('<option value="Nessuna">None</option>');
                $.each(result.subcategories, function(key, value) {
                    $("#sottocategoria").append('<option value="' + value + '">' + value + '</option>');
                });
                // Mostra il menu delle sottocategorie
                $("#sottocategorie_container").show();
            } else {
                // Se non ci sono sottocategorie, nasconde il menu delle sottocategorie e rimuove le opzioni
                $("#sottocategoria").empty();
                // Aggiungi l'opzione predefinita "Sottocategoria" non selezionabile
                $("#sottocategoria").append('<option value="" disabled selected>Subcategory</option>');
                $("#sottocategorie_container").hide();
            }
        },
        error: function(xhr, status, error){
            console.error("AJAX Error: " + status + "; " + error);
        }
    });
});
});
</script>

<!-- Script per verificare le dimensioni dell'immagine caricata -->
<script>
    $(document).ready(function(){
        $('#immagine').on('change', function(){
            var fileSize = this.files[0].size;
            var maxSize = 20 * 1024 * 1024;
            if(fileSize > maxSize){
                $('#fileError').html('The file is too large! The maximum file size is 20MB.');
                $(this).val(''); // Svuota il campo
            } else {
                $('#fileError').html(''); // Cancella il messaggio di errore se l'immagine e' valida
            }
        });
    });
</script>

<!-- Script per verificare l'estensione dell'immagine caricata -->
<script>
    $(document).ready(function(){
        $('#immagine').on('change', function(){
            var fileName = this.files[0].name;
            var fileExtension = fileName.split('.').pop().toLowerCase();
            var validExtensions = ['jpg', 'jpeg', 'png']; // Estensioni dei file valide

            if($.inArray(fileExtension, validExtensions) == -1){
                $('#fileError').html('File format not allowed! The allowed file formats are .jpg, .jpeg, .png.');
                $(this).val(''); // Svuota il campo
            } else {
                $('#fileError').html(''); // Cancella il messaggio di errore se l'immagine e' valida
            }
        });
    });123123
</script>

<!-- jQuery Validation per il form-->
<script>
    $(document).ready(function() {
        $('#form').validate({
            rules: {
                titolo: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                categoria: {
                    required: true 
                },
                descrizione: {
                    required: true,
                    minlength: 10,
                    maxlength: 150
                }
            },
            messages: {
                titolo: {
                    required: 'Please enter the blog title',
                    minlength: 'The blog title must be at least 5 characters long',
                    maxlength: 'The blog title cannot be longer than 50 characters'
                },
                categoria: {
                    required: 'Select the blog category'
                },
                descrizione: {
                    required: 'Please enter the blog description',
                    minlength: 'The blog description must be at least 10 characters long',
                    maxlength: 'The blog description cannot be longer than 150 characters'
                }
            }

        });
    });
</script>

<!-- /div for col-9 -->
</div>
<!-- /div for container -->
</div>

</body>


<?php
    $conn_db->close();
    include "includes/footer.php";
?>
</html>