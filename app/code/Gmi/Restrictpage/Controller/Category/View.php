<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
 
namespace Gmi\Restrictpage\Controller\Category;

  use Magento\Framework\Controller\ResultFactory;
  use Magento\Catalog\Api\CategoryRepositoryInterface;
  use \Magento\Framework\View\Result\PageFactory;
  
class View extends \Magento\Catalog\Controller\Category\View
{
    public function execute()
    {
			
			$om = \Magento\Framework\App\ObjectManager::getInstance();
			/** @var \Magento\Customer\Model\Session $session */
			$session = $om->get('Magento\Customer\Model\Session');
			
			$categoryId = (int)$this->getRequest()->getParam('id', false);
			$pageid='page'.$categoryId;
			$sppass=$session->getData($pageid);
			//print_r($session->getData());
			//die;
			$sppass=strtolower($sppass);
            $_category = $om->create('Magento\Catalog\Model\Category')->load($categoryId);
            // print_r($_category->getData());
            $pagepassword=$_category->getData('page_password');
            $pagepassword=strtolower($pagepassword);
			
            if($pagepassword)
            {
				if($sppass==$pagepassword)
				{
					$session->setpagemessage('Login Successfully');
					return parent::execute();
					die;
				}
				else
				{
					$session->setpagemessage('Please Login To view');
					$this->messageManager->addError('Please Login To view');
					$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
					$url = $this->_url->getUrl();
					$resultRedirect->setUrl($url);
					return $resultRedirect;
					die;
				}
			}
			else
			{  return parent::execute();   }
    }
    
  
}
