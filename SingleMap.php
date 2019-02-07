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
 */
class SingleMap extends Widget
{
    public $apiKey;
    
    public $boxWidth = "100%";
    
    public $boxHeight = "300px";
    
    public $mapId = "googleMap";
    
    public $location;
    
    public $zoom = 13;


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
            if(!isset($this->location[0]) || !isset($this->location[1])) {
                throw new InvalidConfigException("Please set 2 coordinates!");
            }
            
            if(!is_numeric($this->location[0]) || !is_numeric($this->location[1])) {
                throw  new InvalidConfigException("Coordinates must be integers!");
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $mapOptions = [
            'center' => $this->location,
            'zoom' => $this->zoom,
        ];
        
        return $this->render('single-map', [
            'apiKey' => $this->apiKey,
            'boxWidth' => $this->boxWidth,
            'boxHeight' => $this->boxHeight,
            'mapId' => $this->mapId,
            'mapOptions' => $this->createJson(),
        ]);
    }
    
    /**
     * @return string
     */
    public function createJson()
    {
        if(is_array($this->location)) {
            $center = $this->location[0] . ", " . $this->location[1];
        } else {
            $center = "46.4174, 20.33";
        }
        
        $json = "{";
        $json .= "mapOptions: {";
        $json .= "center: new google.maps.LatLng($center)";
        $json .= ", zoom: " . $this->zoom;
        $json .= "}";
        
        if(is_string($this->location)) {
            $json .= ", geocodeOptions: {";
            $json .= '"address": "' . $this->location . '"';
            $json .= "}";
        }
        
        $json .= "}";
        
        return $json;
    }
}
