{if isset($message)}<p class="alert alert-{if $success}success{else}error{/if}">{$message}</p>{/if}
{* Formular zum Eintragen von Herstellern *}
<form action="index.php?site={$smarty.get.site}" method="post" id="herstellerForm" class="teaserForm collapseForm form-horizontal">
	<input type="hidden" name="type" value="hersteller" >
	<button class="showForm btn">Hersteller hinzufügen</button>
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
{* Formular zum Eintragen von Vertretern *}
<form action="index.php?site={$smarty.get.site}" method="post" id="vertreterForm" class="teaserForm collapseForm form-horizontal">
	<input type="hidden" name="type" value="vertreter" >
	<button class="showForm btn">Vertreter hinzufügen</button>
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
{* Formular zum Eintragen von Artikeln *}
<form action="index.php?site={$smarty.get.site}" method="post" id="artikelForm" class="teaserForm collapseForm form-horizontal">
	<input type="hidden" name="type" value="artikel" >
	<button class="showForm btn">Artikel hinzufügen</button>
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
{* Ausgabe aller Artikel *}
{include file="../inc/table.tpl" headItems=$keys bodyItems=$res id="material"}
{include file="../inc/modal.tpl" name="material" title="Herstellerinformationen"}