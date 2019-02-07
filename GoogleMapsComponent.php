<?php

namespace elektromann\googlemaps;

use Yii;
use yii\base\Component;

/**
 * 'apiKey' => 'your google api key'
 */
class GoogleMapsComponent extends Component
{
    public $apiKey;
    
    /**
     * @param string $property_name
     * @return type
     */
    public static function getProperty($property_name)
    {
        if(array_key_exists('GoogleMapsComponent', Yii::$app->components)) {
            return Yii::$app->GoogleMapsComponent->$property_name;
        }
        
        return null;
    }
}