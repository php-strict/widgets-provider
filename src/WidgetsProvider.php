<?php
/**
 * PHP Strict.
 * 
 * @copyright   Copyright (C) 2018 - 2019 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

declare(strict_types=1);

namespace PhpStrict\WidgetsProvider;

use PhpStrict\WidgetsConsumer\WidgetsConsumerInterface;
use PhpStrict\WidgetsProducer\WidgetInterface as ProducedWidget;

/**
 * Simple implementation a part of functionality of a widgets provider.
 */
abstract class WidgetsProvider implements WidgetsProviderInterface
{
    /**
     * @var \PhpStrict\WidgetsProvider\WidgetsDataStorageInterface
     */
    protected $storage;
    
    /**
     * @var array
     */
    protected $widgets;
    
    /**
     * Generates widgets and inject its into consumer.
     * 
     * @param \PhpStrict\WidgetsConsumer\WidgetsConsumerInterface $consumer 
     *      consumer class which implements WidgetsConsumerInterface
     * 
     * @return void
     * 
     * @throws PhpStrict\WidgetsProvider\WidgetsGenerationException
     */
    public function setWidgets(WidgetsConsumerInterface $consumer): void
    {
        $this->widgets = null;
        
        $this->generateForScope(
            $consumer->getCurrentScope(),
            $consumer->getWidgetsPlaces()
        );
        
        $consumer->setWidgets($this->widgets);
    }
    
    /**
     * Gets array of widgets available for passed scope.
     * 
     * @param string $scope
     * @param array $places = [] Specify places or omit to get widgets for all places.
     * 
     * @return array Associated array of pairs - string place => array widgets
     * 
     * @throws PhpStrict\Widgets\WidgetsGenerationException
     */
    public function getScopeWidgets(string $scope, array $places = []): array
    {
        $this->generateForScope($scope, $places);
        
        return $this->widgets;
    }
    
    /**
     * Gets array of widgets available for passed scope and place.
     * 
     * @param string $scope
     * @param string $place
     * 
     * @return array
     * 
     * @throws PhpStrict\Widgets\WidgetsGenerationException
     */
    public function getWidgets(string $scope, string $place): array
    {
        $this->generateForPlace($scope, $place);
        
        if (array_key_exists($place, $this->widgets)) {
            return $this->widgets[$place];
        }
        
        return [];
    }
    
    /**
     * Generates widgets for all scope places.
     * 
     * @param string $scope
     * @param array $places = []
     * 
     * @return void
     * 
     * @throws PhpStrict\Widgets\WidgetsGenerationException
     */
    protected function generateForScope(string $scope, array $places = []): void
    {
        if (null !== $this->widgets) {
            return;
        }
        
        if (0 == count($places)) {
            $this->generate($scope, $this->storage->getWidgetsData($scope));
            return;
        }
        
        foreach ($places as $place) {
            $this->generateForPlace($scope, $place);
        }
    }
    
    /**
     * Generates widgets for specified scope and place.
     * 
     * @param string $scope
     * @param string $place
     * 
     * @return void
     * 
     * @throws PhpStrict\Widgets\WidgetsGenerationException
     */
    protected function generateForPlace(string $scope, string $place): void
    {
        if (null !== $this->widgets && array_key_exists($place, $this->widgets)) {
            return;
        }
        
        $this->generate($scope, $this->storage->getWidgetsData($scope, $place));
    }
    
    /**
     * Generates widgets from widgets data.
     * 
     * @param string $scope
     * @param array $data
     * 
     * @return void
     * 
     * @throws PhpStrict\Widgets\WidgetsGenerationException
     */
    protected function generate(string $scope, array $data): void
    {
        $sequenceNumber = 0;
        
        foreach ($data as $place => $widgetsData) {
            foreach ($widgetsData as $widgetData) {
                $this->widgets[$place][] = 
                    new Widget($scope, $place, ++$sequenceNumber, $this->produceWidget($widgetData));
            }
        }
    }
    
    /**
     * Implementation of this method depends from $widgetData structure.
     * $widgetData must contain information of widgets producer.
     * 
     * @param array $widgetData
     * 
     * @return ProducedWidget
     * 
     * @throws PhpStrict\Widgets\WidgetsGenerationException
     */
    abstract protected function produceWidget(array $widgetData): ProducedWidget;
}
