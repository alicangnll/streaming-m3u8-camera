<?php

class IPTVClass {

public function extControl($name) {
  if (!extension_loaded(''.strip_tags($name).'')) {
    die('The '.strip_tags($name).' extension is not loaded.');
}
}

public function funcControl($name) {
  if (!function_exists(''.strip_tags($name).'')) {
    die('The '.strip_tags($name).' function is not loaded.');
}
}

public function getIPAddress() {
$client  = @$_SERVER['HTTP_CLIENT_IP'];
$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
$remote  = $_SERVER['REMOTE_ADDR'];

if(filter_var($client, FILTER_VALIDATE_IP))
{
    $ip = $client;
}
elseif(filter_var($forward, FILTER_VALIDATE_IP))
{
    $ip = $forward;
}
else
{
    $ip = $remote;
}

return $ip;
}

public function M3U8DebugStream($pubname, $tslinks, $config) {
  $filename = ''.strip_tags($pubname).'.m3u8';
  $tslink = ''.dirname(__FILE__).'/m3u/'.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' "'.$tslink.'" >"'.$logfile.'" 2>&1';
  echo '<meta name="viewport" content="width=device-width, initial-scale=1">
	<br><textarea class="container" data-role="textarea" style="width:100%;height:50%;">'.$com.'</textarea><br><br>';
  if(!$fp = @fopen(strip_tags($tslink), "r")) {
    echo '<br><b>Stream : (Can Stream) Online</b>';
  } else {
    echo '<br><b>Stream : (Cant Stream)Offline</b>';
  }
  die();
}

public function M3U8DebugStreamWin($pubname, $tslinks, $config) {
  $filename = ''.strip_tags($pubname).'.m3u8';
  $tslink = ''.dirname(__FILE__).'\m3u/'.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'\log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' "'.$tslink.'" >"'.$logfile.'" 2>&1';
  echo '<meta name="viewport" content="width=device-width, initial-scale=1">
	<br><textarea class="container" data-role="textarea" style="width:100%;height:50%;">'.$com.'</textarea><br><br>';
  if(!$fp = @fopen(strip_tags($tslink), "r")) {
    echo '<br><b>Stream : (Can Stream) Online</b>';
  } else {
    echo '<br><b>Stream : (Cant Stream)Offline</b>';
  }
  die();
}

public function StopFFMPEG() {
  echo '<b>FFMpeg Killing...</b<br>';
  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$winffmpeg = 'taskkill /F /IM "ffmpeg.exe"';
    $winrtsp = 'taskkill /F /IM "rtsp-simple-server.exe"';
	shell_exec($winffmpeg);
    shell_exec($winrtsp);
	echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Exit | ".$winffmpeg." & ".$winrtsp."');
    window.location.href='index.php';
    </script>";
  } else {
  $linffmpeg = "pkill ffmpeg";
  $linrtsp = "pkill rtsp-simple-server";
  shell_exec($linffmpeg);
  shell_exec($linrtsp);
  echo "<script LANGUAGE='JavaScript'>
  window.alert('Succesfully Exit | ".$linffmpeg." & ".$linrtsp."');
  window.location.href='index.php';
  </script>";
  }
}
public function TSDebugStream($pubname, $tslinks, $configts) {
  $filename = ''.strip_tags($pubname).'.ts';
  $tslink = ''.dirname(__FILE__).'/m3u/'.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$configts.' "'.$tslink.'" >"'.$logfile.'" 2>&1';
  echo '<meta name="viewport" content="width=device-width, initial-scale=1">
	<br><textarea class="container" data-role="textarea" style="width:100%;height:50%;">'.$com.'</textarea><br><br>';
  if(!$fp = @fopen(strip_tags($tslink), "r")) {
    echo '<b>Online</b>';
  } else {
    echo '<b>Offline</b>';
  }
  die();
}

public function TSDebugStreamWin($pubname, $tslinks, $configts) {
  $filename = ''.strip_tags($pubname).'.ts';
  $tslink = ''.dirname(__FILE__).'\m3u/'.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'\log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$configts.' "'.$tslink.'" >"'.$logfile.'" 2>&1';
  echo '<meta name="viewport" content="width=device-width, initial-scale=1">
	<br><textarea class="container" data-role="textarea" style="width:100%;height:50%;">'.$com.'</textarea><br><br>';
  
  if(!$fp = @fopen(strip_tags($tslink), "r")) {
    echo '<b>Online</b>';
  } else {
    echo '<b>Offline</b>';
  }
  die();
}

public function StartRecordStreamLin($pubname, $tslinks, $url, $config) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $recfile = ''.dirname(__FILE__).'/m3u/'.strip_tags($pubname).'.mp4';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' '.$recfile.' >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartRecordStreamWin($pubname, $tslinks, $url, $config) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $recfile = ''.dirname(__FILE__).'/m3u/'.strip_tags($pubname).'.mp4';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' '.$recfile.' >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}


