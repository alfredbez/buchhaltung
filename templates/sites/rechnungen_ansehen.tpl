<button id="refreshAllPDFs" data-type="rechnung" class="btn btn-success"><i class="icon-refresh"></i> Alle Rechnungen neu generieren</button>
<hr>
{include file="../inc/table.tpl" headItems=$keys bodyItems=$res id="rechnungen"}
<!-- Modal -->
<div id="rechnungModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="rechnungModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="rechnungModalLabel">Name</h3>
  </div>
  <div class="modal-body">
	Rechnung #<span id="rechnungsnummer">0</span>
   	<div class="buttons">

  	</div>
  </div>
</div>