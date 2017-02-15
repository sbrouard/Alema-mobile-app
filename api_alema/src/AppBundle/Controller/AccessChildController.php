<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\AccessChild;

class AccessChildController extends Controller
{
	
	/**
	 * @Rest\View(serializerGroups={"access_child"})
	 * @Rest\Get("/users/{login}/access_children")
	 */
	public function getAccessChildForUserAction(Request $request)
	{
		$userToken = $this->get('security.token_storage')->getToken()->getUser();
		$user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('login'));

        if (empty($user)) {
            return $this->userNotFound();
        }
        if($userToken->getLogin() != $user->getLogin()){
        	return $this->userNotPermit();
        }
        return $user->getAccessChildren();
	}

	/**
	 * @Rest\View(serializerGroups={"access_child"})
	 * @Rest\Get("/children/{id}/access_children")
	 */
	public function getAccessChildByUserAction(Request $request)
	{
		$userToken = $this->get('security.token_storage')->getToken()->getUser();
		$child = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Child')
                ->find($request->get('id'));
        /* @var $user User */

        if (empty($child)) {
            return $this->childNotFound();
        }
        if($userToken->getLogin() != $child->getRelative()->getUser()->getLogin()){
        	return $this->userNotPermit();
        }
        return $child->getAccessChildren();
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"access_child"})
	 * @Rest\Post("/users/{loginUser}/access_children/{idChild}")
	 */
	public function postAccessChildAction(Request $request)
	{
		$userToken = $this->get('security.token_storage')->getToken()->getUser();
		$accessChild = new AccessChild();
		$user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('loginUser'));
        if(empty($user)){
        	return $this->userNotFound();
        }
        $child = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Child')
                ->find($request->get('idChild'));
        if(empty($child)){
        	return $this->childNotFound();
        }
        if(!empty($this->get('doctrine.orm.entity_manager')
                       ->getRepository('AppBundle:AccessChild')
                       ->findBy(array("idChild" => $request->get('idChild'), "loginUser" => $request->get('loginUser'))))){
            return $this->alreadyExist();
        }
        if($userToken->getLogin() != $child->getRelative()->getUser()->getLogin()){
        	return $this->userNotPermit();
        }
        $accessChild->setLoginUser($user);
        $accessChild->setIdChild($child);
        $familyLink = $request->get('familyLink');
        if(empty($familyLink)){
        	$this->familyLinkMissing();
        }
        $accessChild->setFamilyLink($familyLink);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($accessChild);
        $em->flush();
        return $accessChild;
	}

	/**
     * @Rest\View(serializerGroups={"access_child"})
     * @Rest\Delete("/access_children/{id}")
     */
    public function removeAccessChildAction(Request $request)
    {
    	$userToken = $this->get('security.token_storage')->getToken()->getUser();
    	$accessChild = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:AccessChild')
                ->find($request->get('id'));
        if(empty($accessChild)){
            return $this->badAccessChild();
        }
        $relativeLogin = $accessChild->getIdChild()->getRelative()->getUser()->getLogin();
        if($userToken->getLogin() != $relativeLogin){
        	return $this->userNotPermit();
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($accessChild);
        $em->flush();
    }

    /**
     * @Rest\View(serializerGroups={"access_child"})
     * @Rest\Patch("/access_children/{id}")
     */
    public function patchAccessChildAction(Request $request)
    {
    	$userToken = $this->get('security.token_storage')->getToken()->getUser();
        $accessChild = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:AccessChild')
                ->find($request->get('id'));
        if(empty($accessChild)){
            return $this->badAccessChild();
        }
        $relativeLogin = $accessChild->getIdChild()->getRelative()->getUser()->getLogin();
        if($userToken->getLogin() != $relativeLogin){
            return $this->userNotPermit();
        }
        $familyLink = $request->get('familyLink');
        if(empty($familyLink)){
        	$this->familyLinkMissing();
        }
        $accessChild->setFamilyLink($familyLink);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->merge($accessChild);
        $em->flush();
        return $accessChild;
    }

	private function userNotPermit(){
		throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Bad user',null, 403);
	}

	private function badAccessChild()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Bad access child');
    }

	private function familyLinkMissing()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Family link missing');
    }

    private function alreadyExist()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Family link already exist');
    }

	private function childNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Child not found');
    }

	private function userNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');
    }
}