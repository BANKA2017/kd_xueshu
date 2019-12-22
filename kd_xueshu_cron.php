<?php
if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}

function cron_kd_xueshu() {
    
    //如果今天签到过了直接返回日志
    if (option::get('kd_xueshu_run') == date('d')) {
        return option::get('kd_xueshu_log');
    }
    global $m;
    $prefix = DB_PREFIX;
    
    //选出用户的options和bduss
    $res = $m->query("SELECT {$prefix}users_options.`name` , {$prefix}users_options.`value` , {$prefix}baiduid.`bduss` 
FROM {$prefix}baiduid
INNER JOIN {$prefix}users_options ON {$prefix}users_options.uid = {$prefix}baiduid.uid
WHERE {$prefix}users_options.`name` =  'kd_xueshu'");

    $xs = 0;
    $bduss = Array();
    if($m->num_rows($res) != 0){
        while ($row = $res->fetch_array()) {
            //判断该选项是否开启
            if($row['value'] == 'on'){
                //记录bduss（数量），如果bduss数组内没有该bduss，则加入数组
                if (!in_array($row['bduss'], $bduss)) {
                    $bduss[] = $row['bduss'];
                }
                if ($row['name'] === 'kd_xueshu') {
                    $xs++;
					$head = array(
						'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
					);
					foreach (["firstLookSub" => 1, "firstLogin" => 1, "firstLoginHelp" => 1, "firstSimpleSearch" => 1, "firstAdvancedSearch" => 1, "firstLookFavorite" => 1, "recommendKeyword" => 1, "newLable" => 3, "favoritePaper" => 2, "addSub" => 2] as $key => $value) {
                        for ($x = 0; $x < $value; $x++) {
                            $c = new wcurl('https://xueshu.baidu.com/usercenter/show/userinfo?cmd=update_task&task_type=' . $key, $head);
                            $c->addCookie('BDUSS=' . $row['bduss']);
                            $c->exec();
                            $c->close();
                        }
                    }
                }
            }
        }
    }
    
    $log = "学术任务完毕<br/>" . date("Y-m-d H:i:s") . "<br/>共计百度账号: " . count($bduss) . " 个<br/>学术任务: {$xs} 个";
    option::set('kd_xueshu_run', date('d'));
    option::set('kd_xueshu_log', $log);
    return $log;
}
