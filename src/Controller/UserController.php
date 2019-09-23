<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Form\SelectHelperType;
use App\Repository\UserRepository;
use App\Repository\SittingRepository;
use App\Form\ConfirmSittingRequestType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/membres")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/{id}", name="user_show", methods={"GET", "POST"})
     */
    public function show(User $user, Request $request, SittingRepository $sittingRepository, UserRepository $userRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        $selectForm = $this->createForm(SelectHelperType::class);
        $selectForm->handleRequest($request);

        $confirmForm = $this->createForm(ConfirmSittingRequestType::class);
        $confirmForm->handleRequest($request);

        // Formulaire nouveau message
        if ($form->isSubmitted() && $form->isValid()) {
            $sittingId = $request->query->get('sittingId');
            $sitting = $sittingRepository->findOneBy(['id' => $sittingId]);

            $message->setUser($this->getUser());
            $message->setSitting($sitting);
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', "Votre message a été envoyé avec succès !");

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
            // return $this->redirectToRoute('message_index');
        }

        // Formulaire sélection du helper
        if ($selectForm->isSubmitted() && $selectForm->isValid()) {
            $helperUserId = $request->query->get('helperUserId');
            $helperUser = $userRepository->findOneBy(['id' => $helperUserId]);

            $sittingId = $request->query->get('sittingId');
            $sitting = $sittingRepository->findOneBy(['id' => $sittingId]);

            $sitting->setHelperUser($helperUser);
            $sitting->setState('requested');

            $entityManager->persist($sitting);
            $entityManager->flush();
            $this->addFlash('success', "Votre requête a bien été envoyée à " . $helperUser->getFirstname());

            return $this->redirectToRoute('sitting_index');
        }

        // Formulaire de confirmation affiché chez le helper (oui/non)
        if ($confirmForm->isSubmitted() && $confirmForm->isValid()) {
            $sittingId = $request->query->get('sittingId');
            $sitting = $sittingRepository->findOneBy(['id' => $sittingId]);

            if ($confirmForm->get('yes')->isClicked()) {
                $sitting->setState('confirmed');
                $this->addFlash('success', "Votre confirmation a bien été prise en compte !");
            } 

            if ($confirmForm->get('no')->isClicked()) {
                $sitting->setState('open');
                $sitting->addUsersWhoDeclined($sitting->getHelperUser()->getId());
                $sitting->setHelperUser(null);
                $this->addFlash('success', "Votre réponse a bien été prise en compte, " . $sitting->getUser()->getFirstname() . " sera prévenu(e).");
            }

            $entityManager->persist($sitting);
            $entityManager->flush();


            return $this->redirectToRoute('message_index');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'formObject' => $form,
            'selectFormObject' => $selectForm,
            'confirmFormObject' => $confirmForm,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
