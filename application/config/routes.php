<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "auth";
$route['404_override'] = '';

/*
*	Auth Routes
*/
$route['login'] = 'auth/login_user';
$route['logout-admin'] = 'auth/logout';

/*
*	Admin Routes
*/
$route['dashboard'] = 'admin/dashboard';
$route['change-password'] = 'admin/users/change_password';

/*
*	administration Routes
*/
$route['administration/configuration'] = 'admin/configuration';
$route['administration/edit-configuration/(:num)'] = 'admin/edit_configuration/$1';

$route['administration/sections'] = 'admin/sections/index';
$route['administration/sections/(:any)/(:any)'] = 'admin/sections/index/$1/$2';
$route['administration/sections/(:any)/(:any)/(:num)'] = 'admin/sections/index/$1/$2/$3';
$route['administration/add-section'] = 'admin/sections/add_section';
$route['administration/edit-section/(:num)'] = 'admin/sections/edit_section/$1';
$route['administration/edit-section/(:num)/(:num)'] = 'admin/sections/edit_section/$1/$2';
$route['administration/delete-section/(:num)'] = 'admin/sections/delete_section/$1';
$route['administration/delete-section/(:num)/(:num)'] = 'admin/sections/delete_section/$1/$2';
$route['administration/activate-section/(:num)'] = 'admin/sections/activate_section/$1';
$route['administration/activate-section/(:num)/(:num)'] = 'admin/sections/activate_section/$1/$2';
$route['administration/deactivate-section/(:num)'] = 'admin/sections/deactivate_section/$1';
$route['administration/deactivate-section/(:num)/(:num)'] = 'admin/sections/deactivate_section/$1/$2';

#$route['administration/company-profile'] = 'admin/contacts/show_contacts';
$route['administration/branches'] = 'admin/branches/index';
$route['administration/tutorial']='admin/tutorial/index';
$route['administration/branches/(:any)/(:any)/(:num)'] = 'admin/branches/index/$1/$2/$3';
$route['administration/branches/(:any)/(:any)'] = 'admin/branches/index/$1/$2';
$route['administration/add-branch'] = 'admin/branches/add_branch';
$route['administration/edit-branch/(:num)'] = 'admin/branches/edit_branch/$1';
$route['administration/edit-branch/(:num)/(:num)'] = 'admin/branches/edit_branch/$1/$2';
$route['administration/delete-branch/(:num)'] = 'admin/branches/delete_branch/$1';
$route['administration/delete-branch/(:num)/(:num)'] = 'admin/branches/delete_branch/$1/$2';
$route['administration/activate-branch/(:num)'] = 'admin/branches/activate_branch/$1';
$route['administration/activate-branch/(:num)/(:num)'] = 'admin/branches/activate_branch/$1/$2';
$route['administration/deactivate-branch/(:num)'] = 'admin/branches/deactivate_branch/$1';
$route['administration/deactivate-branch/(:num)/(:num)'] = 'admin/branches/deactivate_branch/$1/$2';

$route['print-payslip/(:num)'] = 'admin/payslip_details/$1';

//tutorial routes
$route['tutorial/human-resource']='hr/tutorial/view_tutorials';
$route['tutorial/administration/add-human-resource']='admin/tutorial/upload_tutorial';
$route['administration/activate-tutorial/(:num)'] = 'admin/tutorial/activate_tutorial/$1';
$route['administration/deactivate-tutorial/(:num)'] = 'admin/tutorial/deactivate_tutorial/$1';
$route['administration/edit-tutorial/(:num)'] = 'admin/tutorial/edit_tutorial/$1';
$route['administration/delete-tutorial/(:num)'] = 'admin/tutorial/delete_tutorial/$1';
$route['tutorial/payroll']='hr/tutorial/payroll';
$route['tutorial/leave']='hr/tutorial/leave';


/*
*	HR Routes
*/
$route['human-resource/schedules'] = 'hr/schedules/index';
$route['human-resource/delete-schedule/(:num)'] = 'hr/schedules/delete_schedule/$1';
$route['human-resource/delete-schedule/(:num)/(:num)'] = 'hr/schedules/delete_schedule/$1/$2';
$route['human-resource/activate-schedule/(:num)'] = 'hr/schedules/activate_schedule/$1';
$route['human-resource/activate-schedule/(:num)/(:num)'] = 'hr/schedules/activate_schedule/$1/$2';
$route['human-resource/deactivate-schedule/(:num)'] = 'hr/schedules/deactivate_schedule/$1';
$route['human-resource/deactivate-schedule/(:num)/(:num)'] = 'hr/schedules/deactivate_schedule/$1/$2';
$route['human-resource/schedule-personnel/(:num)'] = 'hr/schedules/schedule_personnel/$1';
$route['human-resource/fill-timesheet/(:num)/(:num)'] = 'hr/schedules/fill_timesheet/$1/$2';
$route['human-resource/doctors-schedule'] = 'hr/schedules/doctors_schedule';
$route['human-resource/schedule-personnel/(:num)/(:any)/(:any)/(:num)'] = 'hr/schedules/schedule_personnel/$1/$2/$3/$4';
$route['human-resource/schedule-personnel/(:num)/(:any)/(:any)'] = 'hr/schedules/schedule_personnel/$1/$2/$3';
$route['human-resource/schedules/(:any)/(:any)/(:num)'] = 'hr/schedules/index/$1/$2/$3';
$route['human-resource/schedules/(:any)/(:any)'] = 'hr/schedules/index/$1/$2';

$route['human-resource/my-account'] = 'admin/dashboard';
$route['human-resource/my-account/edit-about/(:num)'] = 'hr/personnel/my_account/update_personnel_about_details/$1';
$route['human-resource/edit-personnel-account/(:num)'] = 'hr/personnel/update_personnel_account_details/$1';
$route['human-resource/configuration'] = 'hr/configuration';
$route['human-resource/add-job-title'] = 'hr/add_job_title';
$route['human-resource/edit-job-title/(:num)'] = 'hr/edit_job_title/$1';
$route['human-resource/delete-job-title/(:num)'] = 'hr/delete_job_title/$1';
$route['human-resource/personnel'] = 'hr/personnel/index';
$route['human-resource/personnel/(:any)/(:any)/(:num)'] = 'hr/personnel/index/$1/$2/$3';
$route['human-resource/add-personnel'] = 'hr/personnel/add_personnel';
$route['human-resource/edit-personnel/(:num)'] = 'hr/personnel/edit_personnel/$1';
$route['human-resource/edit-store-authorize/(:num)'] = 'hr/personnel/edit_store_authorize/$1';
$route['human-resource/edit-order-authorize/(:num)'] = 'hr/personnel/edit_order_authorize/$1';

$route['human-resource/edit-personnel-about/(:num)'] = 'hr/personnel/update_personnel_about_details/$1';
$route['human-resource/edit-personnel-account/(:num)'] = 'hr/personnel/update_personnel_account_details/$1';
$route['human-resource/edit-personnel/(:num)/(:num)'] = 'hr/personnel/edit_personnel/$1/$2';
$route['human-resource/delete-personnel/(:num)'] = 'hr/personnel/delete_personnel/$1';
$route['human-resource/delete-personnel/(:num)/(:num)'] = 'hr/personnel/delete_personnel/$1/$2';
$route['human-resource/activate-personnel/(:num)'] = 'hr/personnel/activate_personnel/$1';
$route['human-resource/activate-personnel/(:num)/(:num)'] = 'hr/personnel/activate_personnel/$1/$2';
$route['human-resource/deactivate-personnel/(:num)'] = 'hr/personnel/deactivate_personnel/$1';
$route['human-resource/deactivate-personnel/(:num)/(:num)'] = 'hr/personnel/deactivate_personnel/$1/$2';
$route['human-resource/reset-password/(:num)'] = 'hr/personnel/reset_password/$1';
$route['human-resource/update-personnel-roles/(:num)'] = 'hr/personnel/update_personnel_roles/$1';
$route['human-resource/add-emergency-contact/(:num)'] = 'hr/personnel/add_emergency_contact/$1';
$route['human-resource/activate-emergency-contact/(:num)/(:num)'] = 'hr/personnel/activate_emergency_contact/$1/$2';
$route['human-resource/deactivate-emergency-contact/(:num)/(:num)'] = 'hr/personnel/deactivate_emergency_contact/$1/$2';
$route['human-resource/delete-emergency-contact/(:num)/(:num)'] = 'hr/personnel/delete_emergency_contact/$1/$2';

