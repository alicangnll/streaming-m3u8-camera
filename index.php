<?php
include("libs.php");
$getir = new IPTVClass();
$getir->funcControl('shell_exec');
$getir->funcControl('exec');
$getir->funcControl('system');
$getir->require_auth('admin', '1234');

//Load Configs
$jsonfile = file_get_contents("conf.json");
$loadconfig = json_decode($jsonfile, true);
$text = $loadconfig["stream_text_left"]; //Text on Stream
$configm3u8 = str_replace("example_text", $text, $loadconfig["m3u8_stream_config"]);
$configflv = str_replace("example_text", $text, $loadconfig["flv_stream_config"]);

//M3U8 Streaming
$configts = $loadconfig["ts_stream_config"];
$m3u8 = $loadconfig["m3u8_link"]; //Edit M3U8

// Social Media Connection
$facebooktk = $loadconfig["facebook_token"];
$twitchtk = $loadconfig["twitch_token"];
$youtubetk = $loadconfig["youtube_token"];
$instatk = $loadconfig["instagram_token"];
$restreamtk = $loadconfig["restream_token"];

//RTMP Streaming
$link = $loadconfig["rtmp_link"];
$token = md5($getir->RandomString());


if (!isset($_GET['git'])) {
	$sayfa = 'index';
} elseif (empty($_GET['git'])) {

	if (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "tr") {
		$getir->Error("Sayfa Bulunamadı");
	} else {
		$getir->Error("Page Not Found");
	}

} else {
	$sayfa = strip_tags($_GET['git']);
}

