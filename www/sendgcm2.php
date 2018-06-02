<?php 
 function sendMessage(){
    $content = array(
      "en" => 'prova' 
      );
	  
	 $headings = array(
      "en" => 'Nuova Prenotazione - Scidoo' 
      ); 
	  
/*
    $fields = array(
      'app_id' => "5870b141-9a5c-4a3e-ad4d-ba95836b1ffa",
      'included_segments' => array('All'),
      'data' => array("foo" => "bar"),
      'contents' => $content
	  'small_icon' =>'https://www.scidoo.com/mobile/icon.png',
	  'android_sound' => 'notification',
	  'android_accent_color' => 'FFFF0000',
	  'included_segments' => array('All')
	  'include_player_ids' => array ('34d11b15-f808-4b82-8006-9b37039ff9ec')
    );*/
	
	$fields = array(
      'app_id' => "5870b141-9a5c-4a3e-ad4d-ba95836b1ffa",
      'data' => array("foo" => "bar"),
       'headings' => $headings,
	  'contents' => $content,
	    'include_player_ids' => array ('174d46eb-ee91-4e8f-b035-9ab3dc8bd032')
    );

    $fields = json_encode($fields);
	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                           'Authorization: Basic ZTZhNWUyMzctN2FiMi00NTQzLWI0NzYtNDQ1ZjIwMzg1NDI2'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
  }
  
  sendMessage();
  
?>
