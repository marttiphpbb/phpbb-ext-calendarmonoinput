;(function($, window, document) {
	$('span.calendar-topicrow').each(function(){
		$(this).insertAfter($(this).parent().find('a.topictitle').eq(0));
		$(this).show();
	});
})(jQuery, window, document);
