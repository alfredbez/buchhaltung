{if isset($message)}<p class="alert alert-{if $success}success{else}error{/if}">{$message}</p>{/if}
{* Formular zum Eintragen von Vertretern *}
<form action="index.php?site={$smarty.get.site}" method="post" id="vertreterForm" class="form-horizontal">
	<div class="control-group">
		<label class="control-label">Name</label>
		<div class="controls">
			<input type="text" name="name">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Telefon</label>
		<div class="controls">
			<input type="text" name="telefon">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Handy</label>
		<div class="controls">
			<input type="text" name="handy">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Herstellerid</label>
		<div class="controls">
			<input type="text" name="herstellerID" autocomplete="off" class="herstellerID">
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="send" value="Speichern" class="btn btn-success" />
		</div>
	</div>
</form>