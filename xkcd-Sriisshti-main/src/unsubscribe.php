<!-- unsubscribe.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe from XKCD Comics</title>
</head>
<body>
    <h2>Unsubscribe</h2>
    <form method="POST">
        <input type="email" name="unsubscribe_email" required placeholder="Enter your email">
        <button id="submit-unsubscribe" type="submit" name="submit-unsubscribe">Unsubscribe</button>
    </form>
    <br>
    <form method="POST">
        <input type="text" name="verification_code" maxlength="6" required placeholder="Enter verification code">
        <button id="submit-verification" type="submit" name="confirm-unsubscribe">Verify</button>
    </form>

    <?php
    session_start();
    require_once 'functions.php';

    if (isset($_POST['submit-unsubscribe'])) {
        $email = $_POST['unsubscribe_email'];
        $_SESSION['unsubscribe_email'] = $email;
        $code = generateVerificationCode();
        $_SESSION['unsubscribe_code'] = $code;
        sendUnsubscribeVerificationEmail($email, $code);
        echo "<p>Verification code sent to $email</p>";
    }

    if (isset($_POST['confirm-unsubscribe'])) {
        $email = $_SESSION['unsubscribe_email'] ?? '';
        $code = $_POST['verification_code'];
        if (verifyCode($email, $code)) {
            unsubscribeEmail($email);
            echo "<p>You have been unsubscribed.</p>";
        } else {
            echo "<p>Verification failed. Please try again.</p>";
        }
    }
    ?>
</body>
</html>
