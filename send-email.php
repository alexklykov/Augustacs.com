<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name    = htmlspecialchars(trim($_POST['name'] ?? ''));
$email   = htmlspecialchars(trim($_POST['email'] ?? ''));
$subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$to      = 'alex@augustacs.com';
$headers = implode("\r\n", [
    'From: Augusta CS Contact Form <alex@augustacs.com>',
    'Reply-To: ' . $email,
    'Content-Type: text/html; charset=UTF-8',
    'MIME-Version: 1.0',
]);

$body = '<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f8faf5;font-family:Inter,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f8faf5;padding:40px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

      <!-- Header -->
      <tr>
        <td style="background:#001e16;padding:32px 40px;border-radius:8px 8px 0 0;">
          <p style="margin:0;font-family:Manrope,Arial,sans-serif;font-size:22px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">Augusta CS</p>
          <p style="margin:6px 0 0;font-size:12px;color:#a7cfc0;text-transform:uppercase;letter-spacing:2px;">New Inquiry</p>
        </td>
      </tr>

      <!-- Body -->
      <tr>
        <td style="background:#ffffff;padding:40px;border-left:1px solid #e1e3de;border-right:1px solid #e1e3de;">
          <p style="margin:0 0 24px;font-size:16px;color:#191c1a;line-height:1.5;">You have a new inquiry from the contact form.</p>

          <!-- Field: Name -->
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
            <tr>
              <td style="background:#f2f4ef;padding:16px 20px;border-radius:6px;">
                <p style="margin:0 0 4px;font-size:10px;font-weight:700;color:#414845;text-transform:uppercase;letter-spacing:1.5px;">Full Name</p>
                <p style="margin:0;font-size:15px;color:#191c1a;font-weight:500;">' . $name . '</p>
              </td>
            </tr>
          </table>

          <!-- Field: Email -->
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
            <tr>
              <td style="background:#f2f4ef;padding:16px 20px;border-radius:6px;">
                <p style="margin:0 0 4px;font-size:10px;font-weight:700;color:#414845;text-transform:uppercase;letter-spacing:1.5px;">Email</p>
                <p style="margin:0;font-size:15px;color:#4d6703;font-weight:500;">' . $email . '</p>
              </td>
            </tr>
          </table>

          <!-- Field: Subject -->
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
            <tr>
              <td style="background:#f2f4ef;padding:16px 20px;border-radius:6px;">
                <p style="margin:0 0 4px;font-size:10px;font-weight:700;color:#414845;text-transform:uppercase;letter-spacing:1.5px;">Subject</p>
                <p style="margin:0;font-size:15px;color:#191c1a;font-weight:500;">' . $subject . '</p>
              </td>
            </tr>
          </table>

          <!-- Field: Message -->
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
            <tr>
              <td style="background:#f2f4ef;padding:16px 20px;border-radius:6px;">
                <p style="margin:0 0 8px;font-size:10px;font-weight:700;color:#414845;text-transform:uppercase;letter-spacing:1.5px;">Message</p>
                <p style="margin:0;font-size:15px;color:#191c1a;line-height:1.7;white-space:pre-wrap;">' . $message . '</p>
              </td>
            </tr>
          </table>

          <!-- Reply CTA -->
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="background:#001e16;padding:14px 28px;border-radius:6px;">
                <a href="mailto:' . $email . '" style="color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;letter-spacing:0.5px;">Reply to ' . $name . '</a>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#e7e9e4;padding:20px 40px;border-radius:0 0 8px 8px;border:1px solid #e1e3de;border-top:none;">
          <p style="margin:0;font-size:11px;color:#717975;">Augusta CS &mdash; Engineered Intelligence &bull; alex@augustacs.com</p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>';

$sent = mail($to, "New Inquiry: $subject", $body, $headers);

if ($sent) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send. Please try again or email us directly.']);
}
