<?php
namespace Training\Repository\Model\Resource\Example;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'example_id';
    
    /**
    * Define resource model
    *
    * @return void
    */
    protected function _construct()
    {
        $this->_init(\Training\Repository\Model\Example::class, \Training\Repository\Model\Resource\Example::class);
    }
}