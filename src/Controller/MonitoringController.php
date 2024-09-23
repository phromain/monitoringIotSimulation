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
    public function index(MonitoringEntityRepository $monitoringRepository, ReleveEntityRepository $releveRepository): Response
    {
        $monitoringModules = $monitoringRepository->findAll();

        // Récupérer les informations des modules associés
        $modulesInfo = [];
        foreach ($monitoringModules as $monitoringModule) {
            $module = $monitoringModule->getModule();
            $releves = $releveRepository->findByModuleId($module->getIdModule()); // Utiliser findByModuleId

            usort($releves, function($a, $b) {
                return $a->getDate() <=> $b->getDate();
            });

            $labels = array_map(fn($releve) => $releve->getDate()->format('H:i'), $releves);
            $dataValues = array_map(fn($releve) => $releve->getValeur(), $releves);

            $modulesInfo[] = [
                'module' => $module,
                'releves' => $releves,
                'chartLabels' => json_encode($labels),
                'chartData' => json_encode($dataValues)
            ];
        }
        return $this->render('monitoring/index.html.twig', [
            'controller_name' => 'MonitoringController',
            'monitoringModules' => $monitoringModules,
            'modulesInfo' => $modulesInfo
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

                $existingMonitoringModule = $monitoringRepository->findOneBy(['module' => $module]);
                if ($existingMonitoringModule) {
                    $this->addFlash('sweetalert_warning', [
                        'message' => 'Module déjà monitoré.'
                    ]);
                    return $this->render('monitoring/formMonitoring.html.twig', [
                        'controller_name' => 'MonitoringController',
                        'form_monitoring' => $form_monitoring->createView(),
                    ]);
                }
                
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
            $monitoring = $monitoringRepository->findOneBy(['module' => $id]);
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