$route['human-resource/add-dependant-contact/(:num)'] = 'hr/personnel/add_dependant_contact/$1';
$route['human-resource/activate-dependant-contact/(:num)/(:num)'] = 'hr/personnel/activate_dependant_contact/$1/$2';
$route['human-resource/deactivate-dependant-contact/(:num)/(:num)'] = 'hr/personnel/deactivate_dependant_contact/$1/$2';
$route['human-resource/delete-dependant-contact/(:num)/(:num)'] = 'hr/personnel/delete_dependant_contact/$1/$2';

$route['human-resource/add-personnel-job/(:num)'] = 'hr/personnel/add_personnel_job/$1';
$route['human-resource/activate-personnel-job/(:num)/(:num)'] = 'hr/personnel/activate_personnel_job/$1/$2';
$route['human-resource/deactivate-personnel-job/(:num)/(:num)'] = 'hr/personnel/deactivate_personnel_job/$1/$2';
$route['human-resource/delete-personnel-job/(:num)/(:num)'] = 'hr/personnel/delete_personnel_job/$1/$2';

$route['human-resource/leave'] = 'hr/leave/calender';
$route['human-resource/leave/(:any)/(:any)'] = 'hr/leave/calender/$1/$2';
$route['human-resource/view-leave/(:any)'] = 'hr/leave/view_leave/$1';
$route['human-resource/add-personnel-leave/(:num)'] = 'hr/personnel/add_personnel_leave/$1';
$route['human-resource/add-leave/(:any)'] = 'hr/leave/add_leave/$1';
$route['human-resource/add-calender-leave'] = 'hr/leave/add_calender_leave';
$route['human-resource/activate-leave/(:num)/(:any)'] = 'hr/leave/activate_leave/$1/$2';
$route['human-resource/deactivate-leave/(:num)/(:any)'] = 'hr/leave/deactivate_leave/$1/$2';
$route['human-resource/delete-leave/(:num)/(:any)'] = 'hr/leave/delete_leave/$1/$2';
$route['human-resource/activate-personnel-leave/(:num)/(:num)'] = 'hr/personnel/activate_personnel_leave/$1/$2';
$route['human-resource/deactivate-personnel-leave/(:num)/(:num)'] = 'hr/personnel/deactivate_personnel_leave/$1/$2';
$route['human-resource/delete-personnel-leave/(:num)/(:num)'] = 'hr/personnel/delete_personnel_leave/$1/$2';

$route['human-resource/delete-personnel-role/(:num)/(:num)'] = 'hr/personnel/delete_personnel_role/$1/$2';
$route['human-resource/delete-personnel-approvals_assigned/(:num)/(:num)'] = 'hr/personnel/delete_personnel_approvals_assigned/$1/$2';

/*
*	Hospital administration
*/
$route['hospital-administration/import-pharmacy-charges/(:num)'] = 'hospital_administration/services/import_pharmacy_charges/$1';
$route['hospital-administration/import-lab-charges/(:num)'] = 'hospital_administration/services/import_lab_charges/$1';
$route['hospital-administration/dashboard'] = 'administration/index';
$route['hospital-administration/services'] = 'hospital_administration/services/index';
$route['hospital-administration/services/(:any)/(:any)/(:num)'] = 'hospital_administration/services/index/$1/$2/$3';
$route['hospital-administration/services/(:any)/(:any)'] = 'hospital_administration/services/index/$1/$2';
$route['hospital-administration/add-service'] = 'hospital_administration/services/add_service';
$route['hospital-administration/edit-service/(:num)'] = 'hospital_administration/services/edit_service/$1';
$route['hospital-administration/edit-service/(:num)/(:num)'] = 'hospital_administration/services/edit_service/$1/$2';
$route['hospital-administration/delete-service/(:num)'] = 'hospital_administration/services/delete_service/$1';
$route['hospital-administration/delete-service/(:num)/(:num)'] = 'hospital_administration/services/delete_service/$1/$2';
$route['hospital-administration/activate-service/(:num)'] = 'hospital_administration/services/activate_service/$1';
$route['hospital-administration/activate-service/(:num)/(:num)'] = 'hospital_administration/services/activate_service/$1/$2';
$route['hospital-administration/deactivate-service/(:num)'] = 'hospital_administration/services/deactivate_service/$1';
$route['hospital-administration/deactivate-service/(:num)/(:num)'] = 'hospital_administration/services/deactivate_service/$1/$2';
$route['hospital-administration/import-services-template'] = 'hospital_administration/services/import_charges_template';
$route['hospital-administration/import-services/(:num)'] = 'hospital_administration/services/do_charges_import/$1';
$route['hospital-administration/import-charges/(:num)'] = 'hospital_administration/services/import_charges/$1';

$route['hospital-administration/service-charges/(:num)'] = 'hospital_administration/services/service_charges/$1';
$route['hospital-administration/service-charges/(:num)/(:any)/(:any)/(:num)'] = 'hospital_administration/services/service_charges/$1/$2/$3/$4';
$route['hospital-administration/service-charges/(:num)/(:any)/(:any)'] = 'hospital_administration/services/service_charges/$1/$2/$3';
$route['hospital-administration/add-service-charge/(:num)'] = 'hospital_administration/services/add_service_charge/$1';
$route['hospital-administration/edit-service-charge/(:num)/(:num)'] = 'hospital_administration/services/edit_service_charge/$1/$2';
$route['hospital-administration/delete-service-charge/(:num)/(:num)'] = 'hospital_administration/services/delete_service_charge/$1/$2';
$route['hospital-administration/activate-service-charge/(:num)/(:num)'] = 'hospital_administration/services/activate_service_charge/$1/$2';
$route['hospital-administration/deactivate-service-charge/(:num)/(:num)'] = 'hospital_administration/services/deactivate_service_charge/$1/$2';

$route['hospital-administration/visit-types'] = 'hospital_administration/visit_types/index';
$route['hospital-administration/visit-types/(:any)/(:any)/(:num)'] = 'hospital_administration/visit_types/index/$1/$2/$3';
$route['hospital-administration/visit-types/(:any)/(:any)'] = 'hospital_administration/visit_types/index/$1/$2';
$route['hospital-administration/add-visit-type'] = 'hospital_administration/visit_types/add_visit_type';
$route['hospital-administration/edit-visit-type/(:num)'] = 'hospital_administration/visit_types/edit_visit_type/$1';
$route['hospital-administration/delete-visit-type/(:num)'] = 'hospital_administration/visit_types/delete_visit_type/$1';
$route['hospital-administration/activate-visit-type/(:num)'] = 'hospital_administration/visit_types/activate_visit_type/$1';
$route['hospital-administration/deactivate-visit-type/(:num)'] = 'hospital_administration/visit_types/deactivate_visit_type/$1';

