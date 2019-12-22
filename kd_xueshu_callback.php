<?php
if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 

function callback_active() {}

function callback_init() {
	cron::set('kd_xueshu','plugins/kd_xueshu/kd_xueshu_cron.php',0,0,0);
}

function callback_inactive() {
	cron::del('kd_xueshu');
}

function callback_remove() {
	option::del('kd_xueshu_run');
	option::del('kd_xueshu_log');
	global $m;
    $m->query("DELETE FROM ".DB_PREFIX."users_options WHERE NAME='kd_xueshu'");
}