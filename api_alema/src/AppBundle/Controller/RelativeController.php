<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\RelativeType;
use AppBundle\Entity\AccessChild;
use AppBundle\Entity\Relative;

class RelativeController extends Controller
{
	/**
    * @Rest\View(serializerGroups={"user"})
    * @Rest\Get("/relatives")
    */
    public function getRelativesAction(Request $request)
    {
        $relatives = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Relative')
                ->findAll();
        /* @var $relatives Relative[] */

        return $relatives;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/relatives/{login}") 
     */
    public function getRelativeAction(Request $request)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser();
        $relative = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Relative')
                ->find($request->get('login'));
        /* @var $relative Relative */
        if($userToken->getLogin()!= $relative->getUser()->getLogin()){
            return $this->userNotPermit();
        }
        if (empty($relative)) {
            return $this->relativeNotFound();
        }
        return $relative;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/relatives")
     */
    public function postRelativesAction(Request $request)
    {
    	$relative = new Relative();
        $form = $this->createForm(RelativeType::class, $relative);
        $form->submit($request->request->all());
        if($form->isValid()){
            $em = $this->get('doctrine.orm.entity_manager');
            $user = $relative->getUser();
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
            $user->setRoles(array('ROLE_RELATIVE'));
            $em->persist($user); //On met l'utilisateur
            $em->persist($relative); //On le met en parent
            $this->addRelativeToChildren($relative->getFamilyNumber(), $relative);
            $em->flush();
            return $relative;
        }
        else{
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/changeInRelative/{login}")
     */
    public function postChangeInRelativeAction(Request $request){
        $relative = new Relative();
        $userToken = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('login'));

        if (empty($user)){
            return $this->userNotFound();
        }
        if($userToken->getLogin()!= $user->getLogin()){
            return $this->userNotPermit();
        }
        $relative->setUser($user);
        $familyNumber = $request->get('familyNumber');
        $existFamilyNumber = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Relative')
                ->findByFamilyNumber($familyNumber);
        if(!empty($existFamilyNumber)){
            return $this->numberFamilyExist();
        }
        $user->setRoles(array('ROLE_RELATIVE'));
        $em = $this->get('doctrine.orm.entity_manager');
        $em->merge($user);
        $relative->setFamilyNumber($familyNumber);
        $this->addRelativeToChildren($familyNumber, $relative);
        $em->persist($relative);
        $em->flush();
        return $relative;

    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/relatives/{login}")
     */
    public function patchRelativeAction(Request $request){
        $userToken = $this->get('security.token_storage')->getToken()->getUser();
        $relative = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Relative')
                ->find($request->get('login'));
        /* @var $relative Relative */

        if (empty($relative)) {
            return $this->relativeNotFound();
        }

        if($userToken->getLogin()!= $relative->getuser()->getLogin()){
            return $this->userNotPermit();
        }

        $oldFamilyNumber = $relative->getFamilyNumber();
        $form = $this->createForm(RelativeType::class, $relative);
        $form->submit($request->request->all(), false);
        if($form->isValid()){
            $em = $this->get('doctrine.orm.entity_manager');
            $this->removeRelativeToChildren($oldFamilyNumber, $relative);
            $this->addRelativeToChildren($relative->getFamilyNumber(), $relative);
            $em->merge($relative);
            $em->flush();
            return $relative;
        }
        else{
            return $form;
        }
    }

    //Fonction mettant le gérant aux enfants
    private function addRelativeToChildren($familyNumber, $relative){
        $children = $this->get('doctrine.orm.entity_manager')
                          ->getRepository('AppBundle:Child')
                          ->findByFamilyNumber($familyNumber);
        $em = $this->get('doctrine.orm.entity_manager');
        foreach ($children as $child) { //On met le responsable à tous les enfants ayant le numéro de famille comme le parent
            $child->setRelative($relative);
            $em->persist($child);
            $accessChild = new AccessChild();
            $accessChild->setLoginUser($relative->getUser());
            $accessChild->setIdChild($child);
            $accessChild->setFamilyLink('Responsable');
            $em->persist($accessChild);
        }
        $em->flush();
    }

    //Fonction enlevant le gérant aux enfants
    private function removeRelativeToChildren($oldFamilyNumber, $relative){
        $children = $this->get('doctrine.orm.entity_manager')
                          ->getRepository('AppBundle:Child')
                          ->findByFamilyNumber($oldFamilyNumber);
        $em = $this->get('doctrine.orm.entity_manager');
        foreach ($children as $child) { //On enleve le responsable à tous les enfants ayant l'ancien numéro de famille du parent
            $accessChild = $em->getRepository('AppBundle:AccessChild')
                              ->findOneBy(array('idChild' => $child->getId(), 'loginUser' => $relative->getUser()->getLogin()));
            $em->remove($accessChild);       
            $child->setRelative(null);
            $em->persist($child);
        }
        $em->flush();
    }

    //Fonction qui retourne une exception lorsqu'un numéro de famille existe déjà
    private function numberFamilyExist(){
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Family Number exist already',null, 400);
    }

    //Fonction qui retourne une exception si l'utilisateur n'est pas le bon
    private function userNotPermit(){
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Bad user',null, 403);
    }

    //Fonction qui retourne une exception lorsqu'un utilisateur n'est pas trouvé
    private function userNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');
    }

    //Fonction qui retourne une exception lorsqu'un parent n'est pas trouvé
    private function relativeNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Relative not found');
    }
}