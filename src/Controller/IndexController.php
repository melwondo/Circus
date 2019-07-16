<?php

namespace App\Controller;

use App\Entity\Performances;
use App\Repository\PerformancesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, PerformancesRepository $perf)
    {
        $perf = $this->getDoctrine()
            ->getRepository(Performances::class)
            ->findAll();


        return $this->render('index/index.html.twig', [
            'perf' => $perf,
        ]);
    }

    
}
