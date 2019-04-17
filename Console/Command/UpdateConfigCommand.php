<?php
namespace Khoazero123\DevFix\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\ObjectManagerFactory;
use Magento\Backend\App\Area\FrontNameResolver;

class UpdateConfigCommand extends Command
{
    /**
     * @var ObjectManagerFactory
     */
    private $objectManagerFactory;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Constructor
     *
     * @param ObjectManagerFactory $objectManagerFactory
     */
    public function __construct(
        ObjectManagerFactory $objectManagerFactory,
        \Magento\Framework\App\Cache\Manager $cacheManager
    ) {
        parent::__construct();
        $this->objectManagerFactory = $objectManagerFactory;
        $this->cacheManager = $cacheManager;
    }

    protected function configure()
    {
        $this->setName('devfix:updateconfig');
        $this->setDescription('Change config in admin');
        
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Updating setting...");
        $configWriter = $this->getObjectManager()
                ->get(\Magento\Framework\App\Config\Storage\WriterInterface::class);
        
        $settings = [
            'web/cookie/cookie_lifetime'            => '31536000',
            
            'admin/security/admin_account_sharing'  => '1',
            'admin/security/use_form_key'           => '0',
            'admin/security/session_lifetime'       => '31536000',
            'admin/security/password_lifetime'      => null,
            'admin/security/password_is_forced'     => '0',

            'dev/css/minify_files'                  => '0',
            'dev/css/merge_css_files'               => '0',
            'dev/js/minify_files'                   => '0',
            'dev/js/enable_js_bundling'             => '0',
            'dev/js/merge_files'                    => '0',
            'dev/template/allow_symlink'            => '1',
        ];
        foreach ($settings as $key => $value) {
            $configWriter->save($key, $value);
            $output->writeln("<info>Updated {$key} -> {$value}</info>");
        }

        $this->cacheManager->flush($this->cacheManager->getAvailableTypes());
        $output->writeln("<comment>Flushed cache</comment>");
    }

    /**
     * Gets initialized object manager
     *
     * @return ObjectManagerInterface
     */
    protected function getObjectManager()
    {
        if (null == $this->objectManager) {
            $area = FrontNameResolver::AREA_CODE;
            $this->objectManager = $this->objectManagerFactory->create($_SERVER);
            /** @var \Magento\Framework\App\State $appState */
            $appState = $this->objectManager->get(\Magento\Framework\App\State::class);
            $appState->setAreaCode($area);
            $configLoader = $this->objectManager->get(\Magento\Framework\ObjectManager\ConfigLoaderInterface::class);
            $this->objectManager->configure($configLoader->load($area));
        }
        return $this->objectManager;
    }
}