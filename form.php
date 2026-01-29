<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data and sanitize inputs
    $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
    $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
    $message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        
        // Email details
        $to = "info@shaax.com"; // Change this to your actual email
        $subject = "New Contact Form Submission from SHAAX Website";
        
        // Email headers
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        // Email body
        $emailBody = "
        <html>
        <head>
            <title>New Contact Form Submission</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4a7c59; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #4a7c59; }
                .value { margin-left: 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Contact Form Submission</h2>
                    <p>SHAAX Skincare Website</p>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='label'>Name:</span>
                        <span class='value'>$name</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span>
                        <span class='value'>$email</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Message:</span>
                        <div class='value' style='margin-top: 10px;'>$message</div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Send email
        $mailSent = mail($to, $subject, $emailBody, $headers);
        
        if ($mailSent) {
            // Redirect with success message
            header("Location: index.html?success=1#contact");
            exit();
        } else {
            // Redirect with error message
            header("Location: index.html?error=1#contact");
            exit();
        }
        
    } else {
        // Redirect with validation errors
        $errorString = urlencode(implode(", ", $errors));
        header("Location: index.html?errors=$errorString#contact");
        exit();
    }
    
} else {
    // If not POST request, redirect to home page
    header("Location: index.html");
    exit();
}
?>

