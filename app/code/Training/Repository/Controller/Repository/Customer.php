<?php

namespace Training\Repository\Controller\Repository;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Customer extends Action
{
    /**
    * @var CustomerRepositoryInterface
    */
    private $customerRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var FilterGroupBuilder
     */
     private $filterGroupBuilder;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    
    public function __construct(
            Context $context, 
            CustomerRepositoryInterface $customerRepository, 
            SearchCriteriaBuilder $searchCriteriaBuilder, 
            FilterGroupBuilder $filterGroupBuilder, 
            FilterBuilder $filterBuilder
        )
    {
        parent::__construct($context);
        $this->customerRepository = $customerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    public function execute()
    {
        $this->getResponse()->setHeader('content-type', 'text/plain');

        $this->addEmailFilter();
        $this->addNameFilter();

        $customers = $this->getCustomersFromRepository();
        
        if(count($customers) == 0)
        {
            $this->getResponse()->appendBody('0');
            return ;
        }
        
        $this->getResponse()->appendBody(
            sprintf("List contains %s\n\n", get_class($customers[0]))
                );
        
        foreach ($customers as $customer)
        {
            $this->outputCustomer($customer);
        }
    }
    
    private function addEmailFilter()
    {
        $emailFilter = $this->filterBuilder->setField('email')->setValue('%@list.ru')->setConditionType('like')->create();
        $this->filterGroupBuilder->addFilter($emailFilter);
    }
    private function addNameFilter()
    {
        $nameFilter = $this->filterBuilder->setField('firstname')->setValue('Hans')->setConditionType('eq')->create();
        $this->filterGroupBuilder->addFilter($nameFilter);
    }
    
    private function getCustomersFromRepository()
    {
        $this->searchCriteriaBuilder->setFilterGroups([$this->filterGroupBuilder->create() ]);
        $criteria = $this->searchCriteriaBuilder->create();
        $customers = $this->customerRepository->getList($criteria);
        return $customers->getItems();
    }
    
    private function outputCustomer(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $this->getResponse()->appendBody(sprintf("\"%s %s\" <%s> (%s)\n", $customer->getFirstname() , $customer->getLastname() , $customer->getEmail() , $customer->getId()));
    }
}