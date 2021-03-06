<?php
/**
 * This file is part of the Ecommerce-Deals package
 *
 * @package Ecommerce-Deals
 */

/**
 * Dependency Injection namespace
 */
namespace Heystack\Deals\DependencyInjection;

use Heystack\Core\Loader\DBClosureLoader;
use Heystack\Core\Services as CoreServices;
use Heystack\Deals\Config\ContainerConfig;
use Heystack\Deals\Interfaces\DealDataInterface;
use Heystack\Ecommerce\Services as EcommerceServices;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Container extension for Heystack.
 *
 * If Heystacks services are loaded as an extension (this happens when there is
 * a primary services.yml file in mysite/config) then this is the container
 * extension that loads heystacks services.yml
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Deals
 */
class ContainerExtension extends Extension
{
    /**
     * Loads a services.yml file into a fresh container, ready to me merged
     * back into the main container
     *
     * @param array            $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        (new YamlFileLoader(
            $container,
            new FileLocator(ECOMMERCE_DEALS_BASE_PATH . '/config')
        ))->load('services.yml');

        $config = (new Processor())->processConfiguration(
            new ContainerConfig(),
            $configs
        );

        if (isset($config['deals_db'])) {
            $dealsDbConfig = [
                'deals' => []
            ];
            
            $handler = function (DealDataInterface $record) use (&$dealsDbConfig) {
                $configArray = $record->getConfigArray();

                if (is_array($configArray)) {
                    $dealsDbConfig['deals'][$record->getName()] = $configArray;
                }
            };
            
            try {
                (new DBClosureLoader($handler))->load([
                    $config['deals_db']['select'],
                    $config['deals_db']['from'],
                    $config['deals_db']['where']
                ]);
            } catch (\ReflectionException $e) {}
            
            $configs[] = $dealsDbConfig;
        }

        if (empty($config['coupon_class'])) {
            $container->setParameter('coupon.class', 'Disabled');
        } else {
            $container->setParameter('coupon.class', $config['coupon_class']);
        }

        $config = (new Processor())->processConfiguration(
            new ContainerConfig(),
            $configs
        );

        foreach ($config['deals'] as $dealId => $deal) {
            $this->addDeal($container, $dealId, $deal);
        }

    }
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $dealId
     * @param array $deal
     * @return array
     */
    protected function addDeal(ContainerBuilder $container, $dealId, $deal)
    {
        $dealDefinitionID = "deals.deal.$dealId";
        $dealDefinition = $this->getDealDefinition($dealId, str_replace('%', '%%', $deal['promotional_message']));

        $dealDefinition->addTag('deals.deal');

        //Add all conditions
        foreach ($deal['conditions'] as $conditionId => $condition) {
            $this->addCondition($container, $dealId, $condition, $conditionId, $dealDefinition);
        }

        //Add the result processor
        if (isset($deal['result']) && isset($deal['result']['configuration']) && isset($deal['result']['type'])) {
            $this->addResult($container, $dealId, $deal, $dealDefinition);
        }

        //Create the deal subscriber and add it to the event dispatcher
        $this->addSubscriber($container, $dealDefinitionID);

        //Put the deal in the container
        $container->setDefinition($dealDefinitionID, $dealDefinition);

        return $deal;
    }
    /**
     * @param string $dealId
     * @param array $promotionalMessage
     * @return \Symfony\Component\DependencyInjection\DefinitionDecorator
     */
    protected function getDealDefinition($dealId, $promotionalMessage)
    {
        $dealDefinition = new DefinitionDecorator('deals.deal_handler');
        $dealDefinition->addArgument($dealId);
        $dealDefinition->addArgument($promotionalMessage);
        $dealDefinition->addTag(EcommerceServices::TRANSACTION . '.modifier');

        return $dealDefinition;
    }
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $dealDefintionID
     * @return void
     */
    protected function addSubscriber(ContainerBuilder $container, $dealDefintionID)
    {
        $subscriberDefinition = new DefinitionDecorator('deals.subscriber');
        $subscriberDefinition->addArgument(new Reference($dealDefintionID));
        $subscriberDefinition->addTag(CoreServices::EVENT_DISPATCHER . '.subscriber');
        $container->setDefinition($dealDefintionID . '.subscriber', $subscriberDefinition);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $dealId
     * @param array $deal
     * @param \Symfony\Component\DependencyInjection\DefinitionDecorator $dealDefinition
     * @return void
     */
    protected function addResult(ContainerBuilder $container, $dealId, $deal, $dealDefinition)
    {
        $resultConfigurationID = $this->addResultConfiguration($container, $dealId, $deal);

        $resultDefinition = new DefinitionDecorator('deals.result.' . strtolower($deal['result']['type']));
        $resultDefinition->addArgument(new Reference($resultConfigurationID));
        $resultDefinition->addTag(CoreServices::EVENT_DISPATCHER . '.subscriber');

        //Set the result definition on the container
        $resultID = "deals.deal.$dealId.result";
        $container->setDefinition($resultID, $resultDefinition);

        //Set the result on the deal
        $dealDefinition->addMethodCall(
            'setResult',
            [
                new Reference($resultID)
            ]
        );
    }
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $dealId
     * @param array $deal
     * @return string
     */
    protected function addResultConfiguration(ContainerBuilder $container, $dealId, $deal)
    {
        $resultConfigurationDefinition = new DefinitionDecorator('deals.result.configuration');
        $resultConfigurationDefinition->addArgument($deal['result']['configuration']);
        $resultConfigurationID = "deals.deal.$dealId.result.configuration";
        $container->setDefinition($resultConfigurationID, $resultConfigurationDefinition);

        return $resultConfigurationID;
    }
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $dealId
     * @param array $condition
     * @param string $conditionId
     * @param \Symfony\Component\DependencyInjection\DefinitionDecorator $dealDefinition
     * @return void
     */
    protected function addCondition(ContainerBuilder $container, $dealId, $condition, $conditionId, $dealDefinition)
    {
        //Create the configuration for the condition
        $conditionConfigurationID = $this->addConditionConfiguration($container, $dealId, $condition, $conditionId);

        //Create the condition and pass the configuration to the constructor
        $conditionDefinition = new DefinitionDecorator("deals.condition." . strtolower($condition['type']));
        $conditionDefinition->addArgument(new Reference($conditionConfigurationID));

        $conditionDefinitionID = "deals.deal.$dealId.condition_$conditionId";

        $container->setDefinition($conditionDefinitionID, $conditionDefinition);

        //Add the condition to the deal
        $dealDefinition->addMethodCall(
            'addCondition',
            [
                new Reference($conditionDefinitionID)
            ]
        );
    }
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $dealId
     * @param array $condition
     * @param string $conditionId
     * @return string
     */
    protected function addConditionConfiguration(ContainerBuilder $container, $dealId, $condition, $conditionId)
    {
        $conditionConfigurationDefinition = new DefinitionDecorator('deals.condition.configuration');
        $conditionConfigurationDefinition->addArgument($condition['configuration']);
        $conditionConfigurationID = "deals.deal.$dealId.condition_$conditionId.configuration";
        $container->setDefinition($conditionConfigurationID, $conditionConfigurationDefinition);

        return $conditionConfigurationID;
    }

    /**
     * Returns the namespace of the container extension
     * @return string
     */
    public function getNamespace()
    {
        return 'deals';
    }

    /**
     * Returns Xsd Validation Base Path, which is not used, so false
     * @return boolean
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }

    /**
     * Returns the container extensions alias
     * @return string
     */
    public function getAlias()
    {
        return 'deals';
    }
}
