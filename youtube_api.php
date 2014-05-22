<?php
  define('YOUTUBE_URL', 'http://gdata.youtube.com/feeds/api/users/ali/favorites');
  define('NUM_VIDEOS', 5);

  $xml = simplexml_load_file(YOUTUBE_URL);

  $num_videos_found = count($xml->entry);
  if ($num_videos_found > 0) {
    echo '<table><tr>';
    for ($i = 0; $i < min($num_videos_found, NUM_VIDEOS); $i++) {
     
      $entry = $xml->entry[$i];
      $media = $entry->children('http://search.yahoo.com/mrss/');
      $title = $media->group->title;

     
      $yt = $media->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->duration->attributes();
      $length_min = floor($attrs['seconds'] / 60);
      $length_sec = $attrs['seconds'] % 60;
      $length_formatted = $length_min . (($length_min != 1) ? ' minutes, ':' minute, ') .
        $length_sec . (($length_sec != 1) ? ' seconds':' second');

     
      $attrs = $media->group->player->attributes();
      $video_url = $attrs['url'];

      
      $attrs = $media->group->thumbnail[0]->attributes();
      $thumbnail_url = $attrs['url']; 
    
      echo '<td style="vertical-align:bottom; text-align:center" width="' . (100 / NUM_VIDEOS) . '%"><a href="' . $video_url . '">' .
        $title . '<br /><span style="font-size:smaller">' . $length_formatted . '</span><br /><img src="' . $thumbnail_url . '" /></a></td>';
    }
    echo '</tr></table>';
  }
  else {
    echo '<p>Sorry, no videos were found.</p>';
  }
?>
