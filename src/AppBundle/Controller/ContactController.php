<?php 

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{	
	/**
     * Render list of contacts
     * 
     * @Route("/", name="contacts")
     */
    public function contactsAction()
    {   
        $repository = $this->getDoctrine()->getRepository('AppBundle:Contact');
        $contacts = $repository->findAll();

        $template = 'contact/contacts.html.twig';
        $data = ['contacts' => $contacts];

        return $this->render($template, $data);
    }    

	/**
     * Handle adding of new contact
     * 
	 * @Route("/contact/add", name="addContact")
	 */
	public function addContactAction(Request $request)
	{	
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice', 'New contact was added');

            return $this->redirectToRoute('contacts');
        }

        $template = 'contact/addContact.html.twig';
        $data = ['form' => $form->createView()];

		return $this->render($template, $data);
	}

	/**
     * Render page to confirm/cancel deleting of contact  
     * 
	 * @Route("/contact/delete/{id}", name="deleteContact", requirements={"id": "\d+"})
	 */
	public function deleteContactAction($id, Request $request)
	{   
        $repository = $this->getDoctrine()->getRepository('AppBundle:Contact');
        
        $contact = $repository->find($id);

        $flashBag = $request->getSession()->getFlashBag();                

        //unset flashbags
        $flashBag->get('contactDeleteHash');
        $flashBag->get('contactDeleteId');

        /**
         * This is needed to avoid accidentally deleting another user.
         * Every time we generate different link to confirm deleting
         * and use contact id that stored is session but not in route param
         */
        $hash = strtr(password_hash($id, PASSWORD_DEFAULT), '/', '_');
        
        $flashBag->add('contactDeleteHash', $hash);

        /**
         * Because {id} part of url can be changed from browser
         * we store needed {id} in flashbag (session)
         */
        $flashBag->add('contactDeleteId', $id);

		$template = 'contact/deleteContact.html.twig';
		$data = [
            'contact' => $contact,
            'hash' => $hash
        ];

		return $this->render($template, $data);
	}

    /**
     * Handle deleting of contact
     * 
     * @Route("/contact/delete/confirm/{hash}", name="deleteContactConfirm")
     */
    public function deleteContactConfirmAction(Request $request, $hash)
    {
        $flashBag = $request->getSession()->getFlashBag();
        $hashSession = @array_shift($flashBag->get('contactDeleteHash'));

        if ($hash != $hashSession) {
            return $this->redirect('contacts');
        }

        $contactIdToDelete = @array_shift($flashBag->get('contactDeleteId'));

        $em = $this->getDoctrine()->getManager();
        
        $query = $em->createQuery(
            'DELETE
            FROM AppBundle:Contact c
            WHERE c.id = :id'
        )->setParameter('id', $contactIdToDelete);

        $result = $query->getResult();

        if ($result) {
            $flashBag->add('notice', 'Contact was deleted');
        } else {
            $flashBag->add('notice', 'Contact wasn`t deleted. Something goes wrong.');
        }

        return $this->redirectToRoute('contacts');
    }
}