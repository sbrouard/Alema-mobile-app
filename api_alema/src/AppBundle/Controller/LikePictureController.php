<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use AppBundle\Entity\LikePicture;

class LikePictureController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"like_picture"})
     * @Rest\GET("/pictures/{id}/like-pictures")
     */
    public function getLikePicturesAction(Request $request){
        $picture = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:Picture')
              ->find($request->get('id'));
        if(empty($picture)){
            $this->pictureNotFound();
        }
        return $picture->getLikePicture();
    }

	/**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"like_picture"})
     * @Rest\POST("/pictures/{idPicture}/like-pictures")
     */
    public function postLikePictureAction(Request $request)
    {
        $loginConnect = $this->get('security.token_storage')->getToken()->getUser()->getLogin();
        $picture = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Picture')
                ->find($request->get('idPicture'));
        if(empty($picture)){
            $this->pictureNotFound();
        }
        $director = $picture->getIdTrip()->getManager()->getUser()->getLogin();
        if($director != $loginConnect && $this->userIsPermit($picture->getIdTrip()->getId(), $loginConnect) === false){
            return $this->userNotPermit();
        }
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($loginConnect);
        $likePicture = new LikePicture();
        $likePicture->setIdPicture($picture);
        $likePicture->setLoginUser($user);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->merge($likePicture);
        $em->flush();


    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/pictures/{idPicture}/like-pictures")
     */
    public function removeLikePicturesAction(Request $request)
    {
        $loginConnect = $this->get('security.token_storage')->getToken()->getUser()->getLogin();
        $picture = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Picture')
                ->find($request->get('idPicture'));
        $likePicture = $picture->getLikePicture();
        $likeToRemove = null;
        foreach($likePicture as $like){
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

    private function pictureNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Picture not found');
    }
}