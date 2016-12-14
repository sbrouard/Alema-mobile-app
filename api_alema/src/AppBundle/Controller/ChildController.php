<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
//use AppBundle\Form\Type\ChildType;
use AppBundle\Entity\Child;

class ChildController extends Controller
{
	
	/**
	 * @Rest\View(serializerGroups={"user"})
	 * @Rest\Get("/relatives/{login}/children")
	 */
	public function getChildrenAction(Request $request)
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		$relative = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Relative')
                ->find($request->get('login'));
        /* @var $relative Relative */
        if (empty($relative)) {
            return $this->relativeNotFound();
        }
        if($relative->getUser()->getLogin() != $user->getLogin()){
        	return $this->relativeNotPermit();
        }
        return $relative->getChildren();
	}

	private function relativeNotPermit(){
		throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Bad user','Relative not permit to access');
	}

	private function relativeNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Relative not found');
    }
}