<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(Request $request,ProduitRepository $produitRepository): Response
    {$searchTerm = $request->query->get('search', '');
        if ($searchTerm) {
            $produits = $produitRepository->createQueryBuilder('p')
                ->where('p.nom LIKE :search OR p.description LIKE :search')
                ->setParameter('search', '%'.$searchTerm.'%')
                ->getQuery()
                ->getResult();
        } else {
            $produits = $produitRepository->findAll(); // If no search, show all products
        }

        return $this->render('produit/index.html.twig', [
           // 'produits' => $produitRepository->findAll(),
           'produits' => $produits,
        'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit ajouté');

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);

    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/patient', name: 'app_produit_patient', methods: ['GET'])]
    public function patientIndex(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/patientp.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * Route pour la recherche avancée avec AJAX
     */
    #[Route('/produit/recherche', name: 'app_produit_search', methods: ['GET'])]
public function search(Request $request, ProduitRepository $produitRepository): Response
{
    $nom = $request->query->get('nom'); // Récupérer la valeur saisie
    $produits = $produitRepository->findByNom($nom); // Appeler une méthode de recherche

    return $this->render('produit/index.html.twig', [
        'produits' => $produits,
    ]);
}

}
