<?php
/*
Plugin Name: 百度学术任务
Version: 1.0
Plugin URL: https://ailand.date
Description: 百度学术任务
Author: KDNETWORK
Author Email: kasumi@bangdream.fun
Author URL: https://ailand.date
For: V3.1+
*/

function kd_xueshu_set(){
	echo '<tr><td>附加签到</td><td><input type="checkbox" name="kd_xueshu" ';
	if (option::uget('kd_xueshu')) { echo 'checked'; }
	echo '> 百度学术任务</td></tr>';
}
function kd_xueshu_setting() {
	global $PostArray;
	if (!empty($PostArray)) {
		$PostArray[] = 'kd_xueshu';
	}
}

addAction('set_2','kd_xueshu_set');
addAction('set_save1','kd_xueshu_setting');