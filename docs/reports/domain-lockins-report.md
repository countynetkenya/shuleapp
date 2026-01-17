# Domain Lock-ins Scan Report

Generated: Sat Jan 17 09:10:57 UTC 2026

## Summary

/tmp/run-domain-scan.sh: line 19: 1
0: syntax error in expression (error token is "0")

**Total occurrences: 0**

## Detailed Findings


### Pattern: shule.wangombe.com

```
./scripts/find-domain-lockins.sh:9:PATTERNS=("shule.wangombe.com" "wangombe.com" "shulelabs.cloud" "app.shulelabs.cloud" "http://" "https://")
```


### Pattern: wangombe.com

```
./scripts/find-domain-lockins.sh:9:PATTERNS=("shule.wangombe.com" "wangombe.com" "shulelabs.cloud" "app.shulelabs.cloud" "http://" "https://")
```


### Pattern: shulelabs.cloud

```
./scripts/find-domain-lockins.sh:9:PATTERNS=("shule.wangombe.com" "wangombe.com" "shulelabs.cloud" "app.shulelabs.cloud" "http://" "https://")
```


### Pattern: app.shulelabs.cloud

```
./scripts/find-domain-lockins.sh:9:PATTERNS=("shule.wangombe.com" "wangombe.com" "shulelabs.cloud" "app.shulelabs.cloud" "http://" "https://")
```


### Pattern: http://

```
./mvc/helpers/action_helper.php:1150:        $file  = file_get_contents('http://localhost/school4/assets/bootstrap/bootstrap.min.css');
./mvc/helpers/action_helper.php:1151:        $file2 = file_get_contents('http://localhost/school4/assets/inilabs/themes/default/style.css');
./mvc/helpers/action_helper.php:1152:        $file3 = file_get_contents('http://localhost/school4/assets/inilabs/themes/default/inilabs.css');
./mvc/helpers/action_helper.php:1153:        $file4 = file_get_contents('http://localhost/school4/assets/inilabs/combined.css');
./mvc/helpers/action_helper.php:1269:                header('Location: http://' . $_SERVER['SERVER_NAME']);
./mvc/controllers/Teacher.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Posts.php:14:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Resetpassword.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Paymentsettings.php:17:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Librarycardreport.php:14:| WEBSITE:			http://iNilabs.net
./mvc/controllers/Hourly_template.php:14:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Marksetting.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Sponsorship.php:17:| WEBSITE:            http://inilabs.net
./mvc/controllers/Student_statement.php:21:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Paymenttypes.php:18:| WEBSITE:			http://inilabs.net
./mvc/controllers/Exam_type.php:14:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Studentfinereport.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Signin.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Tutorial.php:17:    | WEBSITE:            http://inilabs.net
./mvc/controllers/Onlineexamquestionreport.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Certificatereport.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Fonlineadmission.php:14:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Lmember.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Assignment.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Examcompilation.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Subject.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Leaveapply.php:15:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Classes.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Backup.php:15:| WEBSITE:			http://inilabs.net
./mvc/controllers/Hmember.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Examranking.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Exceptionpage.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Leaveassign.php:14:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Studentgroup.php:14:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Sociallink.php:14:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Onlineadmissionreport.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Migration.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Tmember.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Instruction.php:14:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Invoice.php:25:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Payment.php:18:    | WEBSITE:			http://inilabs.net
./mvc/controllers/Media.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Privacy.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Quickbookssettings.php:16:| WEBSITE:			http://inilabs.net
./mvc/controllers/Mailandsmstemplate.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Tattendance.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Dashboard.php:19:        | WEBSITE:			http://inilabs.net
./mvc/controllers/Dashboard.php:22:        protected $_versionCheckingUrl = 'http://demo.inilabs.net/autoupdate/update/index';
./mvc/controllers/Sattendance.php:14:| WEBSITE:			http://inilabs.net
./mvc/controllers/Addons.php:17:    | WEBSITE:            http://inilabs.net
```


### Pattern: https://

