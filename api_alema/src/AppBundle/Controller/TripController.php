<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
//use AppBundle\Form\Type\TripType;
use AppBundle\Entity\AccessChild;
use AppBundle\Entity\Trip;

class TripController extends Controller
{
	/**
    * @Rest\View()
    * @Rest\Get("/trips")
    */
    public function getTripsAction(Request $request)
    {
        $trips = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Trip')
                ->findAll();
        /* @var $trips Trip[] */

        return $trips;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/trips/{id}") 
     */
    public function getTripAction(Request $request)
    {
        $trip = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Trip')
                ->find($request->get('id'));
        /* @var $trip Trip */
        if (empty($trip)) {
            return $this->tripNotFound();
        }
        return $trip;
    }


    private function tripNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Trip not found');
    }
}