<?php

namespace App\Controller;

use App\Entity\Performances;
use App\Form\PerformancesType;
use App\Repository\PerformancesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * 
 * @IsGranted("ROLE_ADMIN")
 * 
 * @Route("/performances")
 */
class PerformancesController extends AbstractController
{
    /**
     * @Route("/", name="performances_index", methods={"GET"})
     */
    public function index(PerformancesRepository $performancesRepository): Response
    {
        return $this->render('performances/index.html.twig', [
            'performances' => $performancesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="performances_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader ): Response
    {
        $performance = new Performances();
        $form = $this->createForm(PerformancesType::class, $performance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form['logo']->getData();
            if (!empty($imageFile)) {
                $imageFileName = $fileUploader->uploadImgPerf($imageFile);
                $performance->setLogo($imageFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($performance);
            $entityManager->flush();

            return $this->redirectToRoute('performances_index');
        }

        return $this->render('performances/new.html.twig', [
            'performance' => $performance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="performances_show", methods={"GET"})
     */
    public function show(Performances $performance): Response
    {
        return $this->render('performances/show.html.twig', [
            'performance' => $performance,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="performances_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Performances $performance, FileUploader $fileUploader): Response
    {
        // $perf = new Performances();
        $form = $this->createForm(PerformancesType::class, $performance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form['logo']->getData();
            if (!empty($imageFile)) {
                $imageFileName = $fileUploader->uploadImgPerf($imageFile);
                $performance->setLogo($imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('performances_index');
        }

        return $this->render('performances/edit.html.twig', [
            'performance' => $performance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="performances_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Performances $performance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$performance->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($performance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('performances_index');
    }
}
