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

class GoodreadsController extends AbstractController
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
        var_dump("I'm in");die;
    }

}