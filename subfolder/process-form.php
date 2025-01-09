<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = htmlspecialchars(strip_tags(trim($_POST['frmname'])));
    $course = htmlspecialchars(strip_tags(trim($_POST['frmcourse'])));
    $mobile = htmlspecialchars(strip_tags(trim($_POST['frmmobile'])));
    $comment = htmlspecialchars(strip_tags(trim($_POST['frmcomment'])));
    $userEmail = isset($_POST['frmemail']) ? htmlspecialchars(strip_tags(trim($_POST['frmemail']))) : '';

    // Apps Script URL
    $url = 'https://script.google.com/macros/s/AKfycbz6ful19kdHE449I-mJIEEQb1-KHaMNJbzAKSN307uVxGHBnv043Uyl_g1ikgjwt621/exec';

    $data = [
        'name' => $name,
        'course' => $course,
        'mobile' => $mobile,
        'comment' => $comment,
        'userEmail' => $userEmail
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10, // Set timeout for the request
        ]
    ];

    // Create a stream context
    $context = stream_context_create($options);

    try {
        $result = @file_get_contents($url, false, $context);

        if ($result === FALSE) {
            // Handle failure (e.g., no response from the server)
            echo "<script>alert('Submission failed. Please try again.'); window.location.href = 'form.html';</script>";
        } else {
            // Assuming your Apps Script responds with "Success" on success
            if (strpos($result, 'Success') !== false) {
                echo "<script>alert('Thank you for your submission!'); window.location.href = 'thankyou.html';</script>";
            } else {
                echo "<script>alert('Error: $result'); window.location.href = 'form.html';</script>";
            }
        }
    } catch (Exception $e) {
        echo "<script>alert('An unexpected error occurred: {$e->getMessage()}'); window.location.href = 'form.html';</script>";
    }
}
?>
