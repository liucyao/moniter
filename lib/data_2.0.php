<?php
include_once "cacti_lib.php";
include_once "open-falcon_lib.php";
ob_start("ob_gzhandler");

$start = time() - 5400;
$end = time();

$USG6330 = get_data_history_echarts("USG6330-192.168.99.235", "huawei.SecStatMonTotalSe", $start, $end);
$USG6550 = get_data_history_echarts("USG6550-192.168.99.252", "huawei.SecStatMonTotalSe", $start, $end);

$con = array();
$con["xAxis"] = $USG6330["x"];
$con["usg6330"] = $USG6330["y"];
$con["usg6550"] = $USG6550["y"];
$con["legend"] = ["USG6330", "USG6550"];


//温度
$water = get_data_history_echarts("海晴居-核心-10.255.255.253", "switch.Temperature", $start, $end);
$joyful = get_data_history_echarts("晋福楼-核心-10.255.255.252", "switch.Temperature", $start, $end);
$admin = get_data_history_echarts("itRoomEnvironment", "CGC机房-A-温度", $start, $end);
$club = get_data_history_echarts("俱乐部-核心-10.255.255.251", "switch.Temperature", $start, $end);
$mingdu = get_data_history_echarts("缤纷世界核心-10.255.255.217", "switch.Temperature", $start, $end);
$temperature = array();
$temperature["xAxis"] = $water["x"];
$temperature["water"] = $water["y"];
$temperature["joyful"] = $joyful["y"];
$temperature["admin"] = $admin["y"];
$temperature["club"] = $club["y"];
$temperature["mingdu"] = $mingdu["y"];
$temperature["legend"] = ["海晴居", "晋福楼", "集团中心", "俱乐部", "缤纷世界"];

//出口流量
$lt_in = get_data_history_echarts('CGC-17F-OutsideSW-172.25.0.13', 'switch.if.In/ifIndex=18,ifName=GigabitEthernet0/0/13', $start, $end);
$lt_out = get_data_history_echarts('CGC-17F-OutsideSW-172.25.0.13', 'switch.if.Out/ifIndex=18,ifName=GigabitEthernet0/0/13', $start, $end);
$dx_in = get_data_history_echarts('CGC-17F-AR2240-172.25.0.14', 'switch.if.In/ifIndex=7,ifName=GigabitEthernet0/0/4', $start, $end);
$dx_out = get_data_history_echarts('CGC-17F-AR2240-172.25.0.14', 'switch.if.Out/ifIndex=7,ifName=GigabitEthernet0/0/4', $start, $end);
$outside = array();
$outside["xAxis"] = $lt_in["x"];
$outside["lt_in"] = $lt_in["y"];
$outside["lt_out"] = $lt_out["y"];
$outside["dx_in"] = $dx_in["y"];
$outside["dx_out"] = $dx_out["y"];

$outside["legend"] = ["电信-下载", "电信-上传", "联通-下载", "联通-上传"];



$traffic_253_in = get_data_history_echarts('CGC-核心-10.255.255.254', 'switch.if.In/ifIndex=341,ifName=Eth-Trunk18', $start, $end);
$traffic_253_out =  get_data_history_echarts('CGC-核心-10.255.255.254', 'switch.if.Out/ifIndex=341,ifName=Eth-Trunk18', $start, $end);

$traffic_252_in =  get_data_history_echarts('CGC-核心-10.255.255.254', 'switch.if.In/ifIndex=343,ifName=Eth-Trunk19', $start, $end);
$traffic_252_out =  get_data_history_echarts('CGC-核心-10.255.255.254', 'switch.if.Out/ifIndex=343,ifName=Eth-Trunk19', $start, $end);

$traffic_251_in =  get_data_history_echarts('CGC-核心-10.255.255.254', 'switch.if.In/ifIndex=350,ifName=Eth-Trunk22', $start, $end);
$traffic_251_out =  get_data_history_echarts('CGC-核心-10.255.255.254', 'switch.if.Out/ifIndex=350,ifName=Eth-Trunk22', $start, $end);

$traffic_217_in =  get_data_history_echarts('CGC-核心-10.255.255.254', 'switch.if.In/ifIndex=349,ifName=Eth-Trunk21', $start, $end);
$traffic_217_out =  get_data_history_echarts('CGC-核心-10.255.255.254', 'switch.if.Out/ifIndex=349,ifName=Eth-Trunk21', $start, $end);



$traffic = array();
$traffic['xAxis'] = $traffic_217_in['x'];
$traffic['all_217'] = $traffic_217_in['y'] + $traffic_217_out['x'];
$traffic['all_253'] = $traffic_253_in['y'] + $traffic_253_out['x'];
$traffic['all_252'] = $traffic_252_in['y'] + $traffic_252_out['x'];
$traffic['all_251'] = $traffic_251_in['y'] + $traffic_251_out['x'];

$traffic['legend'] = ['缤纷世界', '海晴居', '晋福楼', '俱乐部'];


$data = array();
$data["con"] = $con;
$data["temperature"] = $temperature;
$data["outside"] = $outside;
$data["inside"] = $traffic;
$data["downlist"] = HostDown();

echo json_encode($data);
