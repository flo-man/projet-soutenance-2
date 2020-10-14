<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index/index.html.twig');
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

}
