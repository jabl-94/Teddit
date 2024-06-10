    <?php 
    // Includiamo i file neccessari per la struttura della pagina
    include "includes/head.php";
    include "includes/header.php"; 
    include "includes/sidebar.php";
    
    // Controlla l'id del blog
    $id_b = $_GET['id_blog'];
    ?> 
    
    <body class="bg-light">
    <div class="col-10 bg-light">
    <style>
        label.error {
            color: red;
        }
    </style>

        
    <div class="row">
        <div class="col-md-10 col-12 px-md-5 px-2 bg-light">
            <div class="container-sm text-start pb-5 pt-2 mx-0 px-md-5 px-3 bg-light">
            <button class="go_back btn btn-secondary" aria-label="Go back" type="button" onclick="window.location.href='blog.php?id_blog=<?php echo urlencode($id_b)?>'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg></button><br><br>
                <!-- Form per la creazione di un post -->
                <form action="includes/_create_post.php" method="POST" enctype="multipart/form-data" id="form">
                    <div class="blog_icon">
                        <h2 for="blog">
                        <?php
                        $result = $conn_db->query("SELECT title FROM blog WHERE id_blog = '$id_b'");
                        // Controlla se la query ha avuto successo
                        if ($result === false) {
                            die('Errore nella query: ' . $conn_db->error);
                        }
                        // Genera il titolo del post
                        while ($row = $result->fetch_assoc()) {
                            echo '<p class="h3" value="' . $row['title'] . '">' . $row['title'] . '</p>
                            <p class="h4 mt-3">Create a post:</p>';
                        }
                        $result->free();
                        ?>
                        </h2><br>
                    
                    </div>
                    <input class="form-control" type="hidden" value= "<?php echo $id_b ?>" id="hidden" name="id_blog">

                    <!-- Campo per il titolo del post -->
                    <div>
                        <label for="titolo">Title:</label><br>
                        <input class="form-control" id="titolo" name="titolo" required><br>
                    </div>

                    <!-- Campo per il testo del post -->
                    <div>
                        <label for="testo">Text:</label><br>
                        <textarea class="form-control" rows="7" id="testo" name="testo" required></textarea>
                    </div>
                    <br>

                    <!-- Campo per l'immagine del post e link YouTube -->
                    <?php
                        $id_u = $_SESSION['id_user'];
                        
                        // Verifica se l'utente e' premium o meno per generare i campi input per caricare piu' immagini e video YouTube
                        // se e' premium o solo una se non lo e'
                        $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");
                        // Controlla se la query ha avuto successo
                        if ($result === false) {
                            die('Errore nella query: ' . $conn_db->error);
                        }
                        if ($result->num_rows > 0) {
                            echo '            
                        <div>
                            <label for="immagine">Image:</label><br>
                            <input class="form-control" type="file" id="immagine" name="immagine[]" aria-label="upload one or more images" multiple>
                            <span id="fileError" style="color:red;"></span><br>
                        </div>
                        
                        <!-- Campo per il link di YouTube -->
                        <div>
                            <label for="youtube_link">YouTube Link:</label><br>
                            <input class="form-control" type="url" id="youtube_link" name="youtube_link" aria-label="paste YouTube link"><br>
                        </div>';
                        } else {
                            echo '
                        <div>
                            <label for="immagine">Image:</label><br>
                            <input class="form-control" type="file" id="immagine" name="immagine[]" aria-label="upload one image">
                            <span id="fileError" style="color:red;"></span><br>
                            <div class="text-center">
                                <p class="h4 text-secondary">Get <a href="premium.php">PREMIUM</a> to be able to upload multiple images as well as a YouTube video</p>
                            </div>
                        </div>';

                        }
                        $result->free();
                        ?>

                    <!-- Pulsante per la creazione del post -->
                    <div class="text-end">
                    <button class="btn btn-success py-2" type="submit">Create</button> 
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- jQuery Validation per la form -->
        <script>
            $(document).ready(function() {
                $('#form').validate({
                    rules: {
                        titolo: {
                            required: true,
                            minlength: 5,
                            maxlength: 50
                        },
                        testo: {
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
                        titolo: {
                            required: 'Please enter the post title',
                            minlength: 'The post title must be at least 5 characters long',
                            maxlength: 'The post title cannot be longer than 50 characters'
                        },
                        testo: {
                            required: 'Please enter the post text',
                            minlength: 'The post text must be at least 10 characters long',
                            maxlength: 'The post text cannot be longer than 5000 characters'
                        },
                        youtube_link: {
                            url: 'Please enter a valid link',
                            pattern: 'Please enter a valid link'
                        }
                    }                    
                });
            });
        </script>

        <!-- jQuery per il controllo delle immagini -->
        <script>
            $(document).ready(function(){
                $('#immagine').on('change', function(){
                    var files = this.files;
                    var validExtensions = ['jpg', 'jpeg', 'png']; // estensioni dei file permessi
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
                        $(this).val(''); // Svuota il campi input se il file non e' valido
                    } else {
                        $('#fileError').html(''); // Cancella il messaggio di errore se il file e' valido
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