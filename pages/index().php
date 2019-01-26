<?php
$invoicelines_no_invoices = DB::select('SELECT * FROM `invoicelines` WHERE `tenant_id` = ? AND (invoice_id IS NULL or invoice_id=0)', $_SESSION['user']['tenant_id']);

$unpaid_invoices = DB::select('SELECT * FROM invoices WHERE `tenant_id` = ? AND (sent and (paid IS NULL or paid=0)) ORDER BY name', $_SESSION['user']['tenant_id']);

$unsent_invoices = DB::select('SELECT * FROM invoices WHERE `tenant_id` = ? AND (invoices.sent IS NULL or invoices.sent=0) ORDER BY invoices.name', $_SESSION['user']['tenant_id']);

$unsent_reminders = DB::select('SELECT * FROM invoices WHERE `tenant_id` = ? AND (sent and (paid IS NULL or paid=0)) and date < NOW() - INTERVAL 1 MONTH and reminder1 IS NULL ORDER BY name', $_SESSION['user']['tenant_id']);

$unsent_reminders2 = DB::select('SELECT * FROM invoices WHERE `tenant_id` = ? AND (paid IS NULL or paid=0) and reminder1 < NOW() - INTERVAL 2 WEEK and reminder2 IS NULL ORDER BY name', $_SESSION['user']['tenant_id']);

$missing_period_ids = DB::selectValues('SELECT `subscriptions`.id, ceil(timestampdiff(MONTH,`subscriptions`.`from`, if(`subscriptions`.`canceled` is null,now(),`subscriptions`.`canceled`))/(`subscriptions`.`months`)) as `expected_periods`, count(`subscriptionperiods`.id) as `actual_periods` from `subscriptions`, `subscriptionperiods` where `subscriptionperiods`.`tenant_id` = ? AND  `subscriptionperiods`.`subscription_id` = `subscriptions`.`id` group by `subscriptions`.id having `actual_periods` < `expected_periods`', $_SESSION['user']['tenant_id']);

if($missing_period_ids) $erronous_subscriptions = DB::select('SELECT * FROM subscriptions WHERE subscriptions.`tenant_id` = ? AND id IN ('.implode(',',$missing_period_ids).')', $_SESSION['user']['tenant_id']);

$hours_no_invoices = DB::select('SELECT `hours`.* FROM `hours`, `invoicelines` WHERE `hours`.`tenant_id` = ? AND `hours`.`invoiceline_id` = `invoicelines`.`id` AND (`invoicelines`.`subtotal` IS NOT NULL and `invoicelines`.`subtotal`<>0) AND `invoicelines`.`invoice_id` IS NULL', $_SESSION['user']['tenant_id']);

$periods_no_invoices = DB::select('SELECT `subscriptionperiods`.*, `subscriptions`.`fee` FROM `subscriptionperiods`, `subscriptions`, `invoicelines` WHERE `subscriptionperiods`.`tenant_id` = ? AND `subscriptionperiods`.`invoiceline_id` = `invoicelines`.`id` AND `subscriptionperiods`.`subscription_id` = `subscriptions`.`id` AND `invoicelines`.`invoice_id` IS NULL', $_SESSION['user']['tenant_id']);
