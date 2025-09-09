<?php
// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect fields safely (support both `name` and `fname`/`lname`)
    $fname    = isset($_POST['fname']) ? strip_tags(trim($_POST['fname'])) : '';
    $lname    = isset($_POST['lname']) ? strip_tags(trim($_POST['lname'])) : '';
    $nameRaw  = isset($_POST['name'])  ? strip_tags(trim($_POST['name']))  : '';
    $name     = trim($nameRaw !== '' ? $nameRaw : trim($fname . ' ' . $lname));
    $name     = str_replace(["\r", "\n"], [' ', ' '], $name);

    $email    = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $number   = isset($_POST['number']) ? strip_tags(trim($_POST['number'])) : '';
    $subjectR = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message  = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Basic validation (subject optional to support contact page)
    if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "দয়া করে প্রয়োজনীয় ঘরগুলো পূরণ করে পুনরায় চেষ্টা করুন।";
        exit;
    }

    // Recipient email address (update if needed)
    $recipient = "abdurrahmansoftw@gmail.com";

    // Subject line
    $subject = $subjectR !== '' ? "New contact from $subjectR" : "New contact message";

    // Build the email content.
    $email_content  = "Name: $name\n";
    if ($number !== '') {
        $email_content .= "Phone: $number\n";
    }
    if ($subjectR !== '') {
        $email_content .= "Topic: $subjectR\n";
    }
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    if (@mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo "ধন্যবাদ! আপনার বার্তা পাঠানো হয়েছে।";
    } else {
        http_response_code(500);
        echo "দুঃখিত! কিছু ভুল হয়েছে, আপনার বার্তা পাঠানো যায়নি।";
    }
} else {
    // Not a POST request
    http_response_code(403);
    echo "আপনার সাবমিশনে সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন।";
}
?>
