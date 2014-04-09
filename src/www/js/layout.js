var initialDataWindows = 0;
var loadedDataWindows = 0;

var saveLayout = function(url, name) {
	if (checkEditing()) {
		error('Завершите редактирование перед сохранением');
		return;
	}
	var layoutName = prompt('Введите имя для сохранения', name);	
	if (!layoutName) {
		return;
	}
	if (layoutName.length == 0) {
		error('Имя не может быть пустым');
		return;
	}	
	var windows = new Array();
	$('.window').each(function(){
		var windowData = {};
		windowData['id'] = $(this).attr('id');
		windowData['content'] = $(this).find('.source-data').htmlize({innerHTML: true});		
		windowData['isData'] = $(this).hasClass('data-window') ? 1 : 0;
		if ($(this).data('parent_id') !== undefined) {
			windowData['parent_id'] = $(this).data('parent_id');
		}
		windows.push(windowData);			
	});	
	var page = $.toJSON(windows);	
	url = url+'/uid/'+layoutName;
	$.ajax({
		url: url,
		type: 'POST',
		data: {WikiPage: windows},
		success: function(data) {
			if (data !== undefined && data.length > 0) {
				window.location = data;
			}
		}
	});
}

var layoutEvents = function() {
	$('#saveLayoutButton').click(function(){			
		saveLayout($(this).data('url'), $(this).data('name'));			
	});
}

var checkEditing = function() {
	var found = false;
	$('.window').each(function(){
		if ($(this).find('.content form').length != 0) {
			found = true;
		}
	});
	return found;	
}

var loadLayout = function() {
	if ($('.window').length > 0) {
		startLoad();		
		$('.window').each(function(){
			$(this).windowEvents();
			if ($(this).hasClass('data-window')) {
				initialDataWindows++;			
				$(this).windowDataEvents();
				$(this).find('form').submit();
			}		
		});	
		var loadInterval = setInterval(function(){
			if (initialDataWindows == loadedDataWindows) {
				clearInterval(loadInterval);			
				$('.window').each(function(){
					if (!$(this).hasClass('data-window')) {
						$(this).windowGraphEvents();					
						$(this).find('button').trigger('click');
					}
				});
				endLoad();
			}
		}, 500);
	}
}

var startLoad = function() {
	$('html').css('overflow', 'hidden');
	$('#loading').show();
}
var endLoad = function() {
	$('#loading').hide();
	$('html').css('overflow', 'auto');
}

$(document).ready(function(){
	layoutEvents();
	loadLayout();
});