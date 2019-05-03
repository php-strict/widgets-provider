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

use PhpStrict\WidgetsProducer\WidgetInterface as ProducedWidget;

/**
 * Simple implementation of a widget.
 */
class Widget implements WidgetInterface
{
    /**
     * @var string
     */
    protected $scope;
    
    /**
     * @var string
     */
    protected $place;
    
    /**
     * @var int
     */
    protected $sequenceNumber;
    
    /**
     * @var ProducedWidget
     */
    protected $widget;
    
    /**
     * @param string $scope
     * @param string $place
     * @param int $sequenceNumber
     * @param ProducedWidget $widget
     */
    public function __construct(string $scope, string $place, int $sequenceNumber, ProducedWidget $widget)
    {
        $this->scope = $scope;
        $this->place = $place;
        $this->sequenceNumber = $sequenceNumber;
        $this->widget = $widget;
    }
    
    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }
    
    /**
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }
    
    /**
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
    }
    
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->widget->getTitle();
    }
    
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->widget->getContent();
    }
}
