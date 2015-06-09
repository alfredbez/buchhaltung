<form action="{if isset($action)}{$action}{else}index.php?site={$smarty.get.site}{/if}" method="{if isset($method)}{$method}{else}post{/if}" class="form-horizontal">
	{foreach $fields as $field}
	<div class="control-group">
		<label class="control-label">{$field.name}</label>
		<div class="controls">
			{if isset($field.input)}
			<input type="{if isset($field.input.type)}{$field.input.type}{else}text{/if}" name="{$field.input.name}"{if isset($field.class)} class="{$field.class}"{/if}{if isset($field.disableAutocomplete)} autocomplete="off"{/if}{if isset($field.input.placeholder)} placeholder="{$field.input.placeholder}"{/if}{if isset($field.input.value)} value="{$field.input.value}"{/if} />
			{/if}
			{if isset($field.select)}
			<select name="{$field.select.name}"{if isset($field.class)} class="{$field.class}"{/if}>
				{foreach $field.select.options as $option}
				<option value="{$option.value}">{$option.text}</option>
				{/foreach}
			</select>
			{/if}
			{if isset($field.textarea)}
			<textarea name="{$field.textarea.name}"{if isset($field.class)} class="{$field.class}"{/if}></textarea>
			{/if}
		</div>
	</div>
	{/foreach}
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="{if isset($submit_name)}{$submit_name}{else}send{/if}" value="{if isset($submit_text)}{$submit_text}{else}Speichern{/if}" class="btn btn-success" />
		</div>
	</div>
</form>