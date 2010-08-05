<!doctype html>
<html>
<head>
	<title>ILRI - International Livestock Research Institutute</title>
	<?php
		require_once('head_includes.php');
		require_once('includes/functions.php');
		$pageref = $_SERVER['PHP_SELF']; // are we index.php?
	 ?>
	<script>
		$(document).ready(function() {
			// select all div tags of class "vid"
			$('div.vid').each(function() {
				//var personID = $(this).attr('id');
				//insertVideo(personID);
				//alert('omg it is: '+this.id);
				flashembed(this, "videos/player_flv_maxi.swf", {
					flv: 'andrew.flv', //relative to player!
					showplayer: 'never', // (hide maxi player "play" button)
					showloading: 'never', // (hide maxi player loading text)
					margin: '0', // (hide maxi player margin)
					startimage: 'images/people/andrew_play.png', // (show a "start" image before playing)
					wmmode: 'opaque'
				});
			});
		});
	</script>
</head>
<body>

<div id="outer">
<div id="header">
<?php require_once('navigation.php'); ?>
<div id="banner" style="padding-top: 0px;"></div>
</div> <!-- //#header -->

<div id="inner">
<div id="peopleLeftMenu">
<form id="sortByPosition" class="sortby" action="<?php echo $pageref; ?>" method="post">
	<input type="hidden" name="sortby" value="position" />
	<input type="image" name="submit" src="images/sort_by_position.jpg" />
</form>
<form id="sortByName" class="sortby" action="<?php echo $pageref; ?>" method="post">
	<input type="hidden" name="sortby" value="location" />
	<input type="image" name="submit" src="images/sort_by_location.jpg" />
</form>
<form id="sortRandom" class="sortby" action="<?php echo $pageref; ?>" method="post">
	<input type="hidden" name="sortby" value="random" />
	<input type="image" name="submit" src="images/sort_by_random.jpg" />
</form>

</div> <!-- //#peopleLeftMenu -->

<div id="ilricrowd">The ILRI Crowd</div>

<?php

?>

<ul id="peopleGrid">
<?php
	// create a SimpleXMLObject
	$people_xml_object = simplexml_load_file('people.xml');

	// convert it to an array
	$people = xmlobj2array($people_xml_object->children());

	// simplexml returns a namespace "person" containing several "people" so
	// we reset our people array to this subset
	$people = $people['person'];

	// determine how to sort our people!
	// first see if a "sortby" value exists, otherwise just sort randomly
	if (!empty($_POST['sortby'])) {
		$sortby = $_POST['sortby'];

		// check for a sane sort value, otherwise just use random
		if($sortby == 'name') $people = sort_subval($people,'name');
		else if($sortby == 'position') $people = sort_subval($people,'position');
		else if($sortby == 'location') $people = sort_subval($people,'location');
		else {
			$sortby = 'random';
			shuffle($people);
		}
	}
	else {
		$sortby = 'random';
		shuffle($people);
	}

	$count = 0;
	if(isset($people) && !empty($people)) {
		foreach($people as $person) {
			echo '<li id="person'.$count.'" class="person">'."\n";
			echo '	<div class="img">'."\n";

			//check to see if the current person has a video
			if($person['video'] != 'false') {
				echo '		<div id="player'.$count.'" class="vid"><img src="'.$person['image'].'" alt="'.$person['name'].'" /></div>'."\n";
			}
			// show image if there is no video
			else {
				echo '		<img src="'.$person['image'].'" alt="'.$person['name'].'" />'."\n";
			}
			echo '	</div>'."\n";
			echo '	<h4>'.$person['name'].'</h4>'."\n";
			if($sortby == 'position') {
				echo '	<h5>'.$person['position'].'</h5>'."\n";
				echo '</li>'."\n";
			}
			else {
				echo '	<h5>'.$person['location'].'</h5>'."\n";
				echo '</li>'."\n";
			}

			$count++;
		}
	}
?>
</ul>
</div> <!-- //#inner -->

<div id="footer">
Footer
</div> <!-- //#footer -->

</div> <!-- //#outer -->

</body>
</html>
