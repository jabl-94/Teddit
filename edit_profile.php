<?php 
// Includiamo i file neccessari per la struttura della pagina
include "includes/head.php";
include "includes/header.php"; 
include "includes/sidebar.php"; 
?>

<body class="bg-light">

<?php

    $id_user = urldecode($_GET['id_user']);
    
    $result = $conn_db->query("SELECT * FROM utente WHERE id_user = '$id_user'");

    // Controlla se la query ha avuto successo
    if ($result === false) {
        die('Errore nella query: ' . $conn_db->error);
    }

    // Itera sui risultati e crea un'ancora per ciascun post
    while ($row = $result->fetch_assoc()) {
        $username = $row['username'];
        $email = $row['email'];
        $propic = $row['propic'];

    }
    $result->free();

?>

<div class="col-10">
    <div class="row">
    <div class="col-12">
        <div class="col-md-10 col-12 px-0">
            <div class="container-sm pb-5 pt-3 mx-md-5 mx-0 px-md-5 px-3 bg-light">
            <!-- pulsante per tornare alla pagina del profilo -->
            <button class="go_back btn btn-secondary" type="button" aria-label="go back" onclick="window.location.href='profile.php'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg></button>
                <p class="h2 mt-md-2 mt-4 ms-md-5 ms-3"><?php echo $username; ?></p>

                <div class="text-center">
                    <img style="width: 10rem; clip-path: circle(40%);" src="propics/<?php echo $propic; ?>" alt="<?php echo $username; ?>">
                </div><br>

                <!-- Form per modificare il profilo-->
                <form action='includes/_edit_profile.php' method="POST" enctype="multipart/form-data" id="form">

                    <div>
                        <label for="immagine">Change profile picture:</label>
                        <input class="form-control" type="file" id="immagine" name="immagine">
                        <span id="fileError" style="color: red;"></span><br>
                    </div>

                    <!-- Campo per cambiare l'username -->
                    <div>
                        <label for="username">Username:</label><br>
                        <input class="form-control" type="username" id="username" name="username" placeholder="<?php echo $username; ?>">
                    </div><br>

                    <!-- Campo per cambiare l'email -->
                    <div>
                        <label for="email">Email:</label><br>
                        <input class="form-control" type="email" id="email" name="email" placeholder="<?php echo $email; ?>">
                    </div><br>

                    <!-- Campo per impostare o modificare la bio -->
                    <div>
                        <label for="bio">Bio:</label><br>
                        <textarea class="form-control" rows="3"type="text" id="bio" name="bio" aria-label="add of change your bio"></textarea>
                    </div><br>

                    <!-- Campo per cambiare la password -->
                    <div>
                        <label for="password">Password:</label><br>
                        <input class="form-control" type="password" id="password" name="password">
                    </div><br>

                    <!-- Campo per cambiare la conferma della password -->
                    <div>
                        <label for="confirm_password">Confirm Password:</label><br>
                        <input class="form-control" type="password" id="confirm_password" name="confirm_password">
                    </div><br>

                    <!-- Pulsante per inviare il form -->
                    <div class="text-end">
                    <button class="btn btn-success" type="submit">Save Changes</button><br> 
                    </div><br>
                </form>
            </div>
        </div>
    </div>

<!-- /div for col-8 -->
</div>
</div>

<!-- jQuery validation per il form -->
<script>
    $(document).ready(function() {
        $.validator.addMethod("passwordCheck",
            function(value, element) {
                return this.optional(element) || /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/.test(value);
            },
        );

        $.validator.addMethod("customEmail", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(value);
        },);

        $('#form').validate({
            rules: {
                username: {
                    required: false,
                    minlength: 3,
                    maxlength: 20,
                    alphanumeric: true,
                    remote: {
                      url: "includes/_check_username.php",
                      type: "post",
                      data: {
                        username: function() {
                          return $("#username").val();
                        }
                      }
                    }
                },
                email: {
                    required: false,
                    email: true,
                    maxlength: 320,
                    customEmail: true,
                    remote: {
                      url: "includes/_check_email.php",
                      type: "post",
                      data: {
                        email: function() {
                          return $("#email").val();
                        }
                      }
                    }
                },
                bio: {
                    required: false,
                    maxlength: 200
                },
                password: {
                    required: false,
                    passwordCheck: true
                },
                confirm_password: {
                    required: false,
                    equalTo: "#password"
                }
            },
            messages: {
                username: {
                    minlength: 'Your username must be at least 3 characters long',
                    maxlength: 'Your username cannot be longer than 20 characters',
                    alphanumeric: 'The username can only contain letters and numbers',
                    remote: 'Username already in use'
                },
                email: {
                    email: 'Enter a valid email address',
                    maxlength: 'Your email cannot be longer than 320 characters',
                    customEmail: 'Enter a valid email address.',
                    remote: 'Email already in use'
                },
                bio: {
                    maxlength: 'Your bio cannot be longer than 200 characters'
                },
                password: {
                    passwordCheck: 'The password must be 8-16 characters long (no special characters) and must include at least one uppercase letter and a number'
                },
                confirm_password: {
                    equalTo: 'The passwords do not match'
                }
            }
        });
    });
</script>

<!-- Script per la dimensione dell'immagini -->
<script>
    $(document).ready(function(){
        $('#immagine').on('change', function(){
            var fileSize = this.files[0].size;
            var maxSize = 20 * 1024 * 1024;
            if(fileSize > maxSize){
                $('#fileError').text('The file is too large! The maximum file size is 20MB.');
                $(this).val('');  // Svuota il campo 
            } else {
                $('#fileError').html(''); // Elimina il messaggio d'errore se c'era
            }
        });
    });
</script>

<!-- Script per l'estensione delle immagini -->
<script>
    $(document).ready(function(){
        $('#immagine').on('change', function(){
            var fileName = this.files[0].name;
            var fileExtension = fileName.split('.').pop().toLowerCase();
            var validExtensions = ['jpg', 'jpeg', 'png'];  // Estensioni che si possono caricare

            if($.inArray(fileExtension, validExtensions) == -1){
                $('#fileError').html('File format not allowed! The allowed file formats are .jpg, .jpeg, .png.');
                $(this).val(''); // Svuota il campo 
            } else {
                $('#fileError').html(''); // Elimina il messaggio d'errore se c'era
            }
        });
    });
</script>

</body>

<?php
    $conn_db->close();
    include "includes/footer.php"; 
?>

</html>