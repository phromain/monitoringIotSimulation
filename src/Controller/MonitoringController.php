<?php

namespace App\Controller;


use App\Form\MonitoringType;
use App\Entity\MonitoringEntity;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UniteEntityRepository;
use App\Repository\ModuleEntityRepository;
use App\Repository\ReleveEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MonitoringEntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MonitoringController extends AbstractController
{
    #[Route('/monitoring', name: 'app_monitoring')]
    public function index(MonitoringEntityRepository $monitoringRepository): Response
    {

        $monitoringModules = $monitoringRepository->findAll();

        return $this->render('monitoring/index.html.twig', [
            'controller_name' => 'MonitoringController',
            'monitoringModules' => $monitoringModules,
        ]);
    }

    #[Route('/addModuleMonitoring', name: 'app_add_module_monitoring')]
    public function addModuleMonitoring(Request $request, MonitoringEntityRepository $monitoringRepository, ModuleEntityRepository $moduleRepository, EntityManagerInterface $manager): Response
    {
        if (!$moduleRepository->hasModule()) {
            $this->addFlash('sweetalert_warning', [
                'message' => 'Merci de créer un module avant'
            ]);
            return $this->redirectToRoute('app_monitoring');
        }

        $monitoringModule = new MonitoringEntity();
        $form_monitoring = $this->createForm(MonitoringType::class);
        $form_monitoring->handleRequest($request);

        if ($form_monitoring->isSubmitted() && $form_monitoring->isValid()) {
            try {
                $module = $form_monitoring->get('module')->getData(); 

                
                $existingModule = $moduleRepository->find($module->getIdModule());

                $monitoringModule->setModule($existingModule);
                $manager->persist($monitoringModule);
                $manager->flush();

                $this->addFlash('sweetalert_success', [
                    'message' => 'Le module a été ajouté avec succès au monitoring.'
                ]);

                return $this->redirectToRoute('app_monitoring');
            } catch (\Exception $e) {
                $this->addFlash('sweetalert_error', [
                    'message' => 'Une erreur est survenue lors de l\'ajout du module au monitoring : ' . $e->getMessage()
                ]);
            }
        }

        return $this->render('monitoring/formMonitoring.html.twig', [
            'controller_name' => 'MonitoringController',
            'form_monitoring' => $form_monitoring->createView(),
        ]);
    }

    // Delete Module
    #[Route('/deleteMonitoring/{id}', name: 'app_delete_monitoring')]
    public function deleteMonitoring(int $id, MonitoringEntityRepository $monitoringRepository, EntityManagerInterface $manager): Response
    {
        try {
            $monitoring = $monitoringRepository->find($id);
            if (!$monitoring) {
                $this->addFlash('sweetalert_error', [
                    'message' => 'Monitoring non trouvé.'
                ]);
                return $this->redirectToRoute('app_monitoring');
            }
            $manager->remove($monitoring);
            $manager->flush();
            $this->addFlash('sweetalert_success', [
                'message' => 'Monitoring supprimé avec succès.'
            ]);
            return $this->redirectToRoute('app_monitoring');
        } catch (\Exception $e) {
            $this->addFlash('sweetalert_error', [
                'message' => 'Une erreur est survenue lors de la suppression du monitoring : ' . $e->getMessage()
            ]);
            return $this->redirectToRoute('app_monitoring');
        }
    }

    
}
