<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends Controller
{
    private $userRepository;
    private $encoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * Create User.
     * @Rest\Post("/api/users", name="user_create")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $user->setPassword($this->encoder->encodePassword($user, $request->request->get('password')));

        $user->setToken(bin2hex(random_bytes(20)));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return View::create($user, Response::HTTP_CREATED);
    }

    /**
     * login User.
     * @Rest\Post("/api/login", name="user_login")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function login(Request $request)
    {
        $user = $this->userRepository->findOneBy(['email'=> $request->request->get('email')]);

        if (!$user)
        {
            return View::create(['invalid email'], Response::HTTP_NOT_FOUND);
        }

        if ( !$this->encoder->isPasswordValid($user, $request->request->get('password')))
        {
            return View::create(['invalid password'], Response::HTTP_NOT_FOUND);
        }

        return View::create($user->getToken(), Response::HTTP_OK);
    }

    /**
     * refresh token.
     * @Rest\Post("/api/refreshToken", name="user_refresh_token")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function refreshToken(Request $request)
    {
        $user = $this->userRepository->findOneBy(['token' => $request->request->get('token')]);

        if (!$user)
        {
            return View::create(['invalid token'], Response::HTTP_NOT_FOUND);
        }

        $user->setToken(bin2hex(random_bytes(20)));
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return View::create($user->getToken(), Response::HTTP_OK);

    }
}
