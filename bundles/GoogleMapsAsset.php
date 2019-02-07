<?php

namespace elektromann\googlemaps\bundles;

use yii\web\View;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class GoogleMapsAsset extends AssetBundle
{
    public $sourcePath = '@elektromann/googlemaps/assets';
    
    public $js = [
        'javascript/map.js',
    ];
    
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
}
