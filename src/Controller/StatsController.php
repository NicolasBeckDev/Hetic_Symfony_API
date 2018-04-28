<?php

namespace App\Controller;

use App\Repository\SignRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatsController extends Controller
{
    private $signRepository;

    public function __construct(SignRepository $signRepository)
    {
        $this->signRepository = $signRepository;
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function index()
    {
        $averagesDalays = $this->signRepository->getAveragesDelays();
    }
}
