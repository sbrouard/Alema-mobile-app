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
        $relative = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Relative')
                ->find($request->get('login'));
        /* @var $relative Relative */
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
            $children = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Child')
                ->findByFamilyNumber($relative->getFamilyNumber());
            $relative->setChildren($children);
            foreach ($children as $child) { //On met le responsable à tous les enfants ayant le numéro de famille comme le parent
                $child->setRelative($relative);
                $em->persist($child);
                $accessChild = new AccessChild();
                $accessChild->setLoginUser($user);
                $accessChild->setIdChild($child);
                $accessChild->setFamilyLink('Responsable');
                $em->persist($accessChild);
            }
            $em->flush();
            return $relative;
        }
        else{
            return $form;
        }
    }

    private function relativeNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Relative not found');
    }
}