```
./mvc/controllers/Update.php:415:				echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
./mvc/controllers/Frontendmenu.php:107:				if (strpos($urlLinkField, 'http://') === false && strpos($urlLinkField, 'https://') === false) {
./mvc/config/profiler.php:12:|	https://codeigniter.com/user_guide/general/profiling.html
./mvc/config/config.php:31:    $config['base_url'] = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . preg_replace('@/+$@', '', dirname($_SERVER['SCRIPT_NAME'])) . '/';
./mvc/config/config.php:73:| https://codeigniter.com/user_guide/general/urls.html
./mvc/config/config.php:121:| https://codeigniter.com/user_guide/general/core_classes.html
./mvc/config/config.php:122:| https://codeigniter.com/user_guide/general/creating_libraries.html
./mvc/config/config.php:332:| https://codeigniter.com/user_guide/libraries/encryption.html
./mvc/config/config.php:545:    'https://demo.inilabs.net/tracker',
./mvc/config/smileys.php:13:| https://codeigniter.com/user_guide/helpers/smiley_helper.html
./mvc/config/rest.php:608:| e.g. $config['allowed_origins'] = ['http://www.example.com', 'https://spa.example.com']
./mvc/config/recaptcha.php:18:	// https://developers.google.com/recaptcha/docs/language
./mvc/config/hooks.php:11:|	https://codeigniter.com/user_guide/general/hooks.html
./mvc/config/memcached.php:10:|	See: https://codeigniter.com/user_guide/libraries/caching.html#memcached
./mvc/views/_layout_signin.php:98:          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
./mvc/views/stripe/stripe-js.php:2:<script src="https://js.stripe.com/v3/"></script>
./mvc/views/example/addtextfield.php:2:<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
./mvc/views/student/getView.php:1320:	<script src="https://code.highcharts.com/highcharts.js"></script>
./mvc/views/student/getView.php:1321:	<script src="https://code.highcharts.com/modules/exporting.js"></script>
./mvc/views/student/getView.php:1322:	<script src="https://code.highcharts.com/modules/export-data.js"></script>
./mvc/views/student/getView.php:1323:	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
./mvc/views/report/terminal/TerminalReportView.php:90:<script src="https://code.highcharts.com/highcharts.js"></script>
./mvc/views/report/terminal/TerminalReportView.php:91:<script src="https://code.highcharts.com/modules/exporting.js"></script>
./mvc/views/report/terminal/TerminalReportView.php:92:<script src="https://code.highcharts.com/modules/export-data.js"></script>
./mvc/views/report/terminal/TerminalReportView.php:93:<script src="https://code.highcharts.com/modules/accessibility.js"></script>
./mvc/views/report/teacherexam/TeacherexamReportView.php:55:<script src="https://code.highcharts.com/highcharts.js"></script>
./mvc/views/report/teacherexam/TeacherexamReportView.php:56:<script src="https://code.highcharts.com/modules/exporting.js"></script>
./mvc/views/report/teacherexam/TeacherexamReportView.php:57:<script src="https://code.highcharts.com/modules/export-data.js"></script>
./mvc/views/report/teacherexam/TeacherexamReportView.php:58:<script src="https://code.highcharts.com/modules/accessibility.js"></script>
./mvc/views/report/certificate/theme1.php:11:	<link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet"> 
./mvc/views/report/certificate/theme1.php:12:    <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet"> 
./mvc/views/report/certificate/theme1.php:13:    <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Extra+Condensed" rel="stylesheet">
./mvc/views/report/certificate/theme1.php:14:    <link href="https://fonts.googleapis.com/css?family=Limelight" rel="stylesheet">  
./mvc/views/report/certificate/theme1.php:15:    <link href="https://fonts.googleapis.com/css?family=Michroma" rel="stylesheet"> 
./mvc/views/report/certificate/theme1.php:16:    <link href="https://fonts.googleapis.com/css?family=Prosto+One" rel="stylesheet"> 
./mvc/views/report/certificate/theme2.php:8:    <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet"> 
./mvc/views/report/certificate/theme2.php:9:    <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet"> 
./mvc/views/report/certificate/theme2.php:10:    <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Extra+Condensed" rel="stylesheet">
./mvc/views/report/certificate/theme2.php:11:    <link href="https://fonts.googleapis.com/css?family=Limelight" rel="stylesheet">  
./mvc/views/report/certificate/theme2.php:12:    <link href="https://fonts.googleapis.com/css?family=Michroma" rel="stylesheet"> 
./mvc/views/report/certificate/theme2.php:13:    <link href="https://fonts.googleapis.com/css?family=Prosto+One" rel="stylesheet"> 
./mvc/views/report/transaction/TransactionSummaryView.php:191:<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
./mvc/views/report/transaction/TransactionSummaryView.php:192:<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
./mvc/views/report/sale/SaleReportView.php:120:<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
./mvc/views/report/sale/SaleReportView.php:121:<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
./mvc/views/report/purchase/PurchaseReportView.php:123:<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
./mvc/views/report/purchase/PurchaseReportView.php:124:<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
./mvc/views/report/studentexam/StudentexamReportView.php:82:<script src="https://code.highcharts.com/highcharts.js"></script>
./mvc/views/report/studentexam/StudentexamReportView.php:83:<script src="https://code.highcharts.com/modules/exporting.js"></script>
./mvc/views/report/studentexam/StudentexamReportView.php:84:<script src="https://code.highcharts.com/modules/export-data.js"></script>
```


## Recommendations

1. Replace hardcoded domains with environment variables
2. Use configuration files for URL management
3. Implement a centralized configuration service
4. Review and update all http:// to https:// where applicable

