<?php


namespace App\Controller;

use App\Entity\Produit;
use App\Form\CommandeType;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use App\Service\Panier\PanierService;
use phpDocumentor\Reflection\Types\AbstractList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route ("/")
 */
class PanierController extends AbstractController
{
    protected $session;
    protected $produitRepository;

    public function __construct(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $this->session=$session;
        $this->produitRepository=$produitRepository;
    }

    /**
     * @Route("/panier/{id}", name="panier_ajout")
     */
    public function add(int $id, PanierService $panierService)
    {
        $panierService->add($id);
        return $this->redirectToRoute("affichage_panier", [
            "panierService" => $panierService
        ]);
    }

    /**
     * @Route("/panier-remove/{id}", name="panier_remove")
     */
    public function remove(int $id, PanierService $panierService)
    {
        $panierService->remove($id);

        return $this->redirectToRoute("affichage_panier", [
            "panierService" => $panierService
        ]);
    }


    /**
     * @Route("/panier", name="affichage_panier")
     */
    public function affichagePanier(ProduitRepository $produitRepository, PanierService $panierService)
    {

        return $this->render('panier/index.html.twig', [
            'panierService' => $panierService->getFullPanier($produitRepository),
            'cartTotal' => $panierService->getTotal($produitRepository)
        ]);
    }

    /**
     * @Route("/panier-commande-valider/{id}", name="commande_valider")
     */
    public function commandeValider(Request $request, PanierService $panierService)
    {

        $form = $this->createForm(CommandeType::class);

        $form->handleRequest($request);

        $this->addFlash('info', 'Commande validée');
    }
}