$route['hospital-administration/departments'] = 'hospital_administration/departments/index';
$route['hospital-administration/departments/(:any)/(:any)/(:num)'] = 'hospital_administration/departments/index/$1/$2/$3';
$route['hospital-administration/departments/(:any)/(:any)'] = 'hospital_administration/departments/index/$1/$2';
$route['hospital-administration/add-department'] = 'hospital_administration/departments/add_department';
$route['hospital-administration/edit-department/(:num)'] = 'hospital_administration/departments/edit_department/$1';
$route['hospital-administration/delete-department/(:num)'] = 'hospital_administration/departments/delete_department/$1';
$route['hospital-administration/activate-department/(:num)'] = 'hospital_administration/departments/activate_department/$1';
$route['hospital-administration/deactivate-department/(:num)'] = 'hospital_administration/departments/deactivate_department/$1';

$route['hospital-administration/wards'] = 'hospital_administration/wards/index';
$route['hospital-administration/wards/(:any)/(:any)/(:num)'] = 'hospital_administration/wards/index/$1/$2/$3';
$route['hospital-administration/wards/(:any)/(:any)'] = 'hospital_administration/wards/index/$1/$2';
$route['hospital-administration/add-ward'] = 'hospital_administration/wards/add_ward';
$route['hospital-administration/edit-ward/(:num)'] = 'hospital_administration/wards/edit_ward/$1';
$route['hospital-administration/delete-ward/(:num)'] = 'hospital_administration/wards/delete_ward/$1';
$route['hospital-administration/activate-ward/(:num)'] = 'hospital_administration/wards/activate_ward/$1';
$route['hospital-administration/deactivate-ward/(:num)'] = 'hospital_administration/wards/deactivate_ward/$1';

$route['hospital-administration/rooms/(:num)'] = 'hospital_administration/rooms/index/$1';
$route['hospital-administration/rooms/(:num)/(:any)/(:any)/(:num)'] = 'hospital_administration/rooms/index/$1/$2/$3/$4';
$route['hospital-administration/rooms/(:num)/(:any)/(:any)'] = 'hospital_administration/rooms/index/$1/$2/$3';
$route['hospital-administration/add-room/(:num)'] = 'hospital_administration/rooms/add_room/$1';
$route['hospital-administration/edit-room/(:num)/(:num)'] = 'hospital_administration/rooms/edit_room/$1/$2';
$route['hospital-administration/delete-room/(:num)/(:num)'] = 'hospital_administration/rooms/delete_room/$1/$2';
$route['hospital-administration/activate-room/(:num)/(:num)'] = 'hospital_administration/rooms/activate_room/$1/$2';
$route['hospital-administration/deactivate-room/(:num)/(:num)'] = 'hospital_administration/rooms/deactivate_room/$1/$2';

$route['hospital-administration/beds/(:num)'] = 'hospital_administration/beds/index/$1';
$route['hospital-administration/beds/(:num)/(:any)/(:any)/(:num)'] = 'hospital_administration/beds/index/$1/$2/$3/$4';
$route['hospital-administration/beds/(:num)/(:any)/(:any)'] = 'hospital_administration/beds/index/$1/$2/$3';
$route['hospital-administration/add-bed/(:num)'] = 'hospital_administration/beds/add_bed/$1';
$route['hospital-administration/edit-bed/(:num)/(:num)'] = 'hospital_administration/beds/edit_bed/$1/$2';
$route['hospital-administration/delete-bed/(:num)/(:num)'] = 'hospital_administration/beds/delete_bed/$1/$2';
$route['hospital-administration/activate-bed/(:num)/(:num)'] = 'hospital_administration/beds/activate_bed/$1/$2';
$route['hospital-administration/deactivate-bed/(:num)/(:num)'] = 'hospital_administration/beds/deactivate_bed/$1/$2';

$route['hospital-administration/insurance-companies'] = 'hospital_administration/companies/index';
$route['hospital-administration/insurance-companies/(:any)/(:any)/(:num)'] = 'hospital_administration/companies/index/$1/$2/$3';
$route['hospital-administration/insurance-companies/(:any)/(:any)'] = 'hospital_administration/companies/index/$1/$2';
$route['hospital-administration/add-insurance-company'] = 'hospital_administration/companies/add_company';
$route['hospital-administration/edit-insurance-company/(:num)'] = 'hospital_administration/companies/edit_company/$1';
$route['hospital-administration/delete-insurance-company/(:num)'] = 'hospital_administration/companies/delete_company/$1';
$route['hospital-administration/activate-insurance-company/(:num)'] = 'hospital_administration/companies/activate_company/$1';
$route['hospital-administration/deactivate-insurance-company/(:num)'] = 'hospital_administration/companies/deactivate_company/$1';

