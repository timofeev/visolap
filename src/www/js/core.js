(function($){
	var windows;
	var gWindowIds = 0;
	var alerts;
	var currentData;
	var ndx;
	var all;
	
	var init = function() {
		windows = $('.windows');
		alerts = $('.alerts');
		$('#source form').ajaxForm({
			dataType: 'json',
			success: function(data){				
				if (data['error'] !== undefined) {
					error(data['error']);					
				} else {
					processData(data);
				}
			}
		});		
	}
	var error = function(message) {		
		var alert = $('<div class="alert alert-danger">'+message+'</div>');		
		alerts.html(alert);
	}
	var cleanErrors = function() {
		alerts.html('');
	}
	
	var dataLoadedEvent = function() {
		$('#addButton').show();
		windows.find('.window.graph').remove();
	}
		
	var processData = function(data) {
		currentData = data;
		cleanErrors();
		var table = createTable(currentData);
		if ($('#data').length == 0) {
			var window = createWindow(table, 'data');
			windows.append(window);
		} else {
			var window = $('#data');
			window.html(table);
		}
		dataLoadedEvent();		
		//---//
		ndx = crossfilter(currentData);		
		
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
	
	var parseDate = d3.time.format("%m/%d/%Y").parse;
	
	var createNewGraph = function(type) {
		$.ajax({
			url : 'site/form',
			data : {type : type},
			success : function(data) {				
				gWindowIds++;
				var window = createWindow(data, 'graph'+gWindowIds, 'graph');
				window.find('.new-graph button').click(function(){
					processGraph($(this).closest('.window'));
				});
				windows.append(window);
			}
		});
	}
	
	var processGraph = function(window) {
		var params = new Array();
		window.find('input, select').each(function(){			
			params[$(this).attr('name')] = $(this).attr('value');
		});
		window.html('');
		if (params['type'] == 'linear') {
			var x = params['x'];
			var y = params['y'];
			var x_type = params['x_type'];
			var y_type = params['y_type'];
			if (x_type == 'date') {
				currentData.forEach(function(d) {
					if (typeof d[x] != 'object') {
						d[x] = parseDate(d[x]);
					}
				});
			}
			var dimension = ndx.dimension(function(d) {
				if (x_type == 'date') {
					return d[x];
				}
				if (x_type == 'numeric') {
					return parseFloat(d[x]);
				}
        		return parseInt(d[x])
        	});
			var hits = dimension.group().reduceSum(function(d) {return d[y]}); 
			var min = dimension.bottom(1)[0][x];	        
			var max = dimension.top(1)[0][x];
			
			var domain = d3.scale.linear().domain([min, max]);
			if (x_type == 'date') {
				domain = d3.time.scale().domain([min,max]);
			}
	        var hitslineChart  = dc.lineChart('#'+window.attr('id')); 
	        hitslineChart
			.width(500).height(200)
			.dimension(dimension)
			.group(hits)
			.x(domain).render();
					
		}
	}
	
	var createWindow = function(content, id, classes) {
		if (classes !== undefined) {
			classes = 'window '+classes;
		} else {
			classes = 'window';
		}
		var window = $('<div class="'+classes+'"></div>');
		if (id !== undefined) {
			window.attr('id', id);
		}
		window.html(content);
		return window;
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
	
	var events = function() {
		$('#addButton li').click(function(){
			createNewGraph($(this).data('type'));
		});		
	}
	
	$(document).ready(function(){
		init();
		events();
	});
})(jQuery);