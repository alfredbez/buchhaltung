{* Ausgabe aller Artikel *}
{include file="../inc/table.tpl" headItems=$keys bodyItems=$res id="artikel"}
{include file="../inc/modal.tpl" name="artikel" title="Artikelinformationen"}
{include file="../inc/modal.tpl" name="delete" title="Artikel löschen"}