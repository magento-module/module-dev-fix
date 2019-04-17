<?php
namespace Khoazero123\DevFix\Setup;
 
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
class InstallData implements InstallDataInterface
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

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
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->_objectManager = $objectManager;
        $this->eavSetupFactory = $eavSetupFactory;
    }
 
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // $setup->startSetup();
        print("\nPlease run: php bin/magento devfix:updateconfig\n");
        sleep(2);
        // $setup->endSetup();
    }
}