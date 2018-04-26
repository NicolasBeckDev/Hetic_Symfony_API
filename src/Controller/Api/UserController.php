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
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;


class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create User.
     * @Rest\Post("/api/users", name="user_create")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function create(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return View::create($user, Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/qrCode", name="qrCode")
     */
    public function qrCode (){
        $qrCode = new QrCode('viande jkbazbkjzabkjzbkjfzbkjzbhjkzf');
        header('Content-Type: '.$qrCode->getContentType());
        return new QrCodeResponse($qrCode);
    }
}
