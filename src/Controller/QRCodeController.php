<?php

namespace App\Controller;

use App\Form\LocationType;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;


class QRCodeController extends Controller
{

    private $userRepository;
    private $locationRepository;
    private $eventRepository;

    public function __construct(UserRepository $userRepository, LocationRepository $locationRepository, EventRepository $eventRepository)
    {
        $this->userRepository = $userRepository;
        $this->locationRepository = $locationRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @Route("/getQRCode", name="qrCode")
     * @Method({"POST"})
     * @param Request $request
     * @return QrCodeResponse
     */
    public function getQRCode (Request $request)
    {
        $location = $this->locationRepository->findOneBy(['description' => $request->request->get('location')] );

        if ($location->getLastRefresh() or time() > $location->getLastRefresh() + 30 )
        {

        }

        $qrCode = new QrCode($location);
        header('Content-Type: '.$qrCode->getContentType());

        return new QrCodeResponse($qrCode);
    }

    /**
     * @Route("/", name="showLocations")
     * @Method({"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setLocation(Request $request)
    {
        $form = $this->createForm(LocationType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            //return $this->redirectToRoute('qrCode');
            return $this->redirect($this->generateUrl('qrCode'));

        }
            return $this->render('qr_code/location.html.twig',[
            'form' => $form->createView()
        ]);
    }
}