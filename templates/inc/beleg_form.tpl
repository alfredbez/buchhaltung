{* wenn eine Rechnung / ein Angebot erstellt wird, ist $data nicht gesetzt. Da alle Felder im fieldset 'Artikel' aber erst durch eine foreach-Schleife über $data erzeugt werden, würde es hier gar keine Felder geben. Ein Workaround ist hier einfach ein Array $data anzulegen, dass zwei Werte beinhaltet, wobei der zweite Wert ein Array ist *}
{if !isset($data)}{assign var=data value=[NULL,[NULL]]}{/if}
<form action="index.php?site={$smarty.get.site}" method="post" id="createPDF" class="form-horizontal">
<input type="hidden" name="type" value="{$type|strtolower}" id="type" />
{if isset($edit)}<input type="hidden" name="type" value="true" id="edit" />{/if}
	{* allgemeine Informationen *}
	<fieldset>
		<legend>Allgemein</legend>
		<div class="row">
			<div class="span4 highlight">
				<label>Kundennummer</label>
				<input type="text" name="kundennummer" id="kundennummer" class="kundeID" autocomplete="off" value="{$data[0].kundennummer}" />
			</div>
			<div class="span4 highlight">
				<label>{$type}süberschrift</label>
				<input type="text" name="{$type|strtolower}sueberschrift" value="{$data[0].ueberschrift}" />
			</div>
			<div class="span4 highlight">
				<label>{$type}snummer</label>
				<input type="text" name="{$type|strtolower}snummer" value="{if $type=='Rechnung'}{$data[0].rechnungsnummer}{else}{$data[0].angebotsnummer}{/if}" />
				<input type="hidden" name="{$type|strtolower}snummer_alt" value="{if $type=='Rechnung'}{$data[0].rechnungsnummer}{else}{$data[0].angebotsnummer}{/if}" />
			</div>
			<div class="span4 highlight">
				<label>{$type}sdatum</label>
				<input type="text" class="datepicker" name="{$type|strtolower}sdatum" value="{if $type=='Rechnung'}{$data[0].rechnungsdatum}{else}{$data[0].angebotsdatum}{/if}" />
			</div>
			<div class="span4 highlight">
				<label>Lieferdatum</label>
				<input type="text" class="datepicker" name="lieferdatum" value="{$data[0].lieferdatum}" />
			</div>
		</div>
	</fieldset>
	{* Textvorlagen *}
	<fieldset>
		<legend>Textvorlagen</legend>
      {if empty($db_info.textvorlagen)}
        <div class="alert alert-info">
          <strong>Es gibt noch keine Textvorlagen</strong>
          Um welche zu erstellen <a href="index.php?site=textvorlagen_erstellen">klicke hier</a>.
        </div>
      {else}
    		<select id="textvorlagen">
    			{foreach from=$db_info.textvorlagen item=textvorlage}
    			{assign var="label" value=$textvorlage.titel}
    			{if $label==""}
    				{assign var="label" value=$textvorlage.text}
    			{/if}
    			<option value="{$textvorlage.id}" data-text="{$textvorlage.text}">{$label}</option>
    			{/foreach}
    		</select>
      {/if}
	</fieldset>
	{* Text oberhalb der Artikel *}
	<fieldset>
		<legend>Text oberhalb der Artikel</legend>
		<textarea class="text-beleg" name="text_oben">{$data[0].text_oben}</textarea>
		{if !empty($db_info.textvorlagen)}
      <button class="insertTextvorlage btn">Textvorlage einfügen</button>
    {/if}
	</fieldset>
	{* Artikel *}
	<fieldset>
		<legend>Artikel</legend>
		<div class="container-fluid">
			<div class="row-fluid">
				<span class="span5">Artikelname</span>
				<span class="span3">Menge</span>
				<span class="span3">Preis</span>
				<span class="span1">&nbsp;</span>
			</div>
			{foreach from=$data[1] item=article}
			<div class="article row-fluid">
				<div class="span5">
					<textarea name="name[]" cols="7">{$article.name}</textarea>
					{if !empty($db_info.textvorlagen)}
            <button class="insertTextvorlage btn">Textvorlage einfügen</button>
          {/if}
				</div>
				<div class="span3">
					<input type="text" name="amount[]" value="{$article.menge}" />
					{include file="inc/einheiten.tpl" name="einheit[]" article=$article}
				</div>
				<div class="span3">
					<input type="text" name="preis[]" value="{$article.preis}" />
				</div>
				<div class="span1"><button class="btn btn-small btn-danger deleteArticle"><i class="icon-remove-circle icon-white"></i></button></div>
			</div>
			{/foreach}
		</div>
		<button class="btn btn-info" id="addArticle"><i class="icon-plus"></i> Artikel hinzufügen</button>
	</fieldset>
	{* Text unterhalb der Artikel *}
	<fieldset>
		<legend>Text unterhalb der Artikel</legend>
		<textarea class="text-beleg" name="text_unten">{$data[0].text_unten}</textarea>
		{if !empty($db_info.textvorlagen)}
      <button class="insertTextvorlage btn">Textvorlage einfügen</button>
    {/if}
	</fieldset>
	{* Abschlag *}
	<fieldset>
		<legend>Abschlag</legend>
		<div class="row">
			<div class="span4 highlight">
				<label>Abschlag Datum</label>
				<input type="text" class="datepicker" name="abschlagsdatum" value="{$data[0].abschlag_datum}" />
			</div>
			<div class="span4 highlight">
				<label>Abschlagsumme</label>
				<input type="text" name="abschlagssumme" value="{$data[0].abschlag_summe}" />
			</div>
		</div>
	</fieldset>
	{* Netto / Brutto *}
	<fieldset>
		<legend>Endbetrag angeben in:</legend>
		<div class="row">
			<div class="highlight span4">
				<label>
					Brutto<br />
					<input type="radio" name="endbetrag_typ" value="brutto"  {if $data[0].endbetrag_typ!='netto'} checked{/if}/>
				</label>
			</div>
			<div class="highlight span4">
				<label>
					Netto<br />
					<input type="radio" name="endbetrag_typ" value="netto"  {if $data[0].endbetrag_typ=='netto'} checked{/if}/>
				</label>
			</div>
		</div>
	</fieldset>
	{* Zahlungsart *}
	<fieldset>
		<legend>Zahlungsart</legend>
		<div class="row zahlungsart">
			<div class="highlight span4">
				<label>
					sofort Netto Kasse<br />
					<input type="radio" name="zahlbar" value="sofort"  {if $data[0].zahlungsart=='sofort'} checked{/if}/>
				</label>
			</div>
			<div class="highlight span4">
				<label>
					innerhalb 14 Tage Netto Kasse<br />
					<input type="radio" name="zahlbar" value="zweiwochen"  {if $data[0].zahlungsart=='zweiwochen'} checked{/if}/>
				</label>
			</div>
			<div class="highlight span4">
				<label>
					skonto<br />
					<input type="radio" name="zahlbar" value="skonto" {if $data[0].zahlungsart=='skonto'} checked{/if}/>
				</label>
			</div>
			<div id="skonto">
				<div class="highlight span4">
					<label>Bis</label>
					<input type="text" name="skontodatum" class="datepicker" />
				</div>
				<div class="highlight span4">
					<label>Prozentsatz</label>
					<input type="text" name="skontoprozent" value="2" />
				</div>
			</div>
		</div>
	</fieldset>
	<button id="save" class="btn btn-success">{$type} speichern</button>
</form>
{include file="inc/modal.tpl" name="generator" title="$type generieren"}