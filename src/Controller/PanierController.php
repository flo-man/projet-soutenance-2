<?php


namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use App\Service\Panier\PanierService;
use Cassandra\Date;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


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
    public function add(int $id, PanierService $panierService, ProduitRepository $produitRepository)
    {
        $panierTotal = $panierService->getTotal($produitRepository);
        $panierService->add($id);
        return $this->redirectToRoute("affichage_panier", [
            "panierService" => $panierService,
            'panierTotal' => $panierTotal
        ]);
    }

    /**
     * @Route("/panier-remove/{id}", name="panier_remove")
     */
    public function remove(int $id, PanierService $panierService, ProduitRepository $produitRepository)
    {
        $panierService->remove($id);
        $panierTotal = $panierService->getTotal($produitRepository);

        return $this->redirectToRoute("affichage_panier", [
            "panierService" => $panierService,
            'panierTotal' => $panierTotal
        ]);
    }


    /**
     * @Route("/panier", name="affichage_panier")
     */
    public function affichagePanier(ProduitRepository $produitRepository, PanierService $panierService)
    {

        $panierTotal = $panierService->getTotal($produitRepository);
        return $this->render('panier/index.html.twig', [
            'panierService' => $panierService->getFullPanier($produitRepository),
            'cartTotal' => $panierService->getTotal($produitRepository),
            'panierTotal' => $panierTotal
        ]);
    }

    /**
     * @Route("/panier-commande-valider", name="commande_valider")
     */
    public function commandeValider(PanierService $panierService, EntityManagerInterface $em, UserInterface $user, ProduitRepository $produitRepository)
    {
        // Créer une nouvelle commande
        $commande = new Commande();

        // Ajouter les produits du panier
        foreach ($panierService->getFullPanier($produitRepository) as $ligne) {
            $commande->addProduit($ligne['produit'])->setQuantite($ligne['quantite']);
        }

        // Ajouter les détails de la commmande
        $commande->setDate($date = date_create());
        $commande->setClient($user);
        $commande->setFacture($panierService->getTotal($produitRepository));

        $em->persist($commande);

        $em->flush();

        // Vider le panier une fois la commande passée


        // Rediriger à l'accueil et confirmer la commande
        $this->addFlash('success', 'Votre commande a été envoyée');
        return $this->redirectToRoute('index');
    }
}



