<?php

namespace App\Controller\Admin;


use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Service\Panier\PanierService;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/commandes", name="admin_commandes_")
 * @IsGranted("ROLE_ADMIN")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(Request $request, CommandeRepository $repository, PanierService $panierService, ProduitRepository $produitRepository)
    {
        $panierTotal = $panierService->getTotal($produitRepository);
        return $this->render('admin/commandes/index.html.twig', [
            'commandes' => $repository->findAll(),
            'panierTotal' => $panierTotal,
        ]);
    }

}
