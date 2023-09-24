<?php
class IPTVClass
{

  public function M3U_Parser($url)
  {
    $m3ufile = file_get_contents($url);
    //$re = '/#(EXTINF|EXTM3U):(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$))/';
    $re = '/#EXTINF:(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$))/';
    //$attributes = '/([a-zA-Z0-9\-]+?)="([^"]*)"/';
    $attributes = '/([a-zA-Z0-9\-\_]+?)="([^"]*)"/';


    $m3ufile = str_replace('tvg-logo', 'thumb_square', $m3ufile);
    $m3ufile = str_replace('tvg-id', 'id', $m3ufile);
    //$m3ufile = str_replace('tvg-name', 'group', $m3ufile);
    //$m3ufile = str_replace('tvg-name', 'name', $m3ufile);
    $m3ufile = str_replace('tvg-name', 'author', $m3ufile);
    $m3ufile = str_replace('group-title', 'group', $m3ufile);
    $m3ufile = str_replace('tvg-country', 'country', $m3ufile);
    $m3ufile = str_replace('tvg-language', 'language', $m3ufile);

    //print_r($m3ufile);

    //$m3ufile = str_replace(' ', '_', $m3ufile); // FOR GROUP

    preg_match_all($re, $m3ufile, $matches);

    // Print the entire match result
    //print_r($matches);

    $items = array();

    foreach ($matches[0] as $list) {

      //echo "$list <br>";

      preg_match($re, $list, $matchList);

      //$mediaURL = str_replace("\r\n","",$matchList[4]);
      //$mediaURL = str_replace("\n","",$matchList[4]);
      //$mediaURL = str_replace("\n","",$mediaURL);
      $mediaURL = preg_replace("/[\n\r]/", "", $matchList[3]);
      $mediaURL = preg_replace('/\s+/', '', $mediaURL);
      //$mediaURL = preg_replace( "/\r|\n/", "", $matches[4] );


      $newdata = array(
        //'ATTRIBUTE' => $matchList[2],
        'service' => "iptv",
        'title' => $matchList[2],
        //'playlistURL' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
        //'playlistURL' => str_replace("url=","",$_SERVER['QUERY_STRING']),
        'playlistURL' => $url,
        'media_url' => $mediaURL,
        'url' => $mediaURL
      );

      preg_match_all($attributes, $list, $matches, PREG_SET_ORDER);

      foreach ($matches as $match) {
        $newdata[$match[1]] = $match[2];
      }

      //array_push($newdata,$attribute);
      //$newdata[] = $attribute;

      $items[] = $newdata;
      //$items[] = $matchList[2];

    }

    //   $globalitem =  array (
    //    //'ATTRIBUTE' => $matchList[2],
    //    'item' => $items
    //    );

    //$globalitem[$items] ;
    //$globalitems['item'] = $items;

    //$globalist['list'] = $globalitems;

    $globalitems = array(
      //'ATTRIBUTE' => $matchList[2],
      'service' => "iptv",
      'title' => "iptv",
      'item' => $items,
    );

    $globalist['list'] = $globalitems;
    //print_r($items);
    return json_encode($globalist);
  }

