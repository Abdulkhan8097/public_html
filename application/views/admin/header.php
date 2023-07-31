<?php
$this->CI = &get_instance();
$this->CI->load->model('admin/User_model', 'user_model');
$user_id = $this->session->userdata('user_id');
$userdetails = $this->CI->user_model->getUserDetails($user_id);

?>
<!doctype html>
<html lang="en-US">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php echo (isset($page_title)) ? $page_title : ''; ?> | <?php echo SITE_FULL_NAME; ?></title>
    <meta name="description" content="" />
    <meta name="Author" content="<?php echo SITE_AUTHOR; ?>" />

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- WEB FONTS -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
        rel="stylesheet" type="text/css" />

    <!-- CORE CSS -->
    <link href="<?php echo ADMIN_ASSETS; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />

    <link href="<?php echo ADMIN_ASSETS; ?>assets/plugins/styleswitcher/styleswitcher.css" rel="stylesheet"
        type="text/css" />

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />

    <!-- THEME CSS -->
    <link href="<?php echo ADMIN_ASSETS; ?>assets/css/essentials.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>assets/css/layout.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo ADMIN_ASSETS; ?>assets/css/layout-datatables.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>assets/css/layout-footable-minimal.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>assets/css/layout-nestable.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo ADMIN_ASSETS; ?>assets/css/color_scheme/blue.css" rel="stylesheet" type="text/css"
        id="color_scheme" />


    <style type="text/css">
    .siteName {
        color: #FFF;
    }

    .required {
        color: red;
        margin-left: 4px;
    }
    .row
    {
        margin-left: 0px;
        margin-right: 0px;
    }
    .badge {
  padding: 1px 9px 2px;
  font-size: 12.025px;
  font-weight: bold;
  white-space: nowrap;
  color: #ffffff;
  background-color: #999999;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
}
.badge:hover {
  color: #ffffff;
  text-decoration: none;
  cursor: pointer;
}
.badge-error {
  background-color: #b94a48;
}
.badge-error:hover {
  background-color: #953b39;
}
.badge-success {
  background-color: #468847;
}
.badge-success:hover {
  background-color: #356635;
}
.badge-warning {
  background-color: #f89406;
}
.badge-warning:hover {
  background-color: #c67605;
}

.badge-warning1 {
  background-color: #33CEE2;
}
.badge-warning1:hover {
  background-color: #10ADC1;
}
.badge-warning11 {
  background-color: #4FB23B;
}
.badge-warning11:hover {
  background-color: #147600;
}

    </style>
</head>
<!--
		.boxed = boxed version
	-->

<body>

    <!-- WRAPPER -->
    <div id="wrapper" class="clearfix">

        <!-- START SIDEBAR -->
        <?php require('sidebar.php'); ?>
        <!-- END SIDEBAR -->

        <!-- HEADER -->
        <header id="header">

            <!-- Mobile Button -->
            <button id="mobileMenuBtn"></button>

            <!-- Logo -->
            <span class="logo pull-left">
                <a href="<?php echo ADMIN_DASHBOARD_URL; ?>">
                    <span
                        style="color: #fff;font-weight:bold;font-size: 12px;margin-left: -16px;"><?php echo SITENAME; ?></span>
                    <!-- <img src="<?php //echo ADMIN_ASSETS; 
                                    ?>assets/images/logo.png" alt="admin panel" height="35"/> -->
                </a>
            </span>
            <span class="pull-left margin-top-10 margin-right-10  ">
                <a href="<?php echo FRONT_HOME_URL; ?>" target="_blank" title="Visit Site">
                    <i class="fa fa-globe fa-2x text-white "></i>
                </a>
            </span>
            <form method="get" action="page-search.html" class="search pull-left hidden-xs">
                <input type="text" class="form-control" name="k" placeholder="Search for something..." />
            </form>
            <nav>

                <!-- OPTIONS LIST -->
                <ul class="nav pull-right">

                    <!-- USER OPTIONS -->
                    <li class="dropdown pull-left">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                            data-close-others="true">
                            <img class="user-avatar" alt=""
                                src="<?php echo (isset($userdetails['profile_img'])) ? PROFILE_DISPLAY_PATH_NAME . $userdetails['profile_img'] : BLANK_IMG; ?>"
                                height="34" />
                            <span class="user-name">
                                <span class="hidden-xs">
                                    <?php echo (isset($userdetails['first_name'])) ? $userdetails['first_name'] : ''; ?>
                                    <?php echo (isset($userdetails['last_name'])) ? $userdetails['last_name'] : ''; ?>
                                    <i class="fa fa-angle-down"></i>
                                </span>
                            </span>
                        </a>
                        <ul class="dropdown-menu hold-on-click">
                            <li>
                                <a href="<?php echo ADMIN_PROFILE_DETAILS_URL . '/' . $user_id; ?>"><i
                                        class="fa fa-user"></i> Profile</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?php echo ADMIN_CHANGE_PASSWORD_DETAILS_URL . '/' . $user_id; ?>"><i
                                        class="fa fa-lock"></i> Change Password</a>
                            </li>

                            <li class="divider"></li>

                            <!-- <li>
									<a href="page-lock.html"><i class="fa fa-lock"></i> Lock Screen</a>
								</li> -->
                            <li>
                                <a href="<?php echo site_url('admin/login/logout'); ?>"><i class="fa fa-power-off"></i>
                                    Log Out</a>
                            </li>
                        </ul>
                    </li>
                    <!-- /USER OPTIONS -->

                </ul>
                <!-- /OPTIONS LIST -->

            </nav>

        </header>
        <!-- /HEADER -->