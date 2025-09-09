<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $subject = trim($_POST["subject"]);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR empty($subject) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "দয়া করে ফর্মটি সম্পূর্ণ করে পুনরায় চেষ্টা করুন।";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $recipient = "abdurrahmansoftw@gmail.com";

        // Set the email subject.
        $subject = "New contact from $subject";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Subject: $subject\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";

        // Build the email headers.
        $email_headers = "From: $name <$email>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "ধন্যবাদ! আপনার বার্তা পাঠানো হয়েছে।";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "দুঃখিত! কিছু ভুল হয়েছে, আপনার বার্তা পাঠানো যায়নি।";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
    echo "আপনার সাবমিশনে সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন।";
    }

?>
