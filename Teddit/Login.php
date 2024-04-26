<body>
<h1>Teddit</h1>
<h2>Login</h2>
<form action="conn.php" method="POST">
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="confirm_password">Conferma Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
        <button type="submit">Registrati</button>
</form>
</body>