/*
*	Accounts Routes
*/
$route['accounts/calculate-paye/(:num)'] = 'accounts/payroll/calculate_personnel_paye/$1';
$route['accounts/creditors'] = 'accounts/creditors/index';
$route['accounts/hospital-accounts'] = 'accounts/hospital_accounts/index';
$route['accounts/petty-cash'] = 'accounts/petty_cash/index';
$route['accounts/petty-cash/(:any)/(:any)'] = 'accounts/petty_cash/index/$1/$2';
$route['accounts/petty-cash/(:any)'] = 'accounts/petty_cash/index/$1';
$route['accounts/change-branch'] = 'accounts/payroll/change_branch';
$route['accounts/print-paye-report/(:num)'] = 'accounts/payroll/print_paye_report/$1';
$route['accounts/print-cc-paye-report/(:num)'] = 'accounts/cc_payment/print_paye_report/$1';
$route['accounts/print-nhif-report/(:num)'] = 'accounts/payroll/print_nhif_report/$1';
$route['accounts/print-nssf-report/(:num)'] = 'accounts/payroll/print_nssf_report/$1';
$route['accounts/print-payroll/(:num)'] = 'accounts/payroll/print_payroll/$1';
$route['accounts/print-month-payslips/(:num)'] = 'accounts/payroll/print_monthly_payslips/$1';
$route['accounts/export-payroll/(:num)'] = 'accounts/payroll/export_payroll/$1';
$route['accounts/print-payroll-pdf/(:num)'] = 'accounts/payroll/print_payroll_pdf/$1';
$route['accounts/payroll/print-payslip/(:num)/(:num)'] = 'accounts/payroll/print_payslip/$1/$2';
$route['accounts/cc-payment/print-payslip/(:num)/(:num)'] = 'accounts/cc_payment/print_payslip/$1/$2';
$route['accounts/payroll/download-payslip/(:num)/(:num)'] = 'accounts/payroll/download_payslip/$1/$2';
$route['accounts/cc-payment/download-payslip/(:num)/(:num)'] = 'accounts/cc_payment/download_payslip/$1/$2';
$route['accounts/payroll-payslips/(:num)'] = 'accounts/payroll/payroll_payslips/$1';
$route['accounts/salary-data'] = 'accounts/payroll/salaries';
$route['accounts/search-payroll'] = 'accounts/payroll/search_payroll';
$route['accounts/close-payroll-search'] = 'accounts/payroll/close_payroll_search';
$route['accounts/create-payroll'] = 'accounts/payroll/create_payroll';
$route['accounts/deactivate-payroll/(:num)'] = 'accounts/payroll/deactivate_payroll/$1';
$route['accounts/print-payslips'] = 'accounts/payroll/print_payslips';
$route['accounts/payroll/edit-payment-details/(:num)'] = 'accounts/payroll/edit_payment_details/$1';
$route['accounts/payroll/edit_allowance/(:num)'] = 'accounts/payroll/edit_allowance/$1';
$route['accounts/payroll/delete_allowance/(:num)'] = 'accounts/payroll/delete_allowance/$1';
$route['accounts/payroll/edit_deduction/(:num)'] = 'accounts/payroll/edit_deduction/$1';
$route['accounts/payroll/delete_deduction/(:num)'] = 'accounts/payroll/delete_deduction/$1';
$route['accounts/payroll/edit_saving/(:num)'] = 'accounts/payroll/edit_saving/$1';
$route['accounts/payroll/delete_saving/(:num)'] = 'accounts/payroll/delete_saving/$1';
$route['accounts/payroll/edit_loan_scheme/(:num)'] = 'accounts/payroll/edit_loan_scheme/$1';
$route['accounts/payroll/delete_loan_scheme/(:num)'] = 'accounts/payroll/delete_loan_scheme/$1';
$route['accounts/payroll'] = 'accounts/payroll/payrolls';
$route['accounts/all-payroll'] = 'accounts/payroll/all_payrolls';
$route['accounts/payment-details/(:num)'] = 'accounts/payroll/payment_details/$1';
$route['accounts/save-payment-details/(:num)'] = 'accounts/payroll/save_payment_details/$1';
$route['accounts/update-savings/(:num)'] = 'accounts/payroll/update_savings/$1';
$route['accounts/update-loan-schemes/(:num)'] = 'accounts/payroll/update_loan_schemes/$1';
$route['payroll/configuration'] = 'accounts/payroll/payroll_configuration';
$route['accounts/payroll-configuration'] = 'accounts/payroll/payroll_configuration';
$route['accounts/payroll/edit-nssf/(:num)'] = 'accounts/payroll/edit_nssf/$1';
$route['accounts/payroll/edit-nhif/(:num)'] = 'accounts/payroll/edit_nhif/$1';
$route['accounts/payroll/delete-nhif/(:num)'] = 'accounts/payroll/delete_nhif/$1';
$route['accounts/payroll/edit-paye/(:num)'] = 'accounts/payroll/edit_paye/$1';
$route['accounts/payroll/delete-paye/(:num)'] = 'accounts/payroll/delete_paye/$1';
$route['accounts/payroll/edit-payment/(:num)'] = 'accounts/payroll/edit_payment/$1';
$route['accounts/payroll/delete-payment/(:num)'] = 'accounts/payroll/delete_payment/$1';
$route['accounts/payroll/edit-benefit/(:num)'] = 'accounts/payroll/edit_benefit/$1';
$route['accounts/payroll/delete-benefit/(:num)'] = 'accounts/payroll/delete_benefit/$1';
$route['accounts/payroll/edit-allowance/(:num)'] = 'accounts/payroll/edit_allowance/$1';
$route['accounts/payroll/delete-allowance/(:num)'] = 'accounts/payroll/delete_allowance/$1';
$route['accounts/payroll/edit-deduction/(:num)'] = 'accounts/payroll/edit_deduction/$1';
$route['accounts/payroll/edit-relief/(:num)'] = 'accounts/payroll/edit_relief/$1';
$route['accounts/payroll/delete-deduction/(:num)'] = 'accounts/payroll/delete_deduction/$1';
$route['accounts/payroll/edit-other-deduction/(:num)'] = 'accounts/payroll/edit_other_deduction/$1';
$route['accounts/payroll/delete-other-deduction/(:num)'] = 'accounts/payroll/delete_other_deduction/$1';
$route['accounts/payroll/edit-loan-scheme/(:num)'] = 'accounts/payroll/edit_loan_scheme/$1';
$route['accounts/payroll/delete-loan-scheme/(:num)'] = 'accounts/payroll/delete_loan_scheme/$1';
$route['accounts/payroll/edit-saving/(:num)'] = 'accounts/payroll/edit_saving/$1';
$route['accounts/payroll/delete-saving/(:num)'] = 'accounts/payroll/delete_saving/$1';
$route['accounts/payroll/edit-personnel-payments/(:num)'] = 'accounts/payroll/edit_personnel_payments/$1';
$route['accounts/payroll/edit-personnel-allowances/(:num)'] = 'accounts/payroll/edit_personnel_allowances/$1';
$route['accounts/payroll/edit-personnel-benefits/(:num)'] = 'accounts/payroll/edit_personnel_benefits/$1';
$route['accounts/payroll/edit-personnel-deductions/(:num)'] = 'accounts/payroll/edit_personnel_deductions/$1';
$route['accounts/payroll/edit-personnel-other-deductions/(:num)'] = 'accounts/payroll/edit_personnel_other_deductions/$1';
$route['accounts/payroll/edit-personnel-savings/(:num)'] = 'accounts/payroll/edit_personnel_savings/$1';
$route['accounts/payroll/edit-personnel-loan-schemes/(:num)'] = 'accounts/payroll/edit_personnel_loan_schemes/$1';
$route['accounts/payroll/edit-personnel-relief/(:num)'] = 'accounts/payroll/edit_personnel_relief/$1';
$route['accounts/payroll/view-payslip/(:num)'] = 'accounts/payroll/view_payslip/$1';
$route['accounts/payroll/view-payslip/(:num)'] = 'accounts/cc_payment/view_payslip/$1';
$route['accounts/payroll/bank'] = 'accounts/payroll/bank';
$route['accounts/payroll/generate-bank-report/(:num)'] = 'accounts/payroll/generate_bank_report/$1';
$route['accounts/print-bank-report/(:num)'] = 'accounts/payroll/print_monthly_payslips/$1';
$route['accounts/export-bank-report/(:num)'] = 'accounts/payroll/export_payroll/$1';

$route['accounts/insurance-invoices'] = 'administration/reports/debtors_report_invoices/0';
$route['accounts/insurance-invoices/(:num)'] = 'administration/reports/debtors_report_invoices/$1';
$route['payroll/add-overtime-hours/(:num)'] = 'accounts/payroll/add_overtime_hours/$1';

