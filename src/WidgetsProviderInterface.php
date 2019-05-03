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

/**
 * Describes the interface of a widgets provider which can obtain widgets 
 * from widgets producers and provide widgets to widgets consumers.
 */
interface WidgetsProviderInterface
{
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
    public function setWidgets(WidgetsConsumerInterface $consumer): void;
    
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
    public function getScopeWidgets(string $scope, array $places = []): array;
    
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
    public function getWidgets(string $scope, string $place): array;
}
