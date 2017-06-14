<?php

namespace Gmi\Shipestimate\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Element\Messages;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;


class Index extends Action
{
	/**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $JsonFactory;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $JsonFactory
     * 
     */
    public function __construct(
        Context $context,
        JsonFactory $JsonFactory,
        PageFactory $resultPageFactory
        
    )
    {
		
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
         $this->resultJsonFactory = $JsonFactory;
        $this->_objectManager=$context->getobjectManager();
        
    }

    public function execute()
    {
		
        $zipcode = $this->getRequest()->getPost('zipcode');
        $region = $this->getRequest()->getPost('region');
      
		$post = $this->getRequest()->getPost();
        if (!empty($zipcode) && !empty($region)) {
			
            // Retrieve your form data
             
            $pro_id = $this->getRequest()->getPost('productid');
            $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($pro_id);
           
            //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$quote=$this->_objectManager->create('Magento\Quote\Model\Quote');
			$data=$quote->getShippingAddress();

			$quote->getShippingAddress()->setCountryId('US');
			$quote->getShippingAddress()->setRegionId($region);
			$quote->getShippingAddress()->setPostcode($zipcode);

			$quote->addProduct($product); 
			//$quote->getShippingAddress()->collectTotals();
			$quote->getShippingAddress()->setCollectShippingRates(true);
			$quote->getShippingAddress()->collectShippingRates();
			$rates = $quote->getShippingAddress()->getShippingRatesCollection();
			
			foreach ($rates as $rate)
			{
				
				$title= $rate->getCarrierTitle();
				$price= $rate->getPrice();
				$responsce[]=array('title' => $title,'price' => $price);
				
			}
			$quote->delete();
			$resultJson = $this->resultJsonFactory->create();
			$resultJson->setData(['responsce'=>$responsce]);
			
			return $resultJson;
			
            // Doing-something with...

            // Display the succes form validation message
           // $this->messageManager->addSuccess('Message sent !');

            // Redirect to your form page (or anywhere you want...)
            //$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            //$resultRedirect->setUrl('/compagny/module/contact');

            //return $resultRedirect;
        }else
        {
			$responsce='';
			$resultJson = $this->resultJsonFactory->create();
			$resultJson->setData(['error'=>'Enter Valid Inputs']);
			
			return $resultJson;
		}
          // Render the page 
          // $this->_view->loadLayout();
         // $this->_view->renderLayout();
        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }
}
