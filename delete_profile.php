<?php 
// Includiamo i file neccessari per la struttura della pagina
include "includes/head.php";
include "includes/header.php"; 
include "includes/sidebar.php";  
?>

<body class="bg-light">
<div class="col-9">
    <div class="d-flex container-sm row px-0 bg-light">
        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 89vh;">
            <div class="container-sm text-center px-5 bg-light">
              
                <!-- Bottone per aprire il modal -->
                <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_profile">Delete profile</a>

                <!-- modal per ocnfermare la cancellazione -->
                <div class="modal fade" id="delete_profile" tabindex="-1" aria-labelledby="delete_comment?" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Delete profile?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-center">
                        <p>Are you sure you want to delete your profile?</p><br>
                        <p> This cannot be undone and <strong>everything</strong> will be lost.</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="includes/_delete_profile.php" type="button" class="btn btn-danger">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<?php
include "includes/footer.php"; 
?>

</html>