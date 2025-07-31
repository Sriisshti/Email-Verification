<!DOCTYPE html>
<html>
<head>
    <title>XKCD Comic Subscription</title>
</head>
<body>
    <h2>Subscribe to XKCD Comics</h2>
    <form method="POST">
        <input type="email" name="email" required placeholder="Enter your email">
        <button id="submit-email" type="submit" name="submit-email">Submit</button>
    </form>
    <br>
    <form method="POST">
        <input type="text" name="verification_code" maxlength="6" required placeholder="Enter verification code">
        <button id="submit-verification" type="submit" name="verify-email">Verify</button>
    </form>

    <?php
    require_once 'functions.php';

    session_start();

    if (isset($_POST['submit-email'])) {
        $email = $_POST['email'];
        $_SESSION['pending_email'] = $email;
        $code = generateVerificationCode();
        $_SESSION['verification_code'] = $code;
        sendVerificationEmail($email, $code);
        echo "<p>Verification code sent to $email</p>";
    }

    if (isset($_POST['verify-email'])) {
        $email = $_SESSION['pending_email'] ?? '';
        $code = $_POST['verification_code'];
        if (verifyCode($email, $code)) {
            registerEmail($email);
            echo "<p>Email verified and registered!</p>";
        } else {
            echo "<p>Verification failed. Please try again.</p>";
        }
    }
    ?>
</body>
</html>

<?php
