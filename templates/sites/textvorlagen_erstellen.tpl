{if isset($message)}<p class="alert alert-{if $success}success{else}error{/if}">{$message}</p>{/if}<form action="index.php?site={$smarty.get.site}" method="post" class="form-horizontal">
  <div class="control-group">
    <label class="control-label">Titel</label>
    <div class="controls">
      <input type="text" name="titel" placeholder="Titel der Textvorlage" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Text</label>
    <div class="controls">
      <textarea name="text" class="auto-width" cols="50" rows="5"></textarea>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <input type="submit" name="send" value="Speichern" class="btn btn-success" />
    </div>
  </div>
</form>