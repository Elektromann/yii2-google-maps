<?php

namespace elektromann\googlemaps;

use Yii;
use yii\web\View;
use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use elektromann\googlemaps\GoogleMapsComponent;
use elektromann\googlemaps\bundles\GoogleMapsAsset;

/**
 * Create one map.
 * 
 * 'apiKey' => 'Your Google Api Key'
 * 
 * 'boxWidth' => '100%'
 * 
 * 'boxHeight' => '300px'
 * 
 * 'location' => [46.4174, 20.33] OR 'Szeged'
 * 
 * 'zoom' => 13
 * 
 * 'type' => self::TYPE_ROADMAP
 * 
 * 'markers' => []
 */
class Map extends Widget
{
    /**
     * Displays the default road map view. This is the default map type.
     */
    const TYPE_ROADMAP = "roadmap";
    
    /**
     * Displays Google Earth satellite images.
     */
    const TYPE_SATELLITE = "satellite";
    
    /**
     * Displays a mixture of normal and satellite views.
     */
    const TYPE_HYBRID = "hybrid";
    
    /**
     * Displays a physical map based on terrain information.
     */
    const TYPE_TERRAIN = "terrain";


    public $apiKey;
    public $boxWidth = "100%";
    public $boxHeight = "300px";
    public $mapId = "googleMap";
    public $location;
    public $zoom = 13;
    public $type;
    public $markers = [];
    public $last = false;
    
    public static $maps = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        
        if(empty($this->apiKey) && $this->last) {
            $this->apiKey = GoogleMapsComponent::getProperty("apiKey");
            
            if(empty($this->apiKey)) {
                throw new InvalidConfigException("Api key is required!");
            }
        }
        
        GoogleMapsAsset::register($this->getView());
        
        if(is_array($this->location)) {
            if(!array_key_exists(0, $this->location) || !array_key_exists(1, $this->location)) {
                throw new InvalidConfigException("Please set 2 coordinates!");
            }
            
            if(!is_numeric($this->location[0]) || !is_numeric($this->location[1])) {
                throw  new InvalidConfigException("Coordinates must be integers!");
            }
        }
        
        if(empty($this->type)) {
            $this->type = self::TYPE_ROADMAP;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        self::$maps[$this->mapId] = $this->createOptions();
        
        if($this->last) {
            $this->close();
        }
        
        return Html::tag('div', '', ['id' => $this->mapId, 'style' => "width: $this->boxWidth; height: $this->boxHeight"]);
    }
    
    /**
     * Close maps
     */
    protected function close()
    {
        $js = "";
        foreach (self::$maps as $mapId => $options) {
            $mapOptions = $options['mapOptions'];
            $geocodeOptions  = array_key_exists("geocodeOptions", $options) ? json_encode($options['geocodeOptions']) : 'false';
            $markers = array_key_exists('markers', $options) ? json_encode($options['markers']) : 'false';
            
            $js .= "var options = {";
            $js .= "mapOptions: {";
            $js .= "center: new google.maps.LatLng({$mapOptions['center'][0]}, {$mapOptions['center'][1]}),";
            $js .= "zoom: {$mapOptions['zoom']},";
            $js .= 'mapTypeId: "' . $mapOptions['mapTypeId'] . '"';
            $js .= "},";
            $js .= "geocodeOptions: $geocodeOptions,";
            $js .= "markers: $markers";
            $js .= "};";
            $js .= 'addMap("' . $mapId . '", options);';
        }
        
        if(empty($js)) {
             throw  new InvalidConfigException("Please create at least one map!");
        }
        
        $script = "function createMap() { $js }";
        
        $this->view->registerJs($script, View::POS_HEAD);
        
        $this->view->registerJsFile("https://maps.googleapis.com/maps/api/js?key={$this->apiKey}&callback=createMap", [
            'position' => View::POS_END,
        ]);
    }
    
    /**
     * @return string
     */
    protected function createOptions()
    {
        $options['mapOptions'] = [
            'center' => is_array($this->location) ? $this->location : [46.4174, 20.33],
            'zoom' => $this->zoom,
            'mapTypeId' => $this->type
        ];
        
        if(is_string($this->location)) {
            $options['geocodeOptions'] = [
                'address' => $this->location
            ];
        }
        
        if(!empty($this->markers)) {
            $options['markers'] = $this->createMarkers();
        }
        
        return $options;
    }
    
    /**
     * @return string
     */
    protected function createMarkers()
    {
        $markers = [];
        foreach ($this->markers as $marker) {
            $location = array_key_exists("location", $marker) ? $marker['location'] : $this->location;
            
            $title = null;
            if(in_array('title', $marker)) {
                $title = is_array($location) ? $location[0] . ", " . $location[1] : $location;
            } elseif(array_key_exists("title", $marker)) {
                $title = $marker['title'];
            }
            
            $markers[] = [
            'location' => $location,
            'title' => $title,
            'description' => array_key_exists("description", $marker) ? $marker['description'] : null,
            ];
        }
        
        return $markers;
    }
}
