<?php
namespace Training\Repository\Api\Data;

interface ExampleSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{    
    /**
    * @api
    * @return \Training\Repository\Api\Data\ExampleInterface[]
    */
    public function getItems();

    /**
    * @api
    * @param \Training\Repository\Api\Data\ExampleInterface[] $items
    * @return $this
    */
    public function setItems(array $items = null);
 
}