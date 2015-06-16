{include file="../inc/table.tpl" headItems=$keys bodyItems=$res id="angebote"}
<!-- Modal -->
<div id="angebotModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="angebotModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="angebotModalLabel">Name</h3>
  </div>
  <div class="modal-body">
	Angebot #<span id="angebotsnummer">0</span>
   	<div class="buttons">

  	</div>
  </div>
</div>
{include file="inc/modal.tpl" name="rechnung_aus_angebot" title="Rechnung aus Angebot erstellen"}