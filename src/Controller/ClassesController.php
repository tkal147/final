<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Form\ClassesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class ClassesController extends AbstractController
{
    #[Route('/classes', name: 'classes_index')]
    public function index(): Response
    {
        $classes = $this->getDoctrine()->getRepository(Classes::class)->findAll();

        return $this->render(
            'classes/index.html.twig',
            [
                'classes' => $classes
            ]
        );
    }

    #[Route('/classes/detail/{id}', name: 'classes_detail')]
    public function ViewDetail($id)
    {
        $classes = $this->getDoctrine()->getRepository(Classes::class)->find($id);

        return $this->render(
            'classes/detail.html.twig',
            [
                'class' => $classes
            ]
        );
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/classes/delete/{id}', name: 'classes_delete')]
    public function courseDelete($id)
    {
        $classes = $this->getDoctrine()->getRepository(Classes::class)->find($id);
        if ($classes == null) {
            $this->addFlash('Error', 'Classes is not existed');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($classes);
            $manager->flush();
            $this->addFlash('Success', 'Classes has been deleted successfully !');
        }
        return $this->redirectToRoute('classes_index');
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/classes/add', name: 'classes_add')]
    public function AddCourse(Request $r)
    {
        $classes = new Classes();
        $form = $this->createForm(ClassesType::class, $classes);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($classes);
            $manager->flush();
            $this->addFlash('Success', "Add new classes successfully");
            return $this->redirectToRoute("classes_index");
        }
        return $this->render(
            '/classes/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/classes/edit/{id}', name: 'classes_edit')]
    public function EditCourse(Request $r, int $id)
    {
        $classes = $this->getDoctrine()->getRepository(Classes::class)->find($id);
        $form = $this->createForm(ClassesType::class, $classes);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($classes);
            $manager->flush();
            $this->addFlash('Success', "Update classes successfully");
            return $this->redirectToRoute("classes_index");
        }
        return $this->render(
            '/classes/edit.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
}
