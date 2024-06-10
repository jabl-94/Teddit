<?php 
// Includiamo i file neccessari per la struttura della pagina
include "includes/head.php";
include "includes/header.php"; 
include "includes/sidebar.php";
?>


<?php

    // Verifica la provenienza dell'id_user
    if (isset($_GET['id_user'])) {
        $id_user = urldecode($_GET['id_user']);
      } elseif (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
      }
    
    $result = $conn_db->query("SELECT * FROM utente WHERE id_user = '$id_user'");

    // Controlla se la query ha avuto successo
    if ($result === false) {
        die('Errore nella query: ' . $conn_db->error);
    }

    // Inizializza le variabili con le info dell'utente
    while ($row = $result->fetch_assoc()) {
        $username = $row['username'];
        $email = $row['email'];
        $propic = $row['propic'];

    }
    $result->free();

?>
<style>
    #cancel_list {
    list-style-type: none;
    padding-left: 5px;
}
</style>


<body class="bg-light">


<div class="col-10">
    <div class="row">
    <div class="col-12">
        <div class="col-md-10 col-12 px-0">
            <div class="container-sm pb-5 pt-3 mx-0 px-md-5 px-3 bg-light">
                <button class="go_back btn btn-secondary" type="button" aria-label="go back" onclick="window.location.href='profile.php'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                </svg></button><br><br>
                <!-- Query per capire se l'utente e' gia' premium o meno -->
                <?php
                    $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_user'");
                    // Controlla se la query ha avuto successo
                    if ($result === false) {
                        die('Errore nella query: ' . $conn_db->error);
                    }
                     // Verifica se l'utente e' premium o no
                     if ($result->num_rows == 1) {

                        $row = $result->fetch_assoc();?>
                        <div>
                            <p><?php echo $row['card_holder']; ?></p>
                        </div><br>

                        <div>
                            <p>Your subscription expires on: <strong><?php echo date('d-m-Y', strtotime($row['expiry_sub']));?></strong></p>
                        </div><br>

                        <div>
                            <p><?php echo date('m-Y', strtotime($row['expiry_card']));?></p>
                        </div><br> 

                        <div>
                            <p><?php echo $row['card_number']; ?></p>
                        </div><br>

                        <div>
                            <p><?php echo $row['card_type']; ?></p>
                        </div><br>

                        <!-- Pulsante per eliminare l'iscrizione premium -->
                        <div class="text-end">
                            <!-- Bottone per mostrare il moda modal -->
                            <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancel_sub">Cancel subscription</a>
                            <!-- modal per confermare l'eliminazione-->
                            <div class="modal fade" id="cancel_sub" tabindex="-1" aria-labelledby="cancel_sub" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Cancel premium subscription?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body text-center">
                                    <p>Are you sure you want to cancel your subscription?</p><br>
                                    <p> There will be <strong>no refunds</strong> and you will <strong>lose access</strong> to:</p>
                                    <ul id="cancel_list">
                                        <li>Uploading <strong>YouTube</strong> videos.</li>
                                        <li>Uploading <strong>multiple</strong> images.</li>
                                        <li><strong>NO ADS</strong>.</li>
                                    </ul>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <a href="includes/_premium_subscription.php?id=<?php echo urlencode($id_user); ?>" type="button" class="btn btn-danger">Confirm</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div><br><?php

                    } elseif ($result->num_rows == 0) {?>  

                        <p class="h1">GET PREMIUM! ($30 a year)</p><br>

                        <!-- Form per l'iscrizione premium -->
                        <form  action="includes/_premium_subscription.php" method="POST" enctype="multipart/form-data" id="form">
                    
                            <!-- Input per card number -->
                            <div>
                                <label for="card_number">Credit Card Number:</label><br>
                                <input class="form-control" type="text" id="card_number" name="card_number" oninput="formatCreditCardNumber()" maxlength="19" placeholder="1234 5678 9012 3456"><br>
                            </div>
                    
                            <!-- Input per il nome del card holder's -->
                            <div>
                                <label for="card_holder">Card Holder's Name:</label><br>
                                <input class="form-control" type="text" id="card_holder" name="card_holder" placeholder="John Doe"><br>
                            </div>
                    
                            <!-- Input per la expiry date -->
                            <div>
                                <label for="expiry_date">Expiry Date:</label><br>
                                <input class="form-control" type="month" id="expiry_date" name="expiry_date"><br>
                            </div>
                    
                            <!-- Input per CVV -->
                            <div>
                                <label for="cvv">CVV:</label><br>
                                <input class="form-control" type="text" id="cvv" name="cvv" maxlength="3" placeholder="123"><br>
                            </div>

                            <div>
                                <label for="card_type">Card Type:</label><br>
                                <select class="form-select" name="card_type" id="card_type">
                                    <option value="" disabled selected>Select</option>
                                    <option value="Visa">Visa</option>
                                    <option value="Master">Master</option>
                                    <option value="Maestro">Maestro</option>
                                </select>
                            </div><br>

                    
                            <!-- Bottone di submit -->
                            <div class="text-end">
                                <button class="btn btn-success" type="submit">Subscribe</button><br> 
                            </div><br>
                        </form>
                    <?php
                    }
                    $result->free();
                    ?>
            </div>
        </div>
    </div>
</div>


<!-- /div for col-8 -->
</div>
</div>

<!-- /div row -->
</div>
<!-- /div for container -->
</div>

<!-- Script formattazione della carta -->
<script>
function formatCreditCardNumber() {
  var ccNum = document.getElementById('card_number').value;
  ccNum = ccNum.replace(/[^\dA-Z]/g, '').replace(/(.{4})/g, '$1 ').trim();
  document.getElementById('card_number').value = ccNum;
}
</script>

<!-- jQuery validation per il form -->
<script>
$.validator.addMethod("expiry_date", function(value, element) {
    var today = new Date();
    var expiryDate = new Date(value);

    // Controlla se la data di scadenza è nel futuro
    return expiryDate > today;
}, 'Enter a valid date');

$(document).ready(function() {
    $('#form').validate({
        rules: {
            card_number: {
                required: true,
                minlength: 19,
                maxlength: 19
            },
            card_holder: {
                required: true,
                pattern: /^[A-Z][a-z]+\s[A-Z][a-z]+(\s[A-Z][a-z]+)*\s*$/, // controlla che il nome del titolare della carta contenga almeno due parole che iniziano con una lettera maiuscola e contengano almeno due lettere
                maxlength: 100
            },
            expiry_date: {
                required: true,
                expiry_date: true
            },
            cvv: {
                required: true,
                minlength: 3,
                digits: true // controlla che il CVV sia un numero
            },
            card_type: {
                required: true
            }
        },
        messages: {
            card_number: {
                required: 'Enter your credit card number',
                minlength: 'The credit card number must be 16 digits',
                maxlength: 'The credit card number must be 16 digits'
            },
            card_holder: {
                required: 'Enter the name of the card holder',
                pattern: 'First and last name must start with a capital letter',
                maxlength: 'The name of the card holder cannot exceed 100 characters'
            },
            expiry_date: {
                required: 'Enter the expiry date of the card',
                expiry_date: 'Enter a valid date'
            },
            cvv: {
                required: 'Enter the CVV of your card',
                minlength: 'The CVV must be a 3-digit number',
                digits: 'The CVV must be a 3-digit number'
            },
            card_type: {
                required: 'Select the type of card'
            }
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