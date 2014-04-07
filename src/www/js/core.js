(function($){
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
	
	var parseDate = d3.time.format("%m/%d/%Y").parse;
	
	var createNewGraph = function(dataWindow, type) {
		$.ajax({
			url : 'graph/form',
			data : {type : type},
			success : function(data) {
				var window = windowEvents(data);
				window.data('parentId', dataWindow.attr('id'));
				window = windowGraphEvents(window);
				windows.append(window);
			}
		});
	}
	
	var processGraph = function(window) {
		var parentId = window.data('parentId');		
		var parentWindow = $('#'+parentId);
		var content = window.find('.content');
		var params = new Array();
		content.find('input, select').each(function(){			
			params[$(this).attr('name')] = $(this).attr('value');
		});
		window.putWindowData('');
		var currentData = parentWindow.data('currentData');
		var ndx = parentWindow.data('ndx');		
		if (params['type'] == 'linear') {
			var x = params['x'];
			var y = params['y'];
			var x_type = params['x_type'];
			var y_type = params['y_type'];
			var aggregate = params['aggregation'];
			
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
        	var hits = dimension.group();
        	switch (aggregate) {
				case 'sum' :
					hits = hits.reduceSum(function(d) {return d[y]});
					break
				case 'count' :
					hits = hits.reduceCount();
					break
				default :
					hits = hits.reduceSum(function(d) {return d[y]});
					break
        	}        	
			var minX = dimension.bottom(1)[0][x];	        
			var maxX = dimension.top(1)[0][x];
			
			var xDomain = d3.scale.linear().domain([minX, maxX]);
			if (x_type == 'date') {
				xDomain = d3.time.scale().domain([minX,maxX]);
			}			
			var hitsArr = hits.all();
			var hitsCount = hits.size();
			var hitsMax = hits.top(1);
			var hitsMin = hits.top(hitsCount);
        	var maxY = hitsMax[0].value;        	
        	var minY = hitsMin[hitsCount-1].value;
        	console.log(minY);
        	console.log(maxY);
        	if (y_type == 'numeric') {
        		if (minY < 0) {
					minY = minY * 1.05
        		} else {
					minY = minY * 0.95;
				}
				if (maxY < 0) {
					maxY = maxY * 0.95;
				} else {
					maxY = maxY * 1.05;
				}
        	}
        	console.log(minY);
        	console.log(maxY);
        	var yDomain = d3.scale.linear().domain([minY, maxY]);
			if (y_type == 'date') {
				yDomain = d3.time.scale().domain([minY,maxY]);
			}			
	        var hitslineChart  = dc.lineChart('#'+content.attr('id')); 
	        hitslineChart
			.width(290).height(290)
			.dimension(dimension)
			.group(hits)
			.x(xDomain).y(yDomain).render();
			window.data('graph', hitslineChart);
		}
		parentWindow.data('currentData', currentData);
		parentWindow.data('ndx', ndx);
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
	
	var windowEvents = function(data) {
		var window = $(data);		
		window.find('.resize').resizable({
			minWidth: 300,
			minHeight: 300
		});
		return window;
	}
	
	var windowDataEvents = function(window) {
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
		return window;
	}
	
	var windowGraphEvents = function(window) {
		window.find('.new-graph button').click(function(){
			processGraph($(this).closest('.window'));
		});
		window.on("resizestop", function(event, ui) {
			var graph = $(this).data('graph');
			if (graph !== undefined) {
				var width = window.find('.content').width() - 10;
				var height = window.find('.content').height() - 10;
				graph.width(width).height(height).render();
			}
		});
		return window;
	}
	
	var events = function() {		
		$('#addDataButton').click(function(){
			//get data window
			var url = $(this).data('url');
			$.ajax({
				url: url,
				success: function(data) {
					var window = windowEvents(data);
					window = windowDataEvents(window);
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
						}
	        		},
	        		disabled: function(key, opt) {
	        			var window = $(this).closest('.window');
	        			if (window.data('parentId') !== undefined) {
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
						var parentId = window.data('parentId');						
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
	            /*"copy": {name: "Copy", icon: "copy"},
	            "paste": {name: "Paste", icon: "paste"},	            
	            "quit": {name: "Quit", icon: "quit"}*/
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
})(jQuery);