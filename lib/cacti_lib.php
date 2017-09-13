<?php
function rrd($file, $datakey = 0, $num_digits = 2, $step = '360', $start = '-6h', $end = 'start+5h+54m', $xtype = 'H:i')
{
    $rrd = rrd_fetch($file, array('AVERAGE', '--resolution', $step, '--start', $start, '--end', $end));
    $filekey = array_keys($rrd['data'])[$datakey];
    $data = $rrd['data'][$filekey];
    foreach ($data as $k => $v) {
        $xAxis[] = date($xtype, $k);
        if (is_nan($v)||$v == 0) {
            //当接受到的数据为NAN时就要加这个判断 否则 json_encode时会出错
            $yAxis[] = '-';
        } else {
            $yAxis[] = round($v, $num_digits);
        }
    }
    $result['xAxis'] = $xAxis;
    $result['yAxis'] = $yAxis;

    return $result;
}
function Get_rrd_path($hostname, $name_cache)
{
    require_once 'connect.php';
    $sql_where = "SELECT `dtd`.data_source_path,`dt`.unit
                FROM data_local as dl,host,data_template as dt,data_template_data as dtd
                WHERE `dl`.host_id=`host`.id
                AND `dt`.id=`dl`.data_template_id
                AND `dl`.id=`dtd`.local_data_id
                AND `host`.hostname = '$hostname'
                AND `dtd`.name_cache LIKE '%$name_cache'
                LIMIT 1";
    $query = mysql_query($sql_where);
    while ($rows = mysql_fetch_array($query)) {
        $rrd_path = $rows['data_source_path'];
    }
    return str_replace('<path_rra>', '/mnt/rra', $rrd_path);
}

function HostDown()
{
    $time = date('Y-m-d H:i:s');
    $results = '';
    $downs = 0;
    $pdo = new PDO("mysql:host=192.168.99.22;dbname=swdashboard", "root", "viproot");
    foreach ($pdo->query('SELECT ipAddress,hostName,downTime FROM swdb WHERE disabled = "NO" AND status = "DOWN"') as $row) {
        $results .= $row["hostName"]." [".$row["ipAddress"]."]  ";
        $downs++;
    }

    if (!$downs) {
        $results = "满分";
    }
    $msg .= $results."  ".$time;
    $msgs = array('msg' =>$msg ,'time' =>$time,'downs' =>$downs );
    return $msgs;
}
