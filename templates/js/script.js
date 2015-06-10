var debug;
$(document).ready(function() {
    $.fn.showSuccessMessage = function(message) {
        $(this).addClass('alert alert-success');
        $(this).html(message);
    };
    var type,
    formData,
    fehlerMeldung,
    action,
    lieferdatum,
    id, // Wenn ein Angebot in eine Rechnung umgewandelt wird, muss die Angebotsnummer mitgegeben werden. Sie steht in 'id'
    edit = $('#edit').length,
        addFehlerMeldung = function(meldung, link) {
            var prefix = '<div class="alert alert-error input-alert-error"><a class="close" data-dismiss="alert">&times;</a><strong>Fehler!</strong><br />',
                suffix = '</div>';
            fehlerMeldung = fehlerMeldung + prefix + meldung + suffix;
        },
        generatePrintPDF = function(id, modalid, type) {
            /* PDF ohne Hintergrundbild generieren */
            $.ajax({
                url: 'php/ajax/createPDF' + type + '_print.php',
                type: 'POST',
                data: 'id=' + id, // Belegnummer
                success: function(data) {
                    var json = $.parseJSON(data);
                    if (json.status === 'success') {
                        /*  Progressbar aktualisieren, 'success'-Status hinzufügen und Animation entfernen   */
                        $('.progress .bar').parent().addClass('progress-success');
                        $('.progress .bar').parent().removeClass('active');
                        $('.progress .bar').css('width', '100%');
                        $('#info-pdfprint').showSuccessMessage('Dokument erfolgreich erstellt!<br /><a href="export/' + type + '/print/' + id + '.pdf" target="_blank">öffnen</a>');
                    } else {
                        alert(json.extra);
                        $('#' + modalid).modal('hide');
                    }
                }
            });
        },
        generatePDF = function(id, modalid, type) {
            /* PDF mit Hintergrundbild generieren */
            $.ajax({
                url: 'php/ajax/createPDF' + type + '.php',
                type: 'POST',
                data: 'id=' + id, // Belegnummer
                success: function(data) {
                    var json = $.parseJSON(data);
                    if (json.status === 'success') {
                        /*  Progressbar aktualisieren   */
                        if($('.progress .bar').css('width') != '100%'){
                            $('.progress .bar').css('width', '60%');
                        }
                        $('#info-pdf').showSuccessMessage('Dokument erfolgreich erstellt!<br /><a href="export/' + type + '/' + id + '.pdf" target="_blank">öffnen</a>');
                        generatePrintPDF(id, modalid, type);
                    } else {
                        alert(json.extra);
                        $('#' + modalid).modal('hide');
                    }
                }
            });
        };

    /* jQuery Plugin 'dataTable' mit einigen Optionen aufrufen. Alle Tabellen mit der HTML-Klasse 'dataTable' sind betroffen */
    $('.dataTable').dataTable({
        "sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>",
        "sPaginationType": "bootstrap",
        "aaSorting" : [[0,"desc"]],
        "iDisplayLength": 100,
        "oLanguage": {
            "sProcessing": "Bitte warten...",
            "sLengthMenu": "_MENU_ Einträge anzeigen",
            "sZeroRecords": "Keine Einträge vorhanden.",
            "sInfo": "_START_ bis _END_ von _TOTAL_ Einträgen",
            "sInfoEmpty": "0 bis 0 von 0 Einträgen",
            "sInfoFiltered": "(gefiltert von _MAX_  Einträgen)",
            "sInfoPostFix": "",
            "sSearch": "Suchen",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Erster",
                "sPrevious": "Zurück",
                "sNext": "Nächster",
                "sLast": "Letzter"
            }
        }
    });

    $('#addTermin').click(function(){
        $('#terminForm').removeClass('hide');
    });
    $('#terminForm .close').click(function(){
        $('#terminForm').addClass('hide');
    });

    $('.mitarbeiterID').typeahead({
        source: function(query, process) {
            var s = $(this)[0].$element.val();
            return $.getJSON(
                'php/ajax/getMitarbeiterId.php?string=' + s, {
                query: query
            },

            function(data) {
                var result = [];
                $.each(data, function(i, e) {
                    result.push(e.result);
                });
                return process(result);
            });
        },
        updater: function(item) {
            return item.substring(0, item.indexOf(' '));
        }
    });

    $('.delete-notiz').click(function(){
        var el = $(this),
            id = el.data('notizid'),
            tr = el.closest('tr');
        $.ajax({
            url: 'php/ajax/deleteNotiz.php?id=' + id,
            success: function(data) {
                /* delete tr */
                tr.find("td").fadeOut(1000, function() {
                    tr.remove();
                });
            }
        });
    });
    $('.delete-zeit').click(function(){
        var el = $(this),
            id = el.data('zeitid'),
            tr = el.closest('tr');
        $.ajax({
            url: 'php/ajax/deleteZeit.php?id=' + id,
            success: function(data) {
                /* delete tr */
                tr.find("td").fadeOut(1000, function() {
                    tr.remove();
                });
                /* update progress-bar */
                $('.progress .bar').css('width', data + '%');
            }
        });
    });


    /*********************************************************************
     *  Funktionen für Materialverwaltung
     *********************************************************************/
    $('.showForm').click(function() {
        $(this).parent().toggleClass('collapseForm');
        return false;
    });
    /* HerstellerID mithilfe des Herstellernamens finden */
    $('.herstellerID').typeahead({
        source: function(query, process) {
            var s = $(this)[0].$element.val();
            return $.getJSON(
                'php/ajax/getHerstellerId.php?string=' + s, {
                query: query
            },

            function(data) {
                var result = [];
                $.each(data, function(i, e) {
                    result.push(e.result);
                });
                return process(result);
            });
        },
        updater: function(item) {
            return item.substring(0, item.indexOf(' '));
        }
    });

    /* Klick auf Hersteller-Datensatz zeigt Modal mit Infos zu Vertretern an */
    $('table#hersteller tbody').delegate('tr', 'click', function() {
        var el = $(this),
            id = el.find('td:nth-child(2)').text(),
            herstellerID = id.substr(0, 3);
        $.id = id; //id global bereitstellen. Wenn man den Hersteller löschen will, braucht man diese
        $.ajax({
            url: 'php/ajax/getHerstellerInfo.php?id=' + herstellerID,
            success: function(data) {
                $('#herstellerModal .modal-body').html(data);
                $('#herstellerModal').modal('show');
            }
        });
    });

    /* Klick auf Material-Datensatz zeigt Modal mit Infos */
    $('table#artikel tbody').delegate('tr', 'click', function() {
        var el = $(this),
            id = el.find('td:nth-child(1)').text();
        $.ajax({
            url: 'php/ajax/getArtikelInfo.php?id=' + id,
            success: function(data) {
                $('#artikelModal .modal-body').html(data);
                $('#artikelModal').modal('show');
                $.id = id;
            }
        });
    });

    /* Artikel löschen */
    $('#artikelModal').delegate('.delete-artikel', 'click', function() {
        $('#artikelModal').modal('hide');
        $('#artikelModal').on('hidden', function() {
            $('#deleteModalLabel').text('Diesen Artikel wirklich löschen?');
            $('.modal-body').html('<div class="buttons"><button class="btn btn-danger" id="sure"><i class="icon-trash icon-white"></i> Ja, wirklich löschen</button><button class="btn" data-dismiss="modal">Nein, doch nicht</button></div>');
            $('#deleteModal').modal('show');
            $('#deleteModal').delegate('#sure','click', function() {
                $('#deleteModal').modal('hide');
                var id = $.id;
                $.ajax({
                    url: "php/ajax/deleteArtikel.php?id=" + id
                }).done(function() {
                    var row;
                    $('table#artikel tbody tr').each(function() {
                        if ($(this).find('td:nth-child(1)').text() === id) {
                            row = $(this);
                        }
                    });
                    row.find("td").fadeOut(1000, function() {
                        row.remove();
                    });
                });
            });
        });
    });

    /* Hersteller löschen */
    $('#herstellerModal').delegate('.delete-hersteller', 'click', function() {
        $('#herstellerModal').modal('hide');
        $('#herstellerModal').on('hidden', function() {
            $('#deleteModalLabel').text('Diesen Hersteller wirklich löschen?');
            $('.modal-body').html('<div class="buttons"><button class="btn btn-danger" id="sure"><i class="icon-trash icon-white"></i> Ja, wirklich löschen</button><button class="btn" data-dismiss="modal">Nein, doch nicht</button></div>');
            $('#deleteModal').modal('show');
            $('#deleteModal').delegate('#sure','click', function() {
                $('#deleteModal').modal('hide');
                var id = $.id;
                $.ajax({
                    url: "php/ajax/deleteHersteller.php?id=" + id
                }).done(function() {
                    var row;
                    $('table#hersteller tbody tr').each(function() {
                        if ($(this).find('td:nth-child(2)').text() === id) {
                            row = $(this);
                        }
                    });
                    row.find("td").fadeOut(1000, function() {
                        row.remove();
                    });
                });
            });
        });
    });

    /* Vertreter löschen */
    $('#herstellerModal').delegate('.delete-vertreter', 'click', function() {
        $.id = $(this).attr('data-id');
        $('#herstellerModal').modal('hide');
        $('#herstellerModal').on('hidden', function() {
            $(this).unbind();
            $('#deleteModalLabel').text('Diesen Vertreter wirklich löschen?');
            $('.modal-body').html('<div class="buttons"><button class="btn btn-danger" id="sure"><i class="icon-trash icon-white"></i> Ja, wirklich löschen</button><button class="btn" data-dismiss="modal">Nein, doch nicht</button></div>');
            $('#deleteModal').modal('show');
            $('#deleteModal').delegate('#sure','click', function() {
                $('#deleteModal').modal('hide');
                var id = $.id;
                $.ajax({
                    url: "php/ajax/deleteVertreter.php?id=" + id
                });
            });
        });
    });


    $('body').on('mouseover', '#herstellerInfo', function() {
        $('.editHersteller').editable(function(value, settings) {
            var cellName = $(this).parent().find('td:nth-child(1)').text().toLowerCase(),
                id = $('#id').text();
            $(this).load('php/ajax/saveHerstellerTable.php?id=' + id + '&value=' + encodeURIComponent(value) + '&cellName=' + cellName);
            return true;
        }, {
            type: 'text',
            submit: 'OK'
        }).trigger("focus");
    });
    $('body').on('mouseover', '.editVertreter', function() {
        $('.editVertreter').editable(function(value, settings) {
            var cellName = $(this).parent().find('td:nth-child(1)').text().toLowerCase(),
                id = $($(this).parents('table')[0]).attr('data-id');
            $(this).load('php/ajax/saveVertreterTable.php?id=' + id + '&value=' + encodeURIComponent(value) + '&cellName=' + cellName);
            return true;
        }, {
            type: 'text',
            submit: 'OK'
        }).trigger("focus");
    });
    $('body').on('mouseover', '.editArtikel', function() {
        $('.editArtikel').editable(function(value, settings) {
            var cellName = $(this).parent().find('td:nth-child(1)').text().toLowerCase(),
                id = $.id;
            $(this).load('php/ajax/saveArtikelTable.php?id=' + id + '&value=' + encodeURIComponent(value) + '&cellName=' + cellName);
            return true;
        }, {
            type: 'text',
            submit: 'OK'
        }).trigger("focus");
    });

    /*********************************************************************
     *  Funktionen zum Formular (für die Rechnungs- / Angebotserstellung)
     *********************************************************************/
    /*  Ins Feld 'Kunde' kann nach der Kundennummer mithilfe des Namens gesucht werden */
    $('.kundeID').typeahead({
        source: function(query, process) {
            var s = $('.kundeID').val();
            return $.getJSON(
                'php/ajax/getKundennummer.php?string=' + s, {
                query: query
            },

            function(data) {
                var result = [];
                $.each(data, function(i, e) {
                    result.push(e.name);
                });
                return process(result);
            });
        },
        updater: function(item) {
            return item.substring(0, item.indexOf(' '));
        }
    });

    /* Datepicker initialisieren    */
    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        weekStart: 1
    });

    /*  Artikel hinzufügen */
    $('button#addArticle').click(function() {
        var el = $('.article').last();
        el.clone(true, true).appendTo(el.parent());
        return false;
    });

    /*  Artikel entfernen */
    $('button.deleteArticle').on('click', function() {
        if ($('.article').size() > 1) {
            var el = $(this).parent().parent().remove();
        }
        return false;
    });

    /*  Skonto Informationen ein- / ausblenden */
    (function() {
        if ($('input[name="zahlbar"]:checked').val() === 'skonto') {
            $('#skonto').show();
        } else {
            $('#skonto').hide();
        }
    }());
    $('input[name="zahlbar"]').click(function() {
        if ($('input[name="zahlbar"]:checked').val() === 'skonto') {
            $('#skonto').show();
        } else {
            $('#skonto').hide();
        }
    });

    /*
        Rechnung / Angebot erstellen

        Formular nicht absenden, sondern ein Modal anzeigen, in dem nacheinandern
        alle SQL Inserts erfolgen und die PDF-Dokumente generiert werden

        anschließend die Links zu den Dokumenten zeigen
    */
    $('#save').click(function(event) {
        /*  Alle Angaben validieren, also prüfen */
        var fehler = false,
            content = '',
            hideFehlerMeldungenTimeout;
        fehlerMeldung = '';
        if ($('#kundennummer').val() === '') {
            fehler = true;
            $('#kundennummer').addClass('input-error');
            addFehlerMeldung('Bitte Kundennummer angeben!');
        }
        $('textarea[name="name[]"]').each(function() {
            if ($(this).val() === '') {
                fehler = true;
                $(this).addClass('input-error');
                addFehlerMeldung('Bitte Artikelnamen angeben!');
            }
        });
        if (typeof $('input[name="zahlbar"]:checked').val() === "undefined") {
            fehler = true;
            $('.zahlungsart').addClass('input-error');
            addFehlerMeldung('Bitte Zahlungsart wählen!');
        }
        if (fehler === true) {
            $(fehlerMeldung).insertAfter('#main > h1');
            hideFehlerMeldungenTimeout = window.setTimeout(function() {
                $(".input-alert-error").alert('close');
            }, 4000);
            return false;
        }
        /* ENDE Validierung */
        type = $('#type').val(); // Typ des Dokumentes 'angebot' oder 'rechnung'
        /*  Alle Formularfelder in formData speichern   */
        formData = $('#createPDF').serialize();

        /*  Progressbar erstellen   */
        content += '<h4>Fortschritt</h4><div class="progress progress-striped active"><div class="bar" style="width: 2%;"></div></div>';
        /*  Infos zu Datenbankeinträgen   */
        content += '<h4>Informationen speichern</h4><div id="info-database">&nbsp;</div>';
        /*  Infos zu PDF Generierung   */
        content += '<h4>Dokument generieren</h4><div id="info-pdf">&nbsp;</div>';
        /*  Infos zu PDF /Druckversion Generierung  */
        content += '<h4>Druckversion generieren</h4><div id="info-pdfprint">&nbsp;</div>';
        $('#generatorModal .modal-body').html(content);
        $('#generatorModal').modal('show');

        /* false zurückgeben, um Formular nicht abzusenden  */
        return false;
    });

    /*  Rechnung generieren    */
    $('#generatorModal').on('shown', function() {
        /* Erst wenn das Modal ganz angezeigt wird, sollen die Daten gesendet werden */

        if (edit === 0) {
            action = 'save'; // Rechnung bzw Angebot erstellen (NICHT BEARBEITEN!)
        } else {
            action = 'update'; // Rechnung bzw Angebot bearbeiten / aktualisieren
        }

        /* DB-Einträge machen   */
        $.ajax({
            url: 'php/ajax/' + action + type + '.php',
            type: 'POST',
            data: formData,
            success: function(data) {
                var json = $.parseJSON(data),
                    id = json.extra;
                if (json.status === 'success') {
                    /*  Progressbar aktualisieren   */
                    $('.progress .bar').css('width', '10%');
                    $('#info-database').showSuccessMessage('Rechnunginformationen wurden gespeichert!');
                    /* PDFs generieren */
                    generatePDF(id, 'generatorModal', type);
                } else {
                    alert(json.extra);
                    $('#generatorModal').modal('hide');
                }
            }
        });
    });

    /********************/
    /*  Modal Events    */
    /********************/
    /* Angebot / Rechnung löschen  */
    $('body').on('click', '#delete', function() {
        var type = $(this).attr('data-type'),
            id = $(this).attr('data-id'),
            tableName;
        if (confirm("Wirklich löschen?")) {
            $.ajax({
                url: 'php/ajax/delete' + type + '.php?id=' + id,
                success: function() {
                    $('#' + type + 'Modal').modal('hide');

                    /* Zeile löschen */
                    tableName = (type === 'rechnung') ? 'rechnungen' : 'angebote';
                    $('table#' + tableName + ' tbody tr').each(function() {
                        if (id === $(this).find('td:nth-child(1)').text()) {
                            $(this).remove();
                            $(".dataTables_info").hide();
                        }
                    });
                }
            });
        }
    });
    /* PDF neu generieren  */
    $('body').on('click', '#refreshPDF', function() {
        /* lokale Variablen */
        var content = '';
        /* globale Variablen */
        type = $(this).attr('data-type');
        id = $(this).attr('data-id');

        $('#' + type + 'Modal').modal('hide');

        /*  Progressbar erstellen   */
        content += '<h4>Fortschritt</h4><div class="progress progress-striped active"><div class="bar" style="width: 2%;"></div></div>';
        /*  Infos zu PDF Generierung   */
        content += '<h4>Dokument generieren</h4><div id="info-pdf">&nbsp;</div>';
        /*  Infos zu PDF /Druckversion Generierung  */
        content += '<h4>Druckversion generieren</h4><div id="info-pdfprint">&nbsp;</div>';

        $('#updateModal .modal-body').html(content);

        $('#updateModal').modal('show');

        $('#updateModal').on('shown', function() {
            generatePDF(id, 'update', type);
        });
    });
    /* Rechnung aus Angebot erstellen  */
    $('body').on('click', '#rechnung_aus_angebot', function() {

        var dateRegex = /^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$/;
        var placeholderFormat = "TT.MM.JJJJ";
        lieferdatum = prompt("Lieferdatum:", placeholderFormat);

        if(lieferdatum === placeholderFormat)
        {
            lieferdatum = '';
        }

        if(!lieferdatum.match(dateRegex) && lieferdatum !== ''){
            alert('Die Eingabe ' + lieferdatum + ' ist kein gültiges Datum'
                    + 'im Format ' + placeholderFormat);
            return false;
        }
        if(lieferdatum === '')
        {
            if(confirm('Soll das im Angebot angegebene Lieferdatum verwendet werden?'))
            {
                lieferdatum = 'ausAngebot';
            }
        }

        var content = '';
        id = $(this).attr('data-id');
        $('#angebotModal').modal('hide');


        /*  Progressbar erstellen   */
        content += '<h4>Fortschritt</h4><div class="progress progress-striped active"><div class="bar" style="width: 2%;"></div></div>';
        /*  Infos zu Datenbankeinträgen   */
        content += '<h4>Informationen speichern</h4><div id="info-database">&nbsp;</div>';
        /*  Infos zu PDF Generierung   */
        content += '<h4>Dokument generieren</h4><div id="info-pdf">&nbsp;</div>';
        /*  Infos zu PDF /Druckversion Generierung  */
        content += '<h4>Druckversion generieren</h4><div id="info-pdfprint">&nbsp;</div>';

        $('#rechnung_aus_angebotModal .modal-body').html(content);

        $('#rechnung_aus_angebotModal').modal('show');
    });
    $('#rechnung_aus_angebotModal').on('shown', function() {
        /* Erst wenn das Modal angezeigt wird, sollen die Daten gesendet werden */
        $.ajax({
            url: 'php/ajax/rechnung_aus_angebot.php?id=' + id + '&lieferdatum=' + lieferdatum,
            success: function(data) {
                /*  Progressbar aktualisieren   */
                $('.progress .bar').css('width', '10%');
                $('#info-database').showSuccessMessage('Rechnunginformationen wurden gespeichert!');
                var json = $.parseJSON(data);
                generatePDF(json.extra, 'rechnung_aus_angebotModal', 'rechnung');
            }
        });
    });

    /********************/
    /*  Tabellen Events */
    /********************/

    $('table#projekte').delegate('tr', 'click', function() {
        var id = $(this).find('td:first').text();
        window.location = "index.php?site=projekt_display&id=" + id;
    });
    $('table#mitarbeiter').delegate('tr', 'click', function() {
        var id = $(this).find('td:first').text();
        window.location = "index.php?site=mitarbeiter_display&id=" + id;
    });

    /********************/
    /*  Rechnungen      */
    /********************/
    /*  click on tr     */
    $('table#rechnungen tbody').delegate('tr', 'click', function() {
        var el = $(this),
            name = el.find('td:nth-child(5)').text(),
            id = el.find('td:first').text();
        $('#rechnungModal #rechnungModalLabel').text(name);
        $('#rechnungModal #rechnungsnummer').text(id);
        $('#rechnungModal .modal-body .buttons').html('<a href="export/rechnung/' + id + '.pdf" target="_blank" class="btn btn-primary"><i class="icon-eye-open icon-white"></i> Öffnen</a><a href="export/rechnung/print/' + id + '.pdf" target="_blank" class="btn"><i class="icon-print"></i> Druckversion öffnen</a><a href="index.php?site=rechnung_bearbeiten&id=' + id + '" class="btn"><i class="icon-edit"></i> Bearbeiten</a><a href="#" id="delete" data-type="rechnung" data-id="' + id + '" class="btn btn-danger"><i class="icon-trash icon-white"></i> Löschen</a><hr /><a href="#" data-id="' + id + '" data-type="rechnung" id="refreshPDF" class="btn"><i class="icon-refresh"></i> PDF neu erstellen</a>');
        $('#rechnungModal').modal('show');
    });

    /********************/
    /*  Angebote        */
    /********************/
    /*  click on tr     */
    $('table#angebote tbody').delegate('tr', 'click', function() {
        var el = $(this),
            name = el.find('td:nth-child(5)').text(),
            id = el.find('td:first').text();
        $.ajax({
            url: 'php/ajax/isConverted.php?id=' + id,
            success: function(data) {
                var json = $.parseJSON(data),
                    extra = '';
                if (json.status === 'success') {
                    extra = '<div class="alert alert-info">Dieses Angebot liegt bereits als Rechnung vor<br />Rechnungsnummer: <a href="export/rechnung/' + json.extra + '.pdf" target="_blank">' + json.extra + '</a></div>';
                }
                $('#angebotModal #angebotModalLabel').text(name);
                $('#angebotModal #angebotsnummer').text(id);
                var buttonsHtml = '';
                buttonsHtml += extra;
                buttonsHtml += '<a href="export/angebot/' + id + '.pdf" target="_blank" class="btn btn-primary"><i class="icon-eye-open icon-white"></i> Öffnen</a>';
                buttonsHtml += '<a href="export/angebot/print/' + id + '.pdf" target="_blank" class="btn"><i class="icon-print"></i> Druckversion öffnen</a>';
                buttonsHtml += '<a href="index.php?site=angebot_bearbeiten&id=' + id + '" class="btn"><i class="icon-edit"></i> Bearbeiten</a>';
                if (json.status !== 'success') {
                    buttonsHtml += '<a href="#" id="rechnung_aus_angebot" data-id="' + id + '" class="btn"><i class="icon-file"></i> Rechnung erstellen</a>';
                }
                buttonsHtml += '<a href="#" id="delete" data-type="angebot" data-id="' + id + '" class="btn btn-danger"><i class="icon-trash icon-white"></i> Löschen</a>';
                buttonsHtml += '<hr /><a href="#" data-id="' + id + '" data-type="angebot" id="refreshPDF" class="btn"><i class="icon-refresh"></i> PDF neu erstellen</a>';
                $('#angebotModal .modal-body .buttons').html(buttonsHtml);
                $('#angebotModal').modal('show');
            }
        });
    });


    /********************/
    /*  Kunden          */
    /********************/
    /*  inline-editing  */
    $('body').on('click', '.edit', function() {
        var cellIndex = parseInt($(this).parent().prevObject[0].cellIndex, 10) + 1,
            cellName = $('#kunden thead th:nth-child(' + cellIndex + ')').text(),
            id = $(this).parent().find('td:nth-child(1)').text();
        $('.edit').editable(function(value, settings) {
            console.log(id);
            $(this).load('php/ajax/saveKundenTable.php?id=' + id + '&value=' + encodeURIComponent(value) + '&cellName=' + cellName);
            return true;
        }, {
            type: 'text',
            submit: 'OK'
        }).trigger("focus");
    });
    $('body').on('mouseover', '.editInWindow', function() {
        var geschlecht = $(this).parent().parent().find('tr:nth-child(8) > td:nth-child(2)');
        $('.hinweis').removeClass('hide');
        switch (geschlecht.text()) {
            case 'männlich':
                geschlecht.text('0');
                break;
            case 'weiblich':
                geschlecht.text('1');
                break;
            case 'sonstige':
                geschlecht.text('2');
                break;
        }
        $('.editInWindow').editable(function(value, settings) {
            var cellName = $(this).parent().find('td:nth-child(1)').text().toLowerCase(),
                id = $(this).parent().parent().find('tr:nth-child(1) > td:nth-child(2)').text();
            $(this).load('php/ajax/saveKundenTable.php?id=' + id + '&value=' + encodeURIComponent(value) + '&cellName=' + cellName);
            return true;
        }, {
            type: 'text',
            submit: 'OK'
        }).trigger("focus");
    });
    /*  Datensatz löschen   */
    $('body').on('click', '.delete', function() {
        var row = $(this).closest('tr'),
            name = row.find('td:nth-child(3)').text() + ' ' + row.find('td:nth-child(4)').text();

        $('#deleteModalLabel').text('Diesen Kunden wirklich löschen?');
        $('#name').text(name);
        $('#deleteModal').modal('show');
        $('#sure').click(function() {
            var id = row.find('td:nth-child(1)').text();
            $.ajax({
                url: "php/ajax/deleteKunde.php?id=" + id
            }).done(function() {
                row.find("td").fadeOut(1000, function() {
                    row.remove();
                });
            });
        });
    });
    /*  Details anzeigen   */
    $('body').on('click', '.details', function() {
        var row = $(this).closest('tr'),
            id = row.find('td:nth-child(1)').text(),
            name = row.find('td:nth-child(3)').text() + ' ' + row.find('td:nth-child(4)').text(),
            titel = row.find('td:nth-child(2)').text();

        $('#deleteModalLabel').text(name);
        $('#name').text(titel);

        $('#detailsModal').modal('show');

        $('#detailsModal .modal-body').load("php/ajax/getKundeInfo.php?id=" + id);
    });
});