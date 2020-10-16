<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/commandes", name="admin_commandes_")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index()
    {
        return $this->render('admin/commandes/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