  private function get_data($url) // YouTube Live to M3U8
  {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: www.youtube.com';
    $headers[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"99\", \"Google Chrome\";v=\"99\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Full-Version: \"99.0.4844.51\"';
    $headers[] = 'Sec-Ch-Ua-Arch: \"x86\"';
    $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $headers[] = 'Sec-Ch-Ua-Platform-Version: \"10.0.0\"';
    $headers[] = 'Sec-Ch-Ua-Model: \"\"';
    $headers[] = 'Sec-Ch-Ua-Bitness: \"64\"';
    $headers[] = 'Sec-Ch-Ua-Full-Version-List: \" Not A;Brand\";v=\"99.0.0.0\", \"Chromium\";v=\"99.0.4844.51\", \"Google Chrome\";v=\"99.0.4844.51\"';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.51 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Service-Worker-Navigation-Preload: true';
    $headers[] = 'X-Client-Data: CJO2yQEIo7bJAQjEtskBCKmdygEIpJTLAQjq8ssBCJ75ywEI54TMAQjxmswBCM6bzAEIz6LMAQ==';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Sec-Fetch-User: ?1';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Accept-Language: tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return $result;
  }

  public function YouTubeM3U8Gen($channelid)
  {
    ini_set("user_agent", "facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)");
    /* gets the data from a URL */
    $arr1 = array("http://", "https://", "www", "/", "youtube", ".", "com", "watch", "?v", "=", );
    $str1 = str_replace($arr1, "", $channelid);
    $urlVideoDetails = "https://www.youtube.com/watch?v=" . htmlentities(strip_tags(trim($str1))) . "";
    // $string = get_data($urlVideoDetails);
    $returnedData = $this->get_data($urlVideoDetails);
    $data = preg_split('/."hlsManifestUrl":"/', strip_tags($returnedData), -1);
    $js = strstr($data[1], '"}', true);
    if ($js == 1) {
      return false;
    } else {
      return strip_tags(str_replace(" ", "", $js));
    }
  }

  // Twitch M3U8
  // Source : http://systools.losthost.org/?code=14
  function get_twich_playlist($channel) {
    $result = '';
    $arr1 = array("http://", "https://", "www", "/", "twitch", ".", "tv");
    $str1 = str_replace($arr1, "", $channel);
    if (!empty($str1)) {
      $str1 = rawurlencode($str1);
      // get clientId
      $token = $this->get_page_from_web('https://www.twitch.tv/'.$str1);
      $auth = array();
      preg_match('/clientId\s*=\s*"([0-9a-z]+)/is', $token, $auth);
      $auth = array(
        'Client-ID: '.array_pop($auth), // required
        'Device-ID: '.substr(md5(strval(time())), 0, 16) // anything
      );
      // get query string
      $link = array();
      preg_match('/var\s+query\s*=\s*\'([^\']+)\'/is', $token, $link);
      $link = html_entity_decode(array_pop($link), ENT_NOQUOTES, 'UTF-8');
      $token = array(
        'operationName' => 'PlaybackAccessToken_Template',
        'query' => $link,
        'variables' => array(
          'isLive' => true,
          'login' => $str1,
          'isVod' => false,
          'vodID' => '',
          'playerType' => 'site'
        )
      );
      // get token
      $token = trim($this->get_page_from_web('https://gql.twitch.tv/gql', json_encode($token), $auth));
      if (!empty($token)) {
        $token = json_decode($token, true);
        if (
          (json_last_error() == JSON_ERROR_NONE) && (is_array($token)) &&
          (array_key_exists('data', $token)) && (is_array($token['data'])) &&
          (array_key_exists('streamPlaybackAccessToken', $token['data']))
        ) {
          // usher.ttvnw.net
          $link = sprintf(
            'https://usher.twitch.tv/api/channel/hls/%s.m3u8?'.
            'acmb=%s'.
            '&allow_source=true'.
            '&fast_bread=true'.
            '&p=%u'.
            '&play_session_id=%s'.
            '&player_backend=mediaplayer'.
            '&playlist_include_framerate=true'.
            '&reassignments_supported=true'.
            '&sig=%s'.
            '&supported_codecs=avc1'.
            '&token=%s'.
            '&cdm=wv'.
            '&player_version=1.16.0',
            $str1,
            rawurlencode(base64_encode('{}')), // empty array
            time(),
            md5(strval(time())), // anything
            rawurlencode($token['data']['streamPlaybackAccessToken']['signature']),
            rawurlencode($token['data']['streamPlaybackAccessToken']['value'])
          );
          $result = $this->get_page_from_web($link);
        }
      }
    }
    return($result);
  }

  function get_page_from_web($link, $data = '', $auth = array()) {
    $CRLF = chr(13).chr(10);
    $type = 'tcp://';
    $port = '80';
    if (!strcasecmp(substr($link, 0, 7), 'http://')) {
      $link = substr($link, 7);
    }
    if (!strcasecmp(substr($link, 0, 8), 'https://')) {
      $link = substr($link, 8);
      $type = 'ssl://';
      $port = '443';
    }
    $host = substr($link, 0, strpos($link, '/'));
    $link = substr($link, strpos($link, '/'));
    $wp = '';
    $context = stream_context_create(
      array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      ))
    );
    $fp = stream_socket_client($type.$host.':'.$port, $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $context);
    if ($fp) {
      $out =
        (empty($data) ? 'GET' : 'POST').' '.$link.' HTTP/1.0'.$CRLF.
        'Host: '.$host.$CRLF.
        (empty($auth) ? '' : implode($CRLF, $auth).$CRLF).
        (empty($data) ? '' :
          'Content-Length: '.strlen($data).$CRLF.
          'Content-Type: text/plain; charset=UTF-8'.$CRLF
        ).
        'Connection: Close'.$CRLF.
        $CRLF.
        $data;
      fwrite($fp, $out);
      while (!feof($fp)) {
        $wp .= fgets($fp, 10 * 1024);
      }
      fclose($fp);
      $fp = $CRLF.$CRLF;
      $out = strpos($wp, $fp);
      if ($out !== false) {
        $wp = substr($wp, $out + strlen($fp));
      }
    }
    return($wp);
  }

  public function RandomString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public function require_auth($AUTH_USER, $AUTH_PASS)
  {
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (
      !$has_supplied_credentials ||
      $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
      $_SERVER['PHP_AUTH_PW'] != $AUTH_PASS
    );
    if ($is_not_authenticated) {
      header('HTTP/1.1 401 Authorization Required');
      header('WWW-Authenticate: Basic realm="Access denied"');
      exit;
    }
  }
  public function extControl($name)
  {
    if (!extension_loaded('' . strip_tags($name) . '')) {
      die('The ' . strip_tags($name) . ' extension is not loaded.');
    }
  }

  public function funcControl($name)
  {
    if (!function_exists('' . strip_tags($name) . '')) {
      die('The ' . strip_tags($name) . ' function is not loaded.');
    }
  }

  public function getIPAddress()
  {
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
      $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
      $ip = $forward;
    } else {
      $ip = $remote;
    }

    return $ip;
  }

  public function M3U8DebugStream($pubname, $tslinks, $config)
  {
    $filename = '' . strip_tags($pubname) . '.m3u8';
    $tslink = '' . dirname(__FILE__) . '/m3u/' . $filename . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $config . ' "' . $tslink . '" >"' . $logfile . '" 2>&1';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">
	<br><textarea class="container" data-role="textarea" style="width:100%;height:50%;">' . $com . '</textarea><br><br>';
    if (!$fp = @fopen(strip_tags($tslink), "r")) {
      echo '<br><b>Stream : (Can Stream) Online</b>';
    } else {
      echo '<br><b>Stream : (Cant Stream)Offline</b>';
    }
    die();
  }

  public function M3U8DebugStreamWin($pubname, $tslinks, $config)
  {
    $filename = '' . strip_tags($pubname) . '.m3u8';
    $tslink = '' . dirname(__FILE__) . '\m3u/' . $filename . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '\log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' "' . $tslink . '" >"' . $logfile . '" 2>&1';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">
	<br><textarea class="container" data-role="textarea" style="width:100%;height:50%;">' . $com . '</textarea><br><br>';
    if (!$fp = @fopen(strip_tags($tslink), "r")) {
      echo '<br><b>Stream : (Can Stream) Online</b>';
    } else {
      echo '<br><b>Stream : (Cant Stream)Offline</b>';
    }
    die();
  }

  public function StopFFMPEG()
  {
    echo '<b>FFMpeg Killing...</b<br>';
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      $winffmpeg = 'taskkill /F /IM "ffmpeg.exe"';
      $winrtsp = 'taskkill /F /IM "rtsp-simple-server.exe"';
      shell_exec($winffmpeg);
      shell_exec($winrtsp);
      echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Exit | " . $winffmpeg . " & " . $winrtsp . "');
    window.location.href='index.php';
    </script>";
    } else {
      $linffmpeg = "pkill ffmpeg";
      $linrtsp = "pkill rtsp";
      shell_exec($linffmpeg);
      shell_exec($linrtsp);
      echo "<script LANGUAGE='JavaScript'>
  window.alert('Succesfully Exit | " . $linffmpeg . " & " . $linrtsp . "');
  window.location.href='index.php';
  </script>";
    }
    array_map('unlink', array_filter((array) glob('' . dirname(__FILE__) . '/m3u/*.ts')));
    array_map('unlink', array_filter((array) glob('' . dirname(__FILE__) . '/m3u/*.m3u8')));
  }
  public function TSDebugStream($pubname, $tslinks, $configts)
  {
    $filename = '' . strip_tags($pubname) . '.ts';
    $tslink = '' . dirname(__FILE__) . '/m3u/' . $filename . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $configts . ' "' . $tslink . '" >"' . $logfile . '" 2>&1';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">
	<br><textarea class="container" data-role="textarea" style="width:100%;height:50%;">' . $com . '</textarea><br><br>';
    if (!$fp = @fopen(strip_tags($tslink), "r")) {
      echo '<b>Online</b>';
    } else {
      echo '<b>Offline</b>';
    }
    die();
  }

  public function TSDebugStreamWin($pubname, $tslinks, $configts)
  {
    $filename = '' . strip_tags($pubname) . '.ts';
    $tslink = '' . dirname(__FILE__) . '\m3u/' . $filename . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '\log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $configts . ' "' . $tslink . '" >"' . $logfile . '" 2>&1';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">
	<br><textarea class="container" data-role="textarea" style="width:100%;height:50%;">' . $com . '</textarea><br><br>';

    if (!$fp = @fopen(strip_tags($tslink), "r")) {
      echo '<b>Online</b>';
    } else {
      echo '<b>Offline</b>';
    }
    die();
  }

  public function StartRecordStreamLin($pubname, $tslinks, $url, $config)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $recfile = '' . dirname(__FILE__) . '/m3u/' . strip_tags($pubname) . '.mp4';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $config . ' ' . $recfile . ' >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartRecordStreamWin($pubname, $tslinks, $url, $config)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $recfile = '' . dirname(__FILE__) . '/m3u/' . strip_tags($pubname) . '.mp4';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' ' . $recfile . ' >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }


  public function StartOtherStreamLin($pubname, $tslinks, $url, $config, $port)
  {
    set_time_limit(0);
    $tslink = 'rtmp://localhost:' . $port . '/' . strip_tags($pubname) . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv ' . $tslink . ' >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br>
  <pre><br>
  ' . $com . '
  </pre><br>
  <b>Server : ' . $tslink . '</b><br>';
    echo '<br><b>URL : rtmp://' . $_SERVER['HTTP_HOST'] . ':' . $port . '/' . strip_tags($pubname) . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartOtherStreamWin($pubname, $tslinks, $url, $config, $port)
  {
    set_time_limit(0);
    $tslink = 'rtmp://localhost:' . $port . '/' . strip_tags($pubname) . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv ' . $tslink . ' >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br>
  <pre><br>
  ' . $com . '
  </pre><br>
  <b>Server : ' . $tslink . '</b><br>';
    echo '<br><b>URL : rtmp://' . $_SERVER['HTTP_HOST'] . ':' . $port . '/' . strip_tags($pubname) . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartYouTubeTSStreamLinux($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmp://a.rtmp.youtube.com/live2/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartYouTubeTSStreamWin($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmp://a.rtmp.youtube.com/live2/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartIGTSStreamWin($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmps://live-upload.instagram.com:443/rtmp/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartIGTSStreamLinux($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmps://live-upload.instagram.com:443/rtmp/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartCstTSStreamWin($pubname, $tslinks, $url, $config, $url2, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "' . $url2 . '/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartCstTSStreamLinux($pubname, $tslinks, $url, $config, $url2, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "' . $url2 . '/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartTwitchTSStreamWin($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmp://live-cdg.twitch.tv/app/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartTwitchTSStreamLinux($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = 'ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmp://live-cdg.twitch.tv/app/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartRestreamTSStreamLinux($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    if (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "tr") {
      $com = 'ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmp://istanbul.restream.io/live/' . $token . '" >"' . $logfile . '" 2>&1';
    } else {
      $com = 'ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmp://live.restream.io/live/' . $token . '" >"' . $logfile . '" 2>&1';
    }
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartRestreamTSStreamWin($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    if (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "tr") {
      $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmp://istanbul.restream.io/live/' . $token . '" >"' . $logfile . '" 2>&1';
    } else {
      $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmp://live.restream.io/live/' . $token . '" >"' . $logfile . '" 2>&1';
    }
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartFacebookTSStreamLinux($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmps://live-api-s.facebook.com:443/rtmp/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartFacebookTSStreamWin($pubname, $tslinks, $url, $config, $token)
  {
    set_time_limit(0);
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' -f flv "rtmps://live-api-s.facebook.com:443/rtmp/' . $token . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartM3U8Stream($pubname, $tslinks, $url, $config)
  {
    set_time_limit(0);
    $filename = '' . strip_tags($pubname) . '.m3u8';
    $tslink = '' . dirname(__FILE__) . '/m3u/' . $filename . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $config . ' "' . $tslink . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartM3U8StreamWin($pubname, $tslinks, $url, $config)
  {
    set_time_limit(0);
    $filename = '' . strip_tags($pubname) . '.m3u8';
    $tslink = '' . dirname(__FILE__) . '\m3u/' . $filename . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '\log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $config . ' "' . $tslink . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartTSStream($pubname, $tslinks, $url, $configts)
  {
    set_time_limit(0);
    $filename = '' . strip_tags($pubname) . '.m3u8';
    $tslink = '' . dirname(__FILE__) . '/m3u/' . $filename . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '/log/' . $logfilename . '';
    $com = ' ffmpeg -y -i "' . $tslinks . '" ' . $configts . ' "' . $tslink . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function StartTSStreamWin($pubname, $tslinks, $url, $configts)
  {
    set_time_limit(0);
    $filename = '' . strip_tags($pubname) . '.m3u8';
    $tslink = '' . dirname(__FILE__) . '\m3u/' . $filename . '';
    $logfilename = '' . strip_tags($pubname) . '-mylog.log';
    $logfile = '' . dirname(__FILE__) . '\log/' . $logfilename . '';
    $com = '' . dirname(__FILE__) . '\ffmpeg\ffmpeg -y -i "' . $tslinks . '" ' . $configts . ' "' . $tslink . '" >"' . $logfile . '" 2>&1';
    shell_exec($com);
    echo '<br>Command : <br><pre>' . $com . '</pre><br>';
    echo '<br><b>URL : ' . $url . '</b><br>';
    echo ('<pre>' . file_get_contents('log/' . $logfilename . '') . '</pre><br>');
  }

  public function M3U8Stream($pubname)
  {
    $filename = '' . strip_tags($pubname) . '.m3u8';
    header('Content-type: application/x-mpegURL');
    header('Content-Disposition: attachment; filename="' . strip_tags($filename) . '.m3u8"');
    echo '#EXTM3U
  #EXTINF:-1,### ' . $pubname . ' ###
  m3u/' . $pubname . '.m3u8';
  }
  public function TSStream($pubname)
  {
    $filename = '' . strip_tags($pubname) . '.ts';
    header('Content-type: video/MP2T');
    header('Content-Disposition: attachment; filename="' . strip_tags($filename) . '.ts"');
    echo '<code>' . file_get_contents('m3u/' . strip_tags($pubname) . '.ts') . '</code><br>';
  }

  public function Error($errorname)
  {
    die('<td align="center" width="90" height="90">
    <br></br>
    <b><u>' . strip_tags($errorname) . '</u></b>
    <hr></hr>
    <p>' . strip_tags($errorname) . '</p></td>');
  }

}
?>
