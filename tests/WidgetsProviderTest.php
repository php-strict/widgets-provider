<?php
declare(strict_types=1);

namespace PhpStrict\WidgetsProvider;

use PHPUnit\Framework\TestCase;
use PhpStrict\WidgetsConsumer\WidgetsConsumerInterface;
use PhpStrict\WidgetsProducer\WidgetInterface as ProducedWidgetInterface;

class WidgetsProviderTest extends TestCase
{
    protected $widgetsProvider = null;
    
    protected $widgetsConsumer = null;
    
    protected function setUp(): void
    {
        $this->widgetsProvider = new class() extends WidgetsProvider {
            protected function produceWidget(array $widgetData): ProducedWidgetInterface
            {
                return new class($widgetData) implements ProducedWidgetInterface {
                    protected $widgetData = [];
                    
                    public function __construct(array $widgetData)
                    {
                        $this->widgetData = $widgetData;
                    }
                    
                    public function getTitle(): string
                    {
                        return 'title';
                    }
                    
                    public function getContent(): string
                    {
                        return 'content';
                    }
                };
            }
        };
        
        $this->widgetsConsumer = new class() implements WidgetsConsumerInterface {
            protected $scope = '';
            
            protected $places = [];
            
            protected $widgets = [];
            
            public function __construct(string $scope = '', array $places = [])
            {
                $this->scope = $scope;
                $this->places = $places;
            }
            
            public function getCurrentScope(): string
            {
                return $this->scope;
            }
            
            public function getWidgetsPlaces(): array
            {
                return $this->places;
            }
            
            public function setWidgets(array $widgets): void
            {
                $this->widgets = $widgets;
            }
            
            public function appendWidgets(array $widgets): void
            {
                $this->widgets = array_merge_recursive($this->widgets, $widgets);
            }
            
            public function renderWidgets(string $place): void
            {
                if (!array_key_exists($place, $this->widgets)) {
                    return;
                }
                foreach ($this->widgets[$place] as $widget) {
                    echo 'title: ' . $widget->getTitle() . PHP_EOL . 
                         'conntent: ' . $widget->getContent();
                }
            }
        };
    }
    
    public function testSetWidgets()
    {
        
    }
}
