<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 8/12/18
 * Time: 3:05 PM
 */

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ResearchUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
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
     * @Route("/login", methods={"POST"}, name="api_login")
     */
    public function loginAction(Request $request){
        try{
//            var_dump($request->request->all());
            $this->requestBody = $request->request->all();
            $response = $this->userService->login($this->requestBody);
            return new JsonResponse([
                'data' => $response,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->requestErrors[] = [
                'message' => $e->getMessage(),
            ];

            return new JsonResponse([
                'success' => false,
                'errors' => $this->requestErrors,
                ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $request
     * @return JsonResponse
     * @Route("/register", methods={"POST"}, name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        try{
            $this->requestBody = $request->request->all();
            $response = $this->userService->register($this->requestBody, $encoder);
            $code = (!empty($response['errors'])) ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK;
            return new JsonResponse($response,$code);
        } catch (\Exception $e) {
            $this->requestErrors[] = [
                'message' => $e->getMessage(),
            ];

            return new JsonResponse([
                'success' => false,
                'errors' => $this->requestErrors,
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}