switch ($sayfa) {

	case 'index':
		echo '<head>
	<title>PHP LiveCam Class v1.1</title>
	</head>';
		$code = "sudo yum -y install --nogpgcheck https://dl.fedoraproject.org/pub/epel/epel-release-latest-8.noarch.rpm
sudo yum -y install --nogpgcheck https://download1.rpmfusion.org/free/el/rpmfusion-free-release-8.noarch.rpm 
sudo rpm -ivh https://download1.rpmfusion.org/nonfree/el/rpmfusion-nonfree-release-8.noarch.rpm
sudo yum -y install nginx epel-release screen php-fpm php-common php-cli mariadb mariadb-server 
sudo dnf config-manager --enable powertools
sudo yum -y install ffmpeg ffmpeg-devel
chown -R apache:apache " . dirname(__FILE__) . "
chmod 777 -R " . dirname(__FILE__) . "
setenforce 0 && sed -i 's/SELINUX=enforcing/SELINUX=permissive/g' /etc/sysconfig/selinux
";
		echo '<script>
  function timedRefresh(timeoutPeriod) {
	setTimeout("location.reload(true);",timeoutPeriod);
	}
	window.onload = timedRefresh(5000);
</script>
<b>You have to write this code as root user. If you are in linux.</b></br>
<b>When you want start stream, you must be edit index.php and add your restream m3u8 server. After that when you had started stream, you ccan find your m3u8 file in m3u folder</b>
<pre>' . htmlentities(strip_tags($code)) . '</pre></br>
<b>Live Stream</b>
<ul>
<li><a target="_blank" href="index.php?git=stream-m3u8">Stream M3U8</a></li>
<li><a target="_blank" href="index.php?git=stream-ts">Stream TS</a></li>
<li><a target="_blank" href="index.php?git=stream-insta">Stream Instagram</a></li>
<li><a target="_blank" href="index.php?git=stream-face">Stream Facebook</a></li>
<li><a target="_blank" href="index.php?git=stream-twitch">Stream Twitch</a></li>
<li><a target="_blank" href="index.php?git=stream-restream">Stream Restream</a></li>
<li><a target="_blank" href="index.php?git=stream-yt">Stream YouTube</a></li>
<li><a target="_blank" href="index.php?git=stream-custom">Stream Custom</a></li>
</ul>
<b>Live to M3U</b>
<ul>
<li><a target="_blank" href="index.php?git=M3UParser">M3U Parser</a></li>
<li><a target="_blank" href="index.php?git=YTM3U">YouTube Live to M3U</a></li>
<li><a target="_blank" href="index.php?git=twitch">Twitch Live to M3U</a></li>
</ul>
<b>Stream Options</b>
<ul>
<li><a href="index.php?git=stream-stop">Stop Streams</a></li>
<li><a target="_blank" href="./m3u">M3U Directory</a></li>
<li><a target="_blank" href="./log">Log Directory</a></li>
</ul>';
		break;

	case 'M3UParser':
		echo '<head>
		<title>PHP LiveCam Class v1.1</title>
		</head>
		<form action="index.php?git=PM3UParser" method="post">
		<label for="url">M3U URL:</label>
		<input type="text" id="url" name="url"><br><br>
		<input type="submit" value="Parse">
	  </form>';
		break;

	case 'PM3UParser':
		$data = json_decode($getir->M3U_Parser($_POST["url"]), true);
		foreach ($data["list"]["item"] as $list) {
			echo 'Name : ' . htmlentities(strip_tags($list["title"])) . '<br> URL : ' . htmlentities(strip_tags($list["media_url"])) . '<br>';
		}
		break;

	case 'YTM3U':
		echo '<head>
		<title>PHP LiveCam Class v1.1</title>
		</head>
		<form action="index.php?git=pytm3u8" method="post">
			<label for="url">YouTube ID (https://www.youtube.com/watch?v=ID):</label>
			<input type="text" id="url" name="url"><br><br>
			<input type="submit" value="Parse">
		  </form>';
		break;

	case 'pytm3u8':
		echo $getir->YouTubeM3U8Gen($_POST["url"]);
		break;

	case 'twitch':
		echo '<head>
		<title>PHP LiveCam Class v1.1</title>
		</head>
		<form action="index.php?git=ptwitch" method="post">
				<label for="url">Twitch ID (https://www.twitch.tv/ID):</label>
				<input type="text" id="url" name="url"><br><br>
				<input type="submit" value="Parse">
			  </form>';
		break;

	case 'ptwitch':
		header('Content-Type: text/plain');
		echo str_replace("#", "\n#", $getir->get_twich_playlist($_POST["url"]));
		break;

	case 'stream-m3u8':
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$getir->StartM3U8StreamWin(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configm3u8);
		} else {
			$getir->StartM3U8Stream(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configm3u8);
		}
		break;

	case 'stream-ts':
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$getir->StartTSStreamWin(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configts);
		} else {
			$getir->StartTSStream(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configts);
		}
		break;

	case 'stream-insta':
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$getir->StartIGTSStreamWin(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($instatk));
		} else {
			$getir->StartIGTSStreamLinux(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($instatk));
		}
		break;

	case 'stream-face':
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$getir->StartFacebookTSStreamWin(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($facebooktk));
		} else {
			$getir->StartFacebookTSStreamLinux(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($facebooktk));
		}
		break;

	case 'stream-twitch':
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$getir->StartTwitchTSStreamWin(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($twitchtk));
		} else {
			$getir->StartTwitchTSStreamLinux(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($twitchtk));
		}
		break;

	case 'stream-restream':
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$getir->StartRestreamTSStreamWin(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($restreamtk));
		} else {
			$getir->StartRestreamTSStreamLinux(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($restreamtk));
		}
		break;

	case 'stream-yt':
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$getir->StartYouTubeTSStreamWin(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($youtubetk));
		} else {
			$getir->StartYouTubeTSStreamLinux(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($youtubetk));
		}
		break;

	case 'stream-custom':
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$getir->StartCstTSStreamWin(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($link), strip_tags($token));
		} else {
			$getir->StartCstTSStreamLinux(strip_tags(trim("channel-" . md5($getir->RandomString()) . "")), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($link), strip_tags($token));
		}
		break;

	case 'stream-stop':
		$getir->StopFFMPEG();
		break;

}
?>