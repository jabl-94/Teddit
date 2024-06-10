
<?php 
  // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  require_once "includes/config.php";
?>
<!-- Stile per centrare il form -->
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

        <!-- Titolo del sito e del form -->
<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
        <div class="text-center">
        <img class="mb-1" src="assets/qmark.png" alt="" width="180rem" height="180rem">
        </div>
        <h1 class="h3 mb-3 fw-normal"><a href="home.php" aria-label="link to home">Teddit</a></h1>
        <h2 class="h5 mb-3 fw-normal">Confirm your identity</h2>

    <!-- Form per il reset della password -->
    <form action="includes/_recovery.php" method="POST" id="form">
    
    <!-- Campo per l'email -->
      <div class="mb-3">
        <label class="form-label" for="email">Email:</label><br>
        <input type="email" class="form-control" id="email" name="email"><br>
      </div>

    <!-- Pulsante per recuperare la domanda di sicurezza -->
    <div class="mb-3">
      <button class="btn btn-secondary w-100 py-2" id="button" aria-label="click to">Retrieve security question</button>  
    </div>

        <!-- Campo per la domanda di sicurezza -->
        <div class="mb-3">
        <fieldset disabled>
            <label class="form-label" for="security_question">Security Question:</label><br>
            <p class="form-control" id="security_question"> </p>
            <input type="hidden" id="hidden_email" name="email">
            </fieldset>
        </div>
        
        <!-- Campo per la risposta alla domanda di sicurezza -->
        <div class="mb-3">
            <label class="form-label" for="answer">Your Answer:</label><br>
            <input type="text" class="form-control" id="answer" name="answer"><br>
        </div>
        
        <!-- Pulsante per inviare il form -->
        <div class="mb-3">
        <button class="btn btn-primary w-100 py-2" type="submit" value="Submit">Check answer</button>
        </div>
    </form>
    
    <!-- Link per il login -->
    <div class="mt-3">
        <p>Just remembered your password? Click <a href="login.php">here</a> to log in</p>
    </div>

<!-- Script jQuery per la gestione del recupero della password -->
  <script>
    $(document).ready(function(){
      $("#button").click(function(){
        var email = $("#email").val();
        $("#hidden_email").val(email); // Assegna la mail all'elemento #hidden_email per mandarlo nel backend per l'insert

        // Chiamata AJAX per prendere la domanda di sicurezza
        $.ajax({
          url: "includes/_forgot.php", 
          type: "POST", 
          data: {email: email},
          dataType: "json", 
          success: function(result){
            $("#security_question").html(result); 
          },
          error: function(xhr, status, error){
            console.error("AJAX Error: " + status + "; " + error);
          }
        });
      });
    });
  </script>


<!-- jQuery validation per il form -->
  <script>
    $(document).ready(function() {
        $.validator.addMethod("customEmail", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(value);
        }, 'Enter a valid email address');

        $('form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    customEmail: true
                },
                answer: {
                    required: true
                }
            },
            messages: {
              email: {
                  required: 'Enter your email',
                  email: 'Enter a valid email address',
                  customEmail: 'Enter a valid email address'
              },
              answer: {
                  required: 'Enter your answer'
              }
          }
        });
    });
    </script>

</body>
</html>