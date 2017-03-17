<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use AppBundle\Entity\User;
use AppBundle\Entity\Director;

class AdminDirectorController extends Controller
{
	public function addDirectorAction(Request $request){
        $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
        $qb->select('p.name, p.id')
           ->from('AppBundle:Trip', 'p');
        $trips = $qb->getQuery()->getResult();
        $tabTrip = $this->transformTabTrip($trips);
        $role = "ROLE_DIRECTOR";
        $qb->select('u')
           ->from('AppBundle:User', 'u')
           ->where('u.roles LIKE :roles')
           ->andWhere('u.login != :login')
           ->setParameters(array('roles' => '%"'.$role.'"%','login' => 'default'));
        $directors = $qb->getQuery()->getResult();
        $tabDirector = $this->transformTabDirector($directors);
        $form = $this->createFormBuilder()
                ->add('choiceTrip', ChoiceType::class, [
                    'choices' => $tabTrip])
                ->add('choiceDirector', ChoiceType::class, [
                    'choices' => $tabDirector])
                ->add('save', SubmitType::class, array('label' => 'Enregistrer'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $trip = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:Trip')
                    ->find($data["choiceTrip"]);
            $director = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:Director')
                    ->find($data["choiceDirector"]);
            $trip->setManager($director);
            $em = $this->get('doctrine.orm.entity_manager');
            $em->merge($trip);
            $em->flush();
            return $this->render('AppBundle:Admin:add_director.html.twig', array(
                'ok' => "ok",
                'form' => $form->createView()));
        }
        return $this->render('AppBundle:Admin:add_director.html.twig', array(
            'form' => $form->createView()));
    }

    public function createDirectorAction(Request $request){
        $form = $this->createFormBuilder()
        		->add('login', TextType::class)
    			->add('firstname', TextType::class)
    			->add('lastname', TextType::class)
    			->add('email', EmailType::class)
    			->add('password', PasswordType::class)
    			->add('confirmPassword', PasswordType::class)
    			->add('save', SubmitType::class, array('label' => 'Enregistrer'))
                ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
        	$data = $form->getData();
        	$user = $this->get('doctrine.orm.entity_manager')
                	->getRepository('AppBundle:User')
                	->find($data["login"]);
        	if(!empty($user)){
        		return $this->render('AppBundle:Admin:create_director.html.twig', array(
            		'form' => $form->createView(),
            		'ko' => "Le login existe déjà"));
        	}
        	if($data["password"] !== $data["confirmPassword"]){
        		return $this->render('AppBundle:Admin:create_director.html.twig', array(
            		'form' => $form->createView(),
            		'ko' => "Les deux mots de passes ne sont pas identiques"));
        	}
        	$user = new User();
        	$director = new Director();
        	$user->setLogin($data["login"]);
        	$user->setFirstname($data["firstname"]);
        	$user->setLastname($data["lastname"]);
        	$user->setEmail($data["email"]);
        	$user->setPlainPassword($data["password"]);
        	$em = $this->get('doctrine.orm.entity_manager');
			$encoder = $this->get('security.password_encoder');
			$encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
            $user->setRoles(array('ROLE_DIRECTOR'));
            $em->persist($user);
            $director->setUser($user);
            $em->persist($director);
            $em->flush();
        	return $this->render('AppBundle:Admin:create_director.html.twig', array(
            'form' => $form->createView(),
            'ok' => "ok"));
        }
    	return $this->render('AppBundle:Admin:create_director.html.twig', array(
            'form' => $form->createView()));
    }

    public function deleteDirectorAction(Request $request){
    	$qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
		$role = "ROLE_DIRECTOR";
        $qb->select('u')
           ->from('AppBundle:User', 'u')
           ->where('u.roles LIKE :roles')
           ->andWhere('u.login != :login')
           ->setParameters(array('roles' => '%"'.$role.'"%','login' => 'default'));
        $directors = $qb->getQuery()->getResult();
        $tabDirector = $this->transformTabDirector($directors);
    	$form = $this->createFormBuilder()
    			->add('choiceDirector', ChoiceType::class, [
                    'choices' => $tabDirector])
                ->add('save', SubmitType::class, array('label' => 'Supprimer'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
        	$data = $form->getData();
        	$qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
        	$qb->select('t')
           	   ->from('AppBundle:Trip', 't')
               ->orderBy('t.dateEnd', 'DESC')
               ->where('t.manager = :login')
               ->setParameter('login', $data['choiceDirector']);
        	$trips = $qb->getQuery()->getResult();
        	dump($trips);
        	$director = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:Director')
                    ->find('default');
            foreach ($trips as $trip) {
            	$trip->setManager($director);
	            $em = $this->get('doctrine.orm.entity_manager');
	            $em->merge($trip);
	            $em->flush();
            }
            $director = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:Director')
					->find($data['choiceDirector']);
			$director->getUser()->setRoles(array('ROLE_USER'));
			$em->remove($director);
			$em->flush();
			return $this->render('AppBundle:Admin:delete_director.html.twig', array(
            'form' => $form->createView(),
            'ok' => "ok"));
        }
    	return $this->render('AppBundle:Admin:delete_director.html.twig', array(
            'form' => $form->createView()));
    }

    //Change the tab 0 => [name => "a", id =>"1"] en a => 1
    private function transformTabTrip($trips){
        foreach ($trips as $trip) {
            $tab[$trip["name"]] = $trip["id"];
        }
        return $tab; 
    }

    private function transformTabDirector($directors){
        foreach ($directors as $director) {
            $tab[$director->getFirstname().' '.$director->getLastname()] = $director->getLogin();
        }
        return $tab; 
    }
}