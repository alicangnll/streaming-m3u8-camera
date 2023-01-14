<?php 
$files = array_diff(scandir("../m3u"), array('.', '..', "index.php"));
echo '<table>
<thead>
<tr>
<th>M3U</th>
</tr>
</thead>
<tbody>';
foreach($files as $dosyalar) {
    if (strstr($dosyalar, "m3u8")) {
        echo '<tr>
        <td><a href="../m3u/' . htmlentities(strip_tags($dosyalar)) . '">' . htmlentities(strip_tags($dosyalar)) . '</a></td>
        </tr>';
    }
}
echo '</tbody></table>';
?>
