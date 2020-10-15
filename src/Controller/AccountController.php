<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProduitFormType;
use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route ("/account", name="account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="user_account")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $email = $this->getUser()->getEmail();

        $form = $this->createForm(UserProfileFormType::class, $this->getUser());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('plainPassword')->getData();

            if($password !== null) {
                $hash = $userPasswordEncoder->encodePassword($this->getUser(), $password);
                $this->getUser()->setPassword($hash);
            }

            $em->flush();
            $this->addFlash('success', 'Vos informations sont à jour.');

        } else {
            $this->getUser()->setEmail($email);
        }

        return $this->render('account/index.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/inscription", name="user_inscription")
     */
    public function inscription(UserProfileFormType $userProfileFormType, Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $form = $this->createForm(UserProfileFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userProfileFormType = $form->getData();

            $password = $form->get('plainPassword')->getData();

            if($password !== null) {
                $hash = $userPasswordEncoder->encodePassword($userProfileFormType, $password);
                $userProfileFormType->setPassword($hash);
            }

            $em->persist($userProfileFormType);

            $em->flush();

            $this->addFlash('success', 'Votre compte a été créé');

            return $this->redirectToRoute('index');
        }

        return $this->render('account/inscription.html.twig', [
            'user_form' => $form->createView()
        ]);
    }
}