public function StartOtherStreamLin($pubname, $tslinks, $url, $config, $port) {
  set_time_limit(0);
  $tslink = 'rtmp://localhost:'.$port.'/'.strip_tags($pubname).'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com1 = '-rtmp '.dirname(__FILE__).'/rtsp/rtsp-simple-server';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv '.$tslink.' >"'.$logfile.'" 2>&1';
  shell_exec($com1);
  shell_exec($com);
  echo '<br>Command : <br>
  <pre>
  '.$com1.'<br>
  '.$com.'
  </pre><br>
  <b>Server : '.$tslink.'</b><br>';
  echo '<br><b>URL : rtmp://'.$_SERVER['HTTP_HOST'].':'.$port.'/'.strip_tags($pubname).'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartOtherStreamWin($pubname, $tslinks, $url, $config, $port) {
  set_time_limit(0);
  $tslink = 'rtmp://localhost:'.$port.'/'.strip_tags($pubname).'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com1 = ''.dirname(__FILE__).'\rtsp\rtsp-simple-server.exe';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv '.$tslink.' >"'.$logfile.'" 2>&1';
  shell_exec($com1);
  shell_exec($com);
  echo '<br>Command : <br>
  <pre>
  '.$com1.'<br>
  '.$com.'
  </pre><br>
  <b>Server : '.$tslink.'</b><br>';
  echo '<br><b>URL : rtmp://'.$_SERVER['HTTP_HOST'].':'.$port.'/'.strip_tags($pubname).'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartYouTubeTSStreamLinux($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmp://a.rtmp.youtube.com/live2/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartYouTubeTSStreamWin($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmp://a.rtmp.youtube.com/live2/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartIGTSStreamWin($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmps://live-upload.instagram.com:443/rtmp/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartIGTSStreamLinux($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmps://live-upload.instagram.com:443/rtmp/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartCstTSStreamWin($pubname, $tslinks, $url, $config, $url2, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "'.$url2.'/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartCstTSStreamLinux($pubname, $tslinks, $url, $config, $url2, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "'.$url2.'/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartTwitchTSStreamWin($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmp://live-cdg.twitch.tv/app/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartRestreamTSStreamLinux($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  if(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2) == "tr") {
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmp://istanbul.restream.io/live/'.$token.'" >"'.$logfile.'" 2>&1';
  } else {
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmp://live.restream.io/live/'.$token.'" >"'.$logfile.'" 2>&1';
  }
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartRestreamTSStreamWin($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  if(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2) == "tr") {
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmp://istanbul.restream.io/live/'.$token.'" >"'.$logfile.'" 2>&1';
  } else {
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmp://live.restream.io/live/'.$token.'" >"'.$logfile.'" 2>&1';
  }
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartFacebookTSStreamLinux($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmps://live-api-s.facebook.com:443/rtmp/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartFacebookTSStreamWin($pubname, $tslinks, $url, $config, $token) {
  set_time_limit(0);
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' -f flv "rtmps://live-api-s.facebook.com:443/rtmp/'.$token.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartM3U8Stream($pubname, $tslinks, $url, $config) {
  set_time_limit(0);
  $filename = ''.strip_tags($pubname).'.m3u8';
  $tslink = ''.dirname(__FILE__).'/m3u/'.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$config.' "'.$tslink.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartM3U8StreamWin($pubname, $tslinks, $url, $config) {
  set_time_limit(0);
  $filename = ''.strip_tags($pubname).'.m3u8';
  $tslink = ''.dirname(__FILE__).'\m3u/'.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'\log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$config.' "'.$tslink.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartTSStream($pubname, $tslinks, $url, $configts) {
  set_time_limit(0);
  $filename = ''.strip_tags($pubname).'.m3u8';
  $tslink = ''.dirname(__FILE__).'/m3u/'.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  $com = ' ffmpeg -y -i "'.$tslinks.'" '.$configts.' "'.$tslink.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function StartTSStreamWin($pubname, $tslinks, $url) {
  set_time_limit(0);
  $filename = ''.strip_tags($pubname).'.m3u8';
  $tslink = ''.dirname(__FILE__).'\m3u/'.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'\log/'.$logfilename.'';
  $com = ''.dirname(__FILE__).'\ffmpeg\ffmpeg -y -i "'.$tslinks.'" '.$configts.' "'.$tslink.'" >"'.$logfile.'" 2>&1';
  shell_exec($com);
  echo '<br>Command : <br><pre>'.$com.'</pre><br>';
  echo '<br><b>URL : '.$url.'</b><br>';
  echo('<pre>'.file_get_contents('log/'.$logfilename.'').'</pre><br>');
}

public function M3U8Stream($pubname) {
  $filename = ''.strip_tags($pubname).'.m3u8';
  $tslink = ''.dirname(__FILE__).''.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  header('Content-type: application/x-mpegURL');
  header('Content-Disposition: attachment; filename="'.strip_tags($filename).'.m3u8"');
  echo '#EXTM3U
  #EXTINF:-1,### '.$pubname.' ###
  m3u/'.$pubname.'.m3u8';
}
public function TSStream($pubname) {
  $filename = ''.strip_tags($pubname).'.ts';
  $tslink = ''.dirname(__FILE__).''.$filename.'';
  $logfilename = ''.strip_tags($pubname).'-mylog.log';
  $logfile = ''.dirname(__FILE__).'/log/'.$logfilename.'';
  header('Content-type: video/MP2T');
  header('Content-Disposition: attachment; filename="'.strip_tags($filename).'.ts"');
  echo '<code>'.file_get_contents('m3u/'.strip_tags($pubname).'.ts').'</code><br>';
}

public function Error($errorname) {
    die('<td align="center" width="90" height="90">
    <br></br>
    <b><u>'.strip_tags($errorname).'</u></b>
    <hr></hr>
    <p>'.strip_tags($errorname).'</p></td>');
  }
  
}
?>
