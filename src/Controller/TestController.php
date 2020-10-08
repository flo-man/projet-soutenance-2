<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class TestController extends AbstractController
{
    /**
     * @Route ("/")
     */
    public function test()
    {
        return $this->render('base.html.twig');
    }
}