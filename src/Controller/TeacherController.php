<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use function PHPUnit\Framework\throwException;

/**
 * @IsGranted("ROLE_USER")
 */
class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'teacher_index')]
    public function index(): Response
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->findAll();

        return $this->render(
            'teacher/index.html.twig',
            [
                'teacher' => $teacher
            ]
        );
    }

    #[Route('/teacher/detail/{id}', name: 'teacher_detail')]
    public function ViewDetail($id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);

        return $this->render(
            'teacher/detail.html.twig',
            [
                'teacher' => $teacher
            ]
        );
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/delete/{id}', name: 'teacher_delete')]
    public function courseDelete($id)
    {
        $course = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($course == null) {
            $this->addFlash('Error', 'Teacher is not existed');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($course);
            $manager->flush();
            $this->addFlash('Success', 'Teacher has been deleted successfully !');
        }
        return $this->redirectToRoute('student_index');
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/add', name: 'teacher_add')]
    public function AddCourse(Request $r)
    {
        $course = new Teacher();
        $form = $this->createForm(TeacherType::class, $course);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $course->getAvatar();
            //B2: Dat ten moi cho anh
            $imgName = uniqid();
            //B3: lay duoi anh
            $imgExtension = $avatar->guessExtension();
            //B4: noi ten va duoi tao thanh 1 file anh hoan chinh
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuyen vao thu muc chi dinh
            
            try {
                $avatar->move(
                    $this->getParameter('teacher_avatar'),
                    $imageName
                );
            } catch (FileException $e) {
                throwException($e);
            }
            $course->setAvatar($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('Success', "Add new teacher successfully");
            return $this->redirectToRoute("teacher_index");
        }
        return $this->render(
            '/teacher/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/edit/{id}', name: 'teacher_edit')]
    public function EditCourse(Request $r, int $id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $teacher->getAvatar();
            //B2: Dat ten moi cho anh
            $imgName = uniqid();
            //B3: lay duoi anh
            $imgExtension = $avatar->guessExtension();
            //B4: noi ten va duoi tao thanh 1 file anh hoan chinh
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuyen vao thu muc chi dinh
            
            try {
                $avatar->move(
                    $this->getParameter('teacher_avatar'),
                    $imageName
                );
            } catch (FileException $e) {
                throwException($e);
            }
            $teacher->setAvatar($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($teacher);
            $manager->flush();
            $this->addFlash('Success', "Edit new teacher successfully");
            return $this->redirectToRoute("teacher_index");
        }
        return $this->render(
            '/teacher/edit.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
}
