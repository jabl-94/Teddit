<?php 
    // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php"; 

  // Si riceve l'id del post tramite GET e s'inizializza.
  $id_p = urldecode($_GET['id_post']);

  // Query al database per ottenere le informazioni del post
  $result = $conn_db->query("SELECT * FROM post WHERE id_post = '$id_p'");
  $row = $result->fetch_assoc();

  // Assegna il risultato della query a delle variabili
  $titolo_post = $row['title_post'];
  $testo = $row['text_post'];
  $youtube_link = $row['link'];

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
            <div class="container-sm pb-5 pt-3 mx-0 px-md-5 px-3 bg-light">
                <!-- pulsante per tornare alla pagina del post -->
            <button class="go_back mb-2 btn btn-secondary" type="button" aria-label="go back" onclick="window.location.href='post.php?id_post=<?php echo urlencode($id_p)?>'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg></button>
                <h1>Edit Post</h1>
    
                <!-- Form per modificare il post-->
                <form action="includes/_edit_post.php" id="form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_post" value="<?php echo $id_p; ?>">
    
                    <label for="title_post">Title:</label><br>
                    <input class="form-control" type="text" id="title_post" name="title_post" value="<?php echo $titolo_post; ?>"><br>
    
                    <label for="text_post">Text:</label><br>
                    <textarea class="form-control" rows="7" id="text_post" name="text_post"><?php echo $testo; ?></textarea><br>
    
                    <!-- Query per mostrare tutte le immagini che gia' ci sono -->
                    <?php
                    $result = $conn_db->query("SELECT id_image, `path` FROM `image` WHERE id_pst = '$id_p' ORDER BY id_image ASC");
                    while($row = $result->fetch_assoc()){
                        // mostra ogni immagine
                        echo '<div class="post_thumbnail"> 
                              <img src="post_images/' . $row['path'] . '" class="w-25" alt="Post image">
                               <div class="btn-group" role="group" aria-label="Edit and delete">                                
                                <!-- Button to trigger modal -->
                                <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_image_'.$row['id_image'].'">Delete image</a>
                                <!-- modal ro confirm deletion -->
                                <div class="modal fade" id="delete_image_'.$row['id_image'].'" tabindex="-1" aria-labelledby="delete_image_'.$row['id_image'].'" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title">Delete blog image?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body text-center">
                                        <p>Are you sure you want to delete the blog\'s image?</p><br>
                                        <p> This <strong>cannot</strong> be undone.</p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="includes/_delete_image.php?id=' . urlencode($row['id_image']) . '&type=' . urlencode('post') . '&id_post=' . urlencode($id_p) . '" type="button" class="btn btn-danger delete-image">Delete</a>                                      
                                      </div>
                                    </div>
                                  </div>
                                </div>
                               
                                </div><br><br><br>
                              </div>';
                    } 
                    $result->free();        
                    ?>
                    <!-- Campo per l'immagine del post e link YouTube -->
                    <?php
                        $id_u = $_SESSION['id_user'];
                        // Si controlla se l'utente e' premium
                        $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");
                        // Controlla se la query ha avuto successo
                        if ($result === false) {
                            die('Errore nella query: ' . $conn_db->error);
                        }
                        // Genera campo d'input per le varie immagini e link YouTube
                        if ($result->num_rows > 0) {
                            echo '            
                        <div>
                            <label for="immagine">Immagine:</label><br>
                            <input class="form-control" type="file" id="immagine" name="immagine[]" aria-label="upload one or more images" multiple>
                            <span id="fileError" style="color:red;"></span><br>
                        </div>  
                        
                        <!-- Campo per il link di YouTube -->
                        <div>
                            <label for="youtube_link">YouTube Link:</label><br>';
                            
                            if (!empty($youtube_link)) { // Verifica se c'e' un link salvato nel DB e genera l'input con il valore di esso come default
                              echo '<input class="form-control" type="url" id="youtube_link" name="youtube_link" value="' . $youtube_link . '"><br>';?>
                                  <button class="btn btn-danger" type="button" id="delete_video">Delete Video</button><?php
                            } else {
                              echo '<input class="form-control" type="url" id="youtube_link" name="youtube_link">';
                            }
                          ?> 
                        </div>
                        <?php
                        } else {
                            echo '
                        <div>
                            <label for="immagine">Immagine:</label><br>
                            <input class="form-control" type="file" id="immagine" name="immagine[]" aria-label="upload one image">
                            <span id="fileError" style="color:red;"></span><br>
                            <div class="text-center">
                                <p class="h4 text-warning">Warning: <p class="h5"> Uploading one image will delete all previous images in the post</p></p>
                                <p class="h4 text-secondary">Get <a href="premium.php">PREMIUM</a> to be able to upload multiple images as well as a YouTube video</p>
                            </div>
                        </div>';

                        }
                        $result->free();
                        ?>
                      <br>
                      <p id="delete-message"></p>
                      <br>
                      <div class="text-end">
                        <button class="btn btn-success" type="submit" value="Submit">Confirm</button>
                      </div>
                </form>
            </div>
        </div>
    </div>
    </div>


