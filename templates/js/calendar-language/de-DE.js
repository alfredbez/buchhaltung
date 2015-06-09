var language = {
	error_noview: 'Kalender: View {0} not found',
	error_dateformat: 'Kalender: Wrong date format {0}',
	error_loadurl: 'Kalender: Events load URL is not set',
	error_where: 'Kalender: Wrong navigation direction {0}. Can be only "next" or "prev" or "today"',

	title_year: 'Jahr {0}',
	title_month: '{0} {1}',
	title_week: '{0}. Woche im Jahr {1}',
	title_day: '{0}, {1}. {2} {3}',

	week:'Woche',

	m0: 'Januar',
	m1: 'Februar',
	m2: 'März',
	m3: 'April',
	m4: 'Mai',
	m5: 'Juni',
	m6: 'Juli',
	m7: 'August',
	m8: 'September',
	m9: 'Oktober',
	m10: 'November',
	m11: 'Dezember',

    ms0: 'Jan',
    ms1: 'Feb',
    ms2: 'Mär',
    ms3: 'Apr',
    ms4: 'Mai',
    ms5: 'Jun',
    ms6: 'Jul',
    ms7: 'Aug',
    ms8: 'Sep',
    ms9: 'Okt',
    ms10: 'Nov',
    ms11: 'Dez',

	d0: 'Sonntag',
	d1: 'Montag',
	d2: 'Dienstag',
	d3: 'Mittwoch',
	d4: 'Donnerstag',
	d5: 'Freitag',
	d6: 'Samstag'
};

if(!String.prototype.format) {
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined' ? args[number] : match;
		});
	};
}