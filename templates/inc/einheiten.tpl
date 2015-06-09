{* Auswahlliste für Einheiten erstellen *}
<select name="{if isset($name)}{$name}{else}einheit{/if}">
	{foreach from=$db_info.einheiten item=einheit}
	<option name="{$einheit.name}"{if isset($article)}{if $article.einheit==$einheit.name} selected{/if}{/if}>{$einheit.html_name}</option>
	{/foreach}
</select>