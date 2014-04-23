var windows;
var alerts;

var init = function() {		
	windows = $('.windows');
	windows.sortable({
		cancel : '.content'
	});
	alerts = $('.alerts');
}
var error = function(message) {		
	var alert = $('<div class="alert alert-danger">'+message+'</div>');		
	alerts.html(alert);
	setTimeout(function(){
		cleanErrors();
	}, 5000);
}
var cleanErrors = function() {
	alerts.html('');
}
	
var processData = function(window, data) {		
	currentData = data;
	var table = createTable(currentData);
	window.putWindowData(table);
	ndx = crossfilter(currentData);
	window.data('currentData', currentData);
	window.data('ndx', ndx);
	loadedDataWindows++;
	//---//
	
	
	/*var gainOrLoss = ndx.dimension(function (d) {
	    return d.date;
	});
	var gainOrLossGroup = gainOrLoss.group();
	var gainOrLossChart = dc.pieChart("#test");
	gainOrLossChart
    .width(180) // (optional) define chart width, :default = 200
    .height(180) // (optional) define chart height, :default = 200
    .radius(80) // define pie radius
    .dimension(gainOrLoss) // set dimension
    .group(gainOrLossGroup).renderLabel(true).render(); // set group   */ 	
}

$.fn.putWindowData = function(data) {
	var content = this.find('.content');
	var source = this.find('.source-data');
	source.html('');
	source.prepend(content.children());
	content.html(data);
	content.prepend(getEdit());
}

$.fn.editSourceData = function() {
	var content = this.find('.content');
	var source = this.find('.source-data');
	content.html('');
	content.prepend(source.children());
}

var createTable = function(data) {
	var table= $('<table class="table table-bordered"></table>');
	var head = createTableHead(data[0]);
	var body = createTableBody(data);
	table.append(head);
	table.append(body);
	var tableContainer = $('<div class="table-responsive"></div>');
	tableContainer.html(table);
	return tableContainer;
}

var createTableHead = function(row) {
	var head = $('<thead></thead>');
	for(var title in row) {
		head.append('<th>'+title+'</th>');
	}
	return head;
}
var createTableBody = function(data) {
	var body = $('<tbody></tbody>');
	for(var i in data) {
		var tr = $('<tr></tr>');
		for(var col in data[i]) {
			tr.append('<td>'+data[i][col]+'</td>');
		}
		body.append(tr);
	}
	return body;
}

var createWindow = function(data) {
	var window = $(data);
	return window;
}

$.fn.windowEvents = function() {
	$(this).find('.resize').resizable({
		minWidth: 300,
		minHeight: 300
	});
}

$.fn.windowDataEvents = function() {
	var window = $(this);	
	window.find('form').ajaxForm({
		dataType: 'json',
		success: function(data){				
			if (data['error'] !== undefined) {
				error(data['error']);					
			} else {
				processData(window, data);
			}
		}
	});	
}

$.fn.windowGraphEvents = function() {
	var window = $(this);
	window.find('.new-graph button').click(function(){
		processGraph($(this).closest('.window'));
	});
	window.on("resizestop", function(event, ui) {
		var graph = $(this).data('graph');		
		if (graph !== undefined && ($(this).find('.content form').length == 0)) {
			var width = window.find('.content').width() - 10;
			var height = window.find('.content').height() - 10;		
			graph.width(width).height(height);
			if (graph.radius !== undefined) {
				var radius = Math.min(width, height) / 2;
				graph.radius(radius);				
			}			
			graph.render();			
		}
	});	
}

var events = function() {		
	$('#addDataButton').click(function(){
		//get data window
		var url = $(this).data('url');
		$.ajax({
			url: url,
			success: function(data) {
				var window = createWindow(data);
				window.windowEvents();
				window.windowDataEvents();
				window.find('.content').prepend(getEdit());
				windows.append(window);
			}
		});
	});		
	$.contextMenu({
	    selector: '.window .content .edit', 
	    trigger: 'left',
	    items: {
	        "add": {
	        	name: "График",
	        	icon: "add",
	        	items: {
					'linear' : {
						name: "Линейный",
						callback: function(key, options) {
							var window = $(this).closest('.window');
							createNewGraph(window, key);
						}							
					},
					'pie' : {
						name: "Круговой",
						callback: function(key, options) {
							var window = $(this).closest('.window');
							createNewGraph(window, key);
						}						
					},
					'row' : {
						name: "Линейчатый",
						callback: function(key, options) {
							var window = $(this).closest('.window');
							createNewGraph(window, key);
						}						
					},
					'bar' : {
						name: "Столбиковый",
						callback: function(key, options) {
							var window = $(this).closest('.window');
							createNewGraph(window, key);
						}						
					}/*,
					'bubble' : {
						name: "Пузырьковый",
						callback: function(key, options) {
							var window = $(this).closest('.window');
							createNewGraph(window, key);
						}						
					}*/
	        	},
	        	disabled: function(key, opt) {
	        		var window = $(this).closest('.window');
	        		if (window.find('.content form').length > 0) {
						return true;
	        		}
	        		if (window.data('parent_id') !== undefined) {
						return true;
					}
					return false;
	        	}
	        },
	        "edit": {
	            name: "Редактировать",
	            icon: "edit",
	            callback: function(key, options) {
	            	var window = $(this).closest('.window');
	            	window.editSourceData();
				},
				disabled: function(key, options) {
					var window = $(this).closest('.window');
					if (window.find('.content form').length > 0) {
						return true;
	        		}
					var parentId = window.data('parent_id');						
					if (parentId !== undefined) {
						var parentWindow = $('#'+parentId);
						if (parentWindow.length == 0) {
							return true;
						}
					}
					return false;
				}
	        },
	        "sep1": "---------",
	        "delete": {
	            name: "Удалить",
	            icon: "delete",
	            callback: function(key, options) {
	            	var window = $(this).closest('.window');
	            	window.remove();
				}
	        },
	    }
	});		
}

var getEdit = function() {
	var edit = $('<a href="javascript:void(0);" class="edit">Редактировать</a>');
	return edit;
}	

$(document).ready(function(){
	init();
	events();
});