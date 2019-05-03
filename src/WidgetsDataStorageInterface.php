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

/**
 * Describes the interface of a widgets data storage.
 */
interface WidgetsDataStorageInterface
{
    /**
     * Gets array of widgets data available for passed scope and place.
     * 
     * @param string $scope
     * @param ?string $place = null Use null or omit parameter to get widgets for all places in scope.
     * 
     * @return array Returned array structure must be:
     *      ['placeX' => [...], 'placeY' => [...], ...]
     * 
     * @throws \PhpStrict\WidgetsProvider\WidgetsDataStorageException
     */
    public function getWidgetsData(string $scope, ?string $place = null): array;
}
