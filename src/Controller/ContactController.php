<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(\Swift_Mailer $mailer, Request $request )
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            
            $tete = $contact->getNom(). ' ' . $contact->getPrenom();
            $message = (new \Swift_Message())
            ->setSubject($tete)
            ->setFrom($this->getParameter('mailer_from'))
            ->setTo('hhggaamlk@gmail.com')
            ->setBody(
                $this->renderView(
                    'Email/notificationContact.html.twig',
                    ['contact' => $contact]
                ),
                'text/html'
            );
            
            $mailer->send($message);

            $this->addFlash(
                'notice',
                'Message send'
            );

            $eM->persist($contact);
            $eM->flush();

            return $this->redirect($request->getUri());
        }


        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
