<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;

class UserController extends Controller
{
	/**
    * @Rest\View(serializerGroups={"user"})
    * @Rest\Get("/users")
    */
    public function getUsersAction(Request $request)
    {
        if(!$this->container->get('security.authorization_checker')->isGranted('ROLE_RELATIVE')){
            return $this->userNotFound();
        }
        $users = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->findAll();
        /* @var $users User[] */

        return $users;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{login}") 
     */
    public function getUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('login'));
        /* @var $user User */
        if (empty($user)) {
            return $this->userNotFound();
        }
        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {
    	$user = new User();
    	$form = $this->createForm(UserType::class, $user);
    	$form->submit($request->request->all());
    	if($form->isValid()){
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
    		$em = $this->get('doctrine.orm.entity_manager');
    		$em->persist($user);
    		$em->flush();
    		return $user;
    	}
    	else{
    		return $form;
    	}
    }
    //Fonction qui retourne une exception lorsqu'un utilisateur n'est pas trouvé
    private function userNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');
    }
}