<?php
#ini_set('display_errors','1');
ini_set('max_execution_time', 3600); //360 seconds = 5 minutes
use \Magento\Framework\App\Bootstrap;
include('../app/bootstrap.php');


/* Objectmanager for magento functionality outside mage */

	$bootstrap = Bootstrap::create(BP, $_SERVER);
	$objectManager = $bootstrap->getObjectManager();
	$url = \Magento\Framework\App\ObjectManager::getInstance();
	$storeManager = $url->get('\Magento\Store\Model\StoreManagerInterface');
	$mediaurl= $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
	$state = $objectManager->get('\Magento\Framework\App\State');
	$state->setAreaCode('frontend');
/* Log Files */

$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/hmpro.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);


function getimg($url) {         
    $imagename=explode('images\products',$url);
	echo $img=$imagename[1];
	$name= stripslashes($img);
	$filepath = '../pub/media/import/'.$name; //path for temp storage folder: ./media/import/
	$save=file_put_contents($filepath, file_get_contents(trim($url))); //store the image from external url to the temp storage folder
	echo $finalpath='import/'.$name; 
    if($save){
		return $finalpath;
	}
} 
	$sku='EXH009C';
	$existproduct = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
	$productCollection = $existproduct->create();
	$productCollection->addAttributeToFilter('sku', $sku);



	$data=$productCollection->getData();
				 
	$existproductdataid=$data[0]['entity_id'];
	
	$e_product = $objectManager->create('\Magento\Catalog\Model\Product');
	$e_product->load($existproductdataid);
	
	$smimage= 'images\products\concession-trailer-hoods-exh009c.jpg';
    $medimage='images\products\concession-trailer-hoods-exh009c.jpg';
    
	$smimagePath = "http://www.hoodmart.com/".$smimage; // path of the image
	$medimagePath = "http://www.hoodmart.com/".$medimage; // path of the image
	$simg=getimg($smimagePath);
	$mimg=getimg($medimagePath);
	
		/*Add Images To The Product*/
		
		if($mimg)
		{
			$e_product->addImageToMediaGallery($mimg, array('image'), false, false);
			echo $log='Product " '. $sku . ' " Image Uploaded';
			$logger->info($log);
		}
		if($simg==$mimg)
		{ 
			$e_product->addImageToMediaGallery($simg, array('image','small_image', 'thumbnail'));
			echo 'both Images are same';
		}
		if($simg)
		{
			$e_product->addImageToMediaGallery($simg, array('small_image', 'thumbnail'));
			echo $log='Product " '. $sku . ' " Small/Thumbnail Uploaded';
			$logger->info($log);
		}
	
	$e_product->save();

?>
