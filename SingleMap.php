<?php

namespace elektromann\googlemaps;

use yii\helpers\Html;

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
class SingleMap extends Map
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        
        self::$maps[$this->mapId] = $this->createOptions();
        
        $this->close();
        
        return Html::tag('div', '', ['id' => $this->mapId, 'style' => "width: $this->boxWidth; height: $this->boxHeight"]);
    }
}
