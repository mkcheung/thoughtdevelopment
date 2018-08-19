<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 8/14/18
 * Time: 9:57 PM
 */


namespace App\Service;


use App\Constants\ErrorMessages;
use App\Entity\ResearchUser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\VarDumper\VarDumper;

class UserService
{


    protected $em;
    protected $omeEm;
    protected $encoder;
    protected $legacyEm;
    protected $formFactory;
    protected $emailService;
    protected $reportService;
    protected $tokenExtractor;


    public function __construct(
        JWTEncoderInterface $encoder,
        EntityManager $em,
        FormFactoryInterface $formFactory,
        TokenExtractorInterface $tokenExtractor
    ) {
        $this->em               = $em;
        $this->encoder          = $encoder;
        $this->formFactory      = $formFactory;
        $this->tokenExtractor   = $tokenExtractor;
    }

    public function getUserFromToken(Request $request)
    {
        try {
            $token = $this->tokenExtractor->extract($request);
            $data = $this->encoder->decode($token);

            if (array_key_exists('username', $data)) {
                $userRepo = $this->em->getRepository(ResearchUser::class);
                $user = $userRepo->findOneBy(['username' => $data['username']]);

                if ($user instanceof ResearchUser) {
                    return $user;
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    public function refreshToken($request, ResearchUser $user)
    {
        if (isset($request['username']) && $request['username'] === $user->getUsername()) {
            return [
                'data' => [
                    'token' => $this->generateToken($user),
                    'username'  => $user->getUsername(),
                ]
            ];
        }

        throw new UnauthorizedHttpException('Invalid or Expired token');
    }

    public function generateToken(ResearchUser $user, $expiration = 900)
    {
        $token = $this->encoder->encode([
            'username' => $user->getUsername(),
            'exp' => time() + $expiration,
        ]);

        return $token;
    }

//    public function checkExistingUser($request)
//    {
//        if (!isset($request['email']) || empty($request['email'])) {
//            throw new \InvalidArgumentException('Email address is required');
//        }
//
//        $email = $request['email'];
//        $userRepo = $this->em->getRepository(ResearchUser::class);
//
//        $user = $userRepo->findByEmail($email);
//
//        if ($user instanceof ResearchUser) {
//            return [
//                'uuid'      => $user->getUuid(),
//                'enabled'   => $user->isEnabled(),
//            ];
//        }
//
//        throw new EntityNotFoundException('No user exists');
//    }


    public function register($requestBody, UserPasswordEncoderInterface $encoder )
    {

        try {
            $username = $requestBody['_username'];
            $firstname = $requestBody['_firstname'];
            $lastname = $requestBody['_lastname'];
            $password = $requestBody['_password'];
            $email = $requestBody['_email'];

            $user = new ResearchUser($username,$firstname,$lastname,$email);
            $user->setPassword($encoder->encodePassword($user, $password));
            $this->em->persist($user);
            $this->em->flush();
            $errors = [];
            $userCreated = true;
        } catch (\Exception $e) {
            $userCreated = false;
            $errors[] = $e->getMessage();
        }

        $userCreatedResponse = [
            'success' => $userCreated,
        ];

        if ($userCreated) {
            $userCreatedResponse['first_name'] = $user->getFirstName();
            $userCreatedResponse['last_name'] = $user->getLastName();
            $userCreatedResponse['email'] = $user->getEmail();
        }

        $userCreatedResponse['errors'] = $errors;

        return $userCreatedResponse;
    }

    public function login($requestBody)
    {
        $data = $requestBody;

        if (empty($data['username']) || empty($data['password'])) {
            throw new BadCredentialsException(ErrorMessages::INVALID_USERNAME_PASSWORD_COMBINATION);
        }

        $userRepo = $this->em->getRepository(ResearchUser::class);
        /** @var ResearchUser $user */
        $user = $userRepo->findOneBy(['username' => $data['username']]);

        if (empty($user)) {
            throw new BadCredentialsException(ErrorMessages::INVALID_USERNAME_PASSWORD_COMBINATION);
        }

        $isValidUser = password_verify($data['password'], $user->getPassword());

        if (!$isValidUser && $user->getLastLogin() == null) {
            $hashPassword = $this->universalHashPassword($data['password']);
            $isValidUser = ($hashPassword == $user->getPassword());

            if ($isValidUser && $user->isEnabled()) {
                $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
            }
        }

        if (!$isValidUser) {
            throw new BadCredentialsException(ErrorMessages::INVALID_USERNAME_PASSWORD_COMBINATION);
        }

        if (!$user->isEnabled()) {
            throw new AuthenticationException(ErrorMessages::ACCOUNT_NOT_ENABLED);
        }

        $userToken = $this->generateToken($user);

        $user->setLastLogin(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();

        return [
            'token'                 => $userToken,
            'username'              => $user->getUserName(),
            'enabled'               => $user->isEnabled(),
            'first_name'            => $user->getFirstName(),
            'last_name'             => $user->getLastName(),
        ];
    }

    private function universalHashPassword($password)
    {
        $salt = base64_encode($this->globalSalt);
        $iterations = 1000;
        $length = 128;
        $algo = 'sha1';

        return base64_encode(hash_pbkdf2(
            $algo,
            $password,
            $salt,
            $iterations,
            $length,
            true
        ));
    }
}