<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Password Reset - Tulip Store</title>
  <style>
    body { font-family: Arial, sans-serif; color: #333; }
  </style>
  </head>
<body>
  <p>Hello {{ $name }},</p>
  <p>Use the following code to reset your password. This code expires in 15 minutes.</p>
  <h2 style="font-family: monospace; letter-spacing: 4px;">{{ $code }}</h2>
  <p>If you didn't request this, you can ignore this email.</p>
  <p>— Tulip Store Team</p>
</body>
</html>


