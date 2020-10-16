<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\TypeRepository;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
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

        return $this->render('index/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route ("/apropos/", name="app_apropos")
     */
    public function apropos()
    {
        return $this->render('index/apropos.html.twig');
    }

    /**
     * @Route ("/contact/", name="app_contact")
     */
    public function contact()
    {
        return $this->render('index/contact.html.twig');
    }

    /**
     * @Route ("/boutique", name="app_boutique")
     */
    public function boutique(TypeRepository $typeRepository, ProduitRepository $produitRepository)
    {
        $types = $typeRepository->findAll();
        $produits = $produitRepository->findAll(); 

        return $this->render('index/boutique.html.twig', [
            'types' => $types,
            'produits' => $produits
        ]);
    }

    /**
     * @Route ("/boutique/{id}", name="app_boutique_id")
     */
    public function type($id, TypeRepository $typeRepository, ProduitRepository $produitRepository)
    {
        $type = $typeRepository->find($id);
        $types = $typeRepository->findAll();
        $produits = $type->getProduits();

        return $this->render('index/boutique.html.twig', [
            'types' => $types,
            'produits' => $produits
        ]);
    }

    /**
     * @Route ("/nouveau", name="app_nouveau")
     */
    public function nouveau(ProduitRepository $produitRepository)
    {
        $produits = $produitRepository->findAll();
        return $this->render('index/nouveau.html.twig', [
            'produits' => $produits
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


}
