<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getQRCode (Request $request)
    {
        //Get location by location description
        $location = $this->locationRepository->find($request->request->get('form')['location']);

        //Set random chars in QRCode attribute
        $location->setQrCode(bin2hex(random_bytes(20)));

        //Flush edited location
        $this->getDoctrine()->getManager()->flush();

        //Return a view with QRCode value and description value
        return $this->render('qr_code/index.html.twig',[
            'qrCode' => $location->getQrCode(),
            'description' => $location->getDescription()
        ]);
    }

    /**
     * Vue with form where you can select a location
     * @Route("/", name="showLocations")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setLocation()
    {
        //Create the form => content list of content description and redirect to qrcode route
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('qrcode'))
            ->setMethod('POST')
            ->add('location',EntityType::class, array(
                'class' => Location::class,
                'choice_label' => 'description'))
            ->add('submit', SubmitType::class)
            ->getForm();

        //Return the vue with the form
        return $this->render('qr_code/location.html.twig',['form' => $form->createView()]);
    }


}
