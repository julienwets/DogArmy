<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Sitting;
use App\Form\MessageType;
use App\Form\SittingType;
use App\Repository\SittingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/sitting")
 */
class SittingController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="sitting_index", methods={"GET", "POST"})
     */
    public function index(SittingRepository $sittingRepository, Request $request, $sittingId = null): Response
    {
        $userHasNoDogs = $this->getUser()->hasDogs();
        $sitting = new Sitting();

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
            return $this->redirectToRoute('sitting_index');
        }

        return $this->render('sitting/index.html.twig', [
            'sittings' => $sittingRepository->findBy(['user' => $this->getUser()]),
            'userHasNoDogs' => $userHasNoDogs,
            'formObject' => $form,
        ]);
    }

    /**
     * @Route("/new", name="sitting_new", methods={"GET","POST"})
     */
    function new (Request $request): Response {
        $sitting = new Sitting();
        $user = $this->getUser();
        $form = $this->createForm(SittingType::class, $sitting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data->getDogs()->isEmpty()) {
                $this->addFlash('danger', "Votre demande d'aide doit concerner un chien. L'avez-vous ajouté à votre profil ?");
                return $this->redirectToRoute('sitting_new', [
                    'sitting' => $sitting,
                    'form' => $form->createView(),
                ]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $sitting->setUser($user);
            $user->setNeedsHelp(true);
            $entityManager->persist($sitting);
            $entityManager->flush();

            $this->addFlash('success', "Votre demande vient d'être créée !");

            return $this->redirectToRoute('sitting_index');
        }

        return $this->render('sitting/new.html.twig', [
            'sitting' => $sitting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sitting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sitting $sitting): Response
    {
        $form = $this->createForm(SittingType::class, $sitting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sitting_index');
        }

        return $this->render('sitting/edit.html.twig', [
            'sitting' => $sitting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sitting_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sitting $sitting, SittingRepository $sittingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sitting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sitting);

            $sittings = $sittingRepository->findBy(['user' => $this->getUser()]);
            if (count($sittings) == 1) {
                $this->getUser()->setNeedsHelp(false);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('sitting_index');
    }
}
