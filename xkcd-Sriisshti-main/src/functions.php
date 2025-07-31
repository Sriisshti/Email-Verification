function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $body = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: no-reply@example.com' . "\r\n";
    mail($email, $subject, $body, $headers);
}

function sendUnsubscribeVerificationEmail($email, $code) {
    $subject = "Confirm Un-subscription";
    $body = "<p>To confirm un-subscription, use this code: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= 'From: no-reply@example.com\r\n';
    mail($email, $subject, $body, $headers);
}

function verifyCode($email, $code) {
    return isset($_SESSION['verification_code']) && $code === $_SESSION['verification_code'];
}

function fetchAndFormatXKCDData() {
    $latestComicUrl = "https://xkcd.com/info.0.json";
    $latestComic = json_decode(file_get_contents($latestComicUrl), true);
    $max = $latestComic['num'];
    $randomId = random_int(1, $max);
    $data = json_decode(file_get_contents("https://xkcd.com/$randomId/info.0.json"), true);
    $img = $data['img'];
    return "<h2>XKCD Comic</h2><img src='$img' alt='XKCD Comic'><p><a href='unsubscribe.php' id='unsubscribe-button'>Unsubscribe</a></p>";
}

function sendXKCDUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $content = fetchAndFormatXKCDData();
    $subject = "Your XKCD Comic";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= 'From: no-reply@example.com\r\n';

    foreach ($emails as $email) {
        mail($email, $subject, $content, $headers);
    }
}
