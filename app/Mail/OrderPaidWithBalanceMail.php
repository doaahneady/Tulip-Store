<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPaidWithBalanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public ?Invoice $invoice = null
    ) {}

    public function build()
    {
        $orderNumber = (string) ($this->order->order_number ?? $this->order->id);
        $total = (float) ($this->order->total ?? $this->order->total_amount ?? 0);
        $invoiceNumber = $this->invoice?->invoice_number;

        $html = '<div style="font-family: Arial, sans-serif;">'
            .'<h2>Order Confirmation</h2>'
            .'<p>Your order <strong>#'.e($orderNumber).'</strong> has been paid using your balance.</p>'
            .'<p><strong>Total:</strong> $'.number_format($total, 2).'</p>'
            .($invoiceNumber ? '<p><strong>Invoice:</strong> '.e($invoiceNumber).'</p>' : '')
            .'</div>';

        return $this->subject('Order Confirmation - Paid with Balance')->html($html);
    }
}

