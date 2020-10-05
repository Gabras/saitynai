<?php

namespace App\Controller;

use App\Entity\Ship;
use App\Form\ShipType;
use App\Repository\ShipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/api")
*/
class ShipController extends AbstractApiController
{
    /**
     * @Route("/ships", methods={"GET"})
     */
    public function index(Request $request, ShipRepository $repository): Response
    {
        $customers = $repository->findAll();
        return  $this->json($customers);
    }

    /**
     * @Route("/ships", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(ShipType::class);

        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid())
        {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Ship $ship */
        $ship = $form->getData();

        $this->getDoctrine()->getManager()->persist($ship);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($ship);
    }

    /**
     * @Route("/ships/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function deleteAction(Request $request, ShipRepository $repository, $id): Response
    {
        $ship = $repository->find($id);

        if(!$ship)
        {
            throw new NotFoundHttpException('Customer not found');
        }

        $this->getDoctrine()->getManager()->remove($ship);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond('Action completed');
    }

    /**
     * @Route("/ships/{id}", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Request $request, ShipRepository $repository): Response
    {
        $id = $request->get('id');
        $ship = $repository->find($id);

        return  $this->json($ship);
    }

    /**
     * @Route("/ships/{id}", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function updateAction(Request $request, ShipRepository $repository, $id): Response
    {
        $ship = $repository->find($id);

        $form = $this->buildForm(ShipType::class, $ship,[
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid())
        {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Ship $ship */
        $ship = $form->getData();

        $this->getDoctrine()->getManager()->persist($ship);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($ship);
    }
}
