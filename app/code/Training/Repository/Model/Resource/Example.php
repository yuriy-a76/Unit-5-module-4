<?php
namespace Training\Repository\Model\Resource;

class Example extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
    {
        parent::__construct($context);
    }
	
    protected function _construct()
    {
        $this->_init('training_repository_example', 'example_id');
    }
}