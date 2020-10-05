<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class CustomerController extends AbstractApiController
{
    /**
     * @Route("/customers", methods={"GET"})
     */
    public function index(Request $request, CustomerRepository $repository): Response
    {
        $customers = $repository->findAll();

        return  $this->json($customers);
    }

    /**
     * @Route("/customers", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(CustomerType::class);

        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid())
        {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Customer $customer */
        $customer = $form->getData();

        $this->getDoctrine()->getManager()->persist($customer);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($customer);
    }

    /**
     * @Route("/customers/{id}", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Request $request, CustomerRepository $repository): Response
    {
        $id = $request->get('id');
        $customer = $repository->find($id);

        return  $this->json($customer);
    }

    /**
     * @Route("/customers/{id}", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function updateAction(Request $request, CustomerRepository $repository, $id): Response
    {
        $customer = $repository->find($id);

        $form = $this->buildForm(CustomerType::class, $customer,[
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid())
        {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Customer $customer */
        $customer = $form->getData();

        $this->getDoctrine()->getManager()->persist($customer);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($customer);
    }

    /**
     * @Route("/customers/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function deleteAction(Request $request, CustomerRepository $repository, $id): Response
    {
        $customer = $repository->find($id);

        if(!$customer)
        {
            throw new NotFoundHttpException('Customer not found');
        }

        $this->getDoctrine()->getManager()->remove($customer);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond('Action completed');
    }

}
