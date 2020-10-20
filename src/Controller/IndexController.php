<?php

namespace App\Controller;


use App\Repository\ProduitRepository;
use App\Repository\TypeRepository;
use App\Service\Panier\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(PanierService $panierService, ProduitRepository $produitRepository)
    {
        $produits = $produitRepository->findAll();
        $panierTotal = $panierService->getTotal($produitRepository);

        return $this->render('index/index.html.twig', [
            'produits' => $produits,
            'panierTotal' => $panierTotal
        ]);
    }

    /**
     * @Route ("/apropos/", name="app_apropos")
     */
    public function apropos(PanierService $panierService, ProduitRepository $produitRepository)
    {
        $panierTotal = $panierService->getTotal($produitRepository);
        return $this->render('index/apropos.html.twig', [
            'panierTotal' => $panierTotal
        ]);
    }

    /**
     * @Route ("/contact/", name="app_contact")
     */
    public function contact(PanierService $panierService, ProduitRepository $produitRepository)
    {
        $panierTotal = $panierService->getTotal($produitRepository);
        return $this->render('index/contact.html.twig', [
            'panierTotal' => $panierTotal
        ]);
    }

    /**
     * @Route ("/boutique", name="app_boutique")
     */
    public function boutique(TypeRepository $typeRepository, ProduitRepository $produitRepository, PanierService $panierService)
    {
        $types = $typeRepository->findAll();
        $produits = $produitRepository->findAll();
        $panierTotal = $panierService->getTotal($produitRepository);

        return $this->render('index/boutique.html.twig', [
            'types' => $types,
            'produits' => $produits,
            'panierTotal' => $panierTotal
        ]);
    }

    /**
     * @Route ("/boutique/{id}", name="app_boutique_id")
     */
    public function type($id, TypeRepository $typeRepository, ProduitRepository $produitRepository, PanierService $panierService)
    {
        $type = $typeRepository->find($id);
        $types = $typeRepository->findAll();
        $produits = $type->getProduits();
        $panierTotal = $panierService->getTotal($produitRepository);

        return $this->render('index/boutique.html.twig', [
            'types' => $types,
            'produits' => $produits,
            'panierTotal' => $panierTotal
        ]);
    }

    /**
     * @Route ("/nouveau", name="app_nouveau")
     */
    public function nouveau(ProduitRepository $produitRepository, PanierService $panierService)
    {
        $produits = $produitRepository->findAll();
        $panierTotal = $panierService->getTotal($produitRepository);
        return $this->render('index/nouveau.html.twig', [
            'produits' => $produits,
            'panierTotal' => $panierTotal
        ]);

    }

    /**
     * @Route("/recherche")
     */
    public function recherche(Request $request, ProduitRepository $repo)
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        $donnees = $repo->findAll();

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $title = $searchForm->getData()->getTitle();

            $donnees = $repo->search($title);

            if ($donnees == null) {
                $this->addFlash('erreur', 'Aucun article contenant ce mot clé dans le titre n\'a été trouvé, essayez en un autre.');

            }
        }
    }

    /**
     * @Route ("/cgv")
     */
    public function CGV(PanierService $panierService, ProduitRepository $produitRepository)
    {
        $panierTotal = $panierService->getTotal($produitRepository);
        return $this->render('index/cgv.html.twig', [
            'panierTotal' => $panierTotal
        ]);
    }


}
