<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $instanceId;
    protected $token;
    protected $baseUrl;

    public function __construct()
    {
        $this->instanceId = config('services.green_api.instance_id');
        $this->token = config('services.green_api.token');
        $this->baseUrl = "https://api.green-api.com/waInstance{$this->instanceId}";
    }

    /**
     * Send a WhatsApp message
     *
     * @param string $phoneNumber Phone number with country code (e.g., 966501234567)
     * @param string $message Message text
     * @return array Response from Green API
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            // Ensure phone number is in correct format (no + or spaces)
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Green API expects phone number with @c.us suffix
            $chatId = $phoneNumber . '@c.us';

            $url = "{$this->baseUrl}/sendMessage/{$this->token}";

            $response = Http::timeout(30)->post($url, [
                'chatId' => $chatId,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp message sent successfully to {$phoneNumber}");
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            } else {
                Log::error("Failed to send WhatsApp message to {$phoneNumber}: " . $response->body());
                return [
                    'success' => false,
                    'error' => $response->body(),
                ];
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp service error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send verification code via WhatsApp
     *
     * @param string $phoneNumber Phone number with country code
     * @param string $code Verification code
     * @param string $userName User's name
     * @return array Response
     */
    public function sendVerificationCode($phoneNumber, $code, $userName = null)
    {
        $greeting = $userName ? "مرحباً {$userName}،\n\n" : "مرحباً،\n\n";
        
        $message = $greeting . "رمز التحقق الخاص بك في Tulip Store هو:\n\n";
        $message .= "*{$code}*\n\n";
        $message .= "الرمز صالح لمدة 10 دقائق.\n";
        $message .= "إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة.\n\n";
        $message .= "شكراً لاستخدامك Tulip Store 🌷";

        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Check if Green API is configured
     *
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->instanceId) && 
               !empty($this->token) && 
               $this->instanceId !== 'your_instance_id_here' &&
               $this->token !== 'your_api_token_here';
    }
}
