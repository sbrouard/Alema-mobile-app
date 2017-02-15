<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use AppBundle\Entity\LikeActuality;

class LikeActualityController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"like_actuality"})
     * @Rest\GET("/actualities/{id}/like-actualities")
     */
    public function getLikeActualitiesAction(Request $request){
        $actuality = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:Actuality')
              ->find($request->get('id'));
        if(empty($actuality)){
            $this->actualityNotFound();
        }
        return $actuality->getLikeActuality();
    }

	/**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"like_actuality"})
     * @Rest\POST("/actualities/{idActuality}/like-actualities")
     */
    public function postLikeActualityAction(Request $request)
    {
        $loginConnect = $this->get('security.token_storage')->getToken()->getUser()->getLogin();
        $actuality = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Actuality')
                ->find($request->get('idActuality'));
        if(empty($actuality)){
            $this->pictureNotFound();
        }
        $director = $actuality->getIdTrip()->getManager()->getUser()->getLogin();
        if($director != $loginConnect && $this->userIsPermit($actuality->getIdTrip()->getId(), $loginConnect) === false){
            return $this->userNotPermit();
        }
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($loginConnect);
        $likeActuality = new LikeActuality();
        $likeActuality->setIdActuality($actuality);
        $likeActuality->setLoginUser($user);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->merge($likeActuality);
        $em->flush();


    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/actualities/{idPicture}/like-actualities")
     */
    public function removeLikeActualityAction(Request $request)
    {
        $loginConnect = $this->get('security.token_storage')->getToken()->getUser()->getLogin();
        $actuality = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Actuality')
                ->find($request->get('idPicture'));
        $likeActuality = $actuality->getLikeActuality();
        $likeToRemove = null;
        foreach($likeActuality as $like){
            if($like->getLoginUser()->getLogin() === $loginConnect){
                $likeToRemove = $like;
                break;
            }
        }
        if($likeToRemove === null){
            $this->likeNotFound();
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($likeToRemove);
        $em->flush();
    }

    private function userIsPermit($idTrip, $login){
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($login);
        $children = $user->getAccessChildren();
        $trip = new ArrayCollection();
        foreach ($children as $child) {
            $id = $child->getIdChild()->getId();
            $participateTrip = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:ParticipateTrip')
                ->findByIdChild($id);
            if(!empty($participateTrip)){
                foreach ($participateTrip as $trips) {
                    $trip->add($trips->getIdTrip()->getId());
                }
            }
        }
        return $trip->indexOf(intval($idTrip));
    }

    private function userNotPermit(){
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Bad user',null, 403);
    }

    private function likeNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Like not found');
    }

    private function actualityNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Actuality not found');
    }
}