$route['accounts/print-cc-payment/(:num)'] = 'accounts/cc_payment/print_cc_payment/$1';
$route['accounts/export-cc-payment/(:num)'] = 'accounts/cc_payment/export_cc_payment/$1';
$route['accounts/print-cc-payment-pdf/(:num)'] = 'accounts/cc_payment/print_cc_payment_pdf/$1';
$route['accounts/cc-payment/print-payslip/(:num)/(:num)'] = 'accounts/cc_payment/print_payslip/$1/$2';
$route['accounts/cc-payment/download-payslip/(:num)/(:num)'] = 'accounts/cc_payment/download_payslip/$1/$2';
$route['accounts/cc-payment-payslips/(:num)'] = 'accounts/cc_payment/cc_payment_payslips/$1';
$route['accounts/salary-data'] = 'accounts/cc_payment/salaries';
$route['accounts/print-cc-payment/(:num)'] = 'accounts/cc_payment/print_cc_payment/$1';
$route['accounts/export-cc-payment/(:num)'] = 'accounts/cc_payment/export_cc_payment/$1';
$route['accounts/print-cc-paymen-pdf/(:num)'] = 'accounts/cc_payment/print_cc_payment_pdf/$1';
$route['print-payslip/(:num)'] = 'admin/payslip_details/$1';
$route['accounts/search-cc-payment'] = 'accounts/cc_payment/search_cc_payment';
$route['accounts/close-cc-payment-search'] = 'accounts/cc_payment/close_cc_payment_search';
$route['accounts/print-cc-nhif-report/(:num)'] = 'accounts/cc_payment/print_nhif_report/$1';
$route['accounts/export-cc-nhif-report/(:num)'] = 'accounts/cc_payment/export_nhif_report/$1';
$route['accounts/print-cc-nhif-report-pdf/(:num)'] = 'accounts/cc_payment/print_nhif_report_pdf/$1';
$route['accounts/print-cc-nssf-report/(:num)'] = 'accounts/cc_payment/print_nssf_report/$1';
$route['accounts/export-cc-nssf-report/(:num)'] = 'accounts/cc_payment/export_nssf_report/$1';
$route['accounts/print-cc-nssf-report-pdf/(:num)'] = 'accounts/cc_payment/print_nssf_report_pdf/$1';
$route['accounts/create-cc-payment'] = 'accounts/cc_payment/create_cc_payment';
$route['accounts/deactivate-cc-payment/(:num)'] = 'accounts/cc_payment/deactivate_cc_payment/$1';
$route['accounts/print-payslips'] = 'accounts/cc_payment/print_payslips';
$route['accounts/cc-payment/edit-payment/(:num)'] = 'accounts/cc_payment/edit_payment/$1';
$route['accounts/cc-payment/edit_allowance/(:num)'] = 'accounts/cc_payment/edit_allowance/$1';
$route['accounts/cc-payment/delete_allowance/(:num)'] = 'accounts/cc_payment/delete_allowance/$1';
$route['accounts/cc-payment/edit_deduction/(:num)'] = 'accounts/cc_payment/edit_deduction/$1';
$route['accounts/cc-payment/delete_deduction/(:num)'] = 'accounts/cc_payment/delete_deduction/$1';
$route['accounts/cc-payment/edit_saving/(:num)'] = 'accounts/cc_payment/edit_saving/$1';
$route['accounts/cc-payment/delete_saving/(:num)'] = 'accounts/cc_payment/delete_saving/$1';
$route['accounts/cc-payment/edit_loan_scheme/(:num)'] = 'accounts/cc_payment/edit_loan_scheme/$1';
$route['accounts/cc-payment/delete_loan_scheme/(:num)'] = 'accounts/cc_payment/delete_loan_scheme/$1';
$route['accounts/cc-payment'] = 'accounts/cc_payment/index';
$route['accounts/payment-details/(:num)'] = 'accounts/cc_payment/payment_details/$1';
$route['accounts/save-payment-details/(:num)'] = 'accounts/cc_payment/save_payment_details/$1';
$route['accounts/update-savings/(:num)'] = 'accounts/cc_payment/update_savings/$1';
$route['accounts/update-loan-schemes/(:num)'] = 'accounts/cc_payment/update_loan_schemes/$1';
$route['cc-payment/configuration'] = 'accounts/cc_payment/cc_payment_configuration';
$route['accounts/cc-payment-configuration'] = 'accounts/cc_payment/cc_payment_configuration';
$route['accounts/cc-payment/edit-cc-nssf/(:num)'] = 'accounts/cc_payment/edit_cc_nssf/$1';
$route['accounts/cc-payment/edit-cc-nhif/(:num)'] = 'accounts/cc_payment/edit_cc_nhif/$1';
$route['accounts/cc-payment/delete-cc-nhif/(:num)'] = 'accounts/cc_payment/delete_cc_nhif/$1';
$route['accounts/cc-payment/edit-cc-paye/(:num)'] = 'accounts/cc_payment/edit_cc_paye/$1';
$route['accounts/cc-payment/delete-cc-paye/(:num)'] = 'accounts/cc_payment/delete_cc_paye/$1';
$route['accounts/cc-payment/add-new-payment/(:num)'] = 'accounts/cc_payment/add_new_payment/$1';
$route['accounts/cc-payment/edit-payment/(:num)'] = 'accounts/cc_payment/edit_payment/$1';
$route['accounts/cc-payment/delete-payment/(:num)'] = 'accounts/cc_payment/delete_payment/$1';
$route['accounts/cc-payment/edit-benefit/(:num)'] = 'accounts/cc_payment/edit_benefit/$1';
$route['accounts/cc-payment/delete-benefit/(:num)'] = 'accounts/cc_payment/delete_benefit/$1';
$route['accounts/cc-payment/edit-allowance/(:num)'] = 'accounts/cc_payment/edit_allowance/$1';
$route['accounts/cc-payment/delete-allowance/(:num)'] = 'accounts/cc_payment/delete_allowance/$1';
$route['accounts/cc-payment/edit-deduction/(:num)'] = 'accounts/cc_payment/edit_deduction/$1';
$route['accounts/cc-payment/edit-relief/(:num)'] = 'accounts/cc_payment/edit_relief/$1';
$route['accounts/cc-payment/delete-deduction/(:num)'] = 'accounts/cc_payment/delete_deduction/$1';
$route['accounts/cc-payment/edit-other-deduction/(:num)'] = 'accounts/cc_payment/edit_other_deduction/$1';
$route['accounts/cc-payment/delete-other-deduction/(:num)'] = 'accounts/cc_payment/delete_other_deduction/$1';
$route['accounts/cc-payment/edit-loan-scheme/(:num)'] = 'accounts/cc_payment/edit_loan_scheme/$1';
$route['accounts/cc-payment/delete-loan-scheme/(:num)'] = 'accounts/cc_payment/delete_loan_scheme/$1';
$route['accounts/cc-payment/edit-saving/(:num)'] = 'accounts/cc_payment/edit_saving/$1';
$route['accounts/cc-payment/delete-saving/(:num)'] = 'accounts/cc_payment/delete_saving/$1';
$route['accounts/cc-payment/edit-personnel-payments/(:num)'] = 'accounts/cc_payment/edit_personnel_payments/$1';
$route['accounts/cc-payment/edit-personnel-allowances/(:num)'] = 'accounts/cc_payment/edit_personnel_allowances/$1';
$route['accounts/cc-payment/edit-personnel-benefits/(:num)'] = 'accounts/cc_payment/edit_personnel_benefits/$1';
$route['accounts/cc-payment/edit-personnel-deductions/(:num)'] = 'accounts/cc_payment/edit_personnel_deductions/$1';
$route['accounts/cc-payment/edit-personnel-other-deductions/(:num)'] = 'accounts/cc_payment/edit_personnel_other_deductions/$1';
$route['accounts/cc-payment/edit-personnel-savings/(:num)'] = 'accounts/cc_payment/edit_personnel_savings/$1';
$route['accounts/cc-payment/edit-personnel-loan-schemes/(:num)'] = 'accounts/cc_payment/edit_personnel_loan_schemes/$1';
$route['accounts/cc-payment/edit-personnel-relief/(:num)'] = 'accounts/cc_payment/edit_personnel_relief/$1';
$route['accounts/cc-payment/view-payslip/(:num)'] = 'accounts/cc_payment/view_payslip/$1';
$route['accounts/cc-payment/bank'] = 'accounts/cc_payments/bank';
$route['accounts/cc-payment/generate-cc-bank-report/(:num)'] = 'accounts/cc_payment/generate_bank_report/$1';
$route['accounts/print-bank-report/(:num)'] = 'accounts/cc_payment/print_monthly_payslips/$1';
$route['accounts/export-cc-bank-report/(:num)'] = 'accounts/cc_payment/export_cc_payment/$1';

$route['accounts/insurance-invoices'] = 'administration/reports/debtors_report_invoices/0';
$route['accounts/insurance-invoices/(:num)'] = 'administration/reports/debtors_report_invoices/$1';
$route['accounts/print-month-payslips/(:num)'] = 'accounts/cc_payment/print_monthly_payslips/$1';

//Always comes last;;nt/cc_payment/$1/$2';


