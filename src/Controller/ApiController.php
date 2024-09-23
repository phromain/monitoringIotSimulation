<?php

namespace App\Controller;

use DateTime;
use App\Entity\ReleveEntity;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ModuleEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    // Ajoute les données générer aux modules
    #[Route('/api/add-data', name: 'app_api_add_data',methods: ['POST'])]
    public function saveData(Request $request, EntityManagerInterface $manager, ModuleEntityRepository $moduleRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $item) {
            $module = $moduleRepository->find($item['id_module']);
            if ($module) {
                $releve = new ReleveEntity();
                $releve->setModule($module);
                $releve->setValeur($item['valeur']);
                $releve->setEtat($item['etat']);
                $releve->setDate(DateTime::createFromFormat('Y-m-d H:i:s', $item['date']));

                $manager->persist($releve);
            }
        }

        $manager->flush();

        return new JsonResponse('Data saved successfully', 200);
    }
    

    //Crée une table de modules avec leurs id
    #[Route('/api/get-modules', name: 'app_api_get_data',methods: ['GET'])]
    public function getModules(ModuleEntityRepository $moduleRepository): JsonResponse
    {
        $modules = $moduleRepository->findAll();
        $moduleData = [];

        foreach ($modules as $module) {
            $moduleData[] = [
                'id_module' => $module->getIdModule()
            ];
        }

        return new JsonResponse($moduleData);
    }
    
}
