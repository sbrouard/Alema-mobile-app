<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use AppBundle\Entity\Trip;

class AdminChildController extends Controller
{
	public function synchronizeAction(Request $request){
        $trips = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Trip')
                ->findAll();
        $tabTrip = $this->transformTabTrip($trips);
        $form = $this->createFormBuilder()
                ->add('choiceTrip', ChoiceType::class, [
                    'choices' => $tabTrip])
                ->add('choiceFile', FileType::class)
                ->add('save', SubmitType::class, array('label' => 'Valider'))
                ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();
            $excel = $this->get('os.excel');
            $excel->loadFile($data['choiceFile']->getRealPath());
            $nbRow = $excel->getRowCount() - 2;
            $nbColumn = $excel->getColumnCount();
            
        }
        return $this->render('AppBundle:Admin:synchronize.html.twig', array(
                   'form' => $form->createView()));
    }

	private function transformTabTrip($trips){
        foreach ($trips as $trip) {
            $tab[$trip->getName()] = $trip->getId();
        }
        return $tab; 
    }
}