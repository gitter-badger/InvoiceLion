<?php

$id+=0;
$reminder+=0;

$tenant = DB::selectOne('select * from `tenants` WHERE `tenants`.`id` = ?', $_SESSION['user']['tenant_id']);
$invoice = DB::selectOne('select * from `invoices` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
$customer = DB::selectOne('select * from `customers` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'],$invoice['invoices']['customer_id']);
$invoicelines = DB::select('select * from `invoicelines` where `tenant_id` = ? AND `invoice_id`=?', $_SESSION['user']['tenant_id'],$id);

// calculate multiLine and set reminder
$invoice['invoices']['reminder'] = $reminder;
$invoice['invoices']['multiLine'] = count($invoicelines)>1;

$formatNumber = function($number, $decimals,$decimalPoint,$thousandsSeperator) {
    return number_format($number, $decimals, chr($decimalPoint), $thousandsSeperator?chr($thousandsSeperator):'');
};

$translateMonth = function($date,$lang) {
	$months = array(
        'en' => array("January","February","March","April","May","June","July","August","September","October","November","December"),
        'nl' => array("januari","februari","maart","april","mei","juni","juli","augustus","september","oktober","november","december"),
    );
	return str_replace($months['en'], $months[$lang], $date);
};

$relativeDate = function($days) {
	return strtotime("+$days days");
};

$formatDate = function($date,$format) {
    return date($format, $date);
};

$eq = function($a,$b) {
    return $a==$b;
};

$functions = array(
    'encodeBase64'=>'base64_encode',
    'formatDate'=>$formatDate,
    'formatNumber'=>$formatNumber,
    'relativeDate'=>$relativeDate,
    'translateMonth'=>$translateMonth,
    'convertLines'=>'nl2br',
    'capitalize'=>'ucfirst',
    'eq'=>$eq,
);

$data = array(
    'company'=>$tenant['tenants'],
    'invoice'=>$invoice['invoices'],
    'customer'=>$customer['customers'],
    'lines'=>array_map(function($v){return $v['invoicelines'];},$invoicelines),
    'now'=>time(),
);

Buffer::set('invoice_styles',$tenant['tenants']['invoice_styles']);
$template = $tenant['tenants']['invoice_template'];
$template = Template::render($template, $data, $functions);
$template = preg_replace('/<script[^>]*>(.*?)<\/script>/is', "", $template);
Buffer::set('invoice_template',$template);
