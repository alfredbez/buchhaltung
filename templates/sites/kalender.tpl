{if isset($message)}<div class="alert alert-{$messageType}">{$message}</div>{/if}
{if isset($smarty.get.delete)}<div class="alert alert-error"><b>ACHTUNG:</b> Diesen Termin wirklich löschen?<br /><br /><a class="btn btn-danger" href="index.php?site={$smarty.get.site}&id={$smarty.get.id}&sure=true"><i class="icon-trash icon-white"></i> Ja, wirklich löschen</a></div>{/if}
<div class="container">

	<div class="page-header">

		<div class="form-inline">
			<button class="btn btn-success" data-calendar-nav="prev" id="addTermin"><i class="icon-white icon-plus-sign"></i> Termin hinzufügen</button>
			<div class="btn-group">
				<button class="btn btn-primary" data-calendar-nav="prev"><< Zurück</button>
				<button class="btn" data-calendar-nav="today">Heute</button>
				<button class="btn btn-primary" data-calendar-nav="next">Weiter >></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-warning" data-calendar-view="year">Jahr</button>
				<button class="btn btn-warning active" data-calendar-view="month">Monat</button>
				<button class="btn btn-warning" data-calendar-view="week">Woche</button>
				<button class="btn btn-warning" data-calendar-view="day">Tag</button>
			</div>
		</div>
		<h3></h3>
	</div>
	<div class="hide well" id="terminForm">
		<button class="close">&times;</button>
		<h3>Termin hinzufügen</h3>
		{include file="../inc/form.tpl" action="index.php?site={$smarty.get.site}&eintragen" fields=$fields}
	</div>

	<div class="row">
		<div class="span12">
			<div id="calendar"></div>
		</div>
	</div>

	<div class="clearfix"></div>


</div>

<script type="text/javascript" src="templates/js/underscore-min.js"></script>
<script type="text/javascript" src="templates/js/calendar-language/de-DE.js"></script>
<script type="text/javascript" src="templates/js/calendar.js"></script>
<script type="text/javascript" src="templates/js/calendar-app.js"></script>