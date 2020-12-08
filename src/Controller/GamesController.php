<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
    /**
     * @Route("/", name="games")
     */

    public function showAll(GameRepository $gameRepository): Response{

        return $this->render('game/show.html.twig', [
            'title' => 'Gaming App',
            'showAll' => $gameRepository->findAll()
        ]);
    }

    /**
     * @Route("/list", name="list")
     */
        public function index(ApiService $service): Response
    {
        $request = $service->requestApi();

        return $this->render('games/listApi.html.twig', [
            'title' => 'Gaming App',
            'content' => $request,
        ]);
    }
    
}
