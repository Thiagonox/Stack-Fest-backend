<?php

namespace Stack\Fest\Controller;

use Exception;
use MareaTurbo\Route;
use Stack\Fest\Config\Request;
use Stack\Fest\Config\Response;
use Stack\Fest\Model\DAO\CompanyDAO;
use Stack\Fest\Model\Entity\Company;
use Stack\Fest\Model\Service\AuthService;

class CompanyController
{
    public function __construct(
        protected AuthService $authService,
        protected CompanyDAO  $companyDAO,
        protected Request     $request,
        protected Response    $response
    )
    {
    }

    #[Route("/api/v1/companies", "GET")]
    public function getCompanies()
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->response->json(200, ['companies' => $this->companyDAO->getCompanies()]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/companies", "POST")]
    public function createCompany()
    {
        try {
            $id = $this->authService->isUserAuthenticated();

            $body = $this->request->getBody();

            $company = new Company(
                $body['name'],
                $body['cpnj'],
                $body['email'],
                $body['phone']
            );

            $companyExits = $this->companyDAO->getCompanyByName($company->getName());
            if ($companyExits) {
                throw new Exception('Company already exists');
            }

            $this->response->json(201, ['company' => $this->companyDAO->createCompany($id, $company)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/companies/{id}", "PUT")]
    public function updateCompany($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $body = $this->request->getBody();

            $company = new Company(
                $body['name'],
                $body['cpnj'],
                $body['email'],
                $body['phone']
            );

            $companyExits = $this->companyDAO->getCompanyByName($company->getName());
            if ($companyExits && $companyExits['id'] !== $parameters->id) {
                throw new Exception('Company already exists');
            }

            $this->response->json(200, ['company' => $this->companyDAO->updateCompany($parameters->id, $company)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/companies/{id}", "DELETE")]
    public function deleteCompany($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->companyDAO->deleteCompany($parameters->id);

            $this->response->json(204);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/companies/{id}", "GET")]
    public function getCompanyById($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->response->json(200, ['company' => $this->companyDAO->getCompanyById($parameters->id)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/companies/{id}/events", "GET")]
    public function getEventsByCompanyId($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->response->json(200, ['events' => $this->companyDAO->getEventsByCompanyId($parameters->id)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }
}