<?php

namespace App\Controller;

use App\Form\UniteType;
use App\Entity\UniteEntity;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UniteEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UniteController extends AbstractController
{

// List Unites / Mesures
    #[Route('/unite', name: 'app_unite')]
    public function listUnites(UniteEntityRepository $uniteRepository): Response
    {
        $listUnites = $uniteRepository->findAll();
        //dd($listUnites);
        return $this->render('unite/index.html.twig', [
            'controller_name' => 'UniteController',
            'listUnites'=> $listUnites
        ]);
    }

// Add Unite / Mesure
#[Route('/unite/add', name: 'app_add_unite')]
public function addUnite(Request $request, EntityManagerInterface $manager): Response
{
    $newUnite = new UniteEntity();
    $form_unite = $this->createForm(UniteType::class, $newUnite);
    $form_unite->handleRequest($request);

    if ($form_unite->isSubmitted() && $form_unite->isValid()) {
        try {
            $data = $form_unite->getData();
            $manager->persist($data);
            $manager->flush();

            $this->addFlash('sweetalert_success', [
                'message' => 'L\'unité a été ajoutée avec succès.'
            ]);

            return $this->redirectToRoute('app_unite');
        } catch (\Exception $e) {
            $this->addFlash('sweetalert_error', [
                'message' => 'Une erreur est survenue lors de l\'ajout de l\'unité : ' . $e->getMessage()
            ]);
        }
    }

    return $this->render('unite/formUnite.html.twig', [
        'form_unite' => $form_unite->createView(),
        'is_edit' => false,
        'button' => true,
        'time' => true
    ]);
}

// Edit Unite / Mesure
#[Route('/unite/edit/{id}', name: 'app_edit_unite')]
public function editUnite(int $id, Request $request, UniteEntityRepository $uniteRepository, EntityManagerInterface $manager): Response
{
    $unite = $uniteRepository->find($id);
    if (!$unite) {
        $this->addFlash('sweetalert_error', [
            'message' => 'Unité non trouvée.'
        ]);
        return $this->redirectToRoute('app_unite');
    }

    $form_unite = $this->createForm(UniteType::class, $unite);
    $form_unite->handleRequest($request);

    if ($form_unite->isSubmitted() && $form_unite->isValid()) {
        try {
            $manager->flush();
            $this->addFlash('sweetalert_success', [
                'message' => 'Unité modifiée avec succès.'
            ]);
            return $this->redirectToRoute('app_unite');
        } catch (\Exception $e) {
            $this->addFlash('sweetalert_error', [
                'message' => 'Une erreur est survenue lors de la modification de l\'unité : ' . $e->getMessage()
            ]);
        }
    }

    return $this->render('unite/formUnite.html.twig', [
        'form_unite' => $form_unite->createView(),
        'is_edit' => true,
    ]);
}



    // Delete Unite / Mesure
#[Route('/unite/delete/{id}', name: 'app_delete_unite')]
public function deleteUnite(int $id, UniteEntityRepository $uniteRepository, EntityManagerInterface $manager): Response
{
    try {
        $unite = $uniteRepository->find($id);
        if (!$unite) {
            $this->addFlash('sweetalert_error', [
                'message' => 'Unité non trouvée.'
            ]);
            return $this->redirectToRoute('app_unite');
        }
        $manager->remove($unite);
        $manager->flush();
        $this->addFlash('sweetalert_success', [
            'message' => 'Unité supprimée avec succès.'
        ]);
        return $this->redirectToRoute('app_unite');
    } catch (\Exception $e) {
        $this->addFlash('sweetalert_error', [
            'message' => 'Une erreur est survenue lors de la suppression de l\'unité : ' . $e->getMessage()
        ]);
        return $this->redirectToRoute('app_unite');
    }
}

}
    


