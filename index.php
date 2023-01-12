<?php
include("libs.php");
$getir = new IPTVClass();
$getir->funcControl('shell_exec');
$getir->funcControl('exec');
$getir->funcControl('system');
$getir->require_auth('admin', '1234');

echo '<head>
<title>PHP LiveCam Class v1.1</title>
</head>';

//Restream Configs
$text = "LiveCam-IPTV-Server-v1.2"; //Text on Stream
$configm3u8 = "-listen 1 -vcodec libx264 -s 1280x1024 -pix_fmt yuv420p -preset ultrafast -r 30 -g 60 -b:v 9500k -acodec aac -ar 44100 -threads 6 -qscale 3 -b:a 712000 -vf drawtext=fontfile=arial.ttf:fontcolor=red:box=1:fontsize=20:text=".htmlentities(strip_tags($text))." -bufsize 9500k";
$configflv = "-deinterlace -vcodec libx264 -x264opts keyint=8:min-keyint=6:scenecut=2 -s 1280x1024 -pix_fmt yuv420p -preset ultrafast -r 30 -g 60 -b:v 9500k -acodec aac -ar 44100 -threads 6 -qscale 3 -b:a 712000 -vf drawtext=fontfile=arial.ttf:fontcolor=red:box=1:fontsize=30:text=AliIPTV -bufsize 9500k";

//M3U8 Streaming
$configts = "-c:v copy -c:a copy -t 00:05:00";
$m3u8 = "https://d2zihajmogu5jn.cloudfront.net/bipbop-advanced/bipbop_16x9_variant.m3u8"; //Edit M3U8
$name = trim("channel-" . md5($getir->RandomString()) . ""); //Edit Channel Name

// Social Media Connection
$facebooktk = "";
$twitchtk = "";
$youtubetk = "";
$instatk = "";
$restreamtk = "";

//RTMP Streaming
$link = "rtmp://localhost:1935";
$token = "";


if(!isset($_GET['git'])) {
$sayfa = 'index';
} elseif(empty($_GET['git'])) {

if(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2) == "tr") {
$getir->Error("Sayfa Bulunamadı");
} else {
$getir->Error("Page Not Found");
}

} else {
$sayfa = strip_tags($_GET['git']);
}

switch ($sayfa) {
	
case 'index':
$code = "sudo yum -y install --nogpgcheck https://dl.fedoraproject.org/pub/epel/epel-release-latest-8.noarch.rpm
sudo yum -y install --nogpgcheck https://download1.rpmfusion.org/free/el/rpmfusion-free-release-8.noarch.rpm 
sudo rpm -ivh https://download1.rpmfusion.org/nonfree/el/rpmfusion-nonfree-release-8.noarch.rpm
sudo yum -y install nginx epel-release screen php-fpm php-common php-cli mariadb mariadb-server 
sudo dnf config-manager --enable powertools
sudo yum -y install ffmpeg ffmpeg-devel
chown -R apache:apache ".dirname(__FILE__)."
chmod 777 -R ".dirname(__FILE__)."
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
<pre>'.$code.'</pre></br>
<a target="_blank" href="index.php?git=M3UParser">M3U Parser</a></br>
<a target="_blank" href="index.php?git=stream-m3u8">Stream M3U8</a></br>
<a target="_blank" href="index.php?git=stream-ts">Stream TS</a></br>
<a target="_blank" href="index.php?git=stream-insta">Stream Instagram</a></br>
<a target="_blank" href="index.php?git=stream-face">Stream Facebook</a></br>
<a target="_blank" href="index.php?git=stream-twitch">Stream Twitch</a></br>
<a target="_blank" href="index.php?git=stream-restream">Stream Restream</a></br>
<a target="_blank" href="index.php?git=stream-yt">Stream YouTube</a></br>
<a target="_blank" href="index.php?git=stream-custom">Stream Custom</a></br>
<a href="index.php?git=stream-stop">Stop Streams</a></br>
<a target="_blank" href="./m3u">M3U Directory</a></br>
<a target="_blank" href="./log">Log Directory</a></br>';
break;

	case 'M3UParser':
		echo '<form action="index.php?git=PM3UParser" method="post">
		<label for="url">M3U URL:</label>
		<input type="text" id="url" name="url"><br><br>
		<input type="submit" value="Parse">
	  </form>';
	break;

	case 'PM3UParser':
		$data = json_decode($getir->M3U_Parser($_POST["url"]), true);
		foreach ($data["list"]["item"] as $list) {
			echo 'Name : ' . htmlentities(strip_tags($list["title"])) . '<br> URL : '.htmlentities(strip_tags($list["media_url"])).'<br>';
		}
	break;

case 'stream-m3u8':
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$getir->StartM3U8StreamWin(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configm3u8);
} else {
$getir->StartM3U8Stream(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configm3u8);
}
break;

case 'stream-ts':
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$getir->StartTSStreamWin(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configts);
} else {
$getir->StartTSStream(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configts);
}
break;

case 'stream-insta':
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$getir->StartIGTSStreamWin(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($instatk));
} else {
$getir->StartIGTSStreamLinux(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($instatk));
}
break;

case 'stream-face':
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$getir->StartFacebookTSStreamWin(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($facebooktk));
} else {
$getir->StartFacebookTSStreamLinux(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($facebooktk));
}
break;

case 'stream-twitch':
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$getir->StartTwitchTSStreamWin(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($twitchtk));
} else {
$getir->StartTwitchTSStreamLinux(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($twitchtk));
}
break;

case 'stream-restream':
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$getir->StartRestreamTSStreamWin(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($restreamtk));
} else {
$getir->StartRestreamTSStreamLinux(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($restreamtk));
}
break;

case 'stream-yt':
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$getir->StartYouTubeTSStreamWin(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($youtubetk));
} else {
$getir->StartYouTubeTSStreamLinux(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($youtubetk));
}
break;

case 'stream-custom':
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$getir->StartCstTSStreamWin(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($link), strip_tags($token));
} else {
$getir->StartCstTSStreamLinux(strip_tags($name), strip_tags($m3u8), strip_tags($m3u8), $configflv, strip_tags($link), strip_tags($token));
}
break;

case 'stream-stop':
$getir->StopFFMPEG();
break;
	
}
?>
