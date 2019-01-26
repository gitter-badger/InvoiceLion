<?php

include('pages/invoices/download($id,$reminder).php');

function formatDate($date) {
	$months_en = array("January","February","March","April","May","June","July","August","September","October","November","December");
	$months_nl = array("januari","februari","maart","april","mei","juni","juli","augustus","september","oktober","november","december");
	return str_replace($months_en, $months_nl, date("j F Y", strtotime($date)));
}

$invoice['invoices']['date'] = formatDate($invoice['invoices']['date']);
$currentdate = formatDate(date('j F Y'));
$vervaldate = formatDate(date('j F Y', strtotime("+14 days")));

$number = $invoice['invoices']['number'];
$name = $invoice['invoices']['name'];
$date = $invoice['invoices']['date'];
$customer_id = $invoice['invoices']['customer_id'];
$kl_id = $customer['customers']['id'];
$kl_name = $customer['customers']['name'];
$kl_email = $customer['customers']['email'];
$kl_contact = $customer['customers']['contact'];
$kl_address = $customer['customers']['address'];
$kl_vat_reverse_charge = $customer['customers']['vat_reverse_charge'];

$include = 1;
include('pages/invoices/download(none).phtml');
$attachmentcontent = $pdfoutput;
$attachmentfilename = $filename;

if($reminder==1) $subject = "Herinnering: Factuur ".$number.' - '.$name;
else if($reminder==2) $subject = "Laatste herinnering: Factuur ".$number.' - '.$name;
else $subject = "Factuur ".$number.' - '.$name;

$content = 'Beste '.$kl_contact.',<br /><br />';
if($reminder==1) $content .= 'Hierbij sturen we u een herinnering aan onderstaande digitale factuur:';
else if($reminder==2) $content .= 'Hierbij sturen we u een tweede herinnering aan onderstaande digitale factuur:';
else $content .= 'Bijgaand vindt u de digitale factuur met de volgende kenmerken:';

$content .= '<br /><br />Factuurdatum: '.$date.'<br />Factuurnummer: '.$number.'<br />totaalbedrag : € ';
if($kl_vat_reverse_charge==1) $content .= number_format((1*$totaal), 2, ',', '');
else $content .= number_format((1.21*$totaal), 2, ',', '');
$content .= '<br /><br />Wilt u deze factuur op een ander e-mailaddress ontvangen, stuur ons dan een reply-mail.<br /><br />';
$content .= 'Wij hopen u hiermee voldoende geïnformeerd te hebben.<br /><br />Met vriendelijke groet,<br /><br />';
$content .= $tenant['tenants']['contact'].'<br />'.nl2br($tenant['tenants']['address']).'<br />M: '.$tenant['tenants']['phone'].'<br />E: '.$tenant['tenants']['email'];

$from = $tenant['tenants']['invoice_email'];
$fromName = $tenant['tenants']['name'];

if (Debugger::$enabled) {
	$error = var_export([$kl_email,$from,$subject,$content],true);
} else {
	$error = Email::send($kl_email,$kl_name,$from,$fromName,$subject,$content,$attachmentcontent,$attachmentfilename);
}

if (!$error) {
	if($reminder==1) DB::update('update invoices set reminder1=now() WHERE `tenant_id` = ? AND id=?', $_SESSION['user']['tenant_id'],$id);
	else if($reminder==2) DB::update('update invoices set reminder2=now() WHERE `tenant_id` = ? AND id=?', $_SESSION['user']['tenant_id'],$id);
	else DB::update('update invoices set sent=1 WHERE `tenant_id` = ? AND id=?', $_SESSION['user']['tenant_id'],$id);
	Flash::set('success','Factuur sent');
	Router::redirect('invoices/index');
}