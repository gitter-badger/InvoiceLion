<?php 
$subscriptionIncome = DB::selectValue('SELECT SUM((fee*(12/months))/12) FROM subscriptions WHERE `tenant_id` = ? AND canceled IS NULL', $_SESSION['user']['tenant_id']);

$incomeThisYear = DB::selectValue('SELECT sum(subtotal) FROM invoices WHERE `tenant_id` = ? AND `sent` and YEAR(`date`) = YEAR(NOW())', $_SESSION['user']['tenant_id']);

$incomeThisYearAddon = DB::selectValue('SELECT sum(subtotal) FROM invoicelines WHERE `tenant_id` = ? AND `invoice_id` IS NULL', $_SESSION['user']['tenant_id']);

$incomeLastYear = DB::selectValue('SELECT sum(subtotal) FROM invoices WHERE `tenant_id` = ? AND `sent` and YEAR(`date`) = YEAR(NOW())-1', $_SESSION['user']['tenant_id']);

$sumhourspertype_thisyear = DB::selectPairs('SELECT `hourtypes`.name,SUM(hours_worked) as `hourtypes.sum` FROM `hours` LEFT JOIN `hourtypes` ON `hours`.`type` = `hourtypes`.id WHERE `hours`.`tenant_id` = ? AND year(hours.date) = year(NOW()) GROUP BY `type`', $_SESSION['user']['tenant_id']);
arsort($sumhourspertype_thisyear);
$totalhours_thisyear = array_sum($sumhourspertype_thisyear);

$sumhourspertype_lastyear = DB::selectPairs('SELECT `hourtypes`.name,SUM(hours_worked) as `hourtypes.sum` FROM `hours` LEFT JOIN `hourtypes` ON `hours`.`type` = `hourtypes`.id WHERE `hours`.`tenant_id` = ? AND year(hours.date) = year(NOW())-1 GROUP BY `type`', $_SESSION['user']['tenant_id']);
arsort($sumhourspertype_lastyear);
$totalhours_lastyear = array_sum($sumhourspertype_lastyear);
