<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Search;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Knp\Component\Pager\PaginatorInterface;

class SearchController extends AbstractController
{

    /**
     * @var $userRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @Route("/membres", name="search")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $user = $this->getUser();

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->get('submit')->isClicked()) {

            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findFilter($search)->getResult();
        }

        $users = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(User::class)
                ->findFilter($search),
            $request->query->getInt('page', 1),
            50
        );


        return $this->render('search/index.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }
}
