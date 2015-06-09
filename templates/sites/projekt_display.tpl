{if isset($message)}<div class="alert alert-{$messageType}">{$message}</div>{/if}{if isset($error)}<div class="alert alert-error">{$error}</div>{else}

{assign "progress" $res.stundenBisher/$res.stundenGesamt*100}

{* Allgemeine Informationen *}
<h2>{$res.name}</h2>
<a class="btn btn-danger btn-mini" href="index.php?site=projekt_delete&id={$res.id}"><i class="icon-white icon-remove"></i> Projekt löschen</a>
<a class="btn btn-primary btn-mini btn-icon-animation" href="index.php?site=projekt_bearbeiten&id={$res.id}">bearbeiten <i class="icon-animation">&#8594;</i></a>
<dl class="dl-horizontal">
  <dt>Projektnummer</dt>
  <dd>{$res.id}</dd>
  <dt>Kunde</dt>
  <dd>{$res.kundenname}</dd>
  <dt>Erstellt am</dt>
  <dd>{$res.erstellDatum}</dd>
  <dt>Fertiggestellt am</dt>
  <dd>{$res.fertigDatum}</dd>
  <dt>Hinweis</dt>
  <dd>{$res.hinweis}</dd>
  <dt>Status</dt>
  <dd>{$res.status}</dd>
  <dt>Stunden verbraucht</dt>
  <dd>{$res.stundenBisher}</dd>
  <dt>Stunden gesamt</dt>
  <dd>{$res.stundenGesamt}</dd>
</dl>
{if $res.status=='fertig'}
<form action="index.php?site={$smarty.get.site}&id={$res.id}" method="post">
	<input type="hidden" name="set_status_bearbeitung" />
	<button class="btn btn-mini btn-info" type="submit">Auf Status 'in Bearbeitung' setzen</button>
</form>
{else}
<form action="index.php?site={$smarty.get.site}&id={$res.id}" method="post">
	<input type="hidden" name="set_status_fertig" />
	<button class="btn btn-mini btn-success" type="submit"><i class="icon-white icon-ok"></i> Als 'fertig' markieren</button>
</form>
{/if}
<hr />
<div class="progress">
  <div class="bar{if $res.status=='fertig'} bar-success{/if}" style="width: {$progress}%;"></div>
</div>

{* Zeiten *}
<h3>Zeiten</h3>
{if isset($res.zeiten)}
<table class="table">
	<thead>
		<tr>
			<th>Tätigkeit</th>
			<th>Mitarbeitername</th>
			<th>Stunden</th>
			<th>Erstellt</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach $res.zeiten as $zeit}
		<tr>
			<td>{$zeit.art}</td>
			<td>{$zeit.mitarbeitername}</td>
			<td>{$zeit.stunden}</td>
			<td>{$zeit.timestamp}</td>
			<td><button class="close delete-zeit" data-zeitid="{$zeit.id}">&times;</button></td>
		</tr>
		{/foreach}
	</tbody>
</table>
{else}
<div class="alert alert-error">Zu diesem Projekt wurden noch keine Zeiten eingetragen.</div>
{/if}
<button data-toggle="collapse" data-target="#zeitenForm" class="btn btn-primary btn-small"><i class="icon-white icon-plus-sign"></i> Zeit hinzufügen</button>
<div id="zeitenForm" class="collapse">
{include file="../inc/form.tpl" action="index.php?site={$smarty.get.site}&id={$res.id}" fields=$fields_zeiten submit_name="send_zeiten"}
</div>

{* Notizen *}
<h3>Notizen</h3>
{if isset($res.notizen)}
<table class="table">
	<thead>
		<tr>
			<th>Notiz</th>
			<th>Mitarbeitername</th>
			<th>Erstellt</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach $res.notizen as $notiz}
		<tr>
			<td>{$notiz.notiz}</td>
			<td>{$notiz.mitarbeitername}</td>
			<td>{$notiz.timestamp}</td>
			<td><button class="close delete-notiz" data-notizid="{$notiz.id}">&times;</button></td>
		</tr>
		{/foreach}
	</tbody>
</table>
{else}
<div class="alert alert-error">Zu diesem Projekt wurden noch keine Notizen eingetragen.</div>
{/if}
<button data-toggle="collapse" data-target="#notizenForm" class="btn btn-primary btn-small"><i class="icon-white icon-plus-sign"></i> Notiz hinzufügen</button>
<div id="notizenForm" class="collapse">
{include file="../inc/form.tpl" action="index.php?site={$smarty.get.site}&id={$res.id}" fields=$fields_notizen submit_name="send_notizen"}
</div>

{/if}