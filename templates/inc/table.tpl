{if empty($bodyItems)}
  Keine Einträge vorhanden.
{else}
  <table class="table dataTable"{if isset($id)} id="{$id}"{/if}>
  	<thead>
  		<tr>
  			{foreach from=$headItems item=item}
  				<th>{$item|capitalize|replace:"_":" "}</th>
  			{/foreach}
  			{if isset($deleteaction)}
  				<th>&nbsp;</th>
  			{/if}
        {if isset($showdetails)}
          <th>&nbsp;</th>
        {/if}
  			{if isset($showDetailPage)}
          <th>&nbsp;</th>
        {/if}
  		</tr>
  	</thead>
  	<tbody>
  		{foreach from=$bodyItems item=row}
  		<tr class="{if $row.bezahlt_am !=''}success{/if}">
  			{foreach from=$row item=col}
  				<td{if isset($inlineedit)} class="edit"{/if}>{$col}</td>
  			{/foreach}
  			{if isset($deleteaction)}
  				<td><button name="delete" class="btn btn-mini btn-danger delete"><i class="icon-trash icon-white"></i></button></td>
  			{/if}
  			{if isset($showdetails)}
  				<td><button name="details" class="btn btn-mini details"><i class="icon-eye-open"></i></button></td>
  			{/if}
        {if isset($showDetailPage)}
          <td><a href="index.php?site={$showDetailPage}&id={$row.id}" class="btn btn-mini"><i class="icon-edit"></i></a></td>
        {/if}
  		</tr>
  		{/foreach}
  	</tbody>
  </table>
  {if isset($deleteaction)}
  <!-- Modal -->
  <div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="deleteModalLabel">Wirklich löschen?</h3>
    </div>
    <div class="modal-body">
  	<span id="name">0</span>
     	<div class="buttons">
  		<button id="sure" class="btn btn-danger" data-dismiss="modal">löschen</button><button class="btn" data-dismiss="modal">doch nicht</button>
    	</div>
    </div>
  </div>
  {/if}
  {if isset($showdetails)}
  <!-- Modal -->
  <div id="detailsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="detailsModalLabel">Details</h3>
    </div>
    <div class="modal-body">
    </div>
  </div>
  {/if}
  {include file="inc/modal.tpl" name="update" title="PDF Dokument neu generieren"}
{/if}
