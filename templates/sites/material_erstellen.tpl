{if isset($message)}<p class="alert alert-{if $success}success{else}error{/if}">{$message}</p>{/if}
{* Formular zum Eintragen von Artikeln *}
<form action="index.php?site={$smarty.get.site}" method="post" id="artikelForm" class="form-horizontal">
	<div class="control-group">
		<label class="control-label">HerstellerID</label>
		<div class="controls">
			<input type="text" name="herstellerID" autocomplete="off" class="herstellerID">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">externe Artikelnummer</label>
		<div class="controls">
			<input type="text" name="artikelnummer">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Name</label>
		<div class="controls">
			<input type="text" name="name">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Menge</label>
		<div class="controls">
			<input type="text" name="menge">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Einheit</label>
		<div class="controls">
			{include file="inc/einheiten.tpl"}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Einzelpreis</label>
		<div class="controls">
			<input type="text" name="einzelpreis">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Gesamtpreis</label>
		<div class="controls">
			<input type="text" name="gesamtpreis">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Lieferzeit</label>
		<div class="controls">
			<input type="text" name="lieferzeit">
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="send" value="Speichern" class="btn btn-success" />
		</div>
	</div>
</form>