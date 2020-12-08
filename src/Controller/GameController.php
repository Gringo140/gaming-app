<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="game_index", methods={"GET", "POST"})
     */

    public function index(Request $req, EntityManagerInterface $em)
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($req);


        if ($form->isSubmitted() && $form->isValid()) {

            $qb = $em->createQueryBuilder();
            $qb->select("g")
                ->from("App:Game", "g");
            if ($game->getName() != null) {
                $qb->andWhere("g.name LIKE :NAME")
                    ->setParameter("NAME", '%' . $game->getName() . '%');
            }
            $result = $qb->getQuery()->getResult();
        } else {
            $result = [];
        }
        return $this->render("game/index.html.twig", [
            "formGame" => $form->createView(),
            "games" => $result
        ]);
    }

}
