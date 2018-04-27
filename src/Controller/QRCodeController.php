<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/getQRCode", name="qrcode")
     * @Method({"POST"})
     * @param Request $request
     * @return QrCodeResponse
     * @throws \Exception
     */
    public function getQRCode (Request $request)
    {
        $location = $this->locationRepository->find($request->request->get('form')['location']);

        $location->setQrCode(bin2hex(random_bytes(20)));
        $this->getDoctrine()->getManager()->flush();

        $qrCode = new QrCode($location->getQrCode());
        header('Content-Type: '.$qrCode->getContentType());

        return $this->render('qr_code/index.html.twig');
//        return $this->render('qr_code/location.html.twig',[
//            'qrCode' => new QrCodeResponse($qrCode)
//        ]);
        //return new QrCodeResponse($qrCode);
    }

    /**
     * @Route("/", name="showLocations")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setLocation()
    {

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('qrcode'))
            ->setMethod('POST')
            ->add('location',EntityType::class, array(
                'class' => Location::class,
                'choice_label' => 'description'))
            ->add('submit', SubmitType::class)
            ->getForm();

        return $this->render('qr_code/location.html.twig',['form' => $form->createView()]);
    }


}
