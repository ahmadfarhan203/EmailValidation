<?php

namespace Custom\EmailValidator\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class EmailValidatorObserver implements ObserverInterface
{
    protected $disposableDomains;

    public function __construct()
    {
        // Load the list of disposable email domains from a local file
        $this->disposableDomains = file('path_to_your_domain_list.txt', FILE_IGNORE_NEW_LINES); // path of database that we created by our own 
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $email = $customer->getEmail();
        $emailDomain = substr(strrchr($email, "@"), 1);

        // Check if the email domain is in the disposable domains list
        if (in_array($emailDomain, $this->disposableDomains)) {
            throw new LocalizedException(__('Disposable email addresses are not allowed.'));
        }
    }
  }
