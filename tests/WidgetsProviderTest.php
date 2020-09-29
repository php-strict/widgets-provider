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
        $this->widgetsProvider = new class(
            new ArrayStorage([
                'page1' => [
                    'place1' => [
                        ['data' => 'data for place 1']
                    ],
                    'place2' => [
                        ['data' => 'data for place 2']
                    ],
                ]
            ])
        ) extends WidgetsProvider {
            public function __construct(WidgetsDataStorageInterface $storage)
            {
                $this->storage = $storage;
            }
            public function flushWidgets(): void
            {
                $this->widgets = null;
            }
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
                        return 'title ' . $this->widgetData['data'];
                    }
                    
                    public function getContent(): string
                    {
                        return 'content ' . $this->widgetData['data'];
                    }
                };
            }
        };
        
        $this->widgetsConsumer = new class(
            'page1', 
            ['place1']
        ) implements WidgetsConsumerInterface {
            protected $scope = '';
            
            protected $places = [];
            
            public $widgets = [];
            
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
                var_dump($this->widgets);
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
        $this->widgetsProvider->setWidgets($this->widgetsConsumer);
        
        // ob_end_clean();
        // $this->widgetsConsumer->renderWidgets('place1');
        // exit();
        
        $this->assertTrue(1 == count($this->widgetsConsumer->widgets));
        $this->assertTrue(array_key_exists('place1', $this->widgetsConsumer->widgets));
        $this->assertFalse(array_key_exists('place2', $this->widgetsConsumer->widgets));
        $this->assertTrue(is_array($this->widgetsConsumer->widgets['place1']));
        $this->assertTrue(1 == count($this->widgetsConsumer->widgets['place1']));
        $this->assertTrue(is_object($this->widgetsConsumer->widgets['place1'][0]));
        $this->assertTrue($this->widgetsConsumer->widgets['place1'][0] instanceof Widget);
        $this->assertTrue('title data for place 1' == $this->widgetsConsumer->widgets['place1'][0]->getTitle());
        $this->assertTrue('content data for place 1' == $this->widgetsConsumer->widgets['place1'][0]->getContent());
    }
    
    public function testGetScopeWidgets()
    {
        $widgets = $this->widgetsProvider->getScopeWidgets('page1', ['place1']);
        $this->assertTrue(is_array($widgets));
        $this->assertTrue(1 == count($widgets));
        $this->assertTrue(array_key_exists('place1', $widgets));
        $this->assertFalse(array_key_exists('place2', $widgets));
        $this->assertTrue(is_array($widgets['place1']));
        $this->assertTrue(1 == count($widgets['place1']));
        $this->assertTrue(is_object($widgets['place1'][0]));
        $this->assertTrue($widgets['place1'][0] instanceof Widget);
        $this->assertTrue('title data for place 1' == $widgets['place1'][0]->getTitle());
        $this->assertTrue('content data for place 1' == $widgets['place1'][0]->getContent());
        
        $this->widgetsProvider->flushWidgets();
        
        $widgets = $this->widgetsProvider->getScopeWidgets('page1', ['place2']);
        $this->assertTrue(is_array($widgets));
        $this->assertTrue(1 == count($widgets));
        $this->assertTrue(array_key_exists('place2', $widgets));
        $this->assertFalse(array_key_exists('place1', $widgets));
        $this->assertTrue(is_array($widgets['place2']));
        $this->assertTrue(1 == count($widgets['place2']));
        $this->assertTrue(is_object($widgets['place2'][0]));
        $this->assertTrue($widgets['place2'][0] instanceof Widget);
        $this->assertTrue('title data for place 2' == $widgets['place2'][0]->getTitle());
        $this->assertTrue('content data for place 2' == $widgets['place2'][0]->getContent());
        
        $this->widgetsProvider->flushWidgets();
        
        $widgets = $this->widgetsProvider->getScopeWidgets('page1');
        $this->assertTrue(is_array($widgets));
        $this->assertTrue(2 == count($widgets));
        $this->assertTrue(array_key_exists('place1', $widgets));
        $this->assertTrue(array_key_exists('place2', $widgets));
        $this->assertTrue(is_array($widgets['place1']));
        $this->assertTrue(is_array($widgets['place2']));
        $this->assertTrue(1 == count($widgets['place1']));
        $this->assertTrue(1 == count($widgets['place2']));
        $this->assertTrue(is_object($widgets['place1'][0]));
        $this->assertTrue(is_object($widgets['place2'][0]));
        $this->assertTrue($widgets['place1'][0] instanceof Widget);
        $this->assertTrue($widgets['place2'][0] instanceof Widget);
        $this->assertTrue('title data for place 1' == $widgets['place1'][0]->getTitle());
        $this->assertTrue('title data for place 2' == $widgets['place2'][0]->getTitle());
        $this->assertTrue('content data for place 1' == $widgets['place1'][0]->getContent());
        $this->assertTrue('content data for place 2' == $widgets['place2'][0]->getContent());
    }
    
    public function testGetWidgets()
    {
        $widgets = $this->widgetsProvider->getWidgets('page0', 'place0');
        $this->assertTrue(is_array($widgets));
        $this->assertTrue(0 == count($widgets));
        
        $widgets = $this->widgetsProvider->getWidgets('page1', 'place1');
        $this->assertTrue(is_array($widgets));
        $this->assertTrue(1 == count($widgets));
        $this->assertTrue(is_object($widgets[0]));
        $this->assertTrue($widgets[0] instanceof Widget);
        $this->assertTrue('title data for place 1' == $widgets[0]->getTitle());
        $this->assertTrue('content data for place 1' == $widgets[0]->getContent());

        $widgets = $this->widgetsProvider->getWidgets('page1', 'place-not-exists');
        $this->assertTrue(is_array($widgets));
        $this->assertTrue(0 == count($widgets));
    }
}
