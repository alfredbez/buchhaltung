{if isset($message)}<p class="alert alert-{if $success}success{else}error{/if}">{$message}</p>{/if}
{* Formular zum Eintragen von Herstellern *}
<form action="index.php?site={$smarty.get.site}" method="post" id="herstellerForm" class="form-horizontal">
	<div class="control-group">
		<label class="control-label">ID</label>
		<div class="controls">
			<input type="text" name="id">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Name</label>
		<div class="controls">
			<input type="text" name="name">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Kundennummer</label>
		<div class="controls">
			<input type="text" name="kundennummer">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Telefon</label>
		<div class="controls">
			<input type="text" name="telefon">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Fax</label>
		<div class="controls">
			<input type="text" name="fax">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Passwort</label>
		<div class="controls">
			<input type="text" name="passwort">
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="send" value="Speichern" class="btn btn-success" />
		</div>
	</div>
</form>