{if isset($smarty.post.send)}
<div class="alert alert-{if $success}success{else}error{/if}"><button type="button" class="close" data-dismiss="alert">&times;</button>{$message}</div>
{/if}
<form action="index.php?site={$smarty.get.site}" method="post">
	<div class="row">
		{foreach from=$res item=r}
		<div class="span4 highlight"><label>{$r.name|strtolower|replace:"_":" "|ucfirst}</label><input type="text" name="{$r.name}" value="{$r.wert}" /></div>
		{/foreach}
	</div>
	<input type="submit" class="btn btn-success" name="send" value="Änderungen Speichern" />
</form>