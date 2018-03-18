<?php
namespace Training\Repository\Model;

use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Training\Repository\Api\Data\ExampleInterface;
use Training\Repository\Api\Data\ExampleInterfaceFactory as ExampleDataFactory;
use Training\Repository\Api\Data\ExampleSearchResultsInterface;
use Training\Repository\Api\Data\ExampleSearchResultsInterfaceFactory;
use Training\Repository\Api\ExampleRepositoryInterface;
use Training\Repository\Model\Example as ExampleModel;
use Training\Repository\Model\Resource\Example\Collection as ExampleCollection;

class ExampleRepository implements ExampleRepositoryInterface
{
    /**
    * @var ExampleSearchResultsInterfaceFactory
    */
    private $searchResultsFactory;

    /**
    * @var ExampleFactory
    */
    private $exampleFactory;

    /**
    * @var ExampleDataFactory
    */
    private $exampleDataFactory;
    
    public function __construct(
            ExampleSearchResultsInterfaceFactory $searchResultsFactory,
            ExampleFactory $exampleFactory,
            ExampleDataFactory $exampleDataFactory
        )
    {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->exampleFactory = $exampleFactory;
        $this->exampleDataFactory = $exampleDataFactory;
    }

    /**
    * @return ExampleSearchResultsInterface
    */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var ExampleCollection $collection */
        $collection = $this->exampleFactory->create()->getCollection();

        /** @var ExampleSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $this->applySearchCriteriaToCollection($searchCriteria, $collection);
        $examples = $this->convertCollectionToDataItemsArray($collection);

        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($examples);

        return $searchResults;
    }
 
    private function addFilterGroupToCollection(
            FilterGroup $filterGroup,
            ExampleCollection $collection
        )
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter)
        {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        
        if ($fields)
        {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }
    
    private function convertCollectionToDataItemsArray(
            ExampleCollection $collection
        )
    {
        $examples = array_map(function (ExampleModel $example) {
            /** @var ExampleInterface $dataObject */
            $dataObject = $this->exampleDataFactory->create();
            $dataObject->setId($example->getId());
            $dataObject->setName($example->getName());
            $dataObject->setCreatedAt($example->getCreatedAt());
            $dataObject->setModifiedAt($example->getModifiedAt());
            return $dataObject;
        }, $collection->getItems());
        return $examples;
    }
    
    private function applySearchCriteriaToCollection(
            SearchCriteriaInterface $searchCriteria,
            ExampleCollection $collection
        )
    {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria,$collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria,$collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria,$collection);
    }
    
    private function applySearchCriteriaFiltersToCollection(
            SearchCriteriaInterface $searchCriteria,
            ExampleCollection $collection
        )
    {
        foreach ($searchCriteria->getFilterGroups() as $group)
        {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }
 
    private function applySearchCriteriaSortOrdersToCollection(
            SearchCriteriaInterface $searchCriteria,
            ExampleCollection $collection
        )
    {
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders)
        {
            $isAscending = $sortOrder->getDirection() == SearchCriteriaInterface::SORT_ASC;
            foreach ($sortOrders as $sortOrder)
            {
                $collection->addOrder(
                    $sortOrder->getField(),
                    $isAscending ? 'ASC' : 'DESC'
                );
            }
        }
    }
    
    private function applySearchCriteriaPagingToCollection(
            SearchCriteriaInterface $searchCriteria,
            ExampleCollection $collection
        )
    {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}