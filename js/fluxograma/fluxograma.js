$(document).ready(function() {

	// notice the 'curviness' argument to this Bezier curve.  the curves on this page are far smoother
	// than the curves on the first demo, which use the default curviness value.
	jsPlumb.DEFAULT_CONNECTOR = new jsPlumb.Connectors.Bezier(100);
	jsPlumb.DEFAULT_DRAG_OPTIONS = { cursor: 'pointer', zIndex:2000 };
	jsPlumb.DEFAULT_PAINT_STYLE = { strokeStyle:'black' };
	jsPlumb.DEFAULT_ENDPOINT_STYLE = { radius:4 };
        jsPlumb.DEFAULT_DRAG_OPTIONS = { cursor: 'crosshair' };

$("#CET635").plumb({target:'CET641'});
$("#CET641").plumb({target:'CET078'});
$("#CET641").plumb({target:'CET077'});
$("#CET078").plumb({target:'CET080'});
$("#CET078").plumb({target:'CET079'});
$("#CET077").plumb({target:'CET082'});
$("#CET079").plumb({target:'CET090'});
$("#CET082").plumb({target:'CET090'});
$("#CET079").plumb({target:'CET096'});
$("#CET090").plumb({target:'CET091'});
$("#CET079").plumb({target:'CET085'});
$("#CET087").plumb({target:'CET085'});
$("#CET085").plumb({target:'CET095'});
$("#CET090").plumb({target:'CET095'});
$("#CET096").plumb({target:'CET095'});
$("#CET085").plumb({target:'CET102'});
$("#CET085").plumb({target:'CET092'});
$("#CET095").plumb({target:'CET097'});
$("#CET633").plumb({target:'CET637'});
$("#CET634").plumb({target:'CET637'});

});
