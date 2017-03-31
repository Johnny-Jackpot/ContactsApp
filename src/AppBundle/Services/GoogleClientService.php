<?php

namespace AppBundle\Services;

class GoogleClientService
{
	/**
	 * @var Google_Client $googleClient
	 */
	protected $googleClient;	

	public function __construct($scope)
	{	
		$client = new \Google_Client();
		$client->addScope($scope);

		//at the moment can`t find better way to get path
		$pathToAuthConfig = __DIR__ . '/../../../app/config/google_client_secret.json';
		$client->setAuthConfig($pathToAuthConfig);
		$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/googleClientOAuth');

		$this->googleClient = $client;	
	}


	/** 
	 * @return Google_Client | redirect to route that handle access token getting
	 */
	public function getGoogleClient()
	{
		$this->checkAccessTokenExistence();
		return $this->googleClient;
	}

	/**
	 * @return Google_Client   without access token
	 */
	public function getRawGoogleClient()
	{		
		return $this->googleClient;
	}

	/**
	 * Check if access token existin session
	 * 
	 * if (true): set access token to Google_Client instance
	 * else:      redirect to route that handle it`s getting
	 */
	public function checkAccessTokenExistence()
	{
		if (
				empty($_SESSION['google_access_token']) ||
				$this->tokenIsExpired()
			) {

			$redirect_uri = $this->googleClient->getRedirectUri();
 			header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
 			exit;

		} else {			

			$client = $this->googleClient;
			$client->setAccessToken($_SESSION['google_access_token']);

		}
	}

	/**
	 * Check if access token is expired
	 * 
	 * @param int $reserveTime seconds left for access token became expired
	 * 
	 * @return bool  (True if less than $reserveTime seconds
	 *  left to expiring of access token)
	 */
	public function tokenIsExpired($reserveTime = 300)
	{
		$now = date_timestamp_get(new \DateTime('now'));
	  	$expires = $_SESSION['google_access_token']['created'] +  
	  			   $_SESSION['google_access_token']['expires_in'];
	  	$left = $expires - $now;

	  	return ($left < $reserveTime) ? true : false;	  	
	}
}