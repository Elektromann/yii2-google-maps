<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $apiKey string */
/* @var $boxWidth string */
/* @var $boxHeight string */
/* @var $mapId string */
/* @var $options array */

$mapOptions = $options['mapOptions'];

?>

<?= Html::tag('div', '', ['id' => $mapId, 'style' => "width: $boxWidth; height: $boxHeight"]) ?>
<script>
    function createMap()
    {
        var options = {
            mapOptions: {
                center: new google.maps.LatLng(<?= $mapOptions['center'][0] ?>, <?= $mapOptions['center'][1] ?>),
                zoom: <?= $mapOptions['zoom'] ?>,
                mapTypeId: "<?= $mapOptions['mapTypeId'] ?>"
            },
                    
            geocodeOptions: <?= array_key_exists("geocodeOptions", $options) ? json_encode($options['geocodeOptions']) : 'false' ?>,
    
            markers: <?= array_key_exists('markers', $options) ? json_encode($options['markers']) : 'false' ?>
        };
        
        addMap("<?= $mapId ?>", options);
    }
</script>

<script <?= 'src="https://maps.googleapis.com/maps/api/js?key=' . $apiKey . '&callback=createMap"' ?>></script>