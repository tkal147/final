<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RoomController extends AbstractController
{
    #[Route('/room', name: 'room_index')]
    public function roomIndex()
    {
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();
        return $this->render(
            'room/index.html.twig',
            [
                'rooms' => $rooms
            ]
        );
    }

    #[Route('/room/detail/{id}', name: 'room_detail')]
    public function ViewDetail($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);

        return $this->render(
            'room/detail.html.twig',
            [
                'room' => $room
            ]
        );
    }

    #[Route('/room/delete/{id}', name: 'room_delete')]
    public function roomDelete($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if ($room == null) {
            $this->addFlash('Error', 'Room is not existed');
        } else
        {
            try{
                $manager = $this->getDoctrine()->getManager();
                $manager->remove($room);
                $manager->flush();
                $this->addFlash('Success', 'Room has been deleted successfully !');
            } catch(ForeignKeyConstraintViolationException $e){
                $this -> addFlash('Error', 'The room contains class');
            }
            return $this->redirectToRoute("room_index");
           
        }
        return $this->redirectToRoute('room_index');
    }

    #[Route('/room/add', name: 'room_add')]
    public function AddCourse(Request $r)
    {
        try{
            $room = new Room();
            $form = $this->createForm(RoomType::class, $room);
            $form->handleRequest($r);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($room);
                $manager->flush();
                $this->addFlash('Success', "Add new room successfully");
                return $this->redirectToRoute("room_index");
            }
                else {
                    return $this->render(
                        '/room/add.html.twig',
                        [
                            "form" => $form->createView()
                        ]
                        ); 
                }
        }
        catch(UniqueConstraintViolationException $r){
            $this -> addFlash('Error', 'The room already have class');
        }
        return $this->redirectToRoute("room_index");
    }

    #[Route('/room/edit/{id}', name: 'room_edit')]
    public function EditCourse(Request $r, int $id)
    {
        try{
            $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
            $form = $this->createForm(RoomType::class, $room);
            $form->handleRequest($r);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($room);
                $manager->flush();
                $this->addFlash('Success', "Edit room successfully");
                return $this->redirectToRoute("room_index");
            }
            return $this->render(
                '/room/edit.html.twig',
                [
                    "form" => $form->createView()
                ]
            );
        }catch(UniqueConstraintViolationException $r){
            $this -> addFlash('Error', 'The room already have class');
        }
        return $this->redirectToRoute("room_index");
 
    }
}
