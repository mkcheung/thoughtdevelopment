<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 8/15/18
 * Time: 10:52 PM
 */

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ResearchUser;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client as GuzzleClient;

class NewYorkTimesController extends AbstractController
{

    protected $requestErrors = [];
    protected $requestBody = [];
    protected $userService ;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/notes", methods={"GET"}, name="get_notes")
     */
    public function getNotesAction(Request $request){

        $client = new GuzzleClient();

//        $headers = [
//            'key' => $this->getParameter('ny_times_key')
//        ];

//        $res = $client->request('GET', 'https://www.goodreads.com/shelf/list.xml ', [
//            'headers' => $headers
//        ]);

        $res = $client->request('GET', 'http://api.nytimes.com/svc/archive/v1/2016/11.json?api-key='.$this->getParameter('ny_times_key'));
        var_dump($res);die;
    }

}