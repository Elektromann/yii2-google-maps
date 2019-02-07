<?php

/* @var $this yii\web\View */
/* @var $apiKey string */
/* @var $boxWidth string */
/* @var $boxHeight string */
/* @var $mapId string */
/* @var $mapOptions string */

?>

<div <?= 'id="' . $mapId . '"' ?> style="width: <?= $boxWidth ?>; height: <?= $boxHeight ?>;"></div>
<script>
    function createMaps()
    {
        addMap("<?= $mapId ?>", <?= $mapOptions ?>);
    }
</script>

<script <?= 'src="https://maps.googleapis.com/maps/api/js?key=' . $apiKey . '&callback=createMaps"' ?>></script>