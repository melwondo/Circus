<?php

namespace App\Controller;

use App\Entity\Performances;
use App\Repository\PerformancesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ContactRepository;
use App\Repository\AboutRepository;
use App\Entity\About;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, PerformancesRepository $perf, AboutRepository $about)
    {
        $about = $this->getDoctrine()
            ->getRepository(About::class)
            ->findAll();

        $perf = $this->getDoctrine()
            ->getRepository(Performances::class)
            ->findAll();


        return $this->render('index/index.html.twig', [
            'perf' => $perf,
            'about' => $about,
        ]);
    }

    
}
