<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Form\TutorialType;
use App\Repository\TutorialRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/tutorial')]
class AdminTutorialController extends AbstractController
{
    #[Route('/', name: 'app_admin_tutorial_index', methods: ['GET'])]
    public function index(TutorialRepository $tutorialRepository): Response
    {
        return $this->render('admin_tutorial/index.html.twig', [
            'tutorials' => $tutorialRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_tutorial_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TutorialRepository $tutorialRepository, SluggerInterface $slugger): Response
    {
        $tutorial = new Tutorial();
        $form = $this->createForm(TutorialType::class, $tutorial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            // // Ajout de cette ligne pour générer le slug automatiquement
            $tutorial->setTutoSlug(strtolower($slugger->slug($tutorial->getTutoName())));
            $tutorialRepository->save($tutorial, true);

            return $this->redirectToRoute('app_admin_tutorial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_tutorial/new.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_tutorial_show', methods: ['GET'])]
    public function show(Tutorial $tutorial): Response
    {
        return $this->render('admin_tutorial/show.html.twig', [
            'tutorial' => $tutorial,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_tutorial_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tutorial $tutorial, TutorialRepository $tutorialRepository): Response
    {
        $form = $this->createForm(TutorialType::class, $tutorial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tutorialRepository->save($tutorial, true);

            return $this->redirectToRoute('app_admin_tutorial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_tutorial/edit.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_tutorial_delete', methods: ['POST'])]
    public function delete(Request $request, Tutorial $tutorial, TutorialRepository $tutorialRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tutorial->getId(), $request->request->get('_token'))) {
            $tutorialRepository->remove($tutorial, true);
        }

        return $this->redirectToRoute('app_admin_tutorial_index', [], Response::HTTP_SEE_OTHER);
    }
}
