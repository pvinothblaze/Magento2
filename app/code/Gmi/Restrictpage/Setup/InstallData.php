<?php

namespace Gmi\Restrictpage\Setup;

use Magento\Eav\Setup\EavSetup;  
use Magento\Eav\Setup\EavSetupFactory;  
use Magento\Framework\Setup\InstallDataInterface;  
use Magento\Framework\Setup\ModuleContextInterface;  
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**  
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface  
{  
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'page_password',
                      [
                        'type' => 'varchar',
                        'group' => 'General Information',
                        'label' => 'Page Password',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 100,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'is_visible_in_grid' => false,
                        'default' => '',
            ]
        );
    }
}
