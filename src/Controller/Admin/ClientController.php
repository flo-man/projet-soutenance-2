<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use App\Service\Panier\PanierService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/admin/client", name="client_liste")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ProduitRepository $produitRepository, PanierService $panierService, UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        $panierTotal = $panierService->getTotal($produitRepository);

        return $this->render('admin/client/index.html.twig', [
            'controller_name' => 'ClientController',
            'panierTotal' => $panierTotal,
            'users' => $users
        ]);
    }
}
