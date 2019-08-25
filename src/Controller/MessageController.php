<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Sitting;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\SittingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message_index", methods={"GET","POST"})
     */
    public function index(MessageRepository $messageRepository, Request $request, SittingRepository $sittingRepository, $sittingId = null): Response
    {
        
        $sittings = [];
        $messages = $messageRepository->findBy(['user' => $this->getUser()]);
        foreach ($messages as $message) {
            $sittings[] = $message->getSitting()->getId();
        }
        $sittings = array_unique($sittings);
        $sittings = $sittingRepository->findBy(['id' => $sittings]);
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sittingId = $request->query->get('sittingId');
            $sitting = $sittingRepository->findOneBy(['id' => $sittingId]);

            $entityManager = $this->getDoctrine()->getManager();
            $message->setUser($this->getUser());
            $message->setSitting($sitting);
            $entityManager->persist($message);
            $entityManager->flush();
            

            $this->addFlash('success', "Votre message a été envoyé avec succès !");

            // return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
            'sittings' => $sittings,
            'formObject' => $form,
        ]);
    }

    /**
     * @Route("/user/{id}/aider/{sittingId}", name="message_new", methods={"GET","POST"})
     */
    function new (User $user, Request $request, SittingRepository $sittingRepository): Response {
        $sitting = $sittingRepository->find($request->attributes->get('sittingId'));
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message->setUser($this->getUser());
            $message->setSitting($sitting);
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', "Votre message a été envoyé avec succès !");

            // return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'user' => $user,
            'sitting' => $sitting,
            'formObject' => $form,
        ]);
    }

    /**
     * @Route("/message/{id}", name="message_show", methods={"GET"})
     */
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/message/{id}/edit", name="message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/{id}", name="message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }
}
