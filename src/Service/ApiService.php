<?php


namespace App\Service;


use App\Entity\Game;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private $bag;
    private $client;


    public function __construct(ParameterBagInterface $bag, HttpClientInterface $client)
    {
        $this->bag = $bag;
        $this->client = $client;
    }

    public function requestApi(){
        $url = $this->bag->get('api.gaming.url');
        $response = $this->client->request('GET', $url);

//        for ($i = 1; $i < 10; $i++) {
//            $pageNext = $this->client->request('GET', $url . "&page=" . $i);
//        }

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray()['results'];


        return $content;

    }

    public function insertInDB(): Response
    {
        $request = $this->requestApi();
        $em = $this->getDoctrine()->getManager();

            foreach ($request as $value) {

                $game = new Game();
                $game->setName($value['name']);
                $game->setReleased(date_create_from_format('Y-m-d', $value['released']));
                $game->setPlatform($value['platforms'][0]['platform']['name']);
                $game->setRating($value['rating']);
                $game->setVideo($value['clip']['clips'][640]);

                $em->persist($game);
            }
            $em->flush();

    }
}