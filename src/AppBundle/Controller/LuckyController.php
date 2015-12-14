<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task;

class LuckyController extends Controller
{
    /**
     * @link: http://train-symf.dev/app_dev.php/lucky/number
     * @Route("/lucky/number")
     */
    public function numberAction()
    {
        $number = rand(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

    /**
     * @Route("/lucky/number2/{count}", defaults={"count" = 1})
     */
    public function number2($count)
    {
        $numbers = array();
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand(0, 100);
        }
        $numbersList = implode(', ', $numbers);

        // $html = $this->container->get('templating')->render(
        //     'lucky/number.html.twig',
        //     array('luckyNumberList' => $numbersList)
        // );

        // return new Response($html);

        return $this->render(
	        'lucky/number.html.twig',
	        array('luckyNumberList' => $numbersList)
	    );
    }

    /**
     * @link: http://train-symf.dev/app_dev.php/api/lucky/number
     * @Route("/api/lucky/number")
     */
    public function apiNumberAction()
    {
        $data = array(
            'lucky_number' => rand(0, 100),
        );

        return new Response(
            json_encode($data),
            200,
            array('Content-Type' => 'application/json')
        );

        // return new JsonResponse($data);
    }

    public function registerAction()
    {
        return $this->render('lucky/register.html.twig');
    }

    /**
     * @link: http://train-symf.dev/app_dev.php/lucky/number
     * @Route("/new_success", name="task_success")
     */
    public function submit_success()
    {

        return new Response(
            '<html><body>Success</body></html>'
        );
    }

    /**
     * @Route("/new")
     */
    public function newAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', 'text')
            ->add('dueDate', 'date')
            ->add('save', 'submit', array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        // ... perform some action, such as saving the task to the database

	        return $this->redirectToRoute('task_success');
	    }

	    $validator = $this->get('validator');
	    $errors = $validator->validate($task);

	    // if (count($errors) > 0) {
	        
	    //      * Uses a __toString method on the $errors variable which is a
	    //      * ConstraintViolationList object. This gives us a nice string
	    //      * for debugging.
	         
	    //     $errorsString = (string) $errors;

	    //     return new Response($errorsString);
	    // }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
            'errors' => $errors
        ));
    }
}