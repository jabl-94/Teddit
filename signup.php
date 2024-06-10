<?php 
  // Includiamo i file neccessari per la struttura della pagina 
  include "includes/head.php";
  // Inclusione del file per la connessione al database
  include "includes/Connessione_database.php"; 
  require_once "includes/config.php";
?>

<!-- media query per centrare il form -->
<style>
      html,
      body {
        height: 100%;
      }
      
      .form-signin {
        max-width: 330px;
        padding: 1rem;
      }
      
      .form-signin .form-floating:focus-within {
        z-index: 2;
      }
      
      .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }
      
      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
      
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }

      .bd-mode-toggle {
        z-index: 1500;
      }

      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }
</style>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
        <!-- Titolo del sito e del form -->
        <div class="text-center">
        <a href="home.php"><img class="mb-1" aria-label="link to home" src="assets/Teddit.png" alt="teddit logo" width="100" height="100"></a>
        </div>

        <h1 class="h3 mb-3 fw-normal"><a href="home.php">Teddit</a></h1>
        <h2 class="h5 mb-3 fw-normal">Sign in</h2>
    
    <!-- Form per la registrazione di un nuovo utente -->
    <form action='includes/_signup.php' method="POST" id="form">
        <!-- Campo per l'username -->
        <div class="mb-3">
            <label class="form-label" for="username">Username:</label>
            <input type="username" class="form-control" id="username" name="username">
        </div>
        
        <!-- Campo per l'email -->
        <div class="mb-3">
            <label class="form-label" for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        
        <!-- Campo per la password -->
        <div class="mb-3">
            <label class="form-label" for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        
        <!-- Campo per la conferma della password -->
        <div class="mb-3">
            <label class="form-label" for="confirm_password">Conferma Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>
        
        <!-- Campo per la domanda di sicurezza -->
        <div class="mb-3">
            <label class="form-label" for="security_question">Security question:</label>
            <select class="form-select" id="security_question" name="security_question">
                <option disabled selected value>Seleziona la domanda di sicurezza:</option>
                <?php
                // Query per ottenere le domande di sicurezza
                $result = $conn_db->query("SELECT question FROM security_question");
                // Controlla se la query ha avuto successo
                if ($result === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }
                // Crea un'opzione per ciascuna domanda di sicurezza
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['question'] . '">' . $row['question'] . '</option>';
                }
                $result->free();
                ?>
            </select>
        </div>
        
        <!-- Campo per la risposta alla domanda di sicurezza -->
        <div class="mb-3">
            <label class="form-label" for="security_answer">Security answer:</label>
            <input type="password" class="form-control" id="security_answer" name="security_answer">
        </div>
        
        <!-- Pulsante per inviare il form -->
        <div>
            <button class="btn btn-primary w-100 py-2" type="submit">Sign Up</button>
        </div>
    </form><br>
    
    <!-- Link per il login -->
    <div>
        <p>Already have an account? Click <a href="login.php">here</a> to log in</p>
    </div>
    </main>
  
    <!-- jQuery Validation per il form -->
  <script>
    $(document).ready(function() {
        $.validator.addMethod("passwordCheck",
            function(value, element) {
                return this.optional(element) || /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/.test(value);
            },
            'The password must be 8-16 characters long (no special characters) and must include at least one uppercase letter and a number'
        );

        $.validator.addMethod("customEmail", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(value);
        }, 'Enter a valid email address');

        $('#form').validate({
            rules: {
                username: {
                    required: true,
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
                    required: true,
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
                password: {
                    required: true,
                    passwordCheck: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                },
                security_question: {
                    required: true
                },
                security_answer: {
                    required: true,
                    maxlength: 50
                }
            },
            messages: {
              username: {
                  required: 'Enter a username',
                  minlength: 'The username must be at least 3 characters long',
                  maxlength: 'The username cannot be longer than 20 characters',
                  alphanumeric: 'The username can only contain letters and numbers',
                  remote: 'Username already in use'
              },
              email: {
                  required: 'Enter your email',
                  email: 'Enter a valid email address',
                  maxlength: 'Your email cannot be longer than 320 characters',
                  customEmail: 'Enter a valid email address',
                  remote: 'Email already in use'
              },
              password: {
                  required: 'Enter a password',
                  passwordCheck: 'The password must be 8-16 characters long (no special characters) and must include at least one uppercase letter and a number'
              },
              confirm_password: {
                  required: 'Confirm the password',
                  equalTo: 'The passwords do not match'
              },
              security_question: {
                  required: 'Select the security question'
              },
              security_answer: {
                  required: 'Enter your answer',
                  maxlength: 'The answer to the security question cannot be longer than 50 characters'
              }
          }
        });
    });
  </script>

</body>

<?php
$conn_db->close();
?>

</html>