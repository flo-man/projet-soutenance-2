<?php

namespace App\Service\Panier;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService {

    protected $session;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->session = $sessionInterface;
    }

    public function add($id) {
        $panier=$this->session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }
        $this->session->set('panier', $panier);}

    public function remove($id)
    {
        $panier=$this->session->get('panier', []);

        if($panier[$id] > 1){
            $panier[$id]--;
        }else {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);}

    public function delete($id)
    {
        $panier=$this->session->get('panier', []);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
    }

    public function getFullPanier(ProduitRepository $produitRepository) : array
    {
        $panier = $this->session->get('panier', []);

        $panierDetail=[];
        foreach ($panier as $id => $quantite){
            if ($quantite < 1){ $quantite = 0;}
            $panierDetail[]=[
                'produit'=>$produitRepository->find($id),
                'quantite'=>$quantite
            ];
        }
        return $panierDetail;
    }

    public function getEmptyPanier()
    {
        $panier = $this->session->get('panier', []);
        unset($panier);

        return "Commande ok!";

    }


    public function getTotal(ProduitRepository $produitRepository) : float
    {
        $total=0;

        foreach ($this->getFullPanier($produitRepository) as $item){

            $total += $item['produit']->getPrix() * $item['quantite'];
        }
        return $total;
    }



        //public function getFullcart() : array {}

        //public function getTotal() : float {}
}
