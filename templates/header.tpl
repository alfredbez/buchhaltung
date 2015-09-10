<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Buchhaltung - {$site}</title>
		<link rel="stylesheet" type="text/css" href="templates/css/main.css" />
		<link rel="stylesheet" href="templates/css/calendar.min.css">
		<script src="templates/js/jquery-1.9.1.min.js"></script>
		<script src="templates/js/bootstrap.min.js"></script>
		<script src="templates/js/Chart.min.js"></script>
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
  			<div class="navbar-inner">
  				<div class="container" style="width: auto;">
					<!-- Toggle Comment -->
					<a href="index.php" class="brand">Start</a>
					<!-- -->
					<ul class="nav">
						{foreach from=$sites item=s}
						{if $s.file|@is_array}
						<li class="dropdown">
							<a href="#" id="dropdown-{$s.name}" class="dropdown-toggle" data-toggle="dropdown">{$s.name|replace:"_":" "|ucfirst} <b class="caret"></b></a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdown-{$s.name}">
						    	{foreach from=$s.file item=sub}
						    	<li><a href="index.php?site={$sub.file}">{$sub.name|replace:"_":" "|ucfirst}</a></li>
						    	{/foreach}
						    </ul>
						</li>
						{else}
						<li><a href="index.php?site={$s.file}">{$s.name|replace:"_":" "|ucfirst}</a></li>
						{/if}
						{/foreach}
						<li class="admin-tools dropdown">
							<a href="#" id="dropdown-admin-tools" class="dropdown-toggle" data-toggle="dropdown">Admin Tools <b class="caret"></b></a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdown-admin-tools">
						    	<li><a href="#" id="clearCache"><i class="icon-trash"></i> <span>Cache leeren</span></a></li>
						    	<li><a href="log" id="log" target="_blank"><i class="icon-eye-open"></i> <span>Log anzeigen</span></a></li>
						    	<li><a href="php_error_log" id="errorLog" target="_blank"><i class="icon-eye-open"></i> <span>Error-Log anzeigen</span></a></li>
						    </ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div id="main">
			<h1>{$siteTitle}</h1>