/*
*	Inventory Routes
*/
$route['inventory/units-of-measurement'] = 'inventory/unit/index';
$route['inventory/units-of-measurement/(:any)/(:any)/(:num)'] = 'inventory/unit/index/$1/$2/$3';
$route['inventory/add-personnel'] = 'inventory/personnel/add_personnel';
$route['inventory/edit-personnel/(:num)'] = 'inventory/personnel/edit_personnel/$1';
$route['inventory/edit-personnel/(:num)/(:num)'] = 'inventory/personnel/edit_personnel/$1/$2';
$route['inventory/delete-personnel/(:num)'] = 'inventory/personnel/delete_personnel/$1';
$route['inventory/delete-personnel/(:num)/(:num)'] = 'inventory/personnel/delete_personnel/$1/$2';
$route['inventory/activate-personnel/(:num)'] = 'inventory/personnel/activate_personnel/$1';
$route['inventory/activate-personnel/(:num)/(:num)'] = 'inventory/personnel/activate_personnel/$1/$2';
$route['inventory/deactivate-personnel/(:num)'] = 'inventory/personnel/deactivate_personnel/$1';
$route['inventory/deactivate-personnel/(:num)/(:num)'] = 'inventory/personnel/deactivate_personnel/$1/$2';

/*
*	Microfinance Routes
*/
$route['microfinance/individual'] = 'microfinance/individual/index';
$route['microfinance/individual/(:any)/(:any)/(:num)'] = 'microfinance/individual/index/$1/$2/$3';
$route['microfinance/add-individual'] = 'microfinance/individual/add_individual';
$route['microfinance/edit-individual/(:num)'] = 'microfinance/individual/edit_individual/$1';
$route['microfinance/update-individual/(:num)'] = 'microfinance/individual/edit_about/$1';
$route['microfinance/update-emergency/(:num)'] = 'microfinance/individual/edit_emergency/$1';
$route['microfinance/add-position/(:num)'] = 'microfinance/individual/add_position/$1';
$route['microfinance/add-nok/(:num)'] = 'microfinance/individual/add_emergency/$1';
$route['microfinance/delete-individual/(:num)'] = 'microfinance/individual/delete_individual/$1';
$route['microfinance/delete-individual/(:num)/(:num)'] = 'microfinance/individual/delete_individual/$1/$2';
$route['microfinance/activate-individual/(:num)'] = 'microfinance/individual/activate_individual/$1';
$route['microfinance/activate-individual/(:num)/(:num)'] = 'microfinance/individual/activate_individual/$1/$2';
$route['microfinance/deactivate-individual/(:num)'] = 'microfinance/individual/deactivate_individual/$1';
$route['microfinance/deactivate-individual/(:num)/(:num)'] = 'microfinance/individual/deactivate_individual/$1/$2';
$route['microfinance/activate-position/(:num)/(:num)'] = 'microfinance/individual/activate_position/$1/$2';
$route['microfinance/deactivate-position/(:num)/(:num)'] = 'microfinance/individual/deactivate_position/$1/$2';
$route['microfinance/delete-emergency/(:num)/(:num)'] = 'microfinance/individual/delete_emergency/$1/$2';

/*
*	Microfinance Routes
*/
$route['microfinance/groups'] = 'microfinance/group/index';
$route['microfinance/group/(:any)/(:any)/(:num)'] = 'microfinance/group/index/$1/$2/$3';
$route['microfinance/add-group'] = 'microfinance/group/add_group';
$route['microfinance/edit-group/(:num)'] = 'microfinance/group/edit_group/$1';
$route['microfinance/edit-about/(:num)'] = 'microfinance/group/edit_about/$1';
$route['microfinance/add-member/(:num)'] = 'microfinance/group/add_member/$1';
$route['microfinance/edit-group/(:num)/(:num)'] = 'microfinance/group/edit_group/$1/$2';
$route['microfinance/delete-group/(:num)'] = 'microfinance/group/delete_group/$1';
$route['microfinance/delete-group/(:num)/(:num)'] = 'microfinance/group/delete_group/$1/$2';
$route['microfinance/activate-group/(:num)'] = 'microfinance/group/activate_group/$1';
$route['microfinance/activate-group/(:num)/(:num)'] = 'microfinance/group/activate_group/$1/$2';
$route['microfinance/deactivate-group/(:num)'] = 'microfinance/group/deactivate_group/$1';
$route['microfinance/deactivate-group/(:num)/(:num)'] = 'microfinance/group/deactivate_group/$1/$2';

$route['microfinance/savings-plan'] = 'microfinance/savings_plan/index';
$route['microfinance/savings-plan/(:any)/(:any)/(:num)'] = 'microfinance/savings_plan/index/$1/$2/$3';
$route['microfinance/add-savings-plan'] = 'microfinance/savings_plan/add_savings_plan';
$route['microfinance/edit-savings-plan/(:num)'] = 'microfinance/savings_plan/edit_savings_plan/$1';
$route['microfinance/edit-savings-plan/(:num)/(:num)'] = 'microfinance/savings_plan/edit_savings_plan/$1/$2';
$route['microfinance/delete-savings-plan/(:num)'] = 'microfinance/savings_plan/delete_savings_plan/$1';
$route['microfinance/delete-savings-plan/(:num)/(:num)'] = 'microfinance/savings_plan/delete_savings_plan/$1/$2';
$route['microfinance/activate-savings-plan/(:num)'] = 'microfinance/savings_plan/activate_savings_plan/$1';
$route['microfinance/activate-savings-plan/(:num)/(:num)'] = 'microfinance/savings_plan/activate_savings_plan/$1/$2';
$route['microfinance/deactivate-savings-plan/(:num)'] = 'microfinance/savings_plan/deactivate_savings_plan/$1';
$route['microfinance/deactivate-savings-plan/(:num)/(:num)'] = 'microfinance/savings_plan/deactivate_savings_plan/$1/$2';
$route['microfinance/add-individual-plan/(:num)'] = 'microfinance/individual/add_individual_plan/$1';
$route['microfinance/activate-individual-plan/(:num)/(:num)'] = 'microfinance/individual/activate_individual_plan/$1/$2';
$route['microfinance/deactivate-individual-plan/(:num)/(:num)'] = 'microfinance/individual/deactivate_individual_plan/$1/$2';


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */

/*
*	reception Routes
*/
$route['reception'] = 'reception/index';
$route['reception/unclosed-visits'] = 'reception/visit_list/3';
$route['reception/dashboard'] = 'reception/index';
$route['reception/patients-list'] = 'reception/patients';
$route['reception/deleted-visits'] = 'reception/visit_list/2';
$route['reception/visit-history'] = 'reception/visit_list/1';
$route['reception/general-queue'] = 'reception/general_queue/reception';
$route['reception/inpatients'] = 'reception/inpatients/reception';
$route['reception/appointments-list'] = 'reception/appointment_list';
$route['reception/register-other-patient'] = 'reception/register_other_patient';
$route['reception/add-patient'] = 'reception/add_patient';
$route['reception/validate-import'] = 'reception/do_patients_import';
$route['reception/import-template'] = 'reception/import_template';
$route['reception/import-patients'] = 'reception/import_patients';
$route['reception/print-invoice/(:num)/(:any)'] = 'accounts/print_invoice_new/$1/$2';

/*
*	nurse Routes
*/
$route['nurse'] = 'nurse/index';
$route['nurse/dashboard'] = 'nurse/index';
$route['nurse/nurse-queue'] = 'nurse/nurse_queue';
$route['nurse/general-queue'] = 'reception/general_queue/nurse';
$route['nurse/visit-history'] = 'reception/visit_list/1/nurse';
$route['nurse/inpatients'] = 'reception/inpatients/nurse';

/*
*	doctor Routes
*/
$route['doctor'] = 'doctor/index';
$route['doctor/dashboard'] = 'doctor/index';
$route['doctor/doctors-queue'] = 'doctor/doctor_queue';
$route['doctor/general-queue'] = 'reception/general_queue/doctor';
$route['doctor/visit-history'] = 'reception/visit_list/1/doctor';
$route['doctor/patient-treatment'] = 'nurse/patient_treatment_statement/doctor';
$route['doctor/inpatients'] = 'reception/inpatients/doctor';

