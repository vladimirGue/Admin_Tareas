<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(): Response
    {
        //prueba de entidades y relaciones
        $manager = $this->getDoctrine()->getManager();
        $task_repo = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $task_repo->findBy([],['id' => 'desc']);

        /*$user_repo = $this->getDoctrine()->getRepository(User::class);
        $users = $user_repo->findAll();

        foreach ($users as $user) {
            echo '<h1>'.$user->getName().'</h1><br>';
            foreach ($user->getTasks() as $task) {
                echo $task->getTitle().'<br>';
            }
        }*/

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
    public function detail(Task $task){
        if (!$task) {
            return $this->redirectToRoute('task');
        }
        return $this->render('task/detail.html.twig',[
            'task' => $task,
        ]);
    }
    public function creation(Request $request, UserInterface $userInterface){
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new \DateTime(null, new \DateTimeZone('America/Mexico_City')));
            //datos del usuario conectado
            $task->setUser($userInterface);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($task);
            $manager->flush();
            return $this->redirect(
                $this->generateUrl('task_detail', ['id' => $task->getId()]));
        }

        return $this->render('task/creation.html.twig',[
            'form' => $form->createView()
        ]);
    }
    public function myTasks(UserInterface $userInterface){
        $tasks = $userInterface->getTasks();

        return $this->render('task/my-tasks.html.twig',[
            'tasks' => $tasks
        ]);
    }
    public function edit(Request $request,UserInterface $userInterface, Task $task){

        if (!$userInterface || $userInterface->getId() != $task->getUser()->getId()) {
            return $this->redirectToRoute('tasks');
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$task->setCreatedAt(new \DateTime(null, new \DateTimeZone('America/Mexico_City')));
            //datos del usuario conectado
            //$task->setUser($userInterface);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($task);
            $manager->flush();
            return $this->redirect(
                $this->generateUrl('task_detail', ['id' => $task->getId()]));
        }

        return $this->render('task/creation.html.twig',[
            'edit' => true,
            'form' => $form->createView()
        ]);
    }
    public function delete(Task $task, UserInterface $userInterface){
        
        if (!$userInterface || $userInterface->getId() != $task->getUser()->getId()) {
            return $this->redirectToRoute('tasks');
        }
        if (!$task) {
            return $this->redirectToRoute('tasks');
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($task);
        $manager->flush();
        return $this->redirectToRoute('tasks');
    }
}
