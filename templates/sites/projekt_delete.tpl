{if isset($message)}<div class="alert alert-{$messageType}">{$message}</div>{else}{include file="../inc/form.tpl" action="index.php?site={$smarty.get.site}&id={$smarty.get.id}" fields=$fields submit_text="Ja, wirklich löschen"}{/if}