/*
*	doctor Routes
*/
$route['dental'] = 'dental/index';
$route['dental/dashboard'] = 'dental/index';
$route['dental/dental-queue'] = 'dental/dental_queue';
$route['dental/general-queue'] = 'reception/general_queue/dental';
$route['dental/visit-history'] = 'reception/visit_list/1/dental';
$route['dental/patient-treatment'] = 'nurse/patient_treatment_statement/dental';


/*
*	doctor Routes
*/
$route['hospital-reports'] = 'hospital-reports/index';
$route['hospital-reports/patient-statements'] = 'administration/patient_statement';
$route['hospital-reports/all-transactions'] = 'administration/reports/all_reports/admin';
$route['hospital-reports/cash-report'] = 'administration/reports/cash_report/admin';
$route['hospital-reports/cash-report/(:num)'] = 'administration/reports/cash_report/$1';
$route['hospital-reports/debtors-report'] = 'administration/reports/debtors_report/0';
$route['hospital-reports/department-report'] = 'administration/reports/department_reports';
$route['hospital-reports/doctors-report'] = 'administration/reports/doctor_reports';

/*
*	ultrasound Routes
*/
$route['radiology/ultrasound-outpatients'] = 'radiology/ultrasound/ultrasound_queue/12';
$route['radiology/ultrasound-inpatients'] = 'reception/inpatients/ultrasound';
$route['radiology/x-ray-outpatients'] = 'radiology/xray/xray_queue/12';
$route['radiology/x-ray-inpatients'] = 'reception/inpatients/xray';
$route['radiology/general-queue'] = 'reception/general_queue/radiology';

/*
*	laboratory Routes
*/
$route['laboratory'] = 'laboratory/index';
$route['laboratory/dashboard'] = 'laboratory/index';
$route['laboratory/lab-queue'] = 'laboratory/lab_queue/12';
$route['laboratory/general-queue'] = 'reception/general_queue/laboratory';
$route['laboratory/inpatients'] = 'reception/inpatients/laboratory';

/*
*	theatre Routes
*/
$route['theatre'] = 'theatre/index';
$route['theatre/dashboard'] = 'theatre/index';
$route['theatre/theatre-queue'] = 'theatre/theatre_queue/12';
$route['theatre/general-queue'] = 'reception/general_queue/theatre';
$route['theatre/inpatients'] = 'reception/inpatients/theatre';

/*
*	laboratory setup Routes
*/
$route['laboratory-setup/classes'] = 'lab_charges/classes';
$route['laboratory-setup/tests'] = 'lab_charges/test_list';
$route['laboratory-setup/tests/(:num)'] = 'lab_charges/test_list/lab_test_name/ASC/__/$1';
$route['laboratory-setup/tests/(:any)/(:any)/(:any)/(:num)'] = 'lab_charges/test_list/$1/$2/$3/$4';
$route['laboratory-setup/tests/(:any)/(:any)'] = 'lab_charges/test_list/$1/$2';



/*
*	pharmacy Routes
*/
$route['pharmacy'] = 'pharmacy/index';
$route['pharmacy/dashboard'] = 'pharmacy/index';
$route['pharmacy/pharmacy-queue'] = 'pharmacy/pharmacy_queue/12';
$route['pharmacy/general-queue'] = 'reception/general_queue/pharmacy';
$route['pharmacy/inpatients'] = 'reception/inpatients/pharmacy';
$route['pharmacy/print-prescription/(:num)'] = 'pharmacy/print_prescription/$1';



/*
*	pharmacy setup Routes
*/
$route['pharmacy-setup/classes'] = 'pharmacy/classes';
$route['pharmacy-setup/inventory'] = 'pharmacy/inventory';
$route['pharmacy-setup/brands'] = 'pharmacy/brands';
$route['pharmacy-setup/generics'] = 'pharmacy/generics';
$route['pharmacy-setup/containers'] = 'pharmacy/containers';
$route['pharmacy-setup/types'] = 'pharmacy/types';


/*
*	Inventory Routes
*/
$route['cash-office'] = 'accounts/index';
$route['accounts/accounts-queue'] = 'accounts/accounts_queue/12';
$route['cash-office/dashboard'] = 'accounts/index';
$route['cash-office/accounts-queue'] = 'accounts/accounts_queue/12';
$route['cash-office/general-queue'] = 'reception/general_queue/accounts';
$route['cash-office/closed-visits'] = 'accounts/accounts_closed_visits';
$route['cash-office/inpatients'] = 'reception/inpatients/accounts';
$route['cash-office/un-closed-visits'] = 'accounts/accounts_unclosed_queue';
$route['accounts/un-closed-visits'] = 'accounts/accounts_unclosed_queue';


/*
*	Cloud Routes
*/
$route['cloud/sync-tables'] = 'cloud/sync_tables/index';
$route['cloud/sync-tables/(:any)/(:any)/(:num)'] = 'cloud/sync_tables/index/$1/$2/$3';
$route['cloud/sync-tables/(:any)/(:any)'] = 'cloud/sync_tables/index/$1/$2';
$route['cloud/add-sync-table'] = 'cloud/sync_tables/add_sync_table';
$route['cloud/edit-sync-table/(:num)'] = 'cloud/sync_tables/edit_sync_table/$1';
$route['cloud/delete-sync-table/(:num)'] = 'cloud/sync_tables/delete_sync_table/$1';
$route['cloud/activate-sync-table/(:num)'] = 'cloud/sync_tables/activate_sync_table/$1';
$route['cloud/deactivate-sync-table/(:num)'] = 'cloud/sync_tables/deactivate_sync_table/$1';
$route['pharmacy/validate-import'] = 'pharmacy/do_drugs_import';
$route['pharmacy/import-template'] = 'pharmacy/import_template';
$route['pharmacy/import-drugs'] = 'pharmacy/import_drugs';

/*
*	Inventory Routes
*/
$route['inventory-setup/inventory-categories'] = 'inventory/categories/index';
$route['inventory-setup/categories/(:num)'] = 'inventory/categories/index/$1';
$route['inventory-setup/add-category'] = 'inventory/categories/add_category';
$route['inventory-setup/edit-category/(:num)'] = 'inventory/categories/edit_category/$1';
$route['inventory-setup/inventory-stores'] = 'inventory/stores/index';
$route['inventory-setup/stores/(:num)'] = 'inventory/stores/index/$1';
$route['inventory-setup/add-store'] = 'inventory/stores/add_store';
$route['inventory-setup/edit-store/(:num)'] = 'inventory/stores/edit_store/$1';

$route['inventory-setup/suppliers'] = 'inventory/suppliers/index';
$route['inventory-setup/suppliers/(:num)'] = 'inventory/suppliers/index/$1';
$route['inventory-setup/add-supplier'] = 'inventory/suppliers/add_supplier';
$route['inventory-setup/edit-supplier/(:num)'] = 'inventory/suppliers/edit_supplier/$1';

$route['inventory/orders'] = 'inventory/orders/index';
$route['inventory/orders/(:num)'] = 'inventory/orders/index/$1';
$route['inventory/add-order'] = 'inventory/orders/add_order';
$route['inventory/add-order-item/(:num)/(:any)'] = 'inventory/orders/add_order_item/$1/$2';
$route['inventory/update-order-item/(:num)/(:any)/(:num)'] = 'inventory/orders/update_order_item/$1/$2/$3';
$route['inventory/update-supplier-prices/(:num)/(:any)/(:num)'] = 'inventory/orders/update_supplier_prices/$1/$2/$3';
$route['inventory/send-for-correction/(:num)'] = 'inventory/orders/send_order_for_correction/$1';
$route['inventory/send-for-approval/(:num)'] = 'inventory/orders/send_order_for_approval/$1';
$route['inventory/send-for-approval/(:num)/(:num)'] = 'inventory/orders/send_order_for_approval/$1/$2';
$route['inventory/submit-supplier/(:num)/(:any)'] = 'inventory/orders/submit_supplier/$1/$2';
$route['inventory/generate-lpo/(:num)'] = 'inventory/orders/print_lpo_new/$1';
$route['inventory/generate-rfq/(:num)/(:num)/(:any)'] = 'inventory/orders/print_rfq_new/$1/$2/$3';
$route['inventory/edit_order/(:num)'] = 'inventory/orders/edit_order/$1';

