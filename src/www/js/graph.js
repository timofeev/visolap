var parseDate = d3.time.format("%m/%d/%Y").parse;
var format = d3.time.format("%Y-%m-%d");

var createNewGraph = function(dataWindow, type) {
	$.ajax({
		url : '/graph/form',
		data : {type : type},
		success : function(data) {
			var window = createWindow(data);
			window.windowEvents();
			window.data('parent_id', dataWindow.attr('id'));
			window.windowGraphEvents();
			window.find('.content').prepend(getEdit());			
			windows.append(window);
		}
	});
}

var processGraph = function(window) {
	var parentId = window.data('parent_id');		
	var parentWindow = $('#'+parentId);
	var content = window.find('.content');	
	var params = content.find('form').serializeObject();	
	window.putWindowData('');
	var currentData = parentWindow.data('currentData');
	var ndx = parentWindow.data('ndx');
	
	var x = params['x'];
	var x_type = params['x_type'];
	var y = params['y'];
	
	currentData.forEach(function(d) {
		if (x_type !== undefined) {
			if (x_type == 'date') {
				d.xxx = parseDate(d[x]);
			} else {
				if (x_type == 'numeric') {
					d.xxx = parseFloat(d[x]);
				} else {
					d.xxx = d[x];
				}
			}
		} else {
			d.xxx = d[x];
		}
		if (y !== undefined) {
			d.yyy = parseFloat(d[y]);
		}
	});
			
	if (params['type'] == 'linear') {
		var y_aggregation = params['y_aggregation']
		var dimension = ndx.dimension(function(d) {
        	return d.xxx;
        });
        var minX = dimension.bottom(1)[0].xxx;	        
		var maxX = dimension.top(1)[0].xxx;
		
		var hits = dimension.group();
		hits = hits.reduce(function(p, v) {
			++p.y_count;
			p.y_sum += v.yyy;
			p.y_average = p.y_count ? p.y_sum / p.y_count : 0;
			return p;
		}, function(p, v) {
			--p.y_count;
			p.y_sum -= v.yyy;
			p.y_average = p.y_count ? p.y_sum / p.y_count : 0;
			return p;
		}, function() {
			return {y_count : 0, y_sum : 0, y_average : 0};
		});	
					
		var hitsArr = hits.all();
		var maxY = -9007199254740992;
		var minY = 9007199254740992;
		
		for(var i in hitsArr) {
			switch (y_aggregation) {
				case 'average' :				
					maxY = Math.max(maxY, hitsArr[i].value.y_average);
	    			minY = Math.min(minY, hitsArr[i].value.y_average);
					break
				case 'sum' :
					maxY = Math.max(maxY, hitsArr[i].value.y_sum);
	    			minY = Math.min(minY, hitsArr[i].value.y_sum);
					break
				default :
					maxY = Math.max(maxY, hitsArr[i].value.y_count);
	    			minY = Math.min(minY, hitsArr[i].value.y_count);
					break
			}
		}
		
		console.log(minY, minX);
                
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
        var xDomain = d3.scale.linear().domain([minX, maxX]);
		if (x_type == 'date') {
			xDomain = d3.time.scale().domain([minX,maxX]);
		}        	
        var yDomain = d3.scale.linear().domain([minY, maxY]);
		var width = window.find('.resize').width() - 10;
		var height = window.find('.resize').height() - 10;
	    var hitslineChart  = dc.lineChart('#'+content.attr('id')); 
	    hitslineChart
		.width(width).height(height)
		.dimension(dimension).group(hits).valueAccessor(function(p) {
			switch (y_aggregation) {
				case 'average' :
					return p.value.y_average;
					break
				case 'sum' :
					return p.value.y_sum;
					break
				default :
					return p.value.y_count;
					break
		    }
		});
		hitslineChart.margins().left = 41;		
		hitslineChart.x(xDomain).y(yDomain).renderHorizontalGridLines(true).render();
		window.data('graph', hitslineChart);
	}
	if (params['type'] == 'pie') {
		var dimension = ndx.dimension(function (d) {
	        return d.xxx;
	    });
	    var group = dimension.group();
	    var width = window.find('.resize').width() - 10;
		var height = window.find('.resize').height() - 10;
	    var pieChart = dc.pieChart('#'+content.attr('id'));
        pieChart.width(width).height(height).dimension(dimension).group(group).
        label(function(d){        	
        	if (x_type == 'date') {
        		return format(d.data.key);
			} else {
				return d.data.key;
			}
        }).render();
        window.data('graph', pieChart);
	}
	if (params['type'] == 'row') {
		var dimension = ndx.dimension(function (d) {
	        return d.xxx;
	    });
	    var group = dimension.group();
	    var width = window.find('.resize').width() - 10;
		var height = window.find('.resize').height() - 10;
	    var rowChart = dc.rowChart('#'+content.attr('id'));
        rowChart.width(width).height(height).dimension(dimension).group(group).
        label(function(d){        	
        	if (x_type == 'date') {
        		return format(d.key);
			} else {
				return d.key;
			}
        }).render();
        window.data('graph', rowChart);
	}
	if (params['type'] == 'bar') {
		var y_aggregation = params['y_aggregation'];
		var dimension = ndx.dimension(function(d) {
        	return d.xxx;
        });
        var minX = dimension.bottom(1)[0].xxx;	        
		var maxX = dimension.top(1)[0].xxx;
		
		var group = dimension.group();
		group = group.reduce(function(p, v) {
			++p.y_count;
			p.y_sum += v.yyy;
			p.y_average = p.y_count ? p.y_sum / p.y_count : 0;
			return p;
		}, function(p, v) {
			--p.y_count;
			p.y_sum -= v.yyy;
			p.y_average = p.y_count ? p.y_sum / p.y_count : 0;
			return p;
		}, function() {
			return {y_count : 0, y_sum : 0, y_average : 0};
		});
		
		var hitsArr = group.all();
		var maxY = -9007199254740992;
		var minY = 9007199254740992;
		
		for(var i in hitsArr) {
			switch (y_aggregation) {
				case 'average' :				
					maxY = Math.max(maxY, hitsArr[i].value.y_average);
	    			minY = Math.min(minY, hitsArr[i].value.y_average);
					break
				case 'sum' :
					maxY = Math.max(maxY, hitsArr[i].value.y_sum);
	    			minY = Math.min(minY, hitsArr[i].value.y_sum);
					break
				default :
					maxY = Math.max(maxY, hitsArr[i].value.y_count);
	    			minY = Math.min(minY, hitsArr[i].value.y_count);
					break
			}
		}    
                
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
        var xDomain = d3.scale.linear().domain([minX, maxX]);
		if (x_type == 'date') {
			xDomain = d3.time.scale().domain([minX,maxX]);
		}        	
        var yDomain = d3.scale.linear().domain([minY, maxY]);
		var width = window.find('.resize').width() - 10;
		var height = window.find('.resize').height() - 10;
	    var barChart  = dc.barChart('#'+content.attr('id')); 
	    barChart
		.width(width).height(height)
		.dimension(dimension).group(group).valueAccessor(function(p) {
			switch (y_aggregation) {
				case 'average' :
					return p.value.y_average;
					break
				case 'sum' :
					return p.value.y_sum;
					break
				default :
					return p.value.y_count;
					break
		    }
		});
		barChart.margins().left = 41;		
		barChart.x(xDomain).y(yDomain).renderHorizontalGridLines(true).render();
		window.data('graph', barChart);
	}
	/*if (params['type'] == 'bubble') {
		var x = params['x'];
		var x_type = params['x_type'];
		var y = params['y'];
		var r = params['r'];
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
        	return d[x];
        });
        var group = dimension.group();
        
	}*/
	parentWindow.data('currentData', currentData);
	parentWindow.data('ndx', ndx);
}
