<?php


namespace App\Controller\Admin;


use App\Entity\Type;
use App\Form\TypeFormType;
use App\Form\ConfirmationType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TypeController
 * @package App\Controller\Admin
 *
 * @Route("/admin/type")
 * @IsGranted("ROLE_ADMIN")
 */
class TypeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findAll();

        return $this->render('admin/type/index.html.twig',
            [
                'types' => $types
            ]
        );
    }

    /**
     * @Route("/edit/{id}", defaults={"id": null})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        TypeRepository $typeRepository,
        $id
    ) {
        if (is_null($id)) {
            $type = new Type();
        } else {
            $type = $typeRepository->find($id);
        }

        $form = $this->createForm(TypeFormType::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                $entityManager->persist($type);

                $entityManager->flush();

                $this->addFlash('success', 'Le type a été enregistrée');

                return $this->redirectToRoute('app_admin_type_index');
            }
        }

        return $this->render('admin/type/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/suppression/{id}", name="admin_type_delete")
     */
    public function delete(Type $type, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($type);
            $em->flush();

            $this->addFlash('info', 'le type ' . $type->getNom() . ' a bien été supprimée.');
            return $this->redirectToRoute('app_admin_type_index');
        }

        return $this->render('admin/type/delete.html.twig', [
            'delete_form' => $form->createView(),
            'category' => $type
        ]);
    }
}