$route['inventory/products'] = 'inventory_management/index';
$route['inventory/products/(:num)'] = 'inventory_management/index/$1';
$route['inventory/add-product'] = 'inventory_management/add_product';
$route['inventory/activate-product/(:num)'] = 'inventory_management/products/activate_product/$1';
$route['inventory/deactivate-product/(:num)'] = 'inventory_management/products/deactivate_product/$1';
$route['inventory/edit-product/(:num)'] = 'inventory_management/edit_product/$1';

$route['inventory/product-details/(:num)'] = 'inventory_management/manage_product/$1';
$route['inventory/manage-store'] = 'inventory_management/manage_store';
$route['inventory/store-requests'] ='inventory_management/store_requests';
$route['inventory/selected-items/(:num)'] = 'inventory_management/now_store_requests/$1';
$route['inventory/make-order/(:num)'] = 'inventory_management/make_order/$1';
$route['inventory/save-product-request/(:num)/(:num)'] = 'inventory_management/save_order_products/$1/$2';
$route['inventory/update-store-order/(:num)/(:num)'] = 'inventory_management/update_order_products/$1/$2';
$route['inventory/award-store-order/(:num)/(:num)'] = 'inventory_management/award_order_products/$1/$2';
$route['inventory/receive-store-order/(:num)/(:num)/(:num)/(:num)'] = 'inventory_management/receive_order_products/$1/$2/$3/$4';
$route['inventory/product-purchases/(:num)'] = 'inventory_management/all_product_purchases/$1';
$route['inventory/purchase-product/(:num)'] = 'inventory_management/product_purchases/$1';
$route['inventory/edit-product-purchase/(:num)/(:num)'] = 'inventory_management/edit_product_purchase/$1/$2';

$route['inventory/product-deductions'] = 'inventory_management/all_product_deductions';
$route['inventory/deduction-product/(:num)'] = 'inventory_management/product_deductions/$1';
$route['inventory/edit-product-deduction/(:num)/(:num)'] = 'inventory_management/edit_product_deduction/$1/$2';

// pharmacy orders
$route['pharmacy-setup/pharmacy-orders'] = 'inventory/orders/index';
$route['inventory/search-products'] = 'inventory_management/search_inventory_product';
$route['inventory/close-product-search'] = 'inventory_management/close_inventory_search';



$route['orders'] = 'inventory/orders/index';

//departments
$route['departments'] = 'admin/departments/index';
$route['administration/add-department'] = 'admini/departments/add_department';
$route['administration/edit-department/(:num)'] = 'admin/departments/edit_department/$1';
$route['administration/delete-department/(:num)'] = 'admini/departments/delete_department/$1';
$route['administration/activate-department/(:num)'] = 'admini/departments/activate_department/$1';
$route['administration/deactivate-department/(:num)'] = 'admin/departments/deactivate_department/$1';

//import personnel routes
$route['import/personnel'] = 'hr/personnel/import_personnel';
$route['import/personnel-template'] = 'hr/personnel/import_personnel_template';
$route['import/import-personnel'] = 'hr/personnel/do_personnel_import';

//import personnel emails
$route['import/personnel-emails'] = 'hr/personnel/import_personnel_emails';
$route['import/personnel-emails-template'] = 'hr/personnel/import_personnel_emails_template';
$route['import/import-personnel-emails'] = 'hr/personnel/do_personnel_emails_import';

//import branches routes
$route['import/branches'] = 'admin/branches/import_branches';
$route['import/branches-template'] = 'admin/branches/import_branches_template';
$route['import/import-branches'] = 'admin/branches/do_branches_import';

//payroll data import
$route['import/payroll-data'] = 'hr/import_payroll';
$route['import/payroll-template'] = 'hr/import_payroll_template';
$route['import/import-payroll']= 'hr/do_payroll_import';

//import salary advances
$route['salary-advance/import-salary-advance'] = 'accounts/salary_advance/import_salary_advance';
$route['import/import-salary-advances'] = 'accounts/salary_advance/do_advance_import';
$route['import/advance-template'] = 'accounts/salary_advance/advances_template';
$route['download-salary-advance'] = 'accounts/salary_advance/download_salary_advance';

// p9 form
$route['my-account/p9'] = 'accounts/payroll/generate_p9_form';
$route['accounts/generate_p9_form'] = 'accounts/payroll/p9_form';

//p10 form
$route['accounts/p10'] = 'accounts/payroll/generate_p10_form';
$route['accounts/generate_p10_form'] = 'accounts/payroll/p10_form';

//timesheets
$route['timesheets/add-timesheet'] = 'hr/personnel/add_personnel_timesheet';

//bank reports


//salary advances
$route['salary-advance'] = 'accounts/salary_advance/index';
$route['accounts/search-advances'] = 'accounts/salary_advance/search_salary_advance';
$route['close-salary-advance-search'] = 'accounts/salary_advance/close_advance_search';
$route['salary-advance/(:any)/(:any)'] = 'accounts/salary_advance/index/$1/$2';

//payroll reports routes
$route['accounts/payroll-reports'] = 'accounts/payroll/payroll_report';
$route['accounts/search-payroll-reports'] = 'accounts/payroll/search_payroll_reports';

//import overtime-hours
$route['import/overtime'] = 'accounts/payroll/import_overtime';
$route['import/overtime-template'] = 'accounts/payroll/import_overtime_template';
$route['import/import-overtime'] = 'accounts/payroll/do_overtime_import';

//send payslips to the specific personnel
$route['accounts/send-month-payslips/(:num)'] = 'accounts/payroll/send_monthly_payslips/$1';
$route['accounts/cc-payment/access-payslip/(:num)/(:num)'] = 'accounts/cc-payment/access_payslip/$1/$2';
$route['accounts/send-cc-month-payslips/(:num)'] = 'accounts/cc_payment/send_monthly_payslips/$1';
$route['accounts/cc-payment/access-payslip/(:num)/(:num)'] = 'accounts/cc-payment/access_payslip/$1/$2';

//Always comes last
$route['accounts/payroll/(:any)/(:any)'] = 'accounts/payroll/payrolls/$1/$2';
$route['accounts/payroll/(:any)/(:any)/(:num)'] = 'accounts/payroll/payrolls/$1/$2/$3';
$route['accounts/salary-data/(:any)/(:any)'] = 'accounts/payroll/salaries/$1/$2';
$route['accounts/salary-data/(:any)/(:any)/(:num)'] = 'accounts/payroll/salaries/$1/$2/$3';
$route['accounts/print-month-summary/(:num)/(:num)'] = 'accounts/payroll/month_summary/$1/$2';
$route['accounts/cc-payment/(:any)/(:any)'] = 'accounts/cc_payment/cc_payments/$1/$2';
$route['accounts/cc-payment/(:any)/(:any)/(:num)'] = 'accounts/cc_payment/cc_payments/$1/$2/$3';
$route['accounts/salary-data/(:any)/(:any)'] = 'accounts/cc_payment/salaries/$1/$2';
$route['accounts/salary-data/(:any)/(:any)/(:num)'] = 'accounts/cc_payment/salaries/$1/$2/$3';
$route['accounts/print-cc-month-summary/(:num)/(:num)'] = 'accounts/cc_payment/month_summary/$1/$2';
