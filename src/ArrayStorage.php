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
 * Simple implementation of a widgets data storage.
 * 
 * [
 *      'scope1' => [
 *          'place1' => [
 *              [windgets data],
 *              [windgets data],
 *              [windgets data],
 *              ...
 *          ],
 *          'place4' => [
 *              [windgets data],
 *              [windgets data],
 *              [windgets data],
 *              ...
 *          ],
 *          ...
 *      ],
 *      'scope2' => [
 *          'place1' => [
 *              [windgets data],
 *              [windgets data],
 *              [windgets data],
 *              ...
 *          ],
 *          'place3' => [
 *              [windgets data],
 *              [windgets data],
 *              [windgets data],
 *              ...
 *          ],
 *          ...
 *      ],
 *      ...
 * ]
 */
class ArrayStorage implements WidgetsDataStorageInterface
{
    /**
     * @var array
     */
    protected $storage = [];
    
    /**
     * Uses incoming array $data as a storage.
     * 
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->storage = $data;
    }
    
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
    public function getWidgetsData(string $scope, ?string $place = null): array
    {
        if (!array_key_exists($scope, $this->storage)) {
            return [];
        }
        
        if (null !== $place) {
            if (!array_key_exists($place, $this->storage[$scope])) {
                return [];
            }
            
            return [$place => $this->storage[$scope][$place]];
        }
        
        return $this->storage[$scope];
    }
}
