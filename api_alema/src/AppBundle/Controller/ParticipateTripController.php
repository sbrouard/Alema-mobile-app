<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\ParticipateTrip;

class ParticipateTripController extends Controller
{
	/**
     * @Rest\View(serializerGroups={"participate_trip"})
     * @Rest\Get("/users/{login}/participate_trips")
     */
    public function getParticipateTripAction(Request $request)
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
        $children = $user->getAccessChildren();
        $trip = new ArrayCollection();
        foreach ($children as $child) {
            $id = $child->getIdChild()->getId();
            $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
            $qb->select('p')
               ->from('AppBundle:ParticipateTrip', 'p')
               ->join('p.idTrip', 't')
               ->orderBy('t.dateEnd', 'DESC')
               ->where('p.idChild = :id')
               ->setParameter('id', $id);
            $participateTrip = $qb->getQuery()->getResult();
            if(!empty($participateTrip)){
                $trip->add($participateTrip);
            }
            
        }
        return $trip;
    }

    private function userNotPermit(){
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Bad user',null, 403);
    }

    private function userNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Relative not found');
    }
}