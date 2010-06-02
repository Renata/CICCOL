(function ($) {
$.fn.jqDynTabs = function( p ) {
p = $.extend({ 
	tabcontrol:"",
	tabcontent:"",
	side: "left",
	orientation: "top",
	imgpath: "",
	onClickTab : null
}, p || {});
this.CreateTab = function(tabName, closable, purl, content,params) {
	var thisg = this.get(0);
	var clicktab = this;
	var tabID = thisg.Name + 'Tab' + thisg.tabNumber;
	var panelID = thisg.Name + 'Panel' + thisg.tabNumber;
	if (closable == null || typeof closable != "boolean" ) { closable = true };
	var panel = document.createElement('div');
	panel.style.left = '0px';
	panel.style.top = '0px';
	panel.style.width = '100%';
	panel.style.height = '100%';
	panel.style.display = 'none';
	panel.tabNum = thisg.tabNumber;
	panel.id = panelID;
	if(thisg.panelContainer.insertAdjacentElement == null)
		thisg.panelContainer.appendChild(panel)
	else
		thisg.panelContainer.insertAdjacentElement("beforeEnd",panel); //Internet Explorer
	var sidenum=1;
	if (p.side == "right") sidenum =0
		else p.side = "left";
	if (purl) {
		if (purl.indexOf('?') == -1) purl +='?'; 
		$.ajax({
			url:purl,
			type:"GET",
			data: $.extend({_nd:new Date().getTime()},params),
			dataType:"html",
			complete: function(req,stat) {
				if(stat=="success")
					$(panel).html(req.responseText);
			},
			error: function( req, errortype) {
				// replace this if outside ACCPress
				if (typeof view_error == 'function')
					view_error(req.status+" : "+req.statusText+"<br/>"+req.responseText,errortype);
				else
					alert(req.status+" : "+req.statusText+" "+req.responseText,errortype)
				//clicktab.TabCloseEl(panel.tabNum);
				//alert(errortype);
			}
		});
	} else {
		if (content) $(panel).html(content);
	}
	var cell = thisg.tabContainer.insertCell(thisg.tabContainer.cells.length - parseInt(sidenum) ); 
	cell.id = tabID;
	cell.className = 'lowTab';
	cell.tabNum = thisg.tabNumber;
	cell.closable = closable;
	cell.tabName = tabName;
	$(cell).click( function(e) { 
		var el = (e.target || e.srcElement);
		clicktab.TabClickEl(el);
		if (p.onClickTab != null && typeof p.onClickTab == "function") { p.onClickTab( tabName, panel )}
	});
	
	if (closable ) {
		cell.innerHTML = '&nbsp;' + tabName+ '&nbsp;' + "<img src='"+p.imgpath+"tab_close.gif'/><span>&nbsp;</span>";
	} else {
		cell.innerHTML = '&nbsp;' + tabName+ '&nbsp;'+'&nbsp;';
	}
	
	this.TabClickEl(cell);
	if (closable )
		$("img",cell).click(function(){
			clicktab.TabCloseEl(cell);
		}).hover(function() {
				this.src= p.imgpath+"tab_close-on1.gif";
			},
			function(){
				this.src= p.imgpath+"tab_close.gif";	
		});
	thisg.tabNumber++;
	return panel;
}

this.TabClickEl = function (element) {
	var $t = this.get(0);
	if($t.currentHighTab == element) return;
	if($t.currentHighTab != null) {
		$t.currentHighTab.className = $t.lowTabStyle;
		if ($t.currentHighTab.closable) { $("img",$t.currentHighTab).hide();}
		//attr({src:p.imgpath+"tab_close.gif"}).unbind('click')}
	}

	if($t.currentHighPanel != null)
		$t.currentHighPanel.style.display = 'none';
	$t.currentHighPanel = null;
	$t.currentHighTab = null;

	if(element == null) return;
	$t.currentHighTab = element;
	$t.currentHighPanel = document.getElementById($t.Name + 'Panel' + $t.currentHighTab.tabNum);
	if($t.currentHighPanel == null)
	{
		$t.currentHighTab = null
		return;
	}
	$t.currentHighTab.className = $t.highTabStyle;
	$t.currentHighPanel.style.display = '';
	if (element.closable) {
		$("img",element).show();
		//attr({src:p.imgpath+"tab_close-on1.gif"})
	}
}

this.TabCloseEl = function(element) {
	if(element == null) return;
	var thisg = this.get(0);
	var tabLength = thisg.tabContainer.cells.length;
	if (  tabLength == 1) return; 
	var isNumber = false, isHighTab=false, elemIndex, i, panel;
	if ( typeof element === 'number' && element >= 0 ) {
		isNumber = true;
		for(i = 0; i<= tabLength-1; i++) {
			if(thisg.tabContainer.cells[i].cellIndex==element) {
				elemIndex = thisg.tabContainer.cells[i].cellIndex;
				if (p.side=="right") { 
					elemIndex++;
					element = thisg.tabContainer.cells[i+1].tabNum;
				} else { element = thisg.tabContainer.cells[i].tabNum; }
				break;
			}
		}
		panel = document.getElementById(thisg.Name + 'Panel' + element);
		if (panel.tabNum == thisg.currentHighTab.tabNum) isHighTab = true;
	}
	if(element == thisg.currentHighTab || isHighTab) {
		i = -1;
		if(tabLength > 2)
		{
			i = isHighTab ? elemIndex: element.cellIndex;
			if(i == tabLength- 2) i--;
			else i++;
			if(p.side=="right") { 
				if(i===0) i=2; 
				else
					if( i === tabLength) i=i-2;  
			}
			if(i >= 0)
				this.TabClickEl(thisg.tabContainer.cells[i]);
			else
				this.TabClickEl(null);
		}
	}

	if ( isNumber ) {
		thisg.tabContainer.deleteCell(elemIndex);
	}
	else {
		panel = document.getElementById(thisg.Name + 'Panel' + element.tabNum);
		$("*",element).unbind();
		$(element).remove();
		//thisg.tabContainer.deleteCell(element.cellIndex);
	}
	if(panel != null) {
		$("*",panel).unbind();
		$(panel).remove();
		//thisg.panelContainer.removeChild(panel);
		//panel = null;
	}
}

this.getTabIndex = function () {
	return this.get(0).tabContainer.cells.length - 1; 
}

this.tabExists = function (tabName) {
	for( var i=0;i<= this.get(0).tabContainer.cells.length-1;i++){
		if( this.get(0).tabContainer.cells[i].tabName == tabName) {
			this.TabClickEl(this.get(0).tabContainer.cells[i]);			
			return true;
		}
	}
	return false;
}

return this.each( function() {
	if (p.tabcontrol == null && p.tabcontrol.length ==0 && p.tabcontent == null && p.tabcontent.length == 0) {
	  return;
	} else {
		p.tabcontrol = p.tabcontrol.get(0);
		p.tabcontent = p.tabcontent.get(0);
	}
	this.Name = 'jqDynTabs'+ Math.round(100*Math.random());
	this.tabNumber = 0;
	this.currentHighPanel = null;
	this.currentHighTab = null;
	this.panelContainer = p.tabcontent; 
	this.tabContainer = p.tabcontrol; 
	this.lowTabStyle = 'lowTab';
	if(p.position=="bottom")
	  this.highTabStyle = 'highTabBottom';
	else
		this.highTabStyle = 'highTab';

});
};
$.fn.jqStaticTabs = function(p) {
	p = $.extend({ 
		tabcontent:"",
		onClickTab : null,
		selected : null
	}, p || {});
	return this.each(function() {
		if (this.stattabs) return;
		this.p = p;
		var $t = this;
		if(!this.p.selected) this.p.selected = 0;
		$("li>a",this).each(function(i){
			if($t.p.selected == i ) {
				$(this).addClass("selected");
				var selid = $(this).attr("rel");
				$("div.tabcell",$t.p.tabcontent).hide();
				$("#"+selid,$t.p.tabcontent).show();
				//return false;
			}
		});
		$("li>a",this).click(function() {
			if(!$(this).is(".selected")) {
				$("li>a",$(this).parents("ul:first")).removeClass("selected");
				var cntID = $(this).attr("rel");
				var cont = $("#"+cntID).parent("div");
				$("div.tabcell",cont).hide();
				$(this).addClass("selected");
				$("#"+cntID,cont).show();
			}
			return false;
		});
	});
};

})(jQuery);
