<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use AppBundle\Entity\Trip;

class TripController extends Controller
{
    /**
    * @Rest\View(serializerGroups={"trip"})
    * @Rest\Get("/trips/{idTrip}")
    */
    public function getTripAction(Request $request){
        $trip = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Trip')
                ->find($request->get('idTrip'));
        if(empty($trip)){
            return $this->tripNotFound();
        }
        return $trip;
    }

	/**
    * @Rest\View(serializerGroups={"trip"})
    * @Rest\Get("/directors/{login}/trips")
    */
    public function getTripManageAction(Request $request)
    {

        $director = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Director')
                ->find($request->get('login'));
        if(empty($director)){
            return $this->directorNotFound();
        }
        $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
        $qb->select('t')
           ->from('AppBundle:Trip', 't')
           ->orderBy('t.dateEnd', 'DESC')
           ->where('t.manager = :login')
           ->setParameter('login', $request->get('login'));
        $trips = $qb->getQuery()->getResult();
        return $trips;
    }

    private function directorNotPermit(){
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Bad director',null, 403);
    }

    private function tripNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Trip not found');
    }

    private function directorNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Director not found');
    }
}