<?php


namespace App\Controller;


use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\TypeRepository;
use PhpParser\Node\Expr\Empty_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{

     /**
     * @Route ("/search", name="app_search")
     */
     public function search(Request $request, ProduitRepository $produitRepository)
     {

         $motcle = $request->get('motcle');

         $liste = [];
         if(!empty($motcle) && $request->isMethod('POST')) {
             $liste = $produitRepository->findBySearch($motcle);

             if (empty($liste)){

                 $this->addFlash('danger', 'Aucun produit ne correspond.');
             }
         } else {
             $this->addFlash('danger', 'Veuiilez taper un mot.');
         }

         return $this->render('search/recherche.html.twig', [
             'produit' => $motcle,
             'donnees' => $liste
         ]);



         /*
              public function search(Request $request, TypeRepository $repo): Response
         {
                  $searchType = $this->createForm(SearchType::class);
                  $searchType->handleRequest($request);

                  $donnees = $repo->findAll();
                  //dd($donnees);

                  if ($searchType->isSubmitted() && $searchType->isValid()) {

                      $produit = $searchType->getData()->getProduit();

                      $donnees = $repo->search($produit);

                      if ($donnees == null) {
                          $this->addFlash('erreur', 'Ce produit n\'existe pas, essayez en un autre.');

                      }
                  }

         $donnees = $request->query->get('produit');
         return $this->render('search/recherche.html.twig', [
             'produit' => $donnees,
             //'donnees' => $donnees->createView()
         ]);
         */

     }


}

