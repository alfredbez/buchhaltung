{if !isset($name)}{assign "name" "default"}{/if}
{if !isset($title)}{assign "title" "Titel"}{/if}
<div id="{$name}Modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="{$name}ModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="{$name}ModalLabel">{$title}</h3>
  </div>
  <div class="modal-body">
  </div>
</div>