<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Entity\Sign;
use App\Entity\User;
use App\Form\LoginType;
use App\Form\UserType;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends Controller
{
    private $userRepository;
    private $locationRepository;
    private $eventRepository;
    private $encoder;
    private $error;

    public function __construct(UserRepository $userRepository, LocationRepository $locationRepository, EventRepository $eventRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->locationRepository = $locationRepository;
        $this->eventRepository = $eventRepository;
        $this->encoder = $encoder;
        $this->error = View::create(['response' => 'KO'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Create User with hashed password and random token
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
     * Return user token by email and password.
     * @Rest\Post("/api/login", name="user_login")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function login(Request $request)
    {
        $user = $this->userRepository->findOneBy(['email' => $request->request->get('Email'),'password' => $request->request->get('Password')]);
        if (!$user) return $this->error;

        return View::create(['token' => $user->getToken()], Response::HTTP_OK);
    }

    /**
     * Refresh user token.
     * @Rest\Post("/api/refreshToken", name="user_refresh_token")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function refreshToken(Request $request)
    {
        $user = $this->userRepository->findOneBy(['token' => $request->request->get('token')]);
        if (!$user) return View::create(['invalid token'], Response::HTTP_NOT_FOUND);
        $user->setToken(bin2hex(random_bytes(20)));
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return View::create(['token' => $user->getToken()], Response::HTTP_OK);
    }

    /**
     * Get location.
     * @Rest\Get("/api/getLocation/{token}", name="user_get_location")
     * @param string $token
     * @return \FOS\RestBundle\View\View
     */

    public function getLocation(string $token)
    {
        $nextEvent = $this->eventRepository->findNextEvent();
        if (!$nextEvent) return $this->error;

        return View::create(['date' => $nextEvent->getDate()->format(\DateTime::ISO8601), 'location' => $nextEvent->getLocation()->getDescription()], Response::HTTP_OK);
    }


    /**
     * Sign a user to an event.
     * @Rest\Post("/api/checkIn", name="checkIn")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function checkIn(Request $request)
    {
        $location = $this->locationRepository->findOneBy(['qrCode' => $request->request->get('QRCodeData')]);
        $date = $request->request->get('date');
        $beaconCollection = json_decode($request->request->get('beaconCollection'));
        $user = $this->userRepository->findOneBy(['token' => $request->request->get('token')]);
        $event = $this->eventRepository->findNextEvent();
        if(!$location && !$date && !$user && !$event && !in_array($location->getBeacon(), $beaconCollection)) return $this->error;
        $sign = new Sign();
        $sign->setDate(new \DateTime($date));
        $sign->setEvent($event);
        $sign->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($sign);
        $em->flush();

        return View::create(['response' => 'OK'], Response::HTTP_OK);

    }

}
