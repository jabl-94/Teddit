
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

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">

        <h2 class="h5 mb-3 fw-normal">Reset your password</address></h2>
        <!-- Form per il reset della password -->
        <form action='includes/_reset.php' method="POST" id="form">
            <!-- Campo per la nuova password -->
          <div class="mb-3">
                <label for="new_password">New password:</label><br>
                <input type="password" class="form-control" id="new_password" name="new_password"><br>
            </div>
            
            <!-- Campo per la conferma della nuova password -->
          <div class="mb-3">
                <label for="new_password_confirm">Confirm password:</label><br>
                <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm"><br>
            </div>
            
            <!-- Pulsante per inviare il form -->
            <div class="mb-3">
                <button class="btn btn-primary w-100 py-2" type="submit" value="Submit">Reset</button><br>        
            </div>
            
            <!-- Link per il login -->
          <div class="mb-3">
                <p>Just remembered your password? Click <a href="login.php">here</a> to log in</p>
            </div>
        </form>
    </main>

  <!-- jQuery Validation per il form -->
  <script>
    $(document).ready(function() {
        $.validator.addMethod("passwordCheck",
            function(value, element) {
                return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/.test(value);
            },
            'The password must be 8-16 characters long, and must include at least one uppercase letter and a number'
        );
        $('#form').validate({
            rules: {
                new_password: {
                    required: true,
                    passwordCheck: true
                },
                new_password_confirm: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            messages: {
              new_password: {
                  required: 'Enter the password',
                  passwordCheck: 'The password must be 8-16 characters long, and must include at least one uppercase letter and a number'
              },
              new_password_confirm: {
                  required: 'Confirm the password',
                  equalTo: 'The passwords do not match'
              }
          }
        });
    });
  </script>

</body>
</html>