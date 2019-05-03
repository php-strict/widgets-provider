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

use PhpStrict\WidgetsProducer\WidgetInterface as ProducedWidgetInterface;

/**
 * Describes the interface of a widget.
 */
interface WidgetInterface extends ProducedWidgetInterface
{
    /**
     * @return string
     */
    public function getScope(): string;
    
    /**
     * @return string
     */
    public function getPlace(): string;
    
    /**
     * @return int
     */
    public function getSequenceNumber(): int;
}
