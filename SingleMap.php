<?php

namespace elektromann\googlemaps;

use yii\base\Widget;
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
class SingleMap extends Widget
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

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        GoogleMapsAsset::register($this->getView());
        
        parent::init();
        
        if(empty($this->apiKey)) {
            $this->apiKey = GoogleMapsComponent::getProperty("apiKey");
            
            if(empty($this->apiKey)) {
                throw new InvalidConfigException("Api key is required!");
            }
        }
        
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
        $markers = [
            'position' => "valami",
            'title' => "cím",
            'content' => "Tartalom",
        ];
        
        return $this->render('single-map', [
            'apiKey' => $this->apiKey,
            'boxWidth' => $this->boxWidth,
            'boxHeight' => $this->boxHeight,
            'mapId' => $this->mapId,
            'options' => $this->createOptions(),
        ]);
    }
    
    /**
     * @return string
     */
    private function createOptions()
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
    private function createMarkers()
    {
        $markers = [];
        foreach ($this->markers as $marker) {
            $location = array_key_exists("location", $marker) ? $marker['location'] : $this->location;
            
            $markers[] = [
            'location' => $location,
            'title' => array_key_exists("title", $marker) ? $marker['title'] : null,
            'description' => array_key_exists("description", $marker) ? $marker['description'] : null,
            ];
        }
        
        return $markers;
    }
}
