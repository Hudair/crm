<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-09 02:14:08 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:14:15 --> Query error: In aggregated query without GROUP BY, expression #1 of SELECT list contains nonaggregated column 'ziscoerp2.tbl_estimates.estimates_id'; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT  tbl_estimates.estimates_id, sum(tbl_estimate_items.total_cost) as cost
        FROM tbl_estimates
        LEFT JOIN tbl_estimate_items ON tbl_estimates.estimates_id = tbl_estimate_items.estimates_id
        WHERE tbl_estimates.status NOT IN ('draft', 'cancelled')
ERROR - 2022-03-09 05:14:15 --> Severity: error --> Exception: Call to a member function row() on bool D:\laragon\www\ziscoerp4\application\controllers\admin\Dashboard.php 154
ERROR - 2022-03-09 05:14:18 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_goal_tracking.goal_tracking_id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: 
        SELECT tbl_goal_tracking.goal_tracking_id, tbl_goal_type.goal_type_id, tbl_goal_type.type_name,
tbl_goal_tracking.account_id, COALESCE(SUM(tbl_goal_tracking.achievement), 0) AS target,

CASE

WHEN tbl_goal_type.type_name='achive_total_income' THEN
(SELECT COALESCE(SUM(tbl_transactions.amount), 0) FROM tbl_transactions  WHERE tbl_transactions.type='Income'  AND tbl_transactions.date >= '2022-03-01 00:00:00' AND tbl_transactions.date <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='achive_total_income_by_bank'  THEN
(SELECT coalesce(SUM(tbl_transactions.amount), 0) FROM tbl_transactions
WHERE  tbl_transactions.account_id = tbl_goal_tracking.account_id
AND tbl_transactions.type='Income'  AND tbl_transactions.date >= '2022-03-01 00:00:00' AND tbl_transactions.date <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='achieve_total_expense' THEN
(SELECT COALESCE(SUM(tbl_transactions.amount), 0) FROM tbl_transactions  WHERE tbl_transactions.type='Expense'  AND tbl_transactions.date >= '2022-03-01 00:00:00' AND tbl_transactions.date <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='achive_total_expense_by_bank' THEN
(SELECT COALESCE(SUM(tbl_transactions.amount), 0) FROM tbl_transactions  WHERE tbl_transactions.account_id = tbl_goal_tracking.account_id AND tbl_transactions.type='Expense' AND tbl_transactions.date >= '2022-03-01 00:00:00' AND tbl_transactions.date <= '2022-03-31 23:59:59')


WHEN tbl_goal_type.type_name='make_invoice' THEN
(SELECT COALESCE(COUNT(tbl_invoices.invoices_id), 0) FROM tbl_invoices  WHERE   tbl_invoices.date_saved >= '2022-03-01 00:00:00' AND tbl_invoices.date_saved <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='make_estimate' THEN
(SELECT COALESCE(COUNT(tbl_estimates.estimates_id), 0) FROM tbl_estimates  WHERE   tbl_estimates.date_saved >= '2022-03-01 00:00:00' AND tbl_estimates.date_saved <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='goal_payment' THEN
(SELECT COALESCE(SUM(tbl_payments.amount), 0) FROM tbl_payments  WHERE   tbl_payments.payment_date >= '2022-03-01 00:00:00' AND tbl_payments.payment_date <= '2022-03-31 23:59:59')


WHEN tbl_goal_type.type_name='task_done' THEN
(SELECT COALESCE(COUNT(tbl_task.task_id), 0) FROM tbl_task  WHERE   tbl_task.task_created_date >= '2022-03-01 00:00:00' AND tbl_task.task_created_date <= '2022-03-31 23:59:59' AND tbl_task.task_status = 'completed')


WHEN tbl_goal_type.type_name='resolved_bugs' THEN
(SELECT COALESCE(COUNT(tbl_bug.bug_id), 0) FROM tbl_bug  WHERE   tbl_bug.update_time >= '2022-03-01 00:00:00' AND tbl_bug.update_time <= '2022-03-31 23:59:59' AND tbl_bug.bug_status = 'resolved')


WHEN tbl_goal_type.type_name='convert_leads_to_client' THEN
(SELECT COALESCE(COUNT(tbl_client.client_id), 0) FROM tbl_client  WHERE   tbl_client.date_added >= '2022-03-01 00:00:00' AND tbl_client.date_added <= '2022-03-31 23:59:59' AND tbl_client.leads_id != 0)

WHEN tbl_goal_type.type_name='direct_client' THEN
(SELECT COALESCE(COUNT(tbl_client.client_id), 0) FROM tbl_client  WHERE   tbl_client.date_added >= '2022-03-01 00:00:00' AND tbl_client.date_added <= '2022-03-31 23:59:59' AND tbl_client.leads_id = 0)

WHEN tbl_goal_type.type_name='complete_project_goal' THEN
(SELECT COALESCE(COUNT(tbl_project.project_id), 0) FROM tbl_project  WHERE   tbl_project.start_date >= '2022-03-01 00:00:00' AND tbl_project.start_date <= '2022-03-31 23:59:59' AND tbl_project.project_status = 'completed')

END
AS amount_or_count

FROM `tbl_goal_type`

LEFT JOIN  tbl_goal_tracking ON tbl_goal_tracking.goal_type_id = tbl_goal_type.goal_type_id AND tbl_goal_tracking.end_date >= '2022-03-01 00:00:00' AND tbl_goal_tracking.end_date <= '2022-03-31 23:59:59'

LEFT JOIN  tbl_transactions ON tbl_goal_tracking.account_id = tbl_transactions.account_id


GROUP BY tbl_goal_type.goal_type_id
  
ERROR - 2022-03-09 05:14:18 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Admin_model.php 157
ERROR - 2022-03-09 02:14:22 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:14:35 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:14:35 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:14:41 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:14:41 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:17:16 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:17:16 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:17:28 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:17:28 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:17:36 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:17:36 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 02:17:44 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:17:54 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:17:54 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:18:01 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:18:01 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 02:18:50 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:18:50 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:18:51 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:19:14 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:19:14 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:19:15 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:19:28 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:19:28 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:19:46 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:19:46 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:19:51 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:19:51 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 02:22:39 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:22:39 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:23:07 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:23:07 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:23:23 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:23:23 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:25:28 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:25:28 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:27:22 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:27:22 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:27:23 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:27:23 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:28:00 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:28:00 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 02:28:08 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:28:34 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:30:38 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:30:59 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:30:59 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:31:53 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:32:39 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:32:40 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:32:48 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:32:48 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:34:56 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:35:33 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:35:33 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:35:38 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:35:38 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:36:09 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:09 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:36:15 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:15 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:36:16 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:16 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:36:20 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:20 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:36:24 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:24 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:36:25 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:25 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:36:26 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:26 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:36:27 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:27 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 05:36:28 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:36:28 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 02:36:59 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:36:59 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:37:06 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:37:06 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:40:17 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:40:17 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:40:28 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:40:28 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:40:29 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:42:50 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:42:51 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:43:25 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:44:52 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:44:57 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:45:05 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:45:22 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:45:22 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 02:45:39 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:47:12 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:47:26 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:47:26 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:47:47 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:47:47 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:48:35 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 05:48:35 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 02:49:35 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:50:12 --> 404 Page Not Found: /index
ERROR - 2022-03-09 02:59:29 --> 404 Page Not Found: /index
ERROR - 2022-03-09 05:59:32 --> Query error: In aggregated query without GROUP BY, expression #1 of SELECT list contains nonaggregated column 'ziscoerp2.tbl_estimates.estimates_id'; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT  tbl_estimates.estimates_id, sum(tbl_estimate_items.total_cost) as cost
        FROM tbl_estimates
        LEFT JOIN tbl_estimate_items ON tbl_estimates.estimates_id = tbl_estimate_items.estimates_id
        WHERE tbl_estimates.status NOT IN ('draft', 'cancelled')
ERROR - 2022-03-09 05:59:32 --> Severity: error --> Exception: Call to a member function row() on bool D:\laragon\www\ziscoerp4\application\controllers\admin\Dashboard.php 154
ERROR - 2022-03-09 05:59:33 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_goal_tracking.goal_tracking_id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: 
        SELECT tbl_goal_tracking.goal_tracking_id, tbl_goal_type.goal_type_id, tbl_goal_type.type_name,
tbl_goal_tracking.account_id, COALESCE(SUM(tbl_goal_tracking.achievement), 0) AS target,

CASE

WHEN tbl_goal_type.type_name='achive_total_income' THEN
(SELECT COALESCE(SUM(tbl_transactions.amount), 0) FROM tbl_transactions  WHERE tbl_transactions.type='Income'  AND tbl_transactions.date >= '2022-03-01 00:00:00' AND tbl_transactions.date <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='achive_total_income_by_bank'  THEN
(SELECT coalesce(SUM(tbl_transactions.amount), 0) FROM tbl_transactions
WHERE  tbl_transactions.account_id = tbl_goal_tracking.account_id
AND tbl_transactions.type='Income'  AND tbl_transactions.date >= '2022-03-01 00:00:00' AND tbl_transactions.date <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='achieve_total_expense' THEN
(SELECT COALESCE(SUM(tbl_transactions.amount), 0) FROM tbl_transactions  WHERE tbl_transactions.type='Expense'  AND tbl_transactions.date >= '2022-03-01 00:00:00' AND tbl_transactions.date <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='achive_total_expense_by_bank' THEN
(SELECT COALESCE(SUM(tbl_transactions.amount), 0) FROM tbl_transactions  WHERE tbl_transactions.account_id = tbl_goal_tracking.account_id AND tbl_transactions.type='Expense' AND tbl_transactions.date >= '2022-03-01 00:00:00' AND tbl_transactions.date <= '2022-03-31 23:59:59')


WHEN tbl_goal_type.type_name='make_invoice' THEN
(SELECT COALESCE(COUNT(tbl_invoices.invoices_id), 0) FROM tbl_invoices  WHERE   tbl_invoices.date_saved >= '2022-03-01 00:00:00' AND tbl_invoices.date_saved <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='make_estimate' THEN
(SELECT COALESCE(COUNT(tbl_estimates.estimates_id), 0) FROM tbl_estimates  WHERE   tbl_estimates.date_saved >= '2022-03-01 00:00:00' AND tbl_estimates.date_saved <= '2022-03-31 23:59:59')

WHEN tbl_goal_type.type_name='goal_payment' THEN
(SELECT COALESCE(SUM(tbl_payments.amount), 0) FROM tbl_payments  WHERE   tbl_payments.payment_date >= '2022-03-01 00:00:00' AND tbl_payments.payment_date <= '2022-03-31 23:59:59')


WHEN tbl_goal_type.type_name='task_done' THEN
(SELECT COALESCE(COUNT(tbl_task.task_id), 0) FROM tbl_task  WHERE   tbl_task.task_created_date >= '2022-03-01 00:00:00' AND tbl_task.task_created_date <= '2022-03-31 23:59:59' AND tbl_task.task_status = 'completed')


WHEN tbl_goal_type.type_name='resolved_bugs' THEN
(SELECT COALESCE(COUNT(tbl_bug.bug_id), 0) FROM tbl_bug  WHERE   tbl_bug.update_time >= '2022-03-01 00:00:00' AND tbl_bug.update_time <= '2022-03-31 23:59:59' AND tbl_bug.bug_status = 'resolved')


WHEN tbl_goal_type.type_name='convert_leads_to_client' THEN
(SELECT COALESCE(COUNT(tbl_client.client_id), 0) FROM tbl_client  WHERE   tbl_client.date_added >= '2022-03-01 00:00:00' AND tbl_client.date_added <= '2022-03-31 23:59:59' AND tbl_client.leads_id != 0)

WHEN tbl_goal_type.type_name='direct_client' THEN
(SELECT COALESCE(COUNT(tbl_client.client_id), 0) FROM tbl_client  WHERE   tbl_client.date_added >= '2022-03-01 00:00:00' AND tbl_client.date_added <= '2022-03-31 23:59:59' AND tbl_client.leads_id = 0)

WHEN tbl_goal_type.type_name='complete_project_goal' THEN
(SELECT COALESCE(COUNT(tbl_project.project_id), 0) FROM tbl_project  WHERE   tbl_project.start_date >= '2022-03-01 00:00:00' AND tbl_project.start_date <= '2022-03-31 23:59:59' AND tbl_project.project_status = 'completed')

END
AS amount_or_count

FROM `tbl_goal_type`

LEFT JOIN  tbl_goal_tracking ON tbl_goal_tracking.goal_type_id = tbl_goal_type.goal_type_id AND tbl_goal_tracking.end_date >= '2022-03-01 00:00:00' AND tbl_goal_tracking.end_date <= '2022-03-31 23:59:59'

LEFT JOIN  tbl_transactions ON tbl_goal_tracking.account_id = tbl_transactions.account_id


GROUP BY tbl_goal_type.goal_type_id
  
ERROR - 2022-03-09 05:59:33 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Admin_model.php 157
ERROR - 2022-03-09 02:59:37 --> 404 Page Not Found: /index
ERROR - 2022-03-09 03:00:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:33 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:00:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:01:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:53 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:02:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:48 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:03:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:04:57 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:02 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:07 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:12 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:17 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:22 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:27 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:32 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:37 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:42 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:46 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:47 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:51 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:52 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:53 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:05:53 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:07:08 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 06:07:09 --> Language file contains no data: language/arabic/form_validation_lang.php
ERROR - 2022-03-09 03:07:11 --> 404 Page Not Found: /index
ERROR - 2022-03-09 03:07:48 --> 404 Page Not Found: /index
ERROR - 2022-03-09 03:07:48 --> 404 Page Not Found: /index
ERROR - 2022-03-09 06:07:54 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 06:07:54 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 06:07:55 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 06:07:55 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 06:11:19 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 06:11:19 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 06:11:22 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 06:11:22 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 06:11:23 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 06:11:23 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 06:11:25 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'ziscoerp2.tbl_locales.locale' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `tbl_locales`
GROUP BY `language`
ERROR - 2022-03-09 06:11:25 --> Severity: error --> Exception: Call to a member function result() on bool D:\laragon\www\ziscoerp4\application\models\Settings_model.php 52
ERROR - 2022-03-09 03:38:58 --> 404 Page Not Found: /index
