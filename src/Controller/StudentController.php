<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */
class StudentController extends AbstractController
{
    #[Route('/student', name: 'student_index')]
    public function studentIndex()
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render(
            'student/index.html.twig',
            [
                'students' => $students
            ]
        );
    }




    #[Route('/student/detail/{id}', name: 'student_detail')]
    public function studentDetail($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('Error', 'student is not existed');
            return $this->redirectToRoute('student_index');
        } else {  
            return $this->render(
                'student/detail.html.twig',
                [
                    'student' => $student
                ]
            );
        }
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/student/delete/{id}', name: 'student_delete')]
    public function studentDelete($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('Error', 'Student is not existed');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($student);
            $manager->flush();
            $this->addFlash('Success', 'Student has been deleted successfully !');
        }
        return $this->redirectToRoute('student_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/student/add', name: 'student_add')]
    public function studentAdd(Request $request)
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //code xu ly up anh
            //B1: lay anh tu file
            $avatar = $student->getAvatar();
            //B2: Dat ten moi cho anh
            $imgName = uniqid();
            //B3: lay duoi anh
            $imgExtension = $avatar->guessExtension();
            //B4: noi ten va duoi tao thanh 1 file anh hoan chinh
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuyen vao thu muc chi dinh
            try {
                $avatar->move(
                    $this->getParameter('student_avatar'),
                    $imageName
                );
            } catch (FileException $e) {
                throwException($e);
            }
            //B6: luu ten anh vao db
            $student->setAvatar($imageName);

            //day du lieu len db
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($student);
            $manager->flush();

            $this->addFlash('Success', "Add new student successfully");
            return $this->redirectToRoute("student_index");
        }
        return $this->render(
            "student/add.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/student/edit/{id}', name: 'student_edit')]
    public function studentEdit(Request $request, $id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //code xu ly up anh
            //B1: lay du lieu anh tu form
            $file = $form['avatar']->getData();
            //B2: check xem cos null khong
            if ($file != null) {
                //B3: lay anh tu file
                $image = $student->getAvatar();
                //B4: Dat ten moi cho anh
                $imgName = uniqid();
                //B5: lay duoi anh
                $imgExtension = $image->guessExtension();
                //B6: noi ten va duoi tao thanh 1 file anh hoan chinh
                $imageName = $imgName . "." . $imgExtension;
                //B7: di chuyen vao thu muc chi dinh
                try {
                    $image->move(
                        $this->getParameter('student_avatar'),
                        $imageName
                    );
                } catch (FileException $e) {
                    throwException($e);
                }
                //B8: luu ten anh vao db
                $student->setAvatar($imageName);
            }


            //day du lieu len db
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($student);
            $manager->flush();

            $this->addFlash('Success', "Edit student successfully");
            return $this->redirectToRoute("student_index");
        }
        return $this->render(
            "student/edit.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }
}
