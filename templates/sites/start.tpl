<div class="row-fluid">
    <div class="span6">
    	<h3>Rechnungsbeträge pro Monat</h3>
    	Gesamtumsatz: <b>{$data.rechnungen.gesamtumsatz}</b>
    	<div id="chart_rechnungen" class="chart"></div>
    </div>
    <div class="span6">
    	<h3>Angebotsbeträge pro Monat</h3>
    	Gesamtumsatz: <b>{$data.angebote.gesamtumsatz}</b>
    	<div id="chart_angebote" class="chart"></div>
    </div>
</div>
<script src="templates/js/highcharts.js"></script>
<script>
var num_rechnungen = [{$data.rechnungen.belegeProMonat}],
	num_angebote = [{$data.angebote.belegeProMonat}],
	formatNum = function(num){
		return num.toFixed(2).toString().replace('.',',');
	};
$(function () {
		/* Chart Rechnung */
        $('#chart_rechnungen').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Umsatz',
                x: -20 //center
            },
            subtitle: {
                text: 'Rechnungen',
                x: -20
            },
            xAxis: {
                categories: [{$data.rechnungen.labels}]
            },
            yAxis: {
                title: {
                    text: 'Umsatz (EUR)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '€',
                formatter: function(){
                	var s = '<b>' + this.x + '</b><br />Rechnungen in diesem Monat: <b>' + num_rechnungen[this.points[0].point.x] + '</b><br />';
                    s += 'Umsatz: <b>' + formatNum(this.points[0].point.y) + '€</b><br />';
                    s += 'Umsatz pro Rechnung: <b>' + formatNum(this.points[1].point.y) + '€</b><br />';
                	return s;
                },
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'center',
                verticalAlign: 'top',
                x: 200,
                y: 0,
                borderWidth: 2
            },
            series: [{
                name: 'Umsatz',
                data: [{$data.rechnungen.monatsumsatz}]
            },{
                name: 'Durchschnittlicher Umsatz pro Rechnung',
                data: [{$data.rechnungen.belegDurchschnittsbetrag}]
            }]
        });
		/* Chart Angebot */
        $('#chart_angebote').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Umsatz',
                x: -20 //center
            },
            subtitle: {
                text: 'Angebote',
                x: -20
            },
            xAxis: {
                categories: [{$data.angebote.labels}]
            },
            yAxis: {
                title: {
                    text: 'Umsatz (EUR)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '€',
                formatter: function(){
                	var s = '<b>' + this.x + '</b><br />Angebote in diesem Monat: <b>' + num_angebote[this.points[0].point.x] + '</b><br />';
                	s += 'Umsatz: <b>' + formatNum(this.points[0].point.y) + '€</b><br />';
                	s += 'Umsatz pro Angebot: <b>' + formatNum(this.points[1].point.y) + '€</b><br />';
                	return s;
                },
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'center',
                verticalAlign: 'top',
                x: 200,
                y: 0,
                borderWidth: 2
            },
            series: [{
                name: 'Umsatz',
                data: [{$data.angebote.monatsumsatz}]
            },{
                name: 'Durchschnittlicher Umsatz pro Angebot',
                data: [{$data.angebote.belegDurchschnittsbetrag}]
            }]
        });
    });
</script>
</div>