<!-- Script per le dimensioni delle immagini -->
<script>
    $(document).ready(function(){
    $('#immagine').on('change', function(){
        var fileSize = this.files[0].size;
        var maxSize = 20 * 1024 * 1024;  // 20mb
        if(fileSize > maxSize){
            $('#fileError').text('The file is too large! The maximum file size is 20MB.');
            $(this).val(''); // Svuota il campo 
        } else {
            $('#fileError').text(''); // Elimina il messaggio d'errore se c'era
        }
    });
});
</script>

<!-- Script per l'estensione delle immagini -->
<script>
    $(document).ready(function(){
        $('#immagine').on('change', function(){
            var files = this.files;
            var validExtensions = ['jpg', 'jpeg', 'png']; // Estensioni che si possono caricare
            var isValid = true;
            var errorMessage = '';
            for(var i = 0; i < files.length; i++){
                var fileName = files[i].name;
                var fileExtension = fileName.split('.').pop().toLowerCase();
                if($.inArray(fileExtension, validExtensions) == -1){
                    isValid = false;
                    errorMessage = 'One or more files are in an unsupported format! The supported file formats are .jpg, .jpeg, .png.';
                    break;
                }
            }
            if(!isValid){
                $('#fileError').html(errorMessage);
                $(this).val('');  // Svuota il campo 
            } else {
                $('#fileError').html(''); // Elimina il messaggio d'errore se c'era
            }
        });
    });
</script>

<!-- Script per cancellare il video se c'e' -->
<script>
$(document).ready(function() {
    $('#delete_video').click(function() {
        // Setta il value dell'elemento a vuoto se viene cliccato il bottone, bisogna salvare i cambiamenti per salvarlo come vuoto nel db
        $('#youtube_link').val('');
        // Mostra il messaggio di warning
        $('#delete-message').text('Please save the changes to ensure the video is deleted.');
    });
});
</script>

<!-- jQuery validation per il form -->
<script>
    $(document).ready(function() {
        $('#form').validate({
            rules: {
                title_post: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                text_post: {
                    required: true,
                    minlength: 10,
                    maxlength: 5000
                },
                youtube_link: {
                    required: false,
                    url: true,
                    pattern: /^https?:\/\/www\.youtube\.com\/watch\?v=.*/
                }  
            },
            messages: {
                title_post: {
                    required: 'Enter the post title',
                    minlength: 'The post title must be at least 5 characters long',
                    maxlength: 'The post title cannot be longer than 50 characters'
                },
                text_post: {
                    required: 'Enter the post text',
                    minlength: 'The post text must be at least 10 characters long',
                    maxlength: 'The post text cannot be longer than 5000 characters'
                },
                youtube_link: {
                    url: 'Enter a valid link',
                    pattern: 'Enter a valid link'
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