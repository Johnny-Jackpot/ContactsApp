<?php

namespace AppBundle\Services;

use AppBundle\Services\GoogleClientService;

class GmailService
{	
	/**
	 * @var Google_Client $client  
	 */
	protected $client;

	/**
	 * @var Google_Service_Gmail $gmail
	 */
	protected $gmail;

	public function __construct(GoogleClientService $googleClientService)
	{
		$this->client = $googleClientService->getGoogleClient();
		$this->gmail = new \Google_Service_Gmail($this->client);
	}

	/**
	 * Send message through Gmail accaunt
	 * 
	 * @param string $recipient  Recipient of email
	 * @param string $subject    Email subject
	 * @param string $body       Message itself
	 * 
	 * @return bool
	 */
	public function sendMessage($recipient, $subject, $body)
	{
		$message = new \Google_Service_Gmail_Message();

		$encodedRaw = $this->encodeMessage($recipient, $subject, $body);	

		$message->setRaw($encodedRaw);

		try {
			$this->gmail->users_messages->send('me', $message);
			return true;
		} catch (Exception $e) {
			
			return false;
		}
		
	}

	/**
	 * Format message according RFC2822 and encode it as base64
	 * 
	 * @param string $recipient  Recipient of email
	 * @param string $subject    Email subject
	 * @param string $body       Message itself
	 * 
	 * @return string  			 Encoded message
	 */
	public function encodeMessage($recipient, $subject, $body)
	{		
		$raw = "To: {$recipient}\r\n" . 
			   "Subject: {$subject}\r\n\r\n" .
			   $body;

		return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
	}
	
}