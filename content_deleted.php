<!-- Includiamo i file neccessari per la struttura della pagina -->
<?php include "includes/head.php"; ?>
<?php include "includes/header.php"; ?>
<?php include "includes/sidebar.php"; ?>

<div class="col-10">
<body class="bg-light">
    <div class="container-sm row px-2 mx-0 bg-light" style="max-height: 100vh; overflow-y: auto;">
      <div class="col-1 ps-4 pe-0 pt-5">
            <!-- Pulsante per tornare indietro -->
            <button type="button" class="go_back btn btn-secondary" aria-label="go back" onclick="window.location.href='home.php'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg></button>
      </div>
      <!-- div per indicare che il blog e' stato cancellato con successo -->
      <div class="col-10 ps-0 pe-0 bg-light">
        <section class="container-sm px-0 bg-light">
        <div class="d-flex container-sm row px-0 bg-light">
          <div class="container-sm row pe-0">
            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 89vh;">
              <h1 class="mx-auto mt-auto">Blog deleted successfully...</h1>
              <img src="assets/trash.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2" style="max-height: 15rem; object-fit: contain;" alt="">
            </div>
          </div>
        </div>
        </section>
      </div>
    </div>

<!-- /div for col-9 -->
</div>
<!-- /div row -->
</div>
<!-- /div for container -->
</div>

</body>

<?php
  include "includes/footer.php"; 
?>

</html>