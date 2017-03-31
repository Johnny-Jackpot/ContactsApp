<?php 

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GoogleClientOAuthController extends Controller
{	
	/**
	 * @Route("/googleClientOAuth", name="googleOAuth")
	 */
	public function googleClientOAuthAction(Request $request)
	{	
		$client = $this->get('app.googleClient')->getRawGoogleClient();
		
		$session = $request->getSession();	

		if (!isset($_GET['code'])) {
		  	$auth_url = $client->createAuthUrl();

		  	return $this->redirect($auth_url);
		} else {

			$client->authenticate($_GET['code']);		  	

		  	/**
			 * This code causes the same problem: ERR_TOO_MANY_REDIRECTS
			 * Could not understand why
			 *
		  	 * $session->set('access_token', $client->getAccessToken());
		  	 * 
		  	 */
		  	$_SESSION['google_access_token'] = $client->getAccessToken();		  	
		  	
		  	return $this->redirectToRoute(
		  		$session->get('google-destination'),
		  		['id' => $session->get('google-destination-id')]
		  	);  		 	
		}		
	}
}