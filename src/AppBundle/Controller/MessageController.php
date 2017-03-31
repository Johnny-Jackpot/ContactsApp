<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends Controller
{
	/**
	 * Render history of messages for choosen contact
	 * 
	 * @Route("/message/history/{id}", name="history", requirements={"id": "\d+"})
	 */
	public function historyAction($id, Request $request)
	{				
		$contact = $this->getDoctrine()
					->getRepository('AppBundle:Contact')
					->find($id);

		$messages = $contact->getMessages();		

		$template = 'message/history.html.twig';
		$data = [
			'contact' => $contact,
			'messages' => $messages
		];

		return $this->render($template, $data);
	}

	/**
	 * Handle sending message
	 * 
	 * @param int $id   Id of contact to send message
	 * 
	 * @Route("/message/send/{id}", name="sendMessage", requirements={"id": "\d+"})
	 */
	public function sendMessageAction($id, Request $request)
	{	
		$session = $request->getSession();
		$session->set('google-destination', 'sendMessage');
		$session->set('google-destination-id', $id);

		$gmailService = $this->get('app.gmail');		

		$repository = $this->getDoctrine()->getRepository('AppBundle:Contact');
		$contact = $repository->find($id);

		$message = new Message();
		$form = $this->createForm(MessageType::class, $message);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$recipient = $contact->getEmail();
			$message = $form->getData();
			$subject = $message->getSubject();
			$body = $message->getBody();

			$result = $gmailService->sendMessage($recipient, $subject, $body);
			if ($result) {
				$this->persistEmail($message, $contact);
				$this->addFlash('notice', 'Message was sent successfully');
			} else {
				$this->addFlash('warning', 'Message wasn`t sent. Error occured.');
			}			

			return $this->redirectToRoute('contacts');
		}

		$template = 'message/sendMessage.html.twig';
		$data = [
			'id' => $id,
			'contact' => $contact,
			'form' => $form->createView()
		];

		return $this->render($template, $data);
	}


	/**
	 * Save message to db
	 * 
	 * @param Message $message
	 * @param Contact $contact
	 */
	private function persistEmail(&$message, &$contact)
	{		
		$message->setContact($contact);
		$contact->addMessage($message);

		$em = $this->getDoctrine()->getManager();
		$em->persist($contact);
		$em->persist($message);
		$em->flush();
	}
	
}