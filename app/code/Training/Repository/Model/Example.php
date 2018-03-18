<?php
namespace Training\Repository\Model;

use Magento\Framework\Model\AbstractModel;
use Training\Repository\Api\Data\ExampleInterface;

class Example extends AbstractModel implements ExampleInterface
{
    protected function _construct()
    {
        $this->_init(\Training\Repository\Model\Resource\Example::class);
    }
    
    public function getName()
    {
        return $this->_getData('name');
    }
    
    public function setName($name)
    {
        $this->setData('name', $name);
    }
    
    public function getCreatedAt()
    {
        return $this->_getData('created_at');
    }
    
    public function setCreatedAt($createdAt)
    {
        $this->setData('modified_at', $createdAt);
    }
    
    public function getModifiedAt()
    {
        return $this->_getData('modified_at');
    }
    
    public function setModifiedAt($modifiedAt)
    {
        $this->setData('modified_at', $modifiedAt);
    }
}