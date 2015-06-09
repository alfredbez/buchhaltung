(function($) {

    "use strict";

    var date = new Date();

	var year = date.getFullYear();

	var month = date.getMonth();
	month++;
	month = (month.toString().length > 1) ? month : '0' + month;

	var day = date.getDate();
	day = (day.toString().length > 1) ? day:'0'+day;

	var today = year + '-' + month + '-' + day;

	var options = {
		events_url: 'php/events.json.php',
		view: 'month',
        day: today,
		first_day: 1,
		onAfterEventsLoad: function(events) {
			if(!events) {
				return;
			}
			var list = $('#eventlist');
			list.html('');

			$.each(events, function(key, val) {
				$(document.createElement('li'))
					.html('<a href="' + val.url + '">' + val.title + '</a>')
					.appendTo(list);
			});
		},
		onAfterViewLoad: function(view) {
			$('.page-header h3').text(this.title());
			$('.btn-group button').removeClass('active');
			$('button[data-calendar-view="' + view + '"]').addClass('active');
		},
		classes: {
			months: {
				general: 'label'
			}
		}
	};

	var calendar = $('#calendar').calendar(options);

	$('.btn-group button[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});

	$('.btn-group button[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});

    $('#first_day').change(function(){
        calendar.set_options({first_day: $(this).val()});
        calendar.view();
    });
}(jQuery));