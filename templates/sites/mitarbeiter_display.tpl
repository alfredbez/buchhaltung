{if isset($message)}<div class="alert alert-{$messageType}">{$message}</div>{/if}{if isset($error)}<div class="alert alert-error">{$error}</div>{else}

{* Allgemeine Informationen *}
<a class="btn btn-primary btn-mini btn-icon-animation" href="index.php?site=mitarbeiter_bearbeiten&id={$res.id}">bearbeiten <i class="icon-animation">&#8594;</i></a>
<dl class="dl-horizontal">
  <dt>Mitarbeiternummer</dt>
  <dd>{$res.id}</dd>
  <dt>Vorname</dt>
  <dd>{$res.vorname}</dd>
  <dt>Nachname</dt>
  <dd>{$res.nachname}</dd>
  <dt>Telefon</dt>
  <dd>{$res.tel}</dd>
  <dt>Handy</dt>
  <dd>{$res.handy}</dd>
  <dt>E-Mail Adresse</dt>
  <dd>{$res.email}</dd>
  <dt>Hinweis</dt>
  <dd>{$res.hinweis}</dd>
</dl>

{* Zeiten *}
<h3>Zeiten</h3>
{if isset($res.zeiten)}
<table class="table">
	<thead>
		<tr>
			<th>Tätigkeit</th>
			<th>Projekt</th>
			<th>Stunden</th>
			<th>Erstellt</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach $res.zeiten as $zeit}
		<tr>
			<td>{$zeit.art}</td>
			<td>{$zeit.projektname}</td>
			<td>{$zeit.stunden}</td>
			<td>{$zeit.timestamp}</td>
			<td><button class="close delete-zeit" data-zeitid="{$zeit.id}">&times;</button></td>
		</tr>
		{/foreach}
	</tbody>
</table>
{else}
<div class="alert alert-error">Dieser Mitarbeiter hat noch keine Zeiten eingetragen.</div>
{/if}

{* Notizen *}
<h3>Notizen</h3>
{if isset($res.notizen)}
<table class="table">
	<thead>
		<tr>
			<th>Notiz</th>
			<th>Projekt</th>
			<th>Erstellt</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach $res.notizen as $notiz}
		<tr>
			<td>{$notiz.notiz}</td>
			<td>{$notiz.projektname}</td>
			<td>{$notiz.timestamp}</td>
			<td><button class="close delete-notiz" data-notizid="{$notiz.id}">&times;</button></td>
		</tr>
		{/foreach}
	</tbody>
</table>
{else}
<div class="alert alert-error">Dieser Mitarbeiter hat noch keine Notizen eingetragen.</div>
{/if}

{/if}