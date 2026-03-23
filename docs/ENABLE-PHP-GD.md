# Enable PHP GD (for PDF invoices)

Dompdf (used for invoice PDFs) needs the **GD** extension. If you see:

> The PHP GD extension is required, but is not installed.

### Windows (XAMPP / WAMP / Laragon)

1. Open `php.ini` (e.g. `C:\xampp\php\php.ini`).
2. Find and uncomment: `extension=gd` (remove the leading `;`).
3. Restart Apache / PHP-FPM / `php artisan serve`.

### Verify

```bash
php -m | findstr /i gd
```

You should see `gd`.

### Without GD

The app falls back to downloading the invoice as **HTML** (same template) so you can still print or “Save as PDF” from the browser.
