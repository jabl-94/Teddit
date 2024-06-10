<?php 
// Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php"; 

  // Si riceve l'id del blog tramite GET e s'inizializza.
  $id_b = urldecode($_GET['id_blog']);

  // Query al database per ottenere le informazioni del blog
  $result = $conn_db->query("SELECT * FROM blog WHERE id_blog = '$id_b'");
  $row = $result->fetch_assoc();

  // Assegna il risultato della query a delle variabili
  $titolo = $row['title'];
  $descrizione = $row['description'];
  $immagine = $row['img'];

  $result->free();
?>

<style>
    label.error {
        color: red;
    }
</style>

<body class="bg-light">
<div class="col-10">

    <div class="row">
        <div class="col-12">
        <div class="col-md-10 col-12 px-0">
            <div class="container-sm text-start pb-5 pt-3 mx-0 px-md-5 px-3 bg-light">
                <div class="container-sm">

                    <!-- Bottone per tornare alla pagina del blog -->
                    <button class="go_back btn btn-secondary" type="button" aria-label="go back" onclick="window.location.href='blog.php?id_blog=<?php echo urlencode($id_b)?>'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg></button><br><br>
                    <h1>Edit Blog</h1>

                    <!-- Form per modificare il blog -->
                    <form action="includes/_edit_blog.php" id="form" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_blog" value="<?php echo $id_b; ?>">

                        <label for="title">Title:</label><br>
                        <input class="form-control" type="text" id="title" name="title" value="<?php echo $titolo; ?>"><br>

                        <label for="description">Description:</label><br>
                        <textarea class="form-control" rows="3" id="description" name="description"><?php echo $descrizione; ?></textarea><br>
                        <div>
                            <label for="immagine">Image:</label><br>
                            <input class="form-control" type="file" id="immagine" name="immagine" aria-label="upload an image for the blog (optional)">
                            <span id="fileError" style="color: red;"></span><br>
                        </div>
                        <!-- Conrolla se l'immagine non e' quella di default -->
                        <?php if ($immagine != '1.png') { ?>
                            <div class="text-start">
                                <div class="btn px-0" role="group" aria-label="Delete image">
                                    <!-- Bottone per attivare il modal -->
                                    <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_blog_image">Delete image</a>
                                    <!-- modal per confermare l'eliminazione dell'immagine -->
                                    <div class="modal fade" id="delete_blog_image" tabindex="-1" aria-labelledby="delete_blog_image" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title">Delete blog's image?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body text-center">
                                            <p>Are you sure you want to delete the blog's image?</p><br>
                                            <p> This <strong>cannot</strong> be undone.</p>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <a href="includes/_delete_image.php?id=<?php echo urlencode($id_b); ?>&type=<?php echo urlencode('blog'); ?>" type="button" class="btn btn-danger">Delete</a>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                
                                </div>
                            </div><br><br><br>
                        <?php } ?>
                        <div class="text-end">
                            <!-- Salva i cambiamenti -->
                            <button class="btn btn-success" type="submit" value="Submit"> Save changes</button> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div> 
    </div>                

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
    });
</script>

<!-- jQuery Validation per il form-->
<script>
    $(document).ready(function() {
        $('#form').validate({
            rules: {
                title: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                description: {
                    required: true,
                    minlength: 10,
                    maxlength: 150
                }
            },
            messages: {
                title: {
                    required: 'Enter the blog title',
                    minlength: 'The blog title must be at least 5 characters long',
                    maxlength: 'The blog title cannot be longer than 50 characters'
                },
                description: {
                    required: 'Enter the blog description',
                    minlength: 'The blog description must be at least 10 characters long',
                    maxlength: 'The blog description cannot be longer than 150 characters'
                }
            }
        });
    });
</script>



<!-- /div for col-9 -->
</div>
<!-- /div row -->
</div>
<!-- /div for container -->
</div>

</body>

<?php
    $conn_db->close();
    include "includes/footer.php"; 
?>

</html>