<?php

namespace App\Controller\Admin;


use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Service\Panier\PanierService;
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

    /**
     * @Route("/new", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em, PanierService $panierService, ProduitRepository $produitRepository)
    {
        $panierTotal = $panierService->getTotal($produitRepository);
        $commande = new Commande();
        $form = $this->createFormBuilder($commande)

            ->add('date', DateTimeType::class)
            ->add('client', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom'
            ])
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'attr' => ['class' => 'quantite', TextType::class],
                'multiple' => true,
                'choice_label' => 'nom'
            ])
            ->add('facture', MoneyType::class)
            ->add('envoyer', SubmitType::class)
            ->getForm();



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($commande);

            $em->persist($commande);

            $em->flush();

            $this->addFlash('success', 'la commande a été créée');
            return $this->redirectToRoute('admin_commandes_list');
        }

        return $this->render('admin/commandes/add.html.twig', [
            'commandes_form' => $form->createView(),
            'panierTotal' => $panierTotal
        ]);

    }

}
