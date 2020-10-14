<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ConfirmationType;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/produit", name="admin_produit_")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(ProduitRepository $repository)
    {
        return $this->render('admin/produit/index.html.twig', [
            'produits' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProduitFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $produit = $form->getData();

            $destination = $this->getParameter("dossier_images");

            if($photoTelechargee = $form->get("photo")->getData()){

                $nomPhoto = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);

                $nouveauNom = '';

                $nouveauNom = str_replace(" ", "_", $nouveauNom);

                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();

                $photoTelechargee->move($destination, $nouveauNom);

                $produit->setPhoto($nouveauNom);

                $em->persist($produit);

                $em->flush();

                $this->addFlash('success', 'le produit a été créé');
                return $this->redirectToRoute('admin_produit_edit', [
                    'id' => $produit->getId()
                ]);
            }
        }

        return $this->render('admin/produit/add.html.twig', [
            'produit_form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/edit/{id}", name="edit")
     */
    public function edit(Produit $produit, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(ProduitFormType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Produit mis à jour' );
        }

        return $this->render('admin/produit/edit.html.twig', [
            'produit' => $produit,
            'produit_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="delete")
     */
    public function delete(Produit $produit, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($produit);
            $em->flush();

            $this->addFlash('info', 'le produit ' . $produit->getNom() . ' a bien été supprimé.');
            return $this->redirectToRoute('admin_produit_list');
        }

        return $this->render('admin/produit/delete.html.twig', [
            'delete_form' => $form->createView(),
            'produit' => $produit
        ]);
    }
}
