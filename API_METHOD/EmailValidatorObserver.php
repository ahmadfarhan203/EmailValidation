<?php

namespace Custom\EmailValidator\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class EmailValidatorObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $email = $customer->getEmail();

        // Example: Using NeverBounce API
        $apiKey = 'your-api-key';
        $url = "https://api.neverbounce.com/v4/single/check?key=$apiKey&email=$email";  // Here put the apikey 

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        // Check response status (e.g., "valid", "invalid", "disposable")
        if ($result['result'] === 'invalid' || $result['result'] === 'disposable') {
            throw new LocalizedException(__('Invalid or disposable email address.'));
        }
    }
}
