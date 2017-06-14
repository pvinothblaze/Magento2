<?php

namespace Gmi\Shipestimate\Block;
use \Magento\Framework\Registry;
use \Magento\Catalog\Model\ProductFactory;
use Magento\Framework\View\Element\Template;

class Contact extends \Magento\Framework\View\Element\Template
{ 
		protected $_product = null;
		protected $_registry;
		protected $_productFactory;

   
	
    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Directory\Block\Data $directoryBlock, 
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    )
    {
		$this->_isScopePrivate = true;
        $this->directoryBlock = $directoryBlock;
		$this->registry = $registry;
        $this->_productFactory = $productFactory;
        parent::__construct($context, $data);
       }

    /**
     * Retrieve form action
     *
     * @return string
     */
    public function getFormAction()
    {
       return $this->getUrl('estimate/index/index', ['_secure' => true]);
    }
    
    private function getProduct()
    {
       
            $this->product = $this->registry->registry('product');

            if (!$this->product->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }
      
        return $this->product;
    }

    public function getProductId()
    {
        return $this->getProduct()->getId();
    }
    
    public function getCountries()
    {
        $country = $this->directoryBlock->getCountryHtmlSelect();
        return $country;
    }
    
    public function getRegion()
    {
        $region = $this->directoryBlock->getRegionHtmlSelect();
        return $region;
    }
}
