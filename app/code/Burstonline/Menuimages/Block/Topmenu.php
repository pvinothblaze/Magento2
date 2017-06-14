<?php
namespace Burstonline\Menuimages\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Helper\Category;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Indexer\Category\Flat\State;

class Topmenu extends Template
{
        /**
     * Catalog category
     *
     * @var \Magento\Catalog\Helper\Category
     */
    protected $catalogCategory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    private $layerResolver;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Catalog\Helper\Category $catalogCategory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    public function __construct(
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver
    ) {
        $this->catalogCategory = $catalogCategory;
        $this->collectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
        $this->layerResolver = $layerResolver;
    }

    /**
	 * Convert category to array
	 *
	 * @param \Magento\Catalog\Model\Category $category
	 * @param \Magento\Catalog\Model\Category $currentCategory
	 * @return array
	 */
	private function getCategoryAsArray($category, $currentCategory)
	{
		return [
			'name' => $category->getName(),
			'id' => 'category-node-' . $category->getId(),
			'url' => $this->catalogCategory->getCategoryUrl($category),
			'has_active' => in_array((string)$category->getId(), explode('/', $currentCategory->getPath()), true),
			'is_active' => $category->getId() == $currentCategory->getId(),
			'image_url' => $category->getImageUrl(), // Get image URL
		];
	}

	/**
	 * Get Category Tree
	 *
	 * @param int $storeId
	 * @param int $rootId
	 * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	protected function getCategoryTree($storeId, $rootId)
	{
		/** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
		$collection = $this->collectionFactory->create();
		$collection->setStoreId($storeId);
		$collection->addAttributeToSelect('name');
		$collection->addAttributeToSelect('image'); // Select image
		$collection->addFieldToFilter('path', ['like' => '1/' . $rootId . '/%']); //load only from store root
		$collection->addAttributeToFilter('include_in_menu', 1);
		$collection->addIsActiveFilter();
		$collection->addUrlRewriteToResult();
		$collection->addOrder('level', Collection::SORT_ORDER_ASC);
		$collection->addOrder('position', Collection::SORT_ORDER_ASC);
		$collection->addOrder('parent_id', Collection::SORT_ORDER_ASC);
		$collection->addOrder('entity_id', Collection::SORT_ORDER_ASC);

		return $collection;
	}
		
}
