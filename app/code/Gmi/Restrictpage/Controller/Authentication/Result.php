<?php
namespace Gmi\Restrictpage\Controller\Authentication;

use Magento\Framework\View\Element\Messages;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session as CustomerSession;


class Result extends \Magento\Framework\App\Action\Action
{
	
   
    /**
     * The controller action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
		$popup='';
		$data = $this->getRequest()->getParams();
		$om = \Magento\Framework\App\ObjectManager::getInstance();
		/** @var \Magento\Customer\Model\Session $session */
		$session = $om->get('Magento\Customer\Model\Session');
		
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		if($data)
		{
			$popup='';
			$password=$this->getRequest()->getParam('password');
			$popup=$this->getRequest()->getParam('status');
			$password=strtolower($password);
			$_category = $om->create('Magento\Catalog\Model\Category')->getCollection()->addAttributeToSelect('url_path')->addAttributeToFilter('page_password',array('eq'=>$password))->getFirstItem();
			$categoryId=$_category->getData('entity_id');
			if($categoryId)
			{
				//$session->unsetData('pagepass');
				$pageid='page'.$categoryId;
				$session->setData($pageid,$password);
				//if(empty($password)){$password=$session->getData($pageid);}
				$catpath=$_category->getData('url_path');
				$session->setpagemessage('Login Successfully');
				$this->messageManager->addSuccess('Login Successfully');
				echo $url = '/'.$catpath.'.html';
				die();
				
			}
			else{
				
				if($popup){
					echo 'Password Mismatch';
					die();
				}else{
					 $session->setpagemessage('Password Mismatch');
					 $this->messageManager->addError('Password Mismatch');
					 $url = $this->_url->getUrl();
					 $resultRedirect->setUrl($url);
					 return $resultRedirect;
					 die();
				}
			}
			
			$url = $this->_url->getUrl();
			$resultRedirect->setUrl($url);
			return $resultRedirect;
			die();
			
		}
		else
		{
			 $this->messageManager->addError('Empty Data');
			 $url = $this->_url->getUrl();
			 $resultRedirect->setUrl($url);
			 return $resultRedirect;
			 die();
		}
    }
}
