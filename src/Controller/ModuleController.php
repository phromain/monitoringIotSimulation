<?php

namespace App\Controller;

use App\Form\ModuleType;
use App\Entity\ModuleEntity;
use App\Service\ModuleService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UniteEntityRepository;
use App\Repository\ModuleEntityRepository;
use App\Repository\ReleveEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    private $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }


    // List Modules
#[Route('/listModule', name: 'app_module')]
public function listModules(ModuleEntityRepository $moduleRepository): Response
{
    $listModules = $this->moduleService->getAllModules();
    return $this->render('module/index.html.twig', [
        'controller_name' => 'ModuleController',
        'listModules' => $listModules
    ]);
}

// View Module
#[Route('/viewModule/{id}', name: 'app_view_module')]
public function viewModule(int $id, ModuleEntityRepository $moduleRepository, ReleveEntityRepository $releveRepository, UniteEntityRepository $uniteRepository): Response
{
    try {
        $module = $moduleRepository->find($id);
        if (!$module) {
            $this->addFlash('sweetalert_error', [
                'message' => 'Module non trouvé.'
            ]);
            return $this->redirectToRoute('app_module');
        
        }

        $releves = $releveRepository->findByModuleId($id);

        // Trier les relevés par date en ordre croissant
        usort($releves, function($a, $b) {
        return $a->getDate() <=> $b->getDate();
        });

        // Info pour Graph
        $labels = array_map(fn($releve) => $releve->getDate()->format('H:i'), $releves);
        $dataValues = array_map(fn($releve) => $releve->getValeur(), $releves);

        $listModules = $this->moduleService->getAllModules();

        return $this->render('module/viewModule.html.twig', [
            'controller_name' => 'ModuleController',
            'listModules' => $listModules,
            'module' => $module,
            'releves'=> $releves,
            'chartLabels' => json_encode($labels),
            'chartData' => json_encode($dataValues)
        ]);
    } catch (\Exception $e) {
        $this->addFlash('sweetalert_error', [
            'message' => 'Une erreur est survenue lors de la récupération du module : ' . $e->getMessage()
        ]);
        return $this->redirectToRoute('app_module');
    }
}


    // Add Module
#[Route('/addModule', name: 'app_add_module')]
public function addModule(Request $request, EntityManagerInterface $manager, UniteEntityRepository $uniteRepository): Response
{

    if (!$uniteRepository->hasUnite()) {
        $this->addFlash('sweetalert_warning', [
            'message' => 'Merci de créer une unité / mesure avant la création d\'un module'
            ]);
        return $this->redirectToRoute('app_module');
    }

    $newModule = new ModuleEntity();
    $form_module = $this->createForm(ModuleType::class, $newModule);
    $form_module->handleRequest($request);

    if ($form_module->isSubmitted() && $form_module->isValid()) {
        try {
            $data = $form_module->getData();
            $manager->persist($data);
            $manager->flush();

            $this->addFlash('sweetalert_success', [
                'message' => 'Le module a été ajouté avec succès.'
            ]);

            return $this->redirectToRoute('app_module');
        } catch (\Exception $e) {
            $this->addFlash('sweetalert_error', [
                'message' => 'Une erreur est survenue lors de l\'ajout du module : ' . $e->getMessage()
            ]);
        }
    }
    $listModules = $this->moduleService->getAllModules();

    return $this->render('module/formModule.html.twig', [
        'form_module' => $form_module->createView(),
        'is_edit' => false,
        'listModules' => $listModules
    ]);
}

// Edit Module
#[Route('/editModule/{id}', name: 'app_edit_module')]
public function editModule(int $id, Request $request, ModuleEntityRepository $moduleRepository, EntityManagerInterface $manager): Response
{
    $module = $moduleRepository->find($id);
    if (!$module) {
        $this->addFlash('sweetalert_error', [
            'message' => 'Module non trouvé.'
        ]);
        return $this->redirectToRoute('app_module');
    }

    $form_module = $this->createForm(ModuleType::class, $module);
    $form_module->handleRequest($request);

    if ($form_module->isSubmitted() && $form_module->isValid()) {
        try {
            $manager->flush();
            $this->addFlash('sweetalert_success', [
                'message' => 'Module modifié avec succès.'
            ]);
            return $this->redirectToRoute('app_module');
        } catch (\Exception $e) {
            $this->addFlash('sweetalert_error', [
                'message' => 'Une erreur est survenue lors de la modification du module : ' . $e->getMessage()
            ]);
        }
    }
    $listModules = $this->moduleService->getAllModules();

    return $this->render('module/formModule.html.twig', [
        'form_module' => $form_module->createView(),
        'is_edit' => true,
        'listModules' => $listModules

    ]);
}

// Delete Module
#[Route('/deleteModule/{id}', name: 'app_delete_module')]
public function deleteModule(int $id, ModuleEntityRepository $moduleRepository, EntityManagerInterface $manager): Response
{
    try {
        $module = $moduleRepository->find($id);
        if (!$module) {
            $this->addFlash('sweetalert_error', [
                'message' => 'Module non trouvé.'
            ]);
            return $this->redirectToRoute('app_module');
        }
        $manager->remove($module);
        $manager->flush();
        $this->addFlash('sweetalert_success', [
            'message' => 'Module supprimé avec succès.'
        ]);
        return $this->redirectToRoute('app_module');
    } catch (\Exception $e) {
        $this->addFlash('sweetalert_error', [
            'message' => 'Une erreur est survenue lors de la suppression du module : ' . $e->getMessage()
        ]);
        return $this->redirectToRoute('app_module');
    }
}



}
