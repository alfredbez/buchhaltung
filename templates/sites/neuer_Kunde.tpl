{if isset($message)}<p class="alert alert-{if $success}success{else}error{/if}">{$message}</p>{/if}<form action="index.php?site={$smarty.get.site}" method="post" class="form-horizontal">
	<div class="control-group">
		<label class="control-label">Kundennummer</label>
		<div class="controls">
			<input type="text" name="kundennummer" placeholder="wird automatisch erzeugt" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Titel</label>
		<div class="controls">
			<input type="text" name="titel" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Vorname</label>
		<div class="controls">
			<input type="text" name="vorname" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Nachname</label>
		<div class="controls">
			<input type="text" name="nachname" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Geschlecht</label>
		<div class="controls">
			<select name="geschlecht">
				<option value="0">männlich</option>
				<option value="1">weiblich</option>
				<option value="2">sonstige (z.B. Firma)</option>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Adresse</label>
		<div class="controls">
			<input type="text" name="adresse" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">PLZ</label>
		<div class="controls">
			<input type="text" name="plz" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Ort</label>
		<div class="controls">
			<input type="text" name="ort" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">E-Mail Adresse</label>
		<div class="controls">
			<input type="text" name="mail" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Fax</label>
		<div class="controls">
			<input type="text" name="fax" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Telefon</label>
		<div class="controls">
			<input type="text" name="telefon" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Bemerkung</label>
		<div class="controls">
			<textarea name="bemerkung"></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="send" value="Speichern" class="btn btn-success" />
		</div>
	</div>
</form>