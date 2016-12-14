<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
//use AppBundle\Form\Type\ChildType;
use AppBundle\Entity\AccessChild;

class AccessChildController extends Controller
{
	
	/**
	 * @Rest\View(serializerGroups={"user"})
	 * @Rest\Get("/users/{login}/access_children")
	 */
	public function getAccessChildAction(Request $request)
	{
		$userToken = $this->get('security.token_storage')->getToken()->getUser();
		$user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('login'));
        /* @var $relative Relative */
        if (empty($user)) {
            return $this->relativeNotFound();
        }
        if($userToken->getLogin() != $user->getLogin()){
        	return $this->relativeNotPermit();
        }
        return $user->getAccessChildren();
	}

	private function relativeNotPermit(){
		throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Bad user','Relative not permit to access');
	}

	private function relativeNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Relative not found');
    }
}