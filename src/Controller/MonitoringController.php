<?php

namespace App\Controller;


use App\Repository\UniteEntityRepository;
use App\Repository\ModuleEntityRepository;
use App\Repository\ReleveEntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MonitoringController extends AbstractController
{
    #[Route('/monitoring', name: 'app_monitoring')]
    public function index(): Response
    {
        return $this->render('monitoring/index.html.twig', [
            'controller_name' => 'MonitoringController',
        ]);
    }

    #[Route('/addModuleMonitoring/{id}', name: 'app_add_module_monitoring')]
    public function addModuleMonitoring(int $id, ModuleEntityRepository $moduleRepository, ReleveEntityRepository $releveRepository,UniteEntityRepository $uniteRepository): Response
    {
        if (!$moduleRepository->hasUnite()) {
            $this->addFlash('sweetalert_warning', [
                'message' => 'Merci de créer un module avant'
                ]);
            return $this->redirectToRoute('app_monitoring');
        }

        

        return $this->render('monitoring/index.html.twig', [
            'controller_name' => 'MonitoringController',
        ]);
    }


}
