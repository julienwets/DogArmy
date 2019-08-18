<?php

namespace App\Controller;

use App\Entity\Sitting;
use App\Entity\Dog;
use App\Form\SittingType;
use App\Repository\SittingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sitting")
 */
class SittingController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="sitting_index", methods={"GET"})
     */
    public function index(SittingRepository $sittingRepository): Response
    {
        $userHasNoDogs = $this->getUser()->hasDogs();
        return $this->render('sitting/index.html.twig', [
            'sittings' => $sittingRepository->findBy(['user' => $this->getUser()]),
            'userHasNoDogs' => $userHasNoDogs
        ]);
    }

    /**
     * @Route("/new", name="sitting_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sitting = new Sitting();
        $form = $this->createForm(SittingType::class, $sitting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data->getDogs()->isEmpty()) {
                $this->addFlash('danger', "Vous devez enregistrer un chien avant de demander de l'aide");
                return $this->redirectToRoute('sitting_new', [
                    'sitting' => $sitting,
                    'form' => $form->createView(),
                ]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $sitting->setUser($this->getUser());
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
    public function delete(Request $request, Sitting $sitting): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sitting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sitting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sitting_index');
    }
}
