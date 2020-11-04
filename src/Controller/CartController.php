<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Ship;
use App\Form\CartType;
use App\Form\ShipType;
use App\Repository\CartRepository;
use App\Repository\ShipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/api")
*/
class CartController extends AbstractApiController
{
    /**
     * @Route("/carts", methods={"GET"})
     */
    public function index(Request $request, CartRepository $repository): Response
    {
        $carts = $repository->findAll();
        return  $this->json($carts);
    }

    /**
     * @Route("/carts", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(CartType::class);

        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid())
        {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Cart $carts */
        $carts = $form->getData();

        $this->getDoctrine()->getManager()->persist($carts);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($carts);
    }

//    /**
//     * @Route("/carts/{shipID}", methods={"GET"}, requirements={"id"="\d+"})
//     */
//    public function cartsByShip(Request $request, CartRepository $cartRepository, ShipRepository $shipRepository, $shipID): Response
//    {
//        $shipID = $request->get('shipID');
//        $ship = $shipRepository->findOneBy($shipID);
//        $carts = $cartRepository->findByShip($ship);
//        return  $this->json($ship);
//    }

    /**
     * @Route("/{id}/carts", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function cartsByShip(Request $request, CartRepository $cartRepository, ShipRepository $shipRepository): Response
    {
        $id = $request->get('id');
        $ship = $shipRepository->find($id);
        $carts = $cartRepository->findByShip($ship);

        return  $this->json($carts);
    }

    /**
     * @Route("/carts/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function deleteAction(Request $request, CartRepository $repository, $id): Response
    {
        $cart = $repository->find($id);

        if(!$cart)
        {
            throw new NotFoundHttpException('Customer not found');
        }

        $this->getDoctrine()->getManager()->remove($cart);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond('Action completed');
    }

    /**
     * @Route("/carts/{id}", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Request $request, CartRepository $repository): Response
    {
        $id = $request->get('id');
        $cart = $repository->find($id);

        return  $this->json($cart);
    }

    /**
     * @Route("/carts/{id}", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function updateAction(Request $request, CartRepository $repository, $id): Response
    {
        $cart = $repository->find($id);

        $form = $this->buildForm(CartType::class, $cart,[
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid())
        {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Cart $cart */
        $cart = $form->getData();

        $this->getDoctrine()->getManager()->persist($cart);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($cart);
    }
}
