<?php
/**
 * The commented out script inclusions are now located in the main template
 */
#$this->Html->css('jquery/ui/blue1/jquery-ui-1.8.11.custom', null, array('inline' => false));
#$this->Html->script('creationsite/google.map', array('inline' => false));
?>
<div id="map" style="width: 728px ; height: 632px;"></div>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">
    var locations = [
	<?php 
	$count = 0;
	foreach($data as $location) : 
		$count = $count + 1;					?>
	      ['<?php echo $location['address']; ?><br /><?php echo $location['city']; ?>, <?php echo $location['state']; ?> <?php echo $location['zip']; ?><br />Scheduled: <?php echo $location['date_schedule']; ?>', <?php echo $location['lat']; ?>, <?php echo $location['long']; ?>, <?php echo $count;?>],
	<?php 
		if($count == 1) {
			$first_lat = $location['lat'];
			$first_long = $location['lng'];
		}
	endforeach;
	?>
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      	zoom: 14,
      	center: new google.maps.LatLng(<?php echo $first_lat; ?>, <?php echo $first_long; ?>),
      	mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    for (i = 0; i < locations.length; i++) {  
    	marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
  	});

  	google.maps.event.addListener(marker, 'click', (function(marker, i) {
    	return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
</script>