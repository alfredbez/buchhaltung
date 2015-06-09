{include file="../inc/table.tpl" headItems=$keys bodyItems=$res id="kunden" inlineedit="true" deleteaction="true" showdetails="true"}
<!-- Modal -->
<div id="kundenModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="kundenModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="kundenModalLabel">Name</h3>
  </div>
  <div class="modal-body">
	Kundennummer <span id="kundennummer">0</span>
   	<div class="buttons">

  	</div>
  </div>
</div>