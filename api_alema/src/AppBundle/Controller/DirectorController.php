<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\DirectorType;
use AppBundle\Entity\Director;

class DirectorController extends Controller
{
	/**
    * @Rest\View(serializerGroups={"user"})
    * @Rest\Get("/directors")
    */
    public function getDirectorsAction(Request $request)
    {
        $directors = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Director')
                ->findAll();
        /* @var $directors Director[] */

        return $directors;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/directors/{login}") 
     */
    public function getDirectorAction(Request $request)
    {
        $director = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Director')
                ->find($request->get('login'));
        /* @var $director Director */
        if (empty($director)) {
            return $this->directorNotFound();
        }
        return $director;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/directors")
     */
    public function postDirectorsAction(Request $request)
    {
    	$director = new Director();
        $form = $this->createForm(DirectorType::class, $director);
        $form->submit($request->request->all());
        if($form->isValid()){
            $em = $this->get('doctrine.orm.entity_manager');
            $user = $director->getUser();
            $em->persist($user);
            $em->persist($director);
            $em->flush();
            return $director;
        }
        else{
            return $form;
        }
    }

    private function directorNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Director not found');
    }
}