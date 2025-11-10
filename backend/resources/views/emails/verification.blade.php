<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email Verification - Tulip Store</title>
    <style>
      /* Basic email-safe styles */
      body { margin:0; padding:0; background:#f2f6f7; }
      .wrapper { width:100%; background:#f2f6f7; }
      .container { max-width:640px; margin:0 auto; background:#ffffff; }
      .brand-bar { background: linear-gradient(135deg, #0D464C 0%, #10707a 100%); padding:28px 20px; text-align:center; color:#fff; }
      .brand-title { margin:0; font-family:Arial, Helvetica, sans-serif; font-size:26px; letter-spacing:0.5px; }
      .brand-sub { margin:8px 0 0; font-size:14px; opacity:0.85; font-family:Arial, Helvetica, sans-serif; }
      .content { padding:32px 28px; font-family:Arial, Helvetica, sans-serif; color:#1c2b2d; }
      h2 { color:#0D464C; margin:0 0 12px; font-size:20px; }
      p { margin:0 0 14px; line-height:1.6; }
      .card { background:#fcfdfd; border:1px solid #e6ecee; border-radius:12px; padding:20px; }
      .code-label { text-transform:uppercase; font-size:12px; letter-spacing:1px; color:#597075; margin-bottom:8px; }
      .code { display:inline-block; font-family: 'Courier New', Courier, monospace; font-weight:bold; font-size:28px; letter-spacing:6px; color:#F05928; background:#fff7f3; border:2px dashed #F05928; border-radius:10px; padding:12px 16px; }
      .note { font-size:12px; color:#6b7d80; margin-top:14px; }
      .footer { text-align:center; color:#7a8a8d; font-size:12px; padding:20px; }
      .divider { height:1px; background:#edf2f4; margin:24px 0; }
      .btn { display:inline-block; padding:10px 16px; border-radius:8px; background:#0D464C; color:#ffffff !important; text-decoration:none; font-weight:bold; font-size:14px; }
      @media (prefers-color-scheme: dark) {
        body { background:#0e1516; }
        .container { background:#0f1b1c !important; }
        .content { color:#eef6f7 !important; }
        .card { background:#122325 !important; border-color:#1e3336 !important; }
        .code { background:#1b2f31 !important; }
        .footer { color:#a9b7b9 !important; }
      }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <div class="container">
        <div class="brand-bar">
          <div class="brand-title">Tulip Store</div>
          <div class="brand-sub">Secure your account in one easy step</div>
        </div>
        <div class="content">
          <h2>Hello {{ $name }},</h2>
          <p>Thank you for joining Tulip Store. Use the code below to verify your email address and get started:</p>
          <div class="card" style="text-align:center; margin-top:16px;">
            <div class="code-label">Your verification code</div>
            <div class="code">{{ $code }}</div>
            <div class="note">This code expires in 15 minutes.</div>
          </div>
          <div class="divider"></div>
          <p>If you didn’t request this, you can safely ignore this email and no changes will be made to your account.</p>
          <p style="margin-top:16px;">— Tulip Store Team</p>
        </div>
        <div class="footer">© {{ date('Y') }} Tulip Store • All rights reserved</div>
      </div>
    </div>
  </body>
</html>

