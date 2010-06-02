;(function ($) {
/*
 * jqGrid  3.3 - jQuery Grid
 * Copyright (c) 2008, Tony Tomov, tony@trirand.com
 * Dual licensed under the MIT and GPL licenses
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Date: 2008-10-14 rev 64
 */
$.fn.jqGrid = function( p ) {
	p = $.extend(true,{
	url: "",
	height: 150,
	page: 1,
	rowNum: 20,
	records: 0,
	pager: "",
	pgbuttons: true,
	pginput: true,
	colModel: [],
	rowList: [],
	colNames: [],
	sortorder: "asc",
	sortname: "",
	datatype: "xml",
	mtype: "GET",
	imgpath: "",
	sortascimg: "sort_asc.gif",
	sortdescimg: "sort_desc.gif",
	firstimg: "first.gif",
	previmg: "prev.gif",
	nextimg: "next.gif",
	lastimg: "last.gif",
	altRows: true,
	selarrrow: [],
	savedRow: [],
	shrinkToFit: true,
	xmlReader: {},
	jsonReader: {},
	subGrid: false,
	subGridModel :[],
	lastpage: 0,
	lastsort: 0,
	selrow: null,
	onSelectRow: null,
	onSortCol: null,
	ondblClickRow: null,
	onRightClickRow: null,
	onPaging: null,
	onSelectAll: null,
	loadComplete: null,
	gridComplete: null,
	loadError: null,
	loadBeforeSend: null,
	afterInsertRow: null,
	beforeRequest: null,
	onHeaderClick: null,
	viewrecords: false,
	loadonce: false,
	multiselect: false,
	multikey: false,
	editurl: null,
	search: false,
	searchdata: {},
	caption: "",
	hidegrid: true,
	hiddengrid: false,
	postData: {},
	userData: {},
	treeGrid : false,
	treeANode: 0,
	treedatatype: null,
	treeReader: {level_field: "level",
		left_field:"lft",
		right_field: "rgt",
		leaf_field: "isLeaf",
		expanded_field: "expanded"
	},
	tree_root_level: 0,
	ExpandColumn: null,
	sortclass: "grid_sort",
	resizeclass: "grid_resize",
	forceFit : false,
	gridstate : "visible",
	cellEdit: false,
	cellsubmit: "remote",
	nv:0,
	loadui: "enable",
	toolbar: [false,""]
	}, $.jgrid.defaults, p || {});
	var grid={         
		headers:[],
		cols:[],
		dragStart: function(i,x) {
			this.resizing = { idx: i, startX: x};
			this.hDiv.style.cursor = "e-resize";
		},
		dragMove: function(x) {
			if(this.resizing) {
				var diff = x-this.resizing.startX;
				var h = this.headers[this.resizing.idx];
				var newWidth = h.width + diff;
				var msie = $.browser.msie;
				if(newWidth > 25) {
					if(p.forceFit===true ){
						var hn = this.headers[this.resizing.idx+p.nv];
						var nWn = hn.width - diff;
						if(nWn >25) {
							h.el.style.width = newWidth+"px";
							h.newWidth = newWidth;
							this.cols[this.resizing.idx].style.width = newWidth+"px";
							hn.el.style.width = nWn +"px";
							hn.newWidth = nWn;
							this.cols[this.resizing.idx+p.nv].style.width = nWn+"px";
							this.newWidth = this.width;
						}
					} else {
						h.el.style.width = newWidth+"px";
						h.newWidth = newWidth;
						this.cols[this.resizing.idx].style.width = newWidth+"px";
						this.newWidth = this.width+diff;
						$('table:first',this.bDiv).css("width",this.newWidth +"px");
						$('table:first',this.hDiv).css("width",this.newWidth +"px");
						var scrLeft = this.bDiv.scrollLeft;
						this.hDiv.scrollLeft = this.bDiv.scrollLeft;
						if(msie) {
							if(scrLeft - this.hDiv.scrollLeft >= 5) {this.bDiv.scrollLeft = this.bDiv.scrollLeft - 17;}
						}
					}
				}
			}
		},
		dragEnd: function() {
			this.hDiv.style.cursor = "default";
			if(this.resizing) {
				var idx = this.resizing.idx;
				this.headers[idx].width = this.headers[idx].newWidth || this.headers[idx].width;
				this.cols[idx].style.width = this.headers[idx].newWidth || this.headers[idx].width;
				if(p.forceFit===true){
					this.headers[idx+p.nv].width = this.headers[idx+p.nv].newWidth || this.headers[idx+p.nv].width;
					this.cols[idx+p.nv].style.width = this.headers[idx+p.nv].newWidth || this.headers[idx+p.nv].width;
				}
				if(this.newWidth) {this.width = this.newWidth;}
				this.resizing = false;
			}
		},
		scrollGrid: function() {
			var scrollLeft = this.bDiv.scrollLeft;
			this.hDiv.scrollLeft = this.bDiv.scrollLeft;
			if(scrollLeft - this.hDiv.scrollLeft > 5) {this.bDiv.scrollLeft = this.bDiv.scrollLeft - 17;}
		}
	};
	$.fn.getGridParam = function(pName) {
		var $t = this[0];
		if (!$t.grid) {return;}
		if (!pName) { return $t.p; }
		else {return $t.p[pName] ? $t.p[pName] : null;}
	};
	$.fn.setGridParam = function (newParams){
		return this.each(function(){
			if (this.grid && typeof(newParams) === 'object') {$.extend(true,this.p,newParams);}
		});
	};
	$.fn.getDataIDs = function () {
		var ids=[];
		this.each(function(){
			$(this.rows).slice(1).each(function(i){
				ids[i]=this.id;
			});
		});
		return ids;
	};
	$.fn.setSortName = function (newsort) {
		return this.each(function(){
			var $t = this;
			for(var i=0;i< $t.p.colModel.length;i++){
				if($t.p.colModel[i].name===newsort || $t.p.colModel[i].index===newsort){
					$("tr th:eq("+$t.p.lastsort+") div img",$t.grid.hDiv).remove();
					$t.p.lastsort = i;
					$t.p.sortname=newsort;
					break;
				}
			}
		});
	};
	$.fn.setSelection = function(selection,sd) {
		return this.each(function(){
			var $t = this, stat,pt;
			if(selection===false) {pt = sd;}
			else { var ind = $($t).getInd($t.rows,selection); pt=$($t.rows[ind]);}
			selection = $(pt).attr("id");
			if (!pt.html()) {return;}
			if(!$t.p.multiselect) {
				if($(pt).attr("class") !== "subgrid") {
				if( $t.p.selrow ) {$("tr#"+$t.p.selrow+":first",$t.grid.bDiv).removeClass("selected");}
				$t.p.selrow = selection;
				$(pt).addClass("selected");
				if( $t.p.onSelectRow ) { $t.p.onSelectRow($t.p.selrow, true); }
				}
			} else {
				$t.p.selrow = selection;
				var ia = $.inArray($t.p.selrow,$t.p.selarrrow);
				if (  ia === -1 ){ 
					if($(pt).attr("class") !== "subgrid") { $(pt).addClass("selected");}
					stat = true;
					$("#jqg_"+$t.p.selrow,$t.rows).attr("checked",stat);
					$t.p.selarrrow.push($t.p.selrow);
					if( $t.p.onSelectRow ) { $t.p.onSelectRow($t.p.selrow, stat); }
				} else {
					if($(pt).attr("class") !== "subgrid") { $(pt).removeClass("selected");}
					stat = false;
					$("#jqg_"+$t.p.selrow,$t.rows).attr("checked",stat);
					$t.p.selarrrow.splice(ia,1);
					if( $t.p.onSelectRow ) { $t.p.onSelectRow($t.p.selrow, stat); }
					var tpsr = $t.p.selarrrow[0];
					$t.p.selrow = (tpsr=='undefined') ? null : tpsr;
				}
			}
		});
	};
	$.fn.resetSelection = function(){
		return this.each(function(){
			var t = this;
			if(!t.p.multiselect) {
				if(t.p.selrow) {
					$("tr#"+t.p.selrow+":first",t.grid.bDiv).removeClass("selected");
					t.p.selrow = null;
				}
			} else {
				$(t.p.selarrrow).each(function(i,n){
					var ind = $(t).getInd(t.rows,n);
					$(t.rows[ind]).removeClass("selected");
					$("#jqg_"+n,t.rows[ind]).attr("checked",false);
				});
				$("#cb_jqg",t.grid.hDiv).attr("checked",false);
				t.p.selarrrow = [];
			}
		});
	};
	$.fn.getRowData = function( rowid ) {
		var res = {};
		if (rowid){
			this.each(function(){
				var $t = this,nm,ind;
				ind = $($t).getInd($t.rows,rowid);
				if (!ind) {return res;}
				$('td:nth-child',$t.rows[ind]).each( function(i) {
					nm = $t.p.colModel[i].name; 
					if ( nm !== 'cb' && nm !== 'subgrid') {
						res[nm] = $(this).html().replace(/\&nbsp\;/ig,'');
					}
				});
			});
		}
		return res;
	};
	$.fn.delRowData = function(rowid) {
		var success = false, rowInd;
		if(rowid) {
			this.each(function() {
				var $t = this;
				rowInd = $($t).getInd($t.rows,rowid);
				if(!rowInd) {return success;}
				else {
					$($t.rows[rowInd]).remove();
					$t.p.records--;
					$t.updatepager();
					success=true;
				}
				if(rowInd == 1 && success && ($.browser.opera || $.browser.safari)) {
					$($t.rows[1]).each( function( k ) {
						$(this).css("width",$t.grid.headers[k].width+"px");
						$t.grid.cols[k] = this;
					});
				}
				if( $t.p.altRows === true && success) {
					$($t.rows).slice(1).each(function(i){
						if(i % 2 ==1) {$(this).addClass('alt');}
						else {$(this).removeClass('alt');}
					});
				}
			});
		}
		return success;
	};
	$.fn.setRowData = function(rowid, data) {
		var nm, success=false;
		this.each(function(){
			var t = this;
			if(!t.grid) {return false;}
			if( data ) {
				var ind = $(t).getInd(t.rows,rowid);
				if(!ind) {return success;}
				success=true;
				$(this.p.colModel).each(function(i){
					nm = this.name;
					if(data[nm] !== 'undefined') {
						$("td:eq("+i+")",t.rows[ind]).html(data[nm]);
						success = true;
					}
				});
			}
		});
		return success;
	};
	$.fn.addRowData = function(rowid,data,pos,src) {
		if(!pos) {pos = "last";}
		var success = false;
		var nm, row, td, gi=0, si=0,sind;
		if(data) {
			this.each(function() {
				var t = this;
				row =  document.createElement("tr");
				row.id = rowid || t.p.records+1;
				$(row).addClass("jqgrow");
				if(t.p.multiselect) {
					td = $('<td></td>');
					$(td[0],t.grid.bDiv).html("<input type='checkbox'"+" id='jqg_"+rowid+"' class='cbox'/>");
					row.appendChild(td[0]);
					gi = 1;
				}
				if(t.p.subGrid ) { try {$(t).addSubGrid(t.grid.bDiv,row,gi);} catch(e){} si=1;}
				for(var i = gi+si; i < this.p.colModel.length;i++){
					nm = this.p.colModel[i].name;
					td  = $('<td></td>');
					$(td[0]).html('&#160;');
					if(data[nm] !== 'undefined') {
						$(td[0]).html(data[nm] || '&#160;');
					}
					t.formatCol($(td[0],t.grid.bDiv),i);
					row.appendChild(td[0]);
				}
				switch (pos) {
					case 'last':
						$(t.rows[t.rows.length-1]).after(row);
						break;
					case 'first':
						$(t.rows[0]).after(row);
						break;
					case 'after':
						sind = $(t).getInd(t.rows,src);
						sind >= 0 ?	$(t.rows[sind]).after(row): "";
						break;
					case 'before':
						sind = $(t).getInd(t.rows,src);
						sind > 0 ?	$(t.rows[sind-1]).after(row): "";
						break;
				}
				t.p.records++;
				if($.browser.safari || $.browser.opera) {
					t.scrollLeft = t.scrollLeft;
					$("td",t.rows[1]).each( function( k ) {
						$(this).css("width",t.grid.headers[k].width+"px");
						t.grid.cols[k] = this;
					});
				}
				if( t.p.altRows === true ) {
					if (pos == "last") {
						if (t.rows.length % 2 == 1)  {$(row).addClass('alt');}
					} else {
						$(t.rows).slice(1).each(function(i){
							if(i % 2 ==1) {$(this).addClass('alt');}
							else {$(this).removeClass('alt');}
						});
					}
				}
				try {t.p.afterInsertRow(row.id,data); } catch(e){}
				t.updatepager();
				success = true;
			});
		}
		return success;
	};
	$.fn.hideCol = function(colname) {
		return this.each(function() {
			var $t = this,w=0, fndh=false;
			if (!$t.grid ) {return;}
			if( typeof colname == 'string') {colname=[colname];}
			$(this.p.colModel).each(function(i) {
				if ($.inArray(this.name,colname) != -1 && !this.hidden) {
					var w = parseInt($("tr th:eq("+i+")",$t.grid.hDiv).css("width"),10);
 					$("tr th:eq("+i+")",$t.grid.hDiv).css({display:"none"});
					$($t.rows).each(function(j){
						$("td:eq("+i+")",$t.rows[j]).css({display:"none"});
					});
					$t.grid.cols[i].style.width = 0;
					$t.grid.headers[i].width = 0;
					$t.grid.width -= w;
					this.hidden=true;
					fndh=true;
				}
			});
			if(fndh===true) {
				var gtw = Math.min($t.p._width,$t.grid.width);
				$("table:first",$t.grid.hDiv).width(gtw);
				$("table:first",$t.grid.bDiv).width(gtw);
				$($t.grid.hDiv).width(gtw);
				$($t.grid.bDiv).width(gtw);
				if($t.p.pager && $($t.p.pager).hasClass("scroll") ) {
					$($t.p.pager).width(gtw);
				}
				if($t.p.caption) {$($t.grid.cDiv).width(gtw);}
				if($t.p.toolbar[0]) {$($t.grid.uDiv).width(gtw);}
				$t.grid.hDiv.scrollLeft = $t.grid.bDiv.scrollLeft;
			}
		});
	};
	$.fn.showCol = function(colname) {
		return this.each(function() {
			var $t = this; var w = 0, fdns=false;
			if (!$t.grid ) {return;}
			if( typeof colname == 'string') {colname=[colname];}
			$($t.p.colModel).each(function(i) {
				if ($.inArray(this.name,colname) != -1 && this.hidden) {
					var w = parseInt($("tr th:eq("+i+")",$t.grid.hDiv).css("width"),10);
					$("tr th:eq("+i+")",$t.grid.hDiv).css("display","");
					$($t.rows).each(function(j){
						$("td:eq("+i+")",$t.rows[j]).css("display","").width(w);
					});
					this.hidden=false;
					$t.grid.cols[i].style.width = w;
					$t.grid.headers[i].width =  w;
					$t.grid.width += w;
					fdns=true;
				}
			});
			if(fdns===true) {
				var gtw = Math.min($t.p._width,$t.grid.width);
				var ofl = ($t.grid.width <= $t.p._width) ? "hidden" : "auto";
				$("table:first",$t.grid.hDiv).width(gtw);
				$("table:first",$t.grid.bDiv).width(gtw);
				$($t.grid.hDiv).width(gtw);
				$($t.grid.bDiv).width(gtw).css("overflow-x",ofl);
				if($t.p.pager && $($t.p.pager).hasClass("scroll") ) {
					$($t.p.pager).width(gtw);
				}
				if($t.p.caption) {$($t.grid.cDiv).width(gtw);}
				if($t.p.toolbar[0]) {$($t.grid.uDiv).width(gtw);}
				$t.grid.hDiv.scrollLeft = $t.grid.bDiv.scrollLeft;
			}
		});
	};
	$.fn.setGridWidth = function(nwidth, shrink) {
		return this.each(function(){
			var $t = this, chw=0,w,cw,ofl;
			if (!$t.grid ) {return;}
			if(typeof shrink != 'boolean') {shrink=true;}
			var testdata = getScale();
			if(shrink !== true) {testdata[0] = Math.min($t.p._width,$t.grid.width); testdata[2]=0;}
			else {testdata[2]= testdata[1]}
			$.each($t.p.colModel,function(i,v){
				if(!this.hidden && this.name != 'cb' && this.name!='subgrid') {
					cw = shrink !== true ? $("tr:first th:eq("+i+")",$t.grid.hDiv).css("width") : this.width;
					w = Math.round((IENum(nwidth)-IENum(testdata[2]))/IENum(testdata[0])*IENum(cw));
					chw += w;
					$("table thead tr:first th:eq("+i+")",$t.grid.hDiv).css("width",w+"px");
					$("table:first tbody tr:first td:eq("+i+")",$t.grid.bDiv).css("width",w+"px");
					$t.grid.cols[i].style.width = w;
					$t.grid.headers[i].width =  w;
				}
				if(this.name=='cb' || this.name == 'subgrid'){chw += IENum(this.width);}
			});
			if(chw + testdata[1] <= nwidth || $t.p.forceFit === true){ ofl = "hidden"; tw = nwidth;}
			else { ofl= "auto"; tw = chw + testdata[1];}
			$("table:first",$t.grid.hDiv).width(tw);
			$("table:first",$t.grid.bDiv).width(tw);
			$($t.grid.hDiv).width(nwidth);
			$($t.grid.bDiv).width(nwidth).css("overflow-x",ofl);
			if($t.p.pager && $($t.p.pager).hasClass("scroll") ) {
				$($t.p.pager).width(nwidth);
			}
			if($t.p.caption) {$($t.grid.cDiv).width(nwidth);}
			if($t.p.toolbar[0]) {$($t.grid.uDiv).width(nwidth);}
			$t.p._width = nwidth; $t.grid.width = tw;
			if($.browser.safari || $.browser.opera ) {
				$("table tbody tr:eq(1) td",$t.grid.bDiv).each( function( k ) {
					$(this).css("width",$t.grid.headers[k].width+"px");
					$t.grid.cols[k] = this;
				});
			}
			$t.grid.hDiv.scrollLeft = $t.grid.bDiv.scrollLeft;
			function IENum(val) {
				val = parseInt(val,10);
				return isNaN(val) ? 0 : val;
			}
			function getScale(){
				var testcell = $("table tr:first th:eq(1)", $t.grid.hDiv);
				var addpix = IENum($(testcell).css("padding-left")) +
					IENum($(testcell).css("padding-right"))+
					IENum($(testcell).css("border-left-width"))+
					IENum($(testcell).css("border-right-width"));
				var w =0,ap=0; 
				$.each($t.p.colModel,function(i,v){
					if(!this.hidden) {
						w += parseInt(this.width);
						ap += addpix;
					}
				});
				return [w,ap,0];
			}
		});
	};
	$.fn.setGridHeight = function (nh) {
		return this.each(function (){
			var ovfl, ovfl2, $t = this;
			if(!$t.grid) {return;}
			if($t.p.forceFit === true) { ovfl2='hidden'; } else {ovfl2=$($t.grid.bDiv).css("overflow-x");}
			ovfl = (isNaN(nh) && $.browser.mozilla && (nh.indexOf("%")!=-1 || nh=="auto")) ? "hidden" : "auto";
			$($t.grid.bDiv).css({height: nh+(isNaN(nh)?"":"px"),"overflow-y":ovfl,"overflow-x": ovfl2});
			$t.p.height = nh;
		});
	};
	$.fn.setCaption = function (newcap){
		return this.each(function(){
			this.p.caption=newcap;
			$("table:first th",this.grid.cDiv).text(newcap);
			$(this.grid.cDiv).show();
		});
	};
	$.fn.setLabel = function(colname, nData, prop ){
		return this.each(function(){
			var $t = this, pos=-1;
			if(!$t.grid) {return;}
			if(isNaN(colname)) {
				$($t.p.colModel).each(function(i){
					if (this.name == colname) {
						pos = i;return false;
					}
				});
			} else {pos = parseInt(colname,10);}
			if(pos>=0) {
				var thecol = $("table:first th:eq("+pos+")",$t.grid.hDiv);
				if (nData){
					$("div",thecol).html(nData);
				}
				if (prop) {
					if(typeof prop == 'string') {$(thecol).addClass(prop);} else {$(thecol).css(prop);}
				}
			}
		});
	};
	$.fn.setCell = function(rowid,colname,nData,prop) {
		return this.each(function(){
			var $t = this, pos =-1;
			if(!$t.grid) {return;}
			if(isNaN(colname)) {
				$($t.p.colModel).each(function(i){
					if (this.name == colname) {
						pos = i;return false;
					}
				});
			} else {pos = parseInt(colname,10);}
			if(pos>=0) {
				var ind = $($t).getInd($t.rows,rowid);
				if (ind){
					var tcell = $("td:eq("+pos+")",$t.rows[ind]);
					if(nData) {$(tcell).html(nData);}
					if (prop){
						if(typeof prop == 'string') {$(tcell).addClass(prop);} else {$(tcell).css(prop);}
					}
				}
			}
		});
	};
	$.fn.getCell = function(rowid,iCol) {
		var ret = false;
		this.each(function(){
			var $t=this;
			if(!$t.grid) {return;}
			if(rowid && iCol>=0) {
				var ind = $($t).getInd($t.rows,rowid);
				if(ind) {
					ret = $("td:eq("+iCol+")",$t.rows[ind]).html().replace(/\&nbsp\;/ig,'');
				}
			}
		});
		return ret;
	};
	$.fn.clearGridData = function() {
		return this.each(function(){
			var $t = this;
			if(!$t.grid) {return;}
			$("tbody tr:gt(0)", $t.grid.bDiv).remove();
			$t.p.selrow = null; $t.p.selarrrow= [];
			$t.p.records = 0;$t.p.page=0;$t.p.lastpage=0;
			$t.updatepager();
		});
	};
	$.fn.getInd = function(obj,rowid,rc){
		var ret =false;
		$(obj).each(function(i){
			if(this.id==rowid) {
				ret = rc===true ? this : i;
				return false;
			}
		});
		return ret;
	};
	return this.each( function() {
		if(this.grid) {return;}
		this.p = p ;
		if( this.p.colNames.length === 0 || this.p.colNames.length !== this.p.colModel.length ) {
			alert("Length of colNames <> colModel or 0!");
			return;
		}
		if(this.p.imgpath !== "" ) {this.p.imgpath += "/";}
		var ts = this;
		$("<div class='loadingui' id=lui_"+this.id+"/>").insertBefore(this);
		$(this).attr({cellSpacing:"0",cellPadding:"0",border:"0"});
		var onSelectRow = $.isFunction(this.p.onSelectRow) ? this.p.onSelectRow :false;
		var ondblClickRow = $.isFunction(this.p.ondblClickRow) ? this.p.ondblClickRow :false;
		var onSortCol = $.isFunction(this.p.onSortCol) ? this.p.onSortCol : false;
		var loadComplete = $.isFunction(this.p.loadComplete) ? this.p.loadComplete : false;
		var loadError = $.isFunction(this.p.loadError) ? this.p.loadError : false;
		var loadBeforeSend = $.isFunction(this.p.loadBeforeSend) ? this.p.loadBeforeSend : false;
		var onRightClickRow = $.isFunction(this.p.onRightClickRow) ? this.p.onRightClickRow : false;
		var afterInsRow = $.isFunction(this.p.afterInsertRow) ? this.p.afterInsertRow : false;
		var onHdCl = $.isFunction(this.p.onHeaderClick) ? this.p.onHeaderClick : false;
		var beReq = $.isFunction(this.p.beforeRequest) ? this.p.beforeRequest : false;
		var onSC = $.isFunction(this.p.onCellSelect) ? this.p.onCellSelect : false;
		var sortkeys = ["shiftKey","altKey","ctrlKey"];
		if ($.inArray(ts.p.multikey,sortkeys) == -1 ) {ts.p.multikey = false;}
		var IntNum = function(val,defval) {
			val = parseInt(val,10);
			if (isNaN(val)) { return (defval) ? defval : 0;}
			else {return val;}
		};
		var formatCol = function (elem, pos){
			var rowalign1 = ts.p.colModel[pos].align || "left";
			$(elem).css("text-align",rowalign1);
			if(ts.p.colModel[pos].hidden) {$(elem).css("display","none");}
		};
		var resizeFirstRow = function (t,er){
			$("tbody tr:eq("+er+") td",t).each( function( k ) {
				$(this).css("width",grid.headers[k].width+"px");
				grid.cols[k] = this;
			});
		};
		var addCell = function(t,row,cell,pos) {
			var td;
			td = document.createElement("td");
			$(td).html(cell);
			row.appendChild(td);
			formatCol($(td,t), pos);
		};
		var addMulti = function(t,row){
			var cbid,td;
			td = document.createElement("td");
			cbid = "jqg_"+row.id;
			$(td,t).html("<input type='checkbox'"+" id='"+cbid+"' class='cbox'/>");
			formatCol($(td,t), 0);
			row.appendChild(td);
		};
		var reader = function (datatype) {
			var field, f=[], j=0;
			for(var i =0; i<ts.p.colModel.length; i++){
				field = ts.p.colModel[i];
				if (field.name !== 'cb' && field.name !=='subgrid') {
					f[j] = (datatype=="xml") ? field.xmlmap || field.name : field.jsonmap || field.name;
					j++;
				}
			}
			return f;
		};
		var addXmlData = function addXmlData (xml,t) {
			if(xml) { var fpos = ts.p.treeANode; if(fpos===0) {$("tbody tr:gt(0)", t).remove();} } else { return; }
			var row,gi=0,si=0,cbid,idn, getId,f=[],rd =[],cn=(ts.p.altRows === true) ? 'alt':'';
			if(!ts.p.xmlReader.repeatitems) {f = reader("xml");}
			if( ts.p.keyIndex===false) {
				idn = ts.p.xmlReader.id;
				if( idn.indexOf("[") === -1 ) {
					getId = function( trow, k) {return $(idn,trow).text() || k;};
				}
				else {
					getId = function( trow, k) {return trow.getAttribute(idn.replace(/[\[\]]/g,"")) || k;};
				}
			} else {
				getId = function(trow) { return (f.length - 1 >= ts.p.keyIndex) ? $(f[ts.p.keyIndex],trow).text() : $(ts.p.xmlReader.cell+":eq("+ts.p.keyIndex+")",trow).text(); };
			}
			$(ts.p.xmlReader.page,xml).each(function() {ts.p.page = this.textContent  || this.text ; });
			$(ts.p.xmlReader.total,xml).each(function() {ts.p.lastpage = this.textContent  || this.text ; }  );
			$(ts.p.xmlReader.records,xml).each(function() {ts.p.records = this.textContent  || this.text ; }  );
			$(ts.p.xmlReader.userdata,xml).each(function() {ts.p.userData[this.getAttribute("name")]=this.textContent || this.text;});
			$(ts.p.xmlReader.root+" "+ts.p.xmlReader.row,xml).each( function( j ) {
				row = document.createElement("tr");
				row.id = getId(this,j+1);
				if(ts.p.multiselect) {
					addMulti(t,row);
					gi = 1;
				}
				if (ts.p.subGrid) {
					try {$(ts).addSubGrid(t,row,gi,this);} catch (e){}
					si= 1;
				}
				var v;
				if(ts.p.xmlReader.repeatitems===true){
					$(ts.p.xmlReader.cell,this).each( function (i) {
						v = this.textContent || this.text;
						addCell(t,row,v || '&#160;',i+gi+si);
						rd[ts.p.colModel[i+gi+si].name] = v;
					});
				} else {
					for(var i = 0; i < f.length;i++) {
						v = $(f[i],this).text();
						addCell(t, row, v || '&#160;', i+gi+si);
						rd[ts.p.colModel[i+gi+si].name] = v;
					}
				}
				if(j%2 == 1) {row.className = cn;} $(row).addClass("jqgrow");
				if( ts.p.treeGrid === true) {
					try {$(ts).setTreeNode(rd,row);} catch (e) {}
				}
				$(ts.rows[j+fpos]).after(row);
				if(afterInsRow) {ts.p.afterInsertRow(row.id,rd,this);}
				rd=[];
			});
			xml = null;
			if(isSafari || isOpera) {resizeFirstRow(t,1);}
		  	if(!ts.p.treeGrid) {ts.grid.bDiv.scrollTop = 0;}
			endReq();
			updatepager();
		};
		var addJSONData = function(data,t) {
			if(data) { var fpos = ts.p.treeANode; if(fpos===0) {$("tbody tr:gt(0)", t).remove();} }  else { return; }
			var row,f=[],cur,gi=0,si=0,drows,idn,rd=[],cn=(ts.p.altRows===true) ? 'alt':'';
			ts.p.page = data[ts.p.jsonReader.page];
			ts.p.lastpage= data[ts.p.jsonReader.total];
			ts.p.records= data[ts.p.jsonReader.records];
			ts.p.userData = data[ts.p.jsonReader.userdata] || {};
			if(!ts.p.jsonReader.repeatitems) {f = reader("json");}
			if( ts.p.keyIndex===false ) {
				idn = ts.p.jsonReader.id;
				if(f.length>0 && !isNaN(idn)) {idn=f[idn];}
			} else {
				idn = f.length>0 ? f[ts.p.keyIndex] : ts.p.keyIndex;
			}
			drows = data[ts.p.jsonReader.root];
			if (drows) {
			for (var i=0;i<drows.length;i++) {
				cur = drows[i];
				row = document.createElement("tr");
				row.id = cur[idn] || "";
				if(row.id === "") {
					if(f.length===0){
						if(ts.p.jsonReader.cell){
							var ccur = cur[ts.p.jsonReader.cell];
							row.id = ccur[idn] || i+1;
							ccur=null;
						} else {row.id=i+1;}
					} else {
						row.id=i+1;
					}
				}
				if(ts.p.multiselect){
					addMulti(t,row);
					gi = 1;
				}
				if (ts.p.subGrid) {
					try { $(ts).addSubGrid(t,row,gi,drows[i]);} catch (e){}
					si= 1;
				}
				if (ts.p.jsonReader.repeatitems === true) {
					if(ts.p.jsonReader.cell) {cur = cur[ts.p.jsonReader.cell];}
					for (var j=0;j<cur.length;j++) {
						addCell(t,row,cur[j] || '&#160;',j+gi+si);
						rd[ts.p.colModel[j+gi+si].name] = cur[j];
					}
				} else {
					for (var j=0;j<f.length;j++) {
						addCell(t,row,cur[f[j]] || '&#160;',j+gi+si);
						rd[ts.p.colModel[j+gi+si].name] = cur[f[j]];
					}
				}
				if(i%2 == 1) {row.className = cn;} $(row).addClass("jqgrow");
				if( ts.p.treeGrid === true) {
					try {$(ts).setTreeNode(rd,row);} catch (e) {}
				}
				$(ts.rows[i+fpos]).after(row);
				if(afterInsRow) {ts.p.afterInsertRow(row.id,rd,drows[i]);}
				rd=[];
			}
			}
			data = null;
			if(isSafari || isOpera) {resizeFirstRow(t,1);}
			if(!ts.p.treeGrid) {ts.grid.bDiv.scrollTop = 0;}
			endReq();
			updatepager();
		};
		var updatepager = function() {
			if(ts.p.pager) {
				var cp, last,imp = ts.p.imgpath;
				if (ts.p.loadonce) {
					cp = last = 1;
					ts.p.lastpage = ts.page =1;
					$(".selbox",ts.p.pager).attr("disabled",true);
				} else {
					cp = IntNum(ts.p.page);
					last = IntNum(ts.p.lastpage);
					$(".selbox",ts.p.pager).attr("disabled",false);
				}
				if(ts.p.pginput===true) {
					$('input.selbox',ts.p.pager).val(ts.p.page);
				}
				if (ts.p.viewrecords){
					$('#sp_1',ts.p.pager).html(ts.p.pgtext+"&#160;"+ts.p.lastpage );
					$('#sp_2',ts.p.pager).html(ts.p.records+"&#160;"+ts.p.recordtext+"&#160;");
				}
				if(ts.p.pgbuttons===true) {
					if(cp<=0) {cp = last = 1;}
					if(cp==1) {$("#first",ts.p.pager).attr({src:imp+"off-"+ts.p.firstimg,disabled:true});} else {$("#first",ts.p.pager).attr({src:imp+ts.p.firstimg,disabled:false});}
					if(cp==1) {$("#prev",ts.p.pager).attr({src:imp+"off-"+ts.p.previmg,disabled:true});} else {$("#prev",ts.p.pager).attr({src:imp+ts.p.previmg,disabled:false});}
					if(cp==last) {$("#next",ts.p.pager).attr({src:imp+"off-"+ts.p.nextimg,disabled:true});} else {$("#next",ts.p.pager).attr({src:imp+ts.p.nextimg,disabled:false});}
					if(cp==last) {$("#last",ts.p.pager).attr({src:imp+"off-"+ts.p.lastimg,disabled:true});} else {$("#last",ts.p.pager).attr({src:imp+ts.p.lastimg,disabled:false});}
				}
			}
			if($.isFunction(ts.p.gridComplete)) {ts.p.gridComplete();}
		};
		var populate = function () {
			if(!grid.hDiv.loading) {
				beginReq();
				var gdata = $.extend(ts.p.postData,{page: ts.p.page, rows: ts.p.rowNum, sidx: ts.p.sortname, sord:ts.p.sortorder, nd: (new Date().getTime()), _search:ts.p.search});
				if (ts.p.search ===true) {gdata =$.extend(gdata,ts.p.searchdata);}				
				if ($.isFunction(ts.p.datatype)) {ts.p.datatype(gdata);endReq();}
				switch(ts.p.datatype)
				{
				case "json":
					$.ajax({url:ts.p.url,type:ts.p.mtype,dataType:"json",data: gdata, complete:function(JSON,st) { if(st=="success") {addJSONData(eval("("+JSON.responseText+")"),ts.grid.bDiv); if(loadComplete) {loadComplete();}}}, error:function(xhr,st,err){if(loadError) {loadError(xhr,st,err);}endReq();}, beforeSend: function(xhr){if(loadBeforeSend) {loadBeforeSend(xhr);}}});
					if( ts.p.loadonce || ts.p.treeGrid) {ts.p.datatype = "local";}
				break;
				case "xml":
					$.ajax({url:ts.p.url,type:ts.p.mtype,dataType:"xml",data: gdata , complete:function(xml,st) {if(st=="success")	{addXmlData(xml.responseXML,ts.grid.bDiv); if(loadComplete) {loadComplete();}}}, error:function(xhr,st,err){if(loadError) {loadError(xhr,st,err);}endReq();}, beforeSend: function(xhr){if(loadBeforeSend) {loadBeforeSend(xhr);}}});
					if( ts.p.loadonce || ts.p.treeGrid) {ts.p.datatype = "local";}
				break;
				case "xmlstring":
					addXmlData(stringToDoc(ts.p.datastr),ts.grid.bDiv);
					ts.p.datastr = null;
					ts.p.datatype = "local";
					if(loadComplete) {loadComplete();}
				break;
				case "jsonstring":
					addJSONData(eval("("+ts.p.datastr+")"),ts.grid.bDiv);
					ts.p.datastr = null;
					ts.p.datatype = "local";
					if(loadComplete) {loadComplete();}
				break;
				case "local":
				case "clientSide":
					sortArrayData();
				break;
				}
			}
		};
		var beginReq = function() {
			if(beReq) {ts.p.beforeRequest();}
			grid.hDiv.loading = true;
			switch(ts.p.loadui) {
				case "disable":
					break;
				case "enable":
					$("div.loading",grid.hDiv).fadeIn("fast");
					break;
				case "block":
					$("div.loading",grid.hDiv).fadeIn("fast");
					$("#lui_"+ts.id).width($(grid.bDiv).width()).height(IntNum($(grid.bDiv).height())+IntNum(ts.p._height)).show();
					break;
			}
		};
		var endReq = function() {
			grid.hDiv.loading = false;
			switch(ts.p.loadui) {
				case "disable":
					break;
				case "enable":
					$("div.loading",grid.hDiv).fadeOut("fast");
					break;
				case "block":
					$("div.loading",grid.hDiv).fadeOut("fast");
					$("#lui_"+ts.id).hide();
					break;
			}
		};
		var stringToDoc =	function (xmlString) {
			var xmlDoc;
			try	{
				var parser = new DOMParser();
				xmlDoc = parser.parseFromString(xmlString,"text/xml");
			}
			catch(e) {
				xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
				xmlDoc.async=false;
				xmlDoc["loadXM"+"L"](xmlString);
			}
			return (xmlDoc && xmlDoc.documentElement && xmlDoc.documentElement.tagName != 'parsererror') ? xmlDoc : null;
		};
		var sortArrayData = function() {
			var stripNum = /[\$,%]/g;
			var col=0,st,findSortKey,newDir = (ts.p.sortorder == "asc") ? 1 :-1;
			$.each(ts.p.colModel,function(i,v){
				if(this.index == ts.p.sortname || this.name == ts.p.sortname){
					col = ts.p.lastsort= i;
					st = this.sorttype;
					return false;
				}
			});
			if (st == 'float') {
				findSortKey = function($cell) {
					var key = parseFloat($cell.text().replace(stripNum, ''));
					return isNaN(key) ? 0 : key;
				};
			} else if (st=='int') {
				findSortKey = function($cell) {
					return IntNum($cell.text().replace(stripNum, ''));
				};
			} else if(st == 'date') {
				findSortKey = function($cell) {
					var fd = ts.p.colModel[col].datefmt || "Y-m-d";
					return parseDate(fd,$cell.text()).getTime();
				};
			} else {
				findSortKey = function($cell) {
					return $cell.text().toUpperCase();
				};
			}
			var rows=[];
			$.each(ts.rows, function(index, row) {
				if (index > 0) {
					row.sortKey = findSortKey($(row).children('td').eq(col));
					rows[index-1] = this;
				}
			});
			if(ts.p.treeGrid) {
				$(ts).SortTree( newDir);
			} else {
				rows.sort(function(a, b) {
					if (a.sortKey < b.sortKey) {return -newDir;}
					if (a.sortKey > b.sortKey) {return newDir;}
					return 0;
				});
				$.each(rows, function(index, row) {
					$('tbody',ts.grid.bDiv).append(row);
					row.sortKey = null;
				});
			}
			if(isSafari || isOpera) {resizeFirstRow(ts.grid.bDiv,1);}
			if(ts.p.multiselect) {
				$("tbody tr:gt(0)", ts.grid.bDiv).removeClass("selected");
				$("[@id^=jqg_]",ts.rows).attr("checked",false);
				$("#cb_jqg",ts.grid.hDiv).attr("checked",false);
				ts.p.selarrrow = [];
			}
			if( ts.p.altRows === true ) {
				$("tbody tr:gt(0)", ts.grid.bDiv).removeClass("alt");
				$("tbody tr:odd", ts.grid.bDiv).addClass("alt");
			}
			ts.grid.bDiv.scrollTop = 0;
			endReq();
		};
		var parseDate = function(format, date) {
			var tsp = {m : 1, d : 1, y : 1970, h : 0, i : 0, s : 0};
			format = format.toLowerCase();
			date = date.split(/[\\\/:_;.\s-]/);
			format = format.split(/[\\\/:_;.\s-]/);
			for(var i=0;i<format.length;i++){
				tsp[format[i]] = IntNum(date[i],tsp[format[i]]);
			}
			tsp.m = parseInt(tsp.m,10)-1;
			var ty = tsp.y;
			if (ty >= 70 && ty <= 99) {tsp.y = 1900+tsp.y;}
			else if (ty >=0 && ty <=69) {tsp.y= 2000+tsp.y;}
			return new Date(tsp.y, tsp.m, tsp.d, tsp.h, tsp.i, tsp.s,0);
		};
		var setPager = function (){
			var inpt = "<img class='pgbuttons' src='"+ts.p.imgpath+"spacer.gif'";
			var pginp = (ts.p.pginput===true) ? "<input class='selbox' type='text' size='3' maxlength='5' value='0'/>" : "";
			if(ts.p.viewrecords===true) {pginp += "<span id='sp_1'></span>&#160;";}
			var pgl="", pgr="";
			if(ts.p.pgbuttons===true) {
				pgl = inpt+" id='first'/>&#160;&#160;"+inpt+" id='prev'/>&#160;";
				pgr = inpt+" id='next' />&#160;&#160;"+inpt+" id='last'/>";
			}
			$(ts.p.pager).append(pgl+pginp+pgr);
			if(ts.p.rowList.length >0){
				var str="<SELECT class='selbox'>";
				for(var i=0;i<ts.p.rowList.length;i++){
					str +="<OPTION value="+ts.p.rowList[i]+((ts.p.rowNum == ts.p.rowList[i])?' selected':'')+">"+ts.p.rowList[i];
				}
				str +="</SELECT>";
				$(ts.p.pager).append("&#160;"+str+"&#160;<span id='sp_2'></span>");
				$(ts.p.pager).find("select").bind('change',function() { 
					ts.p.rowNum = (this.value>0) ? this.value : ts.p.rowNum; 
					if (typeof ts.p.onPaging =='function') {ts.p.onPaging('records');}
					populate();
					ts.p.selrow = null;
				});
			} else { $(ts.p.pager).append("&#160;<span id='sp_2'></span>");}
			if(ts.p.pgbuttons===true) {
			$(".pgbuttons",ts.p.pager).mouseover(function(e){
				this.style.cursor= "pointer";
				return false;
			}).mouseout(function(e) {
				this.style.cursor= "normal";
				return false;
			});
			$("#first, #prev, #next, #last",ts.p.pager).click( function(e) {
				var cp = IntNum(ts.p.page);
				var last = IntNum(ts.p.lastpage), selclick = false;
				var fp=true; var pp=true; var np=true; var lp=true;
				if(last ===0 || last===1) {fp=false;pp=false;np=false;lp=false; }
				else if( last>1 && cp >=1) {
					if( cp === 1) { fp=false; pp=false; } 
					else if( cp>1 && cp <last){ }
					else if( cp===last){ np=false;lp=false; }
				} else if( last>1 && cp===0 ) { np=false;lp=false; cp=last-1;}
				if( this.id === 'first' && fp ) { ts.p.page=1; selclick=true;} 
				if( this.id === 'prev' && pp) { ts.p.page=(cp-1); selclick=true;} 
				if( this.id === 'next' && np) { ts.p.page=(cp+1); selclick=true;} 
				if( this.id === 'last' && lp) { ts.p.page=last; selclick=true;}
				if(selclick) {
					if (typeof ts.p.onPaging =='function') {ts.p.onPaging(this.id);}
					populate();
					ts.p.selrow = null;
					if(ts.p.multiselect) {ts.p.selarrrow =[];$('#cb_jqg',ts.grid.hDiv).attr("checked",false);}
					ts.p.savedRow = [];
				}
				e.stopPropagation();
				return false;
			});
			}
			if(ts.p.pginput===true) {
			$('input.selbox',ts.p.pager).keypress( function(e) {
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if(key == 13) {
					ts.p.page = ($(this).val()>0) ? $(this).val():ts.p.page;
					if (typeof ts.p.onPaging =='function') {ts.p.onPaging( 'user');}
					populate();
					ts.p.selrow = null;
					return false;
				}
				return this;
			});
			}
		};
		var sortData = function (index, idxcol,reload){
			if(!reload) {
				if( ts.p.lastsort === idxcol ) {
					if( ts.p.sortorder === 'asc') {
						ts.p.sortorder = 'desc';
					} else if(ts.p.sortorder === 'desc') { ts.p.sortorder='asc';}
				} else { ts.p.sortorder='asc';}
				ts.p.page = 1;
			}
			var imgs = (ts.p.sortorder==='asc') ? ts.p.sortascimg : ts.p.sortdescimg;
			imgs = "<img src='"+ts.p.imgpath+imgs+"'>";
			var thd= $("thead:first",grid.hDiv).get(0);
			$("tr th div#jqgh_"+ts.p.colModel[ts.p.lastsort].name+" img",thd).remove();
			$("tr th div#jqgh_"+ts.p.colModel[ts.p.lastsort].name,thd).parent().removeClass(ts.p.sortclass);
			$("tr th div#"+index,thd).append(imgs).parent().addClass(ts.p.sortclass);
			ts.p.lastsort = idxcol;
			index = index.substring(5);
			ts.p.sortname = ts.p.colModel[idxcol].index || index;
			var so = ts.p.sortorder;
			if(onSortCol) {onSortCol(index,idxcol,so);}
			if(ts.p.selrow && ts.p.datatype == "local" && !ts.p.multiselect){ $('#'+ts.p.selrow,grid.bDiv).removeClass("selected");}
			ts.p.selrow = null;
			if(ts.p.multiselect && ts.p.datatype !== "local"){ts.p.selarrrow =[]; $("#cb_jqg",ts.grid.hDiv).attr("checked",false);}
			ts.p.savedRow =[];
			populate();
			if(ts.p.sortname != index && idxcol) {ts.p.lastsort = idxcol;}
		};
		var setColWidth = function () {
			var initwidth = 0; 
			for(var l=0;l<ts.p.colModel.length;l++){
				if(!ts.p.colModel[l].hidden){
					initwidth += IntNum(ts.p.colModel[l].width);
				}
			}
			var tblwidth = ts.p.width ? ts.p.width : initwidth;
			for(l=0;l<ts.p.colModel.length;l++) {
				if(!ts.p.shrinkToFit){
					ts.p.colModel[l].owidth = ts.p.colModel[l].width;
				}
				ts.p.colModel[l].width = Math.round(tblwidth/initwidth*ts.p.colModel[l].width);
			}
		};
		var nextVisible= function(iCol) {
			var ret = iCol, j=iCol;
			for (var i = iCol+1;i<ts.p.colModel.length;i++){
				if(ts.p.colModel[i].hidden !== true ) {
					j=i; break;
				}
			}
			return j-ret;
		};
		this.p.id = this.id;
		if(this.p.treeGrid === true) {
			this.p.subGrid = false; this.p.altRows =false;
			this.p.pgbuttons = false; this.p.pginput = false;
			this.p.multiselect = false; this.p.rowList = [];
			this.p.treedatatype = this.p.datatype;
			$.each(this.p.treeReader,function(i,n){
				if(n){
					ts.p.colNames.push(n);
					ts.p.colModel.push({name:n,width:1,hidden:true,sortable:false,resizable:false,hidedlg:true,editable:true,search:false});
				}
			});
		}
		if(this.p.subGrid) {
			this.p.colNames.unshift("");
			this.p.colModel.unshift({name:'subgrid',width:25,sortable: false,resizable:false,hidedlg:true,search:false});
		}
		if(this.p.multiselect) {
			this.p.colNames.unshift("<input id='cb_jqg' class='cbox' type='checkbox'/>");
			this.p.colModel.unshift({name:'cb',width:27,sortable:false,resizable:false,hidedlg:true,search:false});
		}
		var	xReader = {
			root: "rows",
			row: "row",
			page: "rows>page",
			total: "rows>total",
			records : "rows>records",
			repeatitems: true,
			cell: "cell",
			id: "[id]",
			userdata: "userdata",
			subgrid: {root:"rows", row: "row", repeatitems: true, cell:"cell"}
		};
		var jReader = {
			root: "rows",
			page: "page",
			total: "total",
			records: "records",
			repeatitems: true,
			cell: "cell",
			id: "id",
			userdata: "userdata",
			subgrid: {root:"rows", repeatitems: true, cell:"cell"}
		};
		ts.p.xmlReader = $.extend(xReader, ts.p.xmlReader);
		ts.p.jsonReader = $.extend(jReader, ts.p.jsonReader);
		$.each(ts.p.colModel, function(i){if(!this.width) {this.width=150;}});
		if (ts.p.width) {setColWidth();}
		var thead = document.createElement("thead");
		var trow = document.createElement("tr");
		thead.appendChild(trow); 
		var i=0, th, idn, thdiv;
		ts.p.keyIndex=false;
		for (var i=0; i<ts.p.colModel.length;i++) {
			if (ts.p.colModel[i].key===true) {
				ts.p.keyIndex = i;
				break;
			}
		}
		if(ts.p.shrinkToFit===true && ts.p.forceFit===true) {
			for (i=ts.p.colModel.length-1;i>=0;i--){
				if(!ts.p.colModel[i].hidden) {
					ts.p.colModel[i].resizable=false;
					break;
				}
			}
		}
		for(i=0;i<this.p.colNames.length;i++){
			th = document.createElement("th");
			idn = ts.p.colModel[i].name;
			thdiv = document.createElement("div");
			$(thdiv).html(ts.p.colNames[i]+"&#160;");
			if (idn == ts.p.sortname) {
				var imgs = (ts.p.sortorder==='asc') ? ts.p.sortascimg : ts.p.sortdescimg;
				imgs = "<img src='"+ts.p.imgpath+imgs+"'>";
				$(thdiv).append(imgs);
				ts.p.lastsort = i;
				$(th).addClass(ts.p.sortclass);
			}
			thdiv.id = "jqgh_"+idn;
			th.appendChild(thdiv);
			trow.appendChild(th);
		}
		if(this.p.multiselect) {
			var onSA = true;
			if(typeof ts.p.onSelectAll !== 'function') {onSA=false;}
			$('#cb_jqg',trow).click(function(){
				var chk;
				if (this.checked) {
					$("[@id^=jqg_]",ts.rows).attr("checked",true);
					$(ts.rows).slice(1).each(function(i) {
						if(!$(this).hasClass("subgrid")){
						$(this).addClass("selected");
						ts.p.selarrrow[i]= ts.p.selrow = this.id; 
						}
					});
					chk=true;
				}
				else {
					$("[@id^=jqg_]",ts.rows).attr("checked",false);
					$(ts.rows).slice(1).each(function(i) {
						if(!$(this).hasClass("subgrid")){
							$(this).removeClass("selected");
						}
					});
					ts.p.selarrrow = []; ts.p.selrow = null;
					chk=false;
				}
				if(onSA) {ts.p.onSelectAll(ts.p.selarrrow,chk);}
			});
		}
		this.appendChild(thead);
		thead = $("thead:first",ts).get(0);
		var w, res, sort;
		$("tr:first th",thead).each(function ( j ) {
			w = ts.p.colModel[j].width;
			if(typeof ts.p.colModel[j].resizable === 'undefined') {ts.p.colModel[j].resizable = true;}
			res = document.createElement("span");
			$(res).html("&#160;");
			if(ts.p.colModel[j].resizable){
				$(this).addClass(ts.p.resizeclass);
				$(res).mousedown(function (e) {
					if(ts.p.forceFit===true) {ts.p.nv= nextVisible(j);}
					grid.dragStart( j ,e.clientX);
					e.preventDefault();
					return false;
				});
			} else {$(res).css("cursor","default");}
			$(this).css("width",w+"px").prepend(res);
			if( ts.p.colModel[j].hidden) {$(this).css("display","none");}
			grid.headers[j] = { width: w, el: this };
			sort = ts.p.colModel[j].sortable;
			if( typeof sort !== 'boolean') {sort =  true;}
			if(sort) { 
				$("div",this).css("cursor","pointer")
				.click(function(){sortData(this.id,j);return false;});
			}
		});
		var isMSIE = $.browser.msie ? true:false;
		var isMoz = $.browser.mozilla ? true:false;
		var isOpera = $.browser.opera ? true:false;
		var isSafari = $.browser.safari ? true : false;
		var tbody = document.createElement("tbody");
		trow = document.createElement("tr");
		trow.id = "_empty";
		tbody.appendChild(trow);
		var td, ptr;
		for(i=0;i<ts.p.colNames.length;i++){
			td = document.createElement("td");
			trow.appendChild(td);
		}
		this.appendChild(tbody);
		var gw=0,hdc=0;
		$("tbody tr:first td",ts).each(function(ii) {
			w = ts.p.colModel[ii].width;
			$(this).css({width:w+"px",height:"0px"});
			w +=  IntNum($(this).css("padding-left")) +
			IntNum($(this).css("padding-right"))+
			IntNum($(this).css("border-left-width"))+
			IntNum($(this).css("border-right-width"));
			if( ts.p.colModel[ii].hidden===true) {
				$(this).css("display","none");
				hdc += w;
			}
			grid.cols[ii] = this;
			gw += w;
		});
		if(isMoz) {$(trow).css({visibility:"collapse"});}
		else if( isSafari || isOpera ) {$(trow).css({display:"none"});}
		grid.width = IntNum(gw)-IntNum(hdc);
		ts.p._width = grid.width;
		grid.hTable = document.createElement("table");
		grid.hTable.appendChild(thead);
		$(grid.hTable).addClass("scroll")
		.attr({cellSpacing:"0",cellPadding:"0",border:"0"})
		.css({width:grid.width+"px"});
		grid.hDiv = document.createElement("div");
		var hg = (ts.p.caption && ts.p.hiddengrid===true) ? true : false;
		$(grid.hDiv)
			.css({ width: grid.width+"px", overflow: "hidden"})
			.prepend('<div class="loading">'+ts.p.loadtext+'</div>')
			.append(grid.hTable)
			.bind("selectstart", function () { return false; });
		if(hg) {$(grid.hDiv).hide(); ts.p.gridstate = 'hidden'}
		if(ts.p.pager){
			if(typeof ts.p.pager == "string") {ts.p.pager = "#"+ts.p.pager;}
			if( $(ts.p.pager).hasClass("scroll")) { $(ts.p.pager).css({ width: grid.width+"px", overflow: "hidden"}).show(); ts.p._height= parseInt($(ts.p.pager).height(),10); if(hg) {$(ts.p.pager).hide();}}
			setPager();
		}
		if( ts.p.cellEdit === false) {
		$(ts).mouseover(function(e) {
			td = (e.target || e.srcElement);
			ptr = $(td,ts.rows).parents("tr:first");
			if($(ptr).hasClass("jqgrow")) {
				$(ptr).addClass("over");
				if(!$(td).hasClass("editable")){
					td.title = $(td).text();
				}
			}
			return false;
		}).mouseout(function(e) {
			td = (e.target || e.srcElement);
			ptr = $(td,ts.rows).parents("tr:first");
			$(ptr).removeClass("over");
			if(!$(td).hasClass("editable")){
				td.title = "";
			}
			return false;
		});
		}
		var ri,ci;
		$(ts).before(grid.hDiv).css("width", grid.width+"px").click(function(e) {
			td = (e.target || e.srcElement);
			var scb = $(td).hasClass("cbox");
			ptr = $(td,ts.rows).parent("tr");
			if($(ptr).length === 0 ){
				ptr = $(td,ts.rows).parents("tr:first");
				td = $(td).parents("td:first")[0];
			}
			if(ts.p.cellEdit === true) {
				ri = ptr[0].rowIndex;
				ci = td.cellIndex;
				try {$(ts).editCell(ri,ci,true,true);} catch (e) {}
			} else 
			if ( !ts.p.multikey ) {
				$(ts).setSelection(false,ptr);
				if(onSC) {
					ri = ptr[0].id;
					ci = td.cellIndex;
					onSC(ri,ci,$(td).html());
				}
			} else {
				if(e[ts.p.multikey]) {
					$(ts).setSelection(false,ptr);
				} else if(ts.p.multiselect) {
					if(scb) { scb = $("[@id^=jqg_]",ptr).attr("checked");
						$("[@id^=jqg_]",ptr).attr("checked",!scb);
					}
				}
			}
			e.stopPropagation();
		}).bind('reloadGrid', function(e) {
			if(!ts.p.treeGrid) {ts.p.selrow=null;}
			if(ts.p.multiselect) {ts.p.selarrrow =[];$('#cb_jqg',ts.grid.hDiv).attr("checked",false);}
			if(ts.p.cellEdit) {ts.p.savedRow = []; }
			populate();
		});
		if( ondblClickRow ) {
			$(this).dblclick(function(e) {
				td = (e.target || e.srcElement);
				ptr = $(td,ts.rows).parent("tr");
				if($(ptr).length === 0 ){
					ptr = $(td,ts.rows).parents("tr:first");
				}
				ts.p.ondblClickRow($(ptr).attr("id"));
				return false;
			});
		}
		if (onRightClickRow) {
			$(this).bind('contextmenu', function(e) {
				td = (e.target || e.srcElement);
				ptr = $(td,ts).parents("tr:first");
				if($(ptr).length === 0 ){
					ptr = $(td,ts.rows).parents("tr:first");
				}
				$(ts).setSelection(false,ptr);
				ts.p.onRightClickRow($(ptr).attr("id"));
				return false;
			});
		}
		grid.bDiv = document.createElement("div");
		var ofl2 = (isNaN(ts.p.height) && isMoz && (ts.p.height.indexOf("%")!=-1 || ts.p.height=="auto")) ? "hidden" : "auto";
		$(grid.bDiv)
			.scroll(function (e) {grid.scrollGrid();})
			.css({ height: ts.p.height+(isNaN(ts.p.height)?"":"px"), padding: "0px", margin: "0px", overflow: ofl2,width: (grid.width)+"px"} ).css("overflow-x","hidden")
			.append(this);
		$("table:first",grid.bDiv).css({width:grid.width+"px",marginRight:"20px"});
		if( isMSIE ) {
			if( $("tbody",this).size() === 2 ) { $("tbody:first",this).remove();}
			if( ts.p.multikey) {$(grid.bDiv).bind("selectstart",function(){return false;});}
			if(ts.p.treeGrid) {$(grid.bDiv).css("position","relative");}
		} else {
			if( ts.p.multikey) {$(grid.bDiv).bind("mousedown",function(){return false;});}
		}
		if(hg) {$(grid.bDiv).hide();}
		grid.cDiv = document.createElement("div");
		$(grid.cDiv).append("<table class='Header' cellspacing='0' cellpadding='0' border='0'><tr><td class='HeaderLeft'><img src='"+ts.p.imgpath+"spacer.gif' border='0' /></td><th>"+ts.p.caption+"</th>"+ ((ts.p.hidegrid===true) ? "<td class='HeaderButton'><img src='"+ts.p.imgpath+"up.gif' border='0'/></td>" :"") +"<td class='HeaderRight'><img src='"+ts.p.imgpath+"spacer.gif' border='0' /></td></tr></table>").addClass("GridHeader");
		$(grid.cDiv).insertBefore(grid.hDiv);
		if( ts.p.toolbar[0] ) {
			grid.uDiv = document.createElement("div");
			if(ts.p.toolbar[1] == "top") {$(grid.uDiv).insertBefore(grid.hDiv);}
			else {$(grid.uDiv).insertAfter(grid.hDiv);}
			$(grid.uDiv,ts).width(grid.width).addClass("userdata").attr("id","t_"+this.id);
			ts.p._height += parseInt($(grid.uDiv,ts).height(),10);
			if(hg) {$(grid.uDiv,ts).hide();}
		}
		if(ts.p.caption) {
			$(grid.cDiv,ts).width(grid.width).css("text-align","center").show("fast");
			ts.p._height += parseInt($(grid.cDiv,ts).height(),10);
			var tdt = ts.p.datatype;
			if(ts.p.hidegrid===true) {
				$(".HeaderButton",grid.cDiv).toggle( function(){
					if(ts.p.pager) {$(ts.p.pager).fadeOut("slow");}
					if(ts.p.toolbar[0]) {$(grid.uDiv,ts).fadeOut("slow");}
					$(grid.bDiv,ts).fadeOut("slow");
					$(grid.hDiv,ts).fadeOut("slow");
					$("img",this).attr("src",ts.p.imgpath+"down.gif");
					ts.p.gridstate = 'hidden';
					if(onHdCl) {if(!hg) {ts.p.onHeaderClick(ts.p.gridstate);}}
					},
					function() {
					$(grid.hDiv ,ts).fadeIn("slow");
					$(grid.bDiv,ts).fadeIn("slow");
					if(ts.p.pager) {$(ts.p.pager).fadeIn("slow");}
					if(ts.p.toolbar[0]) {$(grid.uDiv).fadeIn("slow");}
					$("img",this).attr("src",ts.p.imgpath+"up.gif");
					if(hg) {ts.p.datatype = tdt;populate();hg=false;}
					ts.p.gridstate = 'visible';
					if(onHdCl) {ts.p.onHeaderClick(ts.p.gridstate)}
					}
				);
				if(hg) { $(".HeaderButton",grid.cDiv).trigger("click"); ts.p.datatype="local";}
			}
		}
		ts.p._height += parseInt($(grid.hDiv,ts).height(),10);
		$(grid.hDiv).mousemove(function (e) {grid.dragMove(e.clientX); return false;}).after(grid.bDiv);
		$(document).mouseup(function (e) {
			if(grid.resizing) {
				grid.dragEnd();
				if(grid.newWidth && ts.p.forceFit===false){
					var gwdt = (grid.width <= ts.p._width) ? grid.width : ts.p._width;
					var overfl = (grid.width <= ts.p._width) ? "hidden" : "auto";
					if(ts.p.pager && $(ts.p.pager).hasClass("scroll") ) {
						$(ts.p.pager).width(gwdt);
					}
					if(ts.p.caption) {$(grid.cDiv).width(gwdt);}
					if(ts.p.toolbar[0]) {$(grid.uDiv).width(gwdt);}
					$(grid.bDiv).width(gwdt).css("overflow-x",overfl);
					$(grid.hDiv).width(gwdt);
				}
			}
			return false;
		});
		ts.formatCol = function(a,b) {formatCol(a,b);};
		ts.sortData = function(a,b,c){sortData(a,b,c);};
		ts.updatepager = function(){updatepager();};
		this.grid = grid;
		ts.addXmlData = function(d) {addXmlData(d,ts.grid.bDiv);};
		ts.addJSONData = function(d) {addJSONData(d,ts.grid.bDiv);};
		populate();
		if (!ts.p.shrinkToFit) {
			ts.p.forceFit = false;
			$("tr:first th", thead).each(function(j){
				var w = ts.p.colModel[j].owidth;
				var diff = w - ts.p.colModel[j].width;
				if (diff > 0 && !ts.p.colModel[j].hidden) {
					grid.headers[j].width = w;
					$(this).add(grid.cols[j]).width(w);
					$('table:first',grid.bDiv).add(grid.hTable).width(ts.grid.width);
					ts.grid.width += diff;
					grid.hDiv.scrollLeft = grid.bDiv.scrollLeft;
				}
			});
			ofl2 = (grid.width <= ts.p._width) ? "hidden" : "auto";
			$(grid.bDiv).css({"overflow-x":ofl2});
		}
		$(window).unload(function () {
			$(this).unbind("*");
			this.grid = null;
			this.p = null;
		});
	});
};
})(jQuery);
/**
 * jqGrid common function
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
// Modal functions
var showModal = function(h) {
	h.w.show();
};
var closeModal = function(h) {
	h.w.hide();
	if(h.o) { h.o.remove(); }
};
function createModal(aIDs, content, p, insertSelector, posSelector, appendsel) {
	var clicon = p.imgpath ? p.imgpath+p.closeicon : p.closeicon;
	var mw  = document.createElement('div');
	jQuery(mw).addClass("modalwin").attr("id",aIDs.themodal);
	var mh = jQuery('<div id="'+aIDs.modalhead+'"><table width="100%"><tbody><tr><td class="modaltext">'+p.caption+'</td> <td align="right"><a href="javascript:void(0);" class="jqmClose">'+(clicon!=''?'<img src="' + clicon + '" border="0"/>':'X') + '</a></td></tr></tbody></table> </div>').addClass("modalhead");
	var mc = document.createElement('div');
	jQuery(mc).addClass("modalcontent").attr("id",aIDs.modalcontent);
	jQuery(mc).append(content);
	mw.appendChild(mc);
	var loading = document.createElement("div");
	jQuery(loading).addClass("loading").html(p.processData||"");
	jQuery(mw).prepend(loading);
	jQuery(mw).prepend(mh);
	jQuery(mw).addClass("jqmWindow");
	if (p.drag) {
		jQuery(mw).append("<img  class='jqResize' src='"+p.imgpath+"resize.gif'/>");
	}
	if(appendsel===true) { jQuery('body').append(mw); } //append as first child in body -for alert dialog
	else { jQuery(mw).insertBefore(insertSelector); }
	if(p.left ==0 && p.top==0) {
		var pos = [];
		pos = findPos(posSelector) ;
		p.left = pos[0] + 4;
		p.top = pos[1] + 4;
	}
	if (p.width == 0 || !p.width) {p.width = 300;}
	if(p.height==0 || !p.width) {p.height =200;}
	if(!p.zIndex) {p.zIndex = 950;}
	jQuery(mw).css({top: p.top+"px",left: p.left+"px",width: p.width+"px",height: p.height+"px", zIndex:p.zIndex});
	return false;
};

function viewModal(selector,o){
	o = jQuery.extend({
		toTop: true,
		overlay: 10,
		modal: false,
		drag: true,
		onShow: showModal,
		onHide: closeModal
	}, o || {});
	jQuery(selector).jqm(o).jqmShow();
	return false;
};
function DnRModal(modwin,handler){
	jQuery(handler).css('cursor','move');
	jQuery(modwin).jqDrag(handler).jqResize(".jqResize");
	return false;
};

function info_dialog(caption, content,c_b, pathimg) {
	var cnt = "<div id='info_id'>";
	cnt += "<div align='center'><br />"+content+"<br /><br />";
	cnt += "<input type='button' size='10' id='closedialog' class='jqmClose EditButton' value='"+c_b+"' />";
	cnt += "</div></div>";
	createModal({
		themodal:'info_dialog',
		modalhead:'info_head',
		modalcontent:'info_content'},
		cnt,
		{ width:290,
		height:120,drag: false,
		caption:"<b>"+caption+"</b>",
		imgpath: pathimg,
		closeicon: 'ico-close.gif',
		left:250,
		top:170 },
		'','',true
	);
	viewModal("#info_dialog",{
		onShow: function(h) {
			h.w.show();
		},
		onHide: function(h) {
			h.w.hide().remove();
			if(h.o) { h.o.remove(); }
		},
		modal :true
	});
};
//Helper functions
function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop; 
		} while (obj = obj.offsetParent);
		//do not change obj == obj.offsetParent 
	}
	return [curleft,curtop];
};
function isArray(obj) {
	if (obj.constructor.toString().indexOf("Array") == -1) {
		return false;
	} else {
		return true;
	}
};
// Form Functions
function createEl(eltype,options,vl,elm) {
	var elem = "";
	switch (eltype)
	{
		case "textarea" :
				elem = document.createElement("textarea");
				if(!options.cols && elm) {jQuery(elem).css("width","99%");}
				jQuery(elem).attr(options);
				jQuery(elem).val(vl);
				break;
		case "checkbox" : //what code for simple checkbox
			elem = document.createElement("input");
			elem.type = "checkbox";
			jQuery(elem).attr({id:options.id,name:options.name});
			if( !options.value) {
				if(vl.toLowerCase() =='on') {
					elem.checked=true;
					elem.defaultChecked=true;
					elem.value = vl;
				} else {
					elem.value = "on";
				}
				jQuery(elem).attr("offval","off");
			} else {
				var cbval = options.value.split(":");
				if(vl == cbval[0]) {
					elem.checked=true;
					elem.defaultChecked=true;
				}
				elem.value = cbval[0];
				jQuery(elem).attr("offval",cbval[1]);
			}
			break;
		case "select" :
			var so = options.value.split(";"),sv, ov;
			elem = document.createElement("select");
			var msl =  options.multiple === true ? true : false;
			jQuery(elem).attr({id:options.id,name:options.name,size:Math.min(options.size,so.length), multiple:msl });
			for(var i=0; i<so.length;i++){
				sv = so[i].split(":");
				ov = document.createElement("option");
				ov.value = sv[0]; ov.innerHTML = sv[1];
				if (!msl &&  sv[1]==vl) ov.selected ="selected";
				if (msl && jQuery.inArray(sv[1],vl.split(","))>-1) ov.selected ="selected";
				elem.appendChild(ov);
			}
			break;
		case "text" :
			elem = document.createElement("input");
			elem.type = "text";
			elem.value = vl;
			if(!options.size && elm) {
				jQuery(elem).css("width","99%");
			}
			jQuery(elem).attr(options);
			break;
		case "password" :
			elem = document.createElement("input");
			elem.type = "password";
			elem.value = vl;
			if(!options.size && elm) { jQuery(elem).css("width","99%"); }
			jQuery(elem).attr(options);
			break;
		case "image" :
			elem = document.createElement("input");
			elem.type = "image";
			jQuery(elem).attr(options);
			break;
	}
	return elem;
};
function checkValues(val, valref,g) {
	if(valref >=0) {
		var edtrul = g.p.colModel[valref].editrules;
	}
	if(edtrul) {
		if(edtrul.required == true) {
			if( val.match(/^s+$/) || val == "" )  return [false,g.p.colNames[valref]+": "+jQuery.jgrid.edit.msg.required,""];
		}
		if(edtrul.number == true) {
			if(isNaN(val)) return [false,g.p.colNames[valref]+": "+jQuery.jgrid.edit.msg.number,""];
		}
		if(edtrul.minValue && !isNaN(edtrul.minValue)) {
			if (parseFloat(val) < parseFloat(edtrul.minValue) ) return [false,g.p.colNames[valref]+": "+jQuery.jgrid.edit.msg.minValue+" "+edtrul.minValue,""];
		}
		if(edtrul.maxValue && !isNaN(edtrul.maxValue)) {
			if (parseFloat(val) > parseFloat(edtrul.maxValue) ) return [false,g.p.colNames[valref]+": "+jQuery.jgrid.edit.msg.maxValue+" "+edtrul.maxValue,""];
		}
		if(edtrul.email == true) {
			// taken from jquery Validate plugin
			var filter = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
			if(!filter.test(val)) {return [false,g.p.colNames[valref]+": "+jQuery.jgrid.edit.msg.email,""];}
		}
		if(edtrul.integer == true) {
			if(isNaN(val)) return [false,g.p.colNames[valref]+": "+jQuery.jgrid.edit.msg.integer,""];
			if ((val < 0) || (val % 1 != 0) || (val.indexOf('.') != -1)) return [false,g.p.colNames[valref]+": "+jQuery.jgrid.edit.msg.integer,""];
		}
	}
	return [true,"",""];
};
;(function($){
/*
**
 * jqGrid extension for cellediting Grid Data
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
/**
 * all events and options here are aded anonynous and not in the base grid
 * since the array is to big. Here is the order of execution.
 * From this point we use jQuery isFunction
 * formatCell
 * beforeEditCell,
 * onSelectCell (used only for noneditable cels)
 * afterEditCell,
 * beforeSaveCell, (called before validation of values if any)
 * beforeSubmitCell (if cellsubmit remote (ajax))
 * afterSubmitCell(if cellsubmit remote (ajax)),
 * afterSaveCell,
 * errorCell,
 * Options
 * cellsubmit (remote,clientArray) (added in grid options)
 * cellurl
* */
$.fn.extend({
	editCell : function (iRow,iCol, ed, fg){
		return this.each(function (){
			var $t = this, nm, tmp,cc;
			if (!$t.grid || $t.p.cellEdit !== true) {return;}
			var currentFocus = null;
			// I HATE IE
			if ($.browser.msie && $.browser.version <=6 && ed===true && fg===true) {
				iCol = getAbsoluteIndex($t.rows[iRow],iCol);
			}
			// select the row that can be used for other methods
			$t.p.selrow = $t.rows[iRow].id;
			if (!$t.p.knv) {$($t).GridNav();}
			// check to see if we have already edited cell
			if ($t.p.savedRow.length>0) {
				// prevent second click on that field and enable selects
				if (ed===true ) {
					if(iRow == $t.p.iRow && iCol == $t.p.iCol){
						return;
					}
				}
				// if so check to see if the content is changed
				var vl = $("td:eq("+$t.p.savedRow[0].ic+")>#"+$t.p.savedRow[0].id+"_"+$t.p.savedRow[0].name,$t.rows[$t.p.savedRow[0].id]).val();
				if ($t.p.savedRow[0].v !=  vl) {
					// save it
					$($t).saveCell($t.p.savedRow[0].id,$t.p.savedRow[0].ic)
				} else {
					// restore it
					$($t).restoreCell($t.p.savedRow[0].id,$t.p.savedRow[0].ic);
				}
			} else {
				window.setTimeout(function () { $("#"+$t.p.knv).attr("tabindex","-1").focus();},0);
			}
			nm = $t.p.colModel[iCol].name;
			if (nm=='subgrid') {return;}
			if ($t.p.colModel[iCol].editable===true && ed===true) {
				cc = $("td:eq("+iCol+")",$t.rows[iRow]);
				if(parseInt($t.p.iCol)>=0  && parseInt($t.p.iRow)>=0) {
					$("td:eq("+$t.p.iCol+")",$t.rows[$t.p.iRow]).removeClass("edit-cell");
					$($t.rows[$t.p.iRow]).removeClass("selected-row");
				}
				$(cc).addClass("edit-cell");
				$($t.rows[iRow]).addClass("selected-row");
				tmp = $(cc).html().replace(/\&nbsp\;/ig,'');
				var opt = $.extend($t.p.colModel[iCol].editoptions || {} ,{id:iRow+"_"+nm,name:nm});
				if (!$t.p.colModel[iCol].edittype) {$t.p.colModel[iCol].edittype = "text";}
				$t.p.savedRow.push({id:iRow,ic:iCol,name:nm,v:tmp});
				if($.isFunction($t.p.formatCell)) {
					var tmp2 = $t.p.formatCell($t.rows[iRow].id,nm,tmp,iRow,iCol);
					if(tmp2) {tmp = tmp2;}
				}
				var elc = createEl($t.p.colModel[iCol].edittype,opt,tmp,cc);
				if ($.isFunction($t.p.beforeEditCell)) {
					$t.p.beforeEditCell($t.rows[iRow].id,nm,tmp,iRow,iCol);
				}
				$(cc).html("").append(elc);
				window.setTimeout(function () { $(elc).focus();},0);
				$("input, select, textarea",cc).bind("keydown",function(e) { 
					if (e.keyCode === 27) {$($t).restoreCell(iRow,iCol);} //ESC
					if (e.keyCode === 13) {$($t).saveCell(iRow,iCol);}//Enter
					if (e.keyCode == 9)  {$($t).nextCell(iRow,iCol);} //Tab
					e.stopPropagation();
				});
				if ($.isFunction($t.p.afterEditCell)) {
					$t.p.afterEditCell($t.rows[iRow].id,nm,tmp,iRow,iCol);
				}
			} else {
				if (parseInt($t.p.iCol)>=0  && parseInt($t.p.iRow)>=0) {
					$("td:eq("+$t.p.iCol+")",$t.rows[$t.p.iRow]).removeClass("edit-cell");
					$($t.rows[$t.p.iRow]).removeClass("selected-row");
				}
				$("td:eq("+iCol+")",$t.rows[iRow]).addClass("edit-cell");
				$($t.rows[iRow]).addClass("selected-row"); 
				if ($.isFunction($t.p.onSelectCell)) {
					tmp = $("td:eq("+iCol+")",$t.rows[iRow]).html().replace(/\&nbsp\;/ig,'');
					$t.p.onSelectCell($t.rows[iRow].id,nm,tmp,iRow,iCol);
				}
			}
			$t.p.iCol = iCol; $t.p.iRow = iRow;
			// IE 6 bug 
			function getAbsoluteIndex(t,relIndex) 
			{ 
				var countnotvisible=0; 
				var countvisible=0; 
				for (i=0;i<t.cells.length;i++) { 
					var cell=t.cells(i); 
					if (cell.style.display=='none') countnotvisible++; else countvisible++; 
					if (countvisible>relIndex) return i; 
				} 
				return i; 
			}
		});
	},
	saveCell : function (iRow, iCol){
		return this.each(function(){
			var $t= this, nm, fr=null;
			if (!$t.grid || $t.p.cellEdit !== true) {return;}
			for( var k=0;k<$t.p.savedRow.length;k++) {
				if ( $t.p.savedRow[k].id===iRow) {fr = k; break;}
			};
			if(fr != null) {
				var cc = $("td:eq("+iCol+")",$t.rows[iRow]);
				nm = $t.p.colModel[iCol].name;
				var v,v2;
				switch ($t.p.colModel[iCol].edittype) {
					case "select":
						v = $("#"+iRow+"_"+nm+">option:selected",$t.rows[iRow]).val();
						v2 = $("#"+iRow+"_"+nm+">option:selected",$t.rows[iRow]).text();
						break;
					case "checkbox":
						var cbv = $t.p.colModel[iCol].editoptions.value.split(":") || ["Yes","No"];
						v = $("#"+iRow+"_"+nm,$t.rows[iRow]).attr("checked") ? cbv[0] : cbv[1];
						v2=v;
						break;
					case "password":
					case "text":
					case "textarea":
						v = $("#"+iRow+"_"+nm,$t.rows[iRow]).val();
						v2=v;
						break;
				}
				// The common approach is if nothing changed do not do anything
				if (v2 != $t.p.savedRow[fr].v){
					if ($.isFunction($t.p.beforeSaveCell)) {
						var vv = $t.p.beforeSaveCell($t.rows[iRow].id,nm, v, iRow,iCol);
						if (vv) {v = vv;}
					}				
					var cv = checkValues(v,iCol,$t);
					if(cv[0] === true) {
						var addpost = {};
						if ($.isFunction($t.p.beforeSubmitCell)) {
							addpost = $t.p.beforeSubmitCell($t.rows[iRow].id,nm, v, iRow,iCol);
							if (!addpost) {addpost={};}
						}
						if ($t.p.cellsubmit == 'remote') {
							if ($t.p.cellurl) {
								var postdata = {};
								postdata[nm] = v;
								postdata["id"] = $t.rows[iRow].id;
								postdata = $.extend(addpost,postdata);
								$.ajax({
									url: $t.p.cellurl,
									data :postdata,
									type: "POST",
									complete: function (result, stat) {
										if (stat == 'success') {
											if ($.isFunction($t.p.afterSubmitCell)) {
												var ret = $t.p.afterSubmitCell(result,postdata.id,nm,v,iRow,iCol);
												if(ret && ret[0] === true) {
													$(cc).empty().html(v2 || "&nbsp;");
													$(cc).addClass("dirty-cell");
													$($t.rows[iRow]).addClass("edited");
													if ($.isFunction($t.p.afterSaveCell)) {
														$t.p.afterSaveCell($t.rows[iRow].id,nm, v, iRow,iCol);
													}
													$t.p.savedRow.splice(fr,1);
												} else {
													info_dialog($.jgrid.errors.errcap,ret[1],$.jgrid.edit.bClose, $t.p.imgpath);
													$($t).restoreCell(iRow,iCol);
												}
											} else {
												$(cc).empty().html(v2 || "&nbsp;");
												$(cc).addClass("dirty-cell");
												$($t.rows[iRow]).addClass("edited");
												if ($.isFunction($t.p.afterSaveCell)) {
													$t.p.afterSaveCell($t.rows[iRow].id,nm, v, iRow,iCol);
												}
												$t.p.savedRow.splice(fr,1);
											}
										}
									},
									error:function(res,stat){
										if ($.isFunction($t.p.errorCell)) {
											$t.p.errorCell(res,stat);
										} else {
											info_dialog($.jgrid.errors.errcap,res.status+" : "+res.statusText+"<br/>"+stat,$.jgrid.edit.bClose, $t.p.imgpath);
											$($t).restoreCell(iRow,iCol);
										}
									}
								});
							} else {
								try {
									info_dialog($.jgrid.errors.errcap,$.jgrid.errors.nourl,$.jgrid.edit.bClose, $t.p.imgpath);
									$($t).restoreCell(iRow,iCol);
								} catch (e) {}
							}
						}
						if ($t.p.cellsubmit == 'clientArray') {
							$(cc).empty().html(v2 || "&nbsp;");
							$(cc).addClass("dirty-cell");
							$($t.rows[iRow]).addClass("edited");
							if ($.isFunction($t.p.afterSaveCell)) {
								$t.p.afterSaveCell($t.rows[iRow].id,nm, v, iRow,iCol);
							}
							$t.p.savedRow.splice(fr,1);
						}
					} else {
						try {
							window.setTimeout(function(){info_dialog($.jgrid.errors.errcap,v+" "+cv[1],$.jgrid.edit.bClose, $t.p.imgpath)},100);
							$($t).restoreCell(iRow,iCol);
						} catch (e) {}
					}
				} else {
					$($t).restoreCell(iRow,iCol);
				}
			}
			if ($.browser.opera) {
				$("#"+$t.p.knv).attr("tabindex","-1").focus();
			} else {
				window.setTimeout(function () { $("#"+$t.p.knv).attr("tabindex","-1").focus();},0);
			}
		});
	},
	nextCell : function (iRow,iCol) {
		return this.each(function (){
			var $t = this, nCol=false, tmp;
			if (!$t.grid || $t.p.cellEdit !== true) {return;}
			// try to find next editable cell
			for (var i=iCol+1; i<$t.p.colModel.length; i++) {
				if ( $t.p.colModel[i].editable ===true) {
					nCol = i; break;
				}
			}
			if(nCol !== false) {
				$($t).saveCell(iRow,iCol);
				$($t).editCell(iRow,nCol,true);
			} else {
				if ($t.p.savedRow.length >0) {
					$($t).saveCell(iRow,iCol);
				}
			}
		});
	},
	restoreCell : function(iRow, iCol) {
		return this.each(function(){
			var $t= this, nm, fr=null;
			if (!$t.grid || $t.p.cellEdit !== true ) {return;}
			for( var k=0;k<$t.p.savedRow.length;k++) {
				if ( $t.p.savedRow[k].id===iRow) {fr = k; break;}
			}
			if(fr != null) {
				var cc = $("td:eq("+iCol+")",$t.rows[iRow]);
				if($.isFunction($.fn['datepicker'])) {
				try {
					$.datepicker('hide');
				} catch (e) {
					try {
						$.datepicker.hideDatepicker();
					} catch (e) {}
				}
				}
				$(":input",cc).unbind();
				nm = $t.p.colModel[iCol].name;
				$(cc).empty()
				.html($t.p.savedRow[fr].v || "&nbsp;");
				//$t.p.savedRow.splice(fr,1);
				$t.p.savedRow = [];
				
			}
			window.setTimeout(function () { $("#"+$t.p.knv).attr("tabindex","-1").focus();},0);
		});
	},
	GridNav : function() {
		return this.each(function () {
			var  $t = this;
			if (!$t.grid || $t.p.cellEdit !== true ) {return;}
			// trick to process keydown on non input elements
			$t.p.knv = $("table:first",$t.grid.bDiv).attr("id") + "_kn";
			var selection = $("<span style='width:0px;height:0px;background-color:black;' tabindex='0'><span tabindex='-1' style='width:0px;height:0px;background-color:grey' id='"+$t.p.knv+"'></span></span>");
			$(selection).insertBefore($t.grid.cDiv);
			$("#"+$t.p.knv).focus();
			$("#"+$t.p.knv).keydown(function (e){
				switch (e.keyCode) {
					case 38:
						if ($t.p.iRow-1 >=1 ) {
							scrollGrid($t.p.iRow-1,$t.p.iCol,'vu');
							$($t).editCell($t.p.iRow-1,$t.p.iCol,false);
						}
					break;
					case 40 :
						if ($t.p.iRow+1 <=  $t.rows.length-1) {
							scrollGrid($t.p.iRow+1,$t.p.iCol,'vd');
							$($t).editCell($t.p.iRow+1,$t.p.iCol,false);
						}
					break;
					case 37 :
						if ($t.p.iCol -1 >=  0) {
							var i = findNextVisible($t.p.iCol-1,'lft');
							scrollGrid($t.p.iRow, i,'h');
							$($t).editCell($t.p.iRow, i,false);
						}
					break;
					case 39 :
						if ($t.p.iCol +1 <=  $t.p.colModel.length-1) {
							var i = findNextVisible($t.p.iCol+1,'rgt');
							scrollGrid($t.p.iRow,i,'h');
							$($t).editCell($t.p.iRow,i,false);
						}
					break;
					case 13:
						if (parseInt($t.p.iCol,10)>=0 && parseInt($t.p.iRow,10)>=0) {
							$($t).editCell($t.p.iRow,$t.p.iCol,true);
						}
					break;
				}
				return false;
			});
			function scrollGrid(iR, iC, tp){
				if (tp.substr(0,1)=='v') {
					var ch = $($t.grid.bDiv)[0].clientHeight;
					var st = $($t.grid.bDiv)[0].scrollTop;
					var nROT = $t.rows[iR].offsetTop+$t.rows[iR].clientHeight;
					var pROT = $t.rows[iR].offsetTop;
					if(tp == 'vd') {
						if(nROT >= ch) {
							$($t.grid.bDiv)[0].scrollTop = $($t.grid.bDiv)[0].scrollTop + $t.rows[iR].clientHeight;
						}
					}
					if(tp == 'vu'){
						if (pROT < st) {
							$($t.grid.bDiv)[0].scrollTop = $($t.grid.bDiv)[0].scrollTop - $t.rows[iR].clientHeight;
						}
					}
				}
				if(tp=='h') {
					var cw = $($t.grid.bDiv)[0].clientWidth;
					var sl = $($t.grid.bDiv)[0].scrollLeft;
					var nCOL = $t.rows[iR].cells[iC].offsetLeft+$t.rows[iR].cells[iC].clientWidth;
					var pCOL = $t.rows[iR].cells[iC].offsetLeft;
					if(nCOL >= cw+parseInt(sl)) {
						$($t.grid.bDiv)[0].scrollLeft = $($t.grid.bDiv)[0].scrollLeft + $t.rows[iR].cells[iC].clientWidth;
					} else if (pCOL < sl) {
						$($t.grid.bDiv)[0].scrollLeft = $($t.grid.bDiv)[0].scrollLeft - $t.rows[iR].cells[iC].clientWidth;
					}
				}
			};
			function findNextVisible(iC,act){
				var ind;
				if(act == 'lft') {
					ind = iC+1;
					for (var i=iC;i>=0;i--){
						if ($t.p.colModel[i].hidden !== true) {
							ind = i;
							break;
						}
					}
				}
				if(act == 'rgt') {
					ind = iC-1;
					for (var i=iC; i<$t.p.colModel.length;i++){
						if ($t.p.colModel[i].hidden !== true) {
							ind = i;
							break;
						}						
					}
				}
				return ind;
			};
		});
	},
	getChangedCells : function (mthd) {
		var ret=[];
		if (!mthd) {mthd='all';}
		this.each(function(){
			var $t= this;
			if (!$t.grid || $t.p.cellEdit !== true ) {return;}
			$($t.rows).slice(1).each(function(j){
				var res = {};
				if ($(this).hasClass("edited")) {
					$('td',this).each( function(i) {
						nm = $t.p.colModel[i].name;
						if ( nm !== 'cb' && nm !== 'subgrid') {
							if (mthd=='dirty') {
								if ($(this).hasClass('dirty-cell')) {
									res[nm] = $(this).html().replace(/\&nbsp\;/ig,'');
								}
							} else {
								res[nm] = $(this).html().replace(/\&nbsp\;/ig,'');
							}
						}
					});
					res["id"] = this.id;
					ret.push(res);
				}
			})
		});
		return ret;
	}
/// end  cell editing
});
})(jQuery);
;(function($){
/**
 * jqGrid extension for form editing Grid Data
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
var rp_ge = null;
$.fn.extend({
	searchGrid : function ( p ) {
		p = $.extend({
			top : 0,
			left: 0,
			width: 360,
			height: 80,
			modal: false,
			drag: true,
			closeicon: 'ico-close.gif',
			dirty: false,
			sField:'searchField',
			sValue:'searchString',
			sOper: 'searchOper',
			processData: "",
			checkInput :false,
			beforeShowSearch: null,
			afterShowSearch : null,
			onInitializeSearch: null,
			// translation
			// if you want to change or remove the order change it in sopt
			// ['bw','eq','ne','lt','le','gt','ge','ew','cn'] 
			sopt: null 
		}, $.jgrid.search, p || {});
		return this.each(function(){
			var $t = this;
			if( !$t.grid ) { return; }
			if(!p.imgpath) { p.imgpath= $t.p.imgpath; }
			var gID = $("table:first",$t.grid.bDiv).attr("id");
			var IDs = { themodal:'srchmod'+gID,modalhead:'srchhead'+gID,modalcontent:'srchcnt'+gID };
			if ( $("#"+IDs.themodal).html() != null ) {
				if( $.isFunction('beforeShowSearch') ) { beforeShowSearch($("#srchcnt"+gID)); }
				viewModal("#"+IDs.themodal,{modal: p.modal});
				if( $.isFunction('afterShowSearch') ) { afterShowSearch($("#srchcnt"+gID)); }
			} else {
				var cM = $t.p.colModel;
				var cNames = "<select id='snames' class='search'>";
				var nm, hc, sf;
				for(var i=0; i< cM.length;i++) {
					nm = cM[i].name;
					hc = (cM[i].hidden===true) ? true : false;
					sf = (cM[i].search===false) ? false: true;
					if( nm !== 'cb' && nm !== 'subgrid' && sf && !hc ) { // add here condition for searchable
						var sname = (cM[i].index) ? cM[i].index : nm;
						cNames += "<option value='"+sname+"'>"+$t.p.colNames[i]+"</option>";
					}
				}
				cNames += "</select>";
				var getopt = p.sopt || ['bw','eq','ne','lt','le','gt','ge','ew','cn'];
				var sOpt = "<select id='sopt' class='search'>";
				for(var i = 0; i<getopt.length;i++) {
					sOpt += getopt[i]=='eq' ? "<option value='eq'>"+p.odata[0]+"</option>" : "";
					sOpt += getopt[i]=='ne' ? "<option value='ne'>"+p.odata[1]+"</option>" : "";
					sOpt += getopt[i]=='lt' ? "<option value='lt'>"+p.odata[2]+"</option>" : "";
					sOpt += getopt[i]=='le' ? "<option value='le'>"+p.odata[3]+"</option>" : "";
					sOpt += getopt[i]=='gt' ? "<option value='gt'>"+p.odata[4]+"</option>" : "";
					sOpt += getopt[i]=='ge' ? "<option value='ge'>"+p.odata[5]+"</option>" : "";
					sOpt += getopt[i]=='bw' ? "<option value='bw'>"+p.odata[6]+"</option>" : "";
					sOpt += getopt[i]=='ew' ? "<option value='ew'>"+p.odata[7]+"</option>" : "";
					sOpt += getopt[i]=='cn' ? "<option value='cn'>"+p.odata[8]+"</option>" : "";
				};
				sOpt += "</select>";
				// field and buttons
				var sField  = "<input id='sval' class='search' type='text' size='20' maxlength='100'/>";
				var bSearch = "<input id='sbut' class='buttonsearch' type='button' value='"+p.Find+"'/>";
				var bReset  = "<input id='sreset' class='buttonsearch' type='button' value='"+p.Reset+"'/>";
				var cnt = $("<table width='100%'><tbody><tr style='display:none' id='srcherr'><td colspan='5'></td></tr><tr><td>"+cNames+"</td><td>"+sOpt+"</td><td>"+sField+"</td><td>"+bSearch+"</td><td>"+bReset+"</td></tr></tbody></table>");
				createModal(IDs,cnt,p,$t.grid.hDiv,$t.grid.hDiv);
				if ( $.isFunction('onInitializeSearch') ) { onInitializeSearch( $("#srchcnt"+gID) ); };
				if ( $.isFunction('beforeShowSearch') ) { beforeShowSearch($("#srchcnt"+gID)); };
				viewModal("#"+IDs.themodal,{modal:p.modal});
				if($.isFunction('afterShowSearch')) { afterShowSearch($("#srchcnt"+gID)); }
				if(p.drag) { DnRModal("#"+IDs.themodal,"#"+IDs.modalhead+" td.modaltext"); }
				$("#sbut","#"+IDs.themodal).click(function(){
					if( $("#sval","#"+IDs.themodal).val() !="" ) {
						var es=[true,"",""];
						$("#srcherr >td","#srchcnt"+gID).html("").hide();
						$t.p.searchdata[p.sField] = $("option[@selected]","#snames").val();
						$t.p.searchdata[p.sOper] = $("option[@selected]","#sopt").val();
						$t.p.searchdata[p.sValue] = $("#sval","#"+IDs.modalcontent).val();
						if(p.checkInput) {
							for(var i=0; i< cM.length;i++) {
								var sname = (cM[i].index) ? cM[i].index : nm;
								if (sname == $t.p.searchdata[p.sField]) {
									break;
								}
							}
							es = checkValues($t.p.searchdata[p.sValue],i,$t);
						}
						if (es[0]===true) {
							$t.p.search = true; // initialize the search
							// construct array of data which is passed in populate() see jqGrid
							if(p.dirty) { $(".no-dirty-cell",$t.p.pager).addClass("dirty-cell"); }
							$t.p.page= 1;
							$($t).trigger("reloadGrid");
						} else {
							$("#srcherr >td","#srchcnt"+gID).html(es[1]).show();
						}
					}
				});
				$("#sreset","#"+IDs.themodal).click(function(){
					if ($t.p.search) {
						$("#srcherr >td","#srchcnt"+gID).html("").hide();
						$t.p.search = false;
						$t.p.searchdata = {};
						$t.p.page= 1;
						$("#sval","#"+IDs.themodal).val("");
						if(p.dirty) { $(".no-dirty-cell",$t.p.pager).removeClass("dirty-cell"); }
						$($t).trigger("reloadGrid");
					}
				});
			}
		});
	},
	editGridRow : function(rowid, p){
		p = $.extend({
			top : 0,
			left: 0,
			width: 0,
			height: 0,
			modal: false,
			drag: true, 
			closeicon: 'ico-close.gif',
			imgpath: '',
			url: null,
			mtype : "POST",
			closeAfterAdd : false,
			clearAfterAdd : true,
			closeAfterEdit : false,
			reloadAfterSubmit : true,
			onInitializeForm: null,
			beforeInitData: null,
			beforeShowForm: null,
			afterShowForm: null,
			beforeSubmit: null,
			afterSubmit: null,
			onclickSubmit: null,
			editData : {},
			recreateForm : false
		}, $.jgrid.edit, p || {});
		rp_ge = p;
		return this.each(function(){
			var $t = this;
			if (!$t.grid || !rowid) { return; }
			if(!p.imgpath) { p.imgpath= $t.p.imgpath; }
			// I hate to rewrite code, but ...
			var gID = $("table:first",$t.grid.bDiv).attr("id");
			var IDs = {themodal:'editmod'+gID,modalhead:'edithd'+gID,modalcontent:'editcnt'+gID};
			var onBeforeShow = $.isFunction(rp_ge.beforeShowForm) ? rp_ge.beforeShowForm : false;
			var onAfterShow = $.isFunction(rp_ge.afterShowForm) ? rp_ge.afterShowForm : false;
			var onBeforeInit = $.isFunction(rp_ge.beforeInitData) ? rp_ge.beforeInitData : false;
			var onInitializeForm = $.isFunction(rp_ge.onInitializeForm) ? rp_ge.onInitializeForm : false;
			if (rowid=="new") {
				rowid = "_empty";
				p.caption=p.addCaption;
			} else {
				p.caption=p.editCaption;
			};
			var frmgr = "FrmGrid_"+gID;
			var frmtb = "TblGrid_"+gID;
			if(p.recreateForm===true && $("#"+IDs.themodal).html() != null) {
				$("#"+IDs.themodal).remove();
			}
			if ( $("#"+IDs.themodal).html() != null ) {
				$(".modaltext","#"+IDs.modalhead).html(p.caption);
				$("#FormError","#"+frmtb).hide();
				if(onBeforeInit) { onBeforeInit($("#"+frmgr)); }
				fillData(rowid,$t);
				if(rowid=="_empty") { $("#pData, #nData","#"+frmtb).hide(); } else { $("#pData, #nData","#"+frmtb).show(); }
				if(onBeforeShow) { onBeforeShow($("#"+frmgr)); }
				viewModal("#"+IDs.themodal,{modal:p.modal});
				if(onAfterShow) { onAfterShow($("#"+frmgr)); }
			} else {
				var frm = $("<form name='FormPost' id='"+frmgr+"' class='FormGrid'></form>");
				var tbl =$("<table id='"+frmtb+"' class='EditTable' cellspacing='0' cellpading='0' border='0'><tbody></tbody></table>");
				$(frm).append(tbl);
				$(tbl).append("<tr id='FormError' style='display:none'><td colspan='2'>"+"&nbsp;"+"</td></tr>");
				// set the id.
				// use carefull only to change here colproperties.
				if(onBeforeInit) { onBeforeInit($("#"+frmgr)); }
				var valref = createData(rowid,$t,tbl);
				// buttons at footer
				var imp = $t.p.imgpath;
				var bP  ="<img id='pData' src='"+imp+$t.p.previmg+"'/>";
				var bN  ="<img id='nData' src='"+imp+$t.p.nextimg+"'/>";
				var bS  ="<input id='sData' type='button' class='EditButton' value='"+p.bSubmit+"'/>";
				var bC  ="<input id='cData' type='button'  class='EditButton' value='"+p.bCancel+"'/>";
				$(tbl).append("<tr id='Act_Buttons'><td class='navButton'>"+bP+"&nbsp;"+bN+"</td><td class='EditButton'>"+bS+"&nbsp;"+bC+"</td></tr>");
				// beforeinitdata after creation of the form
				createModal(IDs,frm,p,$t.grid.hDiv,$t.grid.hDiv);
				// here initform - only once
				if(onInitializeForm) { onInitializeForm($("#"+frmgr)); }
				if( p.drag ) { DnRModal("#"+IDs.themodal,"#"+IDs.modalhead+" td.modaltext"); }
				if(rowid=="_empty") { $("#pData,#nData","#"+frmtb).hide(); } else { $("#pData,#nData","#"+frmtb).show(); }
				if(onBeforeShow) { onBeforeShow($("#"+frmgr)); }
				viewModal("#"+IDs.themodal,{modal:p.modal});
				if(onAfterShow) { onAfterShow($("#"+frmgr)); }
				$("#sData", "#"+frmtb).click(function(e){
					var postdata = {}, ret=[true,"",""], extpost={};
					$("#FormError","#"+frmtb).hide();
					// all depend on ret array
					//ret[0] - succes
					//ret[1] - msg if not succes
					//ret[2] - the id  that will be set if reload after submit false
					var j =0;
					$(".FormElement", "#"+frmtb).each(function(i){
						var suc =  true;
						switch ($(this).get(0).type) {
							case "checkbox":
								if($(this).attr("checked")) {
									postdata[this.name]= $(this).val();
								}else {
									postdata[this.name]= "";
									extpost[this.name] = $(this).attr("offval");
								}
							break;
							case "select-one":
								postdata[this.name]= $("option:selected",this).val();
								extpost[this.name]= $("option:selected",this).text();
							break;
							case "select-multiple":
								postdata[this.name]= $(this).val();
								var selectedText = [];
								$("option:selected",this).each(
									function(i,selected){
										selectedText[i] = $(selected).text();
									}
								);
								extpost[this.name]= selectedText.join(",");
							break;								
							case "password":
							case "text":
							case "textarea":
								postdata[this.name] = $(this).val();
								ret = checkValues($(this).val(),valref[i],$t);
								if(ret[0] === false) { suc=false; }
							break;
						}
						j++;
						if(!suc) { return false; }
					});
					if(j==0) { ret[0] = false; ret[1] = $.jgrid.errors.norecords; }
					if( $.isFunction( rp_ge.onclickSubmit)) { p.editData = rp_ge.onclickSubmit(p) || {}; }
					if(ret[0]) {
						if( $.isFunction(rp_ge.beforeSubmit))  { ret = rp_ge.beforeSubmit(postdata,$("#"+frmgr)); }
					}
					var gurl = p.url ? p.url : $t.p.editurl;
					if(ret[0]) {
						if(!gurl) { ret[0]=false; ret[1] += " "+$.jgrid.errors.nourl; }
					}
					if(ret[0] === false) {
						$("#FormError>td","#"+frmtb).html(ret[1]);
						$("#FormError","#"+frmtb).show();
					} else {
						if(!p.processing) {
							p.processing = true;
							$("div.loading","#"+IDs.themodal).fadeIn("fast");
							$(this).attr("disabled",true);
							// we add to pos data array the action - the name is oper
							postdata.oper = postdata.id == "_empty" ? "add" : "edit";
							postdata = $.extend(postdata,p.editData);
							$.ajax({
								url:gurl,
								type: p.mtype,
								data:postdata,
								complete:function(data,Status){
									if(Status != "success") {
										ret[0] = false;
										ret[1] = Status+" Status: "+data.statusText +" Error code: "+data.status;
									} else {
										// data is posted successful
										// execute aftersubmit with the returned data from server
										if( $.isFunction(rp_ge.afterSubmit) ) {
											ret = rp_ge.afterSubmit(data,postdata);
										}
									}
									if(ret[0] === false) {
										$("#FormError>td","#"+frmtb).html(ret[1]);
										$("#FormError","#"+frmtb).show();
									} else {
										postdata = $.extend(postdata,extpost);
										// the action is add
										if(postdata.id=="_empty" ) {
											//id processing
											// user not set the id ret[2]
											if(!ret[2]) { ret[2] = parseInt($($t).getGridParam('records'))+1; }
											postdata.id = ret[2];
											if(p.closeAfterAdd) {
												if(rp_ge.reloadAfterSubmit) { $($t).trigger("reloadGrid"); }
												else { $($t).addRowData(ret[2],postdata,"first"); }
												$("#"+IDs.themodal).jqmHide();
											} else if (rp_ge.clearAfterAdd) {
												if(rp_ge.reloadAfterSubmit) { $($t).trigger("reloadGrid"); }
												else { $($t).addRowData(ret[2],postdata,"first"); }
												$(".FormElement", "#"+frmtb).each(function(i){
													switch ($(this).get(0).type) {
													case "checkbox":
														$(this).attr("checked",0);
														break;
													case "select-one":
													case "select-multiple":
														$("option",this).attr("selected","");
														break;
														case "password":
														case "text":
														case "textarea":
															if(this.name =='id') { $(this).val("_empty"); }
															else { $(this).val(""); }
														break;
													}
												});
											} else {
												if(rp_ge.reloadAfterSubmit) { $($t).trigger("reloadGrid"); }
												else { $($t).addRowData(ret[2],postdata,"first"); }
											}
										} else {
											// the action is update
											if(rp_ge.reloadAfterSubmit) {
												$($t).trigger("reloadGrid");
												if( !rp_ge.closeAfterEdit ) { $($t).setSelection(postdata.id); }
											} else {
												if($t.p.treeGrid === true) {
													$($t).setTreeRow(postdata.id,postdata);
												} else {
													$($t).setRowData(postdata.id,postdata);
												}
											}
											if(rp_ge.closeAfterEdit) { $("#"+IDs.themodal).jqmHide(); }
										}
									}
									p.processing=false;
									$("#sData", "#"+frmtb).attr("disabled",false);
									$("div.loading","#"+IDs.themodal).fadeOut("fast");
								}
							});
						}
					}
					e.stopPropagation();
				});
				$("#cData", "#"+frmtb).click(function(e){
					$("#"+IDs.themodal).jqmHide();
					e.stopPropagation();
				});
				$("#nData", "#"+frmtb).click(function(e){
					$("#FormError","#"+frmtb).hide();
					var npos = getCurrPos();
					npos[0] = parseInt(npos[0]);
					if(npos[0] != -1 && npos[1][npos[0]+1]) {
						fillData(npos[1][npos[0]+1],$t);
						$($t).setSelection(npos[1][npos[0]+1]);
						updateNav(npos[0]+1,npos[1].length-1);
					};
					return false;
				});
				$("#pData", "#"+frmtb).click(function(e){
					$("#FormError","#"+frmtb).hide();
					var ppos = getCurrPos();
					if(ppos[0] != -1 && ppos[1][ppos[0]-1]) {
						fillData(ppos[1][ppos[0]-1],$t);
						$($t).setSelection(ppos[1][ppos[0]-1]);
						updateNav(ppos[0]-1,ppos[1].length-1);
					};
					return false;
				});
			};
			var posInit =getCurrPos();
			updateNav(posInit[0],posInit[1].length-1);
			function updateNav(cr,totr,rid){                
				var imp = $t.p.imgpath;
				if (cr==0) { $("#pData","#"+frmtb).attr("src",imp+"off-"+$t.p.previmg); } else { $("#pData","#"+frmtb).attr("src",imp+$t.p.previmg); }
				if (cr==totr) { $("#nData","#"+frmtb).attr("src",imp+"off-"+$t.p.nextimg); } else { $("#nData","#"+frmtb).attr("src",imp+$t.p.nextimg); }
			};
			function getCurrPos() {
				var rowsInGrid = $($t).getDataIDs();
				var selrow = $("#id_g","#"+frmtb).val();
				var pos = $.inArray(selrow,rowsInGrid);
				return [pos,rowsInGrid];
			};
			function createData(rowid,obj,tb){
				var nm, hc,trdata, tdl, tde, cnt=0,tmp, dc,elc, retpos=[];
				$('#'+rowid+' td',obj.grid.bDiv).each( function(i) {
					nm = obj.p.colModel[i].name;
					// hidden fields are included in the form
					if(obj.p.colModel[i].editrules && obj.p.colModel[i].editrules.edithidden == true) {
						hc = false;
					} else {
						hc = obj.p.colModel[i].hidden === true ? true : false;
					}
					dc = hc ? "style='display:none'" : "";
					if ( nm !== 'cb' && nm !== 'subgrid' && obj.p.colModel[i].editable===true) {
						if(nm == obj.p.ExpandColumn && obj.p.treeGrid === true) {
							tmp = $(this).text();
						} else {
							tmp = $(this).html().replace(/\&nbsp\;/ig,'');
						}
						var opt = $.extend(obj.p.colModel[i].editoptions || {} ,{id:nm,name:nm});
						if(!obj.p.colModel[i].edittype) obj.p.colModel[i].edittype = "text";
						elc = createEl(obj.p.colModel[i].edittype,opt,tmp);
						$(elc).addClass("FormElement");
						trdata = $("<tr "+dc+"></tr>").addClass("FormData");
						tdl = $("<td></td>").addClass("CaptionTD");
						tde = $("<td></td>").addClass("DataTD");
						$(tdl).html(obj.p.colNames[i]+": ");
						$(tde).append(elc);
						trdata.append(tdl);
						trdata.append(tde);
						if(tb) { $(tb).append(trdata); }
						else { $(trdata).insertBefore("#Act_Buttons"); }
						retpos[cnt] = i;
						cnt++;
					};
				});
				if( cnt > 0) {
					var idrow = $("<tr class='FormData' style='display:none'><td class='CaptionTD'>"+"&nbsp;"+"</td><td class='DataTD'><input class='FormElement' id='id_g' type='text' name='id' value='"+rowid+"'/></td></tr>");
					if(tb) { $(tb).append(idrow); }
					else { $(idrow).insertBefore("#Act_Buttons"); }
				}
				return retpos;
			};
			function fillData(rowid,obj){
				var nm, hc,cnt=0,tmp;
				$('#'+rowid+' td',obj.grid.bDiv).each( function(i) {
					nm = obj.p.colModel[i].name;
					// hidden fields are included in the form
					if(obj.p.colModel[i].editrules && obj.p.colModel[i].editrules.edithidden === true) {
						hc = false;
					} else {
						hc = obj.p.colModel[i].hidden === true ? true : false;
					}
					if ( nm !== 'cb' && nm !== 'subgrid' && obj.p.colModel[i].editable===true) {
						if(nm == obj.p.ExpandColumn && obj.p.treeGrid === true) {
							tmp = $(this).text();
						} else {
							tmp = $(this).html().replace(/\&nbsp\;/ig,'');
						}
						switch (obj.p.colModel[i].edittype) {
							case "password":
							case "text":
							case "textarea":
								$("#"+nm,"#"+frmtb).val(tmp);
								break;
							case "select":
								$("#"+nm+" option","#"+frmtb).each(function(j){
									if (!obj.p.colModel[i].editoptions.multiple && tmp == $(this).text() ){
										this.selected= true;
									} else if (obj.p.colModel[i].editoptions.multiple){
										if(  $.inArray($(this).text(), tmp.split(",") ) > -1  ){
											this.selected = true;
										}else{
											this.selected = false;
										}
									} else {
										this.selected = false;
									}
								});
								break;
							case "checkbox":
								if(tmp==$("#"+nm,"#"+frmtb).val()) {
									$("#"+nm,"#"+frmtb).attr("checked",true);
									$("#"+nm,"#"+frmtb).attr("defaultChecked",true); //ie
								} else {
									$("#"+nm,"#"+frmtb).attr("checked",false);
									$("#"+nm,"#"+frmtb).attr("defaultChecked",""); //ie
								}
								break; 
						}
						if (hc) { $("#"+nm,"#"+frmtb).parents("tr:first").hide(); }
						cnt++;
					}
				});
				if(cnt>0) { $("#id_g","#"+frmtb).val(rowid); }
				else { $("#id_g","#"+frmtb).val(""); }
				return cnt;
			};
		});
	},
	delGridRow : function(rowids,p) {
		p = $.extend({
			top : 0,
			left: 0,
			width: 240,
			height: 90,
			modal: false,
			drag: true, 
			closeicon: 'ico-close.gif',
			imgpath: '',
			url : '',
			mtype : "POST",
			reloadAfterSubmit: true,
			beforeShowForm: null,
			afterShowForm: null,
			beforeSubmit: null,
			onclickSubmit: null,
			afterSubmit: null,
			onclickSubmit: null,
			delData: {}
		}, $.jgrid.del, p ||{});
		return this.each(function(){
			var $t = this;
			if (!$t.grid ) { return; }
			if(!rowids) { return; }
			if(!p.imgpath) { p.imgpath= $t.p.imgpath; }
			var onBeforeShow = typeof p.beforeShowForm === 'function' ? true: false;
			var onAfterShow = typeof p.afterShowForm === 'function' ? true: false;
			if (isArray(rowids)) { rowids = rowids.join(); }
			var gID = $("table:first",$t.grid.bDiv).attr("id");
			var IDs = {themodal:'delmod'+gID,modalhead:'delhd'+gID,modalcontent:'delcnt'+gID};
			var dtbl = "DelTbl_"+gID;
			if ( $("#"+IDs.themodal).html() != null ) {
				$("#DelData>td","#"+dtbl).text(rowids);
				$("#DelError","#"+dtbl).hide();
				if(onBeforeShow) { p.beforeShowForm($("#"+dtbl)); }
				viewModal("#"+IDs.themodal,{modal:p.modal});
				if(onAfterShow) { p.afterShowForm($("#"+dtbl)); }
			} else {
				var tbl =$("<table id='"+dtbl+"' class='DelTable'><tbody></tbody></table>");
				// error data 
				$(tbl).append("<tr id='DelError' style='display:none'><td >"+"&nbsp;"+"</td></tr>");
				$(tbl).append("<tr id='DelData' style='display:none'><td >"+rowids+"</td></tr>");
				$(tbl).append("<tr><td >"+p.msg+"</td></tr>");
				// buttons at footer
				var bS  ="<input id='dData' type='button' value='"+p.bSubmit+"'/>";
				var bC  ="<input id='eData' type='button' value='"+p.bCancel+"'/>";
				$(tbl).append("<tr><td class='DelButton'>"+bS+"&nbsp;"+bC+"</td></tr>");
				createModal(IDs,tbl,p,$t.grid.hDiv,$t.grid.hDiv);
				if( p.drag) { DnRModal("#"+IDs.themodal,"#"+IDs.modalhead+" td.modaltext"); }
				$("#dData","#"+dtbl).click(function(e){
					var ret=[true,""];
					var postdata = $("#DelData>td","#"+dtbl).text(); //the pair is name=val1,val2,...
					if( typeof p.onclickSubmit === 'function' ) { p.delData = p.onclickSubmit(p) || {}; }
					if( typeof p.beforeSubmit === 'function' ) { ret = p.beforeSubmit(postdata); }
					var gurl = p.url ? p.url : $t.p.editurl;
					if(!gurl) { ret[0]=false;ret[1] += " "+$.jgrid.errors.nourl;}
					if(ret[0] === false) {
						$("#DelError>td","#"+dtbl).html(ret[1]);
						$("#DelError","#"+dtbl).show();
					} else {
						if(!p.processing) {
							p.processing = true;
							$("div.loading","#"+IDs.themodal).fadeIn("fast");
							$(this).attr("disabled",true);
							var postd = $.extend({oper:"del", id:postdata},p.delData);
							$.ajax({
								url:gurl,
								type: p.mtype,
								data:postd,
								complete:function(data,Status){
									if(Status != "success") {
										ret[0] = false;
										ret[1] = Status+" Status: "+data.statusText +" Error code: "+data.status;
									} else {
										// data is posted successful
										// execute aftersubmit with the returned data from server
										if( typeof p.afterSubmit === 'function' ) {
											ret = p.afterSubmit(data,postdata);
										}
									}
									if(ret[0] === false) {
										$("#DelError>td","#"+dtbl).html(ret[1]);
										$("#DelError","#"+dtbl).show();
									} else {
										if(p.reloadAfterSubmit) {
											if($t.p.treeGrid) {
												$($t).setGridParam({treeANode:0,datatype:$t.p.treedatatype});
											}
											$($t).trigger("reloadGrid");
										} else {
											var toarr = [];
											toarr = postdata.split(",");
											if($t.p.treeGrid===true){
												try {$($t).delTreeNode(toarr[0])} catch(e){}
											} else {
												for(var i=0;i<toarr.length;i++) {
													$($t).delRowData(toarr[i]);
												}
											}
											$t.p.selrow = null;
											$t.p.selarrrow = [];
										}
									}
									p.processing=false;
									$("#dData", "#"+dtbl).attr("disabled",false);
									$("div.loading","#"+IDs.themodal).fadeOut("fast");
									if(ret[0]) { $("#"+IDs.themodal).jqmHide(); }
								}
							});
						}
					}
					return false;
				});
				$("#eData", "#"+dtbl).click(function(e){
					$("#"+IDs.themodal).jqmHide();
					return false;
				});
				if(onBeforeShow) { p.beforeShowForm($("#"+dtbl)); }
				viewModal("#"+IDs.themodal,{modal:p.modal});
				if(onAfterShow) { p.afterShowForm($("#"+dtbl)); }
			}
		});
	},
	navGrid : function (elem, o, pEdit,pAdd,pDel,pSearch) {
		o = $.extend({
			edit: true,
			editicon: "row_edit.gif",

			add: true,
			addicon:"row_add.gif",

			del: true,
			delicon:"row_delete.gif",

			search: true,
			searchicon:"find.gif",

			refresh: true,
			refreshicon:"refresh.gif",
			refreshstate: 'firstpage',

			position : "left",
			closeicon: "ico-close.gif"
		}, $.jgrid.nav, o ||{});
		return this.each(function() {       
			var alertIDs = {themodal:'alertmod',modalhead:'alerthd',modalcontent:'alertcnt'};
			var $t = this;
			if(!$t.grid) { return; }
			if ($("#"+alertIDs.themodal).html() == null) {
				var vwidth;
				var vheight;
				if (typeof window.innerWidth != 'undefined') {
					vwidth = window.innerWidth,
					vheight = window.innerHeight
				} else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0) {
					vwidth = document.documentElement.clientWidth,
					vheight = document.documentElement.clientHeight
				} else {
					vwidth=1024;
					vheight=768;
				}
				createModal(alertIDs,"<div>"+o.alerttext+"</div>",{imgpath:$t.p.imgpath,closeicon:o.closeicon,caption:o.alertcap,top:vheight/2-25,left:vwidth/2-100,width:200,height:50},$t.grid.hDiv,$t.grid.hDiv,true);
				DnRModal("#"+alertIDs.themodal,"#"+alertIDs.modalhead);
			}
			var navTbl = $("<table cellspacing='0' cellpadding='0' border='0' class='navtable'><tbody></tbody></table>").height(20);
			var trd = document.createElement("tr");
			$(trd).addClass("nav-row");
			var imp = $t.p.imgpath;
			var tbd;
			if (o.edit) {
				tbd = document.createElement("td");
				$(tbd).append("&nbsp;").css({border:"none",padding:"0px"});
				trd.appendChild(tbd);
				tbd = document.createElement("td");
				tbd.title = o.edittitle || "";
				$(tbd).append("<table cellspacing='0' cellpadding='0' border='0' class='tbutton'><tr><td><img src='"+imp+o.editicon+"'/></td><td valign='center'>"+o.edittext+"&nbsp;</td></tr></table>")
				.css("cursor","pointer")
				.addClass("nav-button")
				.click(function(){
					var sr = $($t).getGridParam('selrow');
					if (sr) { $($t).editGridRow(sr,pEdit || {}); }
					else { viewModal("#"+alertIDs.themodal); }
					return false;
				})
				.hover( function () {
					$(this).addClass("nav-hover");
					},
					function () {
						$(this).removeClass("nav-hover");
					}
				);
				trd.appendChild(tbd);
				tbd = null;
			}
			if (o.add) {
				tbd = document.createElement("td");
				$(tbd).append("&nbsp;").css({border:"none",padding:"0px"});
				trd.appendChild(tbd);
				tbd = document.createElement("td");
				tbd.title = o.addtitle || "";
				$(tbd).append("<table cellspacing='0' cellpadding='0' border='0' class='tbutton'><tr><td><img src='"+imp+o.addicon+"'/></td><td>"+o.addtext+"&nbsp;</td></tr></table>")
				.css("cursor","pointer")
				.addClass("nav-button")
				.click(function(){
					if (typeof o.addfunc == 'function') {
						o.addfunc();
					} else {
						$($t).editGridRow("new",pAdd || {});
					}
					return false;
				})
				.hover(
					function () {
						$(this).addClass("nav-hover");
					},
					function () {
						$(this).removeClass("nav-hover");
					}
				);
				trd.appendChild(tbd);
				tbd = null;
			}
			if (o.del) {
				tbd = document.createElement("td");
				$(tbd).append("&nbsp;").css({border:"none",padding:"0px"});
				trd.appendChild(tbd);
				tbd = document.createElement("td");
				tbd.title = o.deltitle || "";
				$(tbd).append("<table cellspacing='0' cellpadding='0' border='0' class='tbutton'><tr><td><img src='"+imp+o.delicon+"'/></td><td>"+o.deltext+"&nbsp;</td></tr></table>")
				.css("cursor","pointer")
				.addClass("nav-button")
				.click(function(){
					var dr;
					if($t.p.multiselect) {
						dr = $($t).getGridParam('selarrrow');
						if(dr.length==0) { dr = null; }
					} else {
						dr = $($t).getGridParam('selrow');
					}
					if (dr) { $($t).delGridRow(dr,pDel || {}); }
					else  { viewModal("#"+alertIDs.themodal); }
					return false;
				})
				.hover(
					function () {
						$(this).addClass("nav-hover");
					},
					function () {
						$(this).removeClass("nav-hover");
					}
				);
				trd.appendChild(tbd);
				tbd = null;
			}
			if (o.search) {
				tbd = document.createElement("td");
				$(tbd).append("&nbsp;").css({border:"none",padding:"0px"});
				trd.appendChild(tbd);
				tbd = document.createElement("td");
				if( $(elem)[0] == $t.p.pager[0] ) { pSearch = $.extend(pSearch,{dirty:true}); }
				tbd.title = o.searchtitle || "";
				$(tbd).append("<table cellspacing='0' cellpadding='0' border='0' class='tbutton'><tr><td class='no-dirty-cell'><img src='"+imp+o.searchicon+"'/></td><td>"+o.searchtext+"&nbsp;</td></tr></table>")
				.css({cursor:"pointer"})
				.addClass("nav-button")
				.click(function(){
					$($t).searchGrid(pSearch || {});
					return false;
				})
				.hover(
					function () {
						$(this).addClass("nav-hover");
					},
					function () {
						$(this).removeClass("nav-hover");
					}
				);
				trd.appendChild(tbd);
				tbd = null;
			}
			if (o.refresh) {
				tbd = document.createElement("td");
				$(tbd).append("&nbsp;").css({border:"none",padding:"0px"});
				trd.appendChild(tbd);
				tbd = document.createElement("td");
				tbd.title = o.refreshtitle || "";
				var dirtycell =  ($(elem)[0] == $t.p.pager[0] ) ? true : false;
				$(tbd).append("<table cellspacing='0' cellpadding='0' border='0' class='tbutton'><tr><td><img src='"+imp+o.refreshicon+"'/></td><td>"+o.refreshtext+"&nbsp;</td></tr></table>")
				.css("cursor","pointer")
				.addClass("nav-button")
				.click(function(){
					$t.p.search = false;
					switch (o.refreshstate) {
						case 'firstpage':
							$t.p.page=1;
							$($t).trigger("reloadGrid");
							break;
						case 'current':
							var sr = $t.p.multiselect===true ? selarrrow : $t.p.selrow;
							$($t).setGridParam({gridComplete: function() {
								if($t.p.multiselect===true) {
									if(sr.length>0) {
										for(var i=0;i<sr.length;i++){
											$($t).setSelection(sr[i]);
										}
									}
								} else {
									if(sr) {
										$($t).setSelection(sr);
									}
								}
							}});
							$($t).trigger("reloadGrid");
							break;
					}
					if (dirtycell) { $(".no-dirty-cell",$t.p.pager).removeClass("dirty-cell"); }
					if(o.search) {
						var gID = $("table:first",$t.grid.bDiv).attr("id");
						$("#sval",'#srchcnt'+gID).val("");
					}
					return false;
				})
				.hover(
					function () {
						$(this).addClass("nav-hover");
					},
					function () {
						$(this).removeClass("nav-hover");
					}
				);
				trd.appendChild(tbd);
				tbd = null;
			}
			if(o.position=="left") {
				$(navTbl).append(trd).addClass("nav-table-left");
			} else {
				$(navTbl).append(trd).addClass("nav-table-right");
			}
			$(elem).prepend(navTbl);
		});
	},
	navButtonAdd : function (elem, p) {
		p = $.extend({
			caption : "newButton",
			title: '',
			buttonimg : '',
			onClickButton: null,
			position : "last"
		}, p ||{});
		return this.each(function() {
			if( !this.grid)  { return; }
			if( elem.indexOf("#") != 0) { elem = "#"+elem; }
			var findnav = $(".navtable",elem)[0];
			if (findnav) {
				var tdb, tbd1;
				var tbd1 = document.createElement("td");
				$(tbd1).append("&nbsp;").css({border:"none",padding:"0px"});
				var trd = $("tr:eq(0)",findnav)[0];
				if( p.position !='first' ) {
					trd.appendChild(tbd1);
				}
				tbd = document.createElement("td");
				tbd.title = p.title;
				var im = (p.buttonimg) ? "<img src='"+p.buttonimg+"'/>" : "&nbsp;";
				$(tbd).append("<table cellspacing='0' cellpadding='0' border='0' class='tbutton'><tr><td>"+im+"</td><td>"+p.caption+"&nbsp;</td></tr></table>")
				.css("cursor","pointer")
				.addClass("nav-button")
				.click(function(e){
					if (typeof p.onClickButton == 'function') { p.onClickButton(); }
					e.stopPropagation();
					return false;
				})
				.hover(
					function () {
						$(this).addClass("nav-hover");
					},
					function () {
						$(this).removeClass("nav-hover");
					}
				);
				if(p.position != 'first') {
					trd.appendChild(tbd);
				} else {
					$(trd).prepend(tbd);
					$(trd).prepend(tbd1);
				}
				tbd=null;tbd1=null;
			}
		});
	},
	GridToForm : function( rowid, formid ) {
		return this.each(function(){
			var $t = this;
			if (!$t.grid) { return; } 
			var rowdata = $($t).getRowData(rowid);
			if (rowdata) {
				for(var i in rowdata) {
					if ( $("[name="+i+"]",formid).is("input:radio") )  {
						$("[name="+i+"]",formid).each( function() {
							if( $(this).val() == rowdata[i] ) {
								$(this).attr("checked","checked");
							} else {
								$(this).attr("checked","");
							}
						});
					} else {
					// this is very slow on big table and form.
						$("[name="+i+"]",formid).val(rowdata[i]);
					}
				}
			}
		});
	},
	FormToGrid : function(rowid, formid){
		return this.each(function() {
			var $t = this;
			if(!$t.grid) { return; }
			var fields = $(formid).serializeArray();
			var griddata = {};
			$.each(fields, function(i, field){
				griddata[field.name] = field.value;
			});
			$($t).setRowData(rowid,griddata);
		});
	}
});
})(jQuery);
;(function($){
/**
 * jqGrid extension for manipulating Grid Data
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
$.fn.extend({
//Editing
	editRow : function(rowid,keys,oneditfunc,succesfunc, url, extraparam, aftersavefunc,errorfunc) {
		return this.each(function(){
			var $t = this, nm, tmp, editable, cnt=0, focus=null, svr=[];
			if (!$t.grid ) { return; }
			var sz, ml,hc;
			if( !$t.p.multiselect ) {
				editable = $("#"+rowid,$t.grid.bDiv).attr("editable") || "0";
				if (editable == "0") {
					$('#'+rowid+' td',$t.grid.bDiv).each( function(i) {						
						nm = $t.p.colModel[i].name;
						hc = $t.p.colModel[i].hidden===true ? true : false;
						tmp = $(this).html().replace(/\&nbsp\;/ig,'');
						svr[nm]=tmp;
						if ( nm !== 'cb' && nm !== 'subgrid' && $t.p.colModel[i].editable===true && !hc) {
							if(focus===null) { focus = i; }
							$(this).html("");
							var opt = $.extend($t.p.colModel[i].editoptions || {} ,{id:rowid+"_"+nm,name:nm});
							if(!$t.p.colModel[i].edittype) { $t.p.colModel[i].edittype = "text"; }
							var elc = createEl($t.p.colModel[i].edittype,opt,tmp,$(this));
							$(elc).addClass("editable");
							$(this).append(elc);
							//Agin IE
							if($t.p.colModel[i].edittype == "select" && $t.p.colModel[i].editoptions.multiple===true && $.browser.msie) {
								$(elc).width($(elc).width());
							}
							cnt++;
						}
					});
					if(cnt > 0) {
						svr['id'] = rowid; $t.p.savedRow.push(svr);
						$('#'+rowid,$t.grid.bDiv).attr("editable","1");
						$('#'+rowid+" td:eq("+focus+") input",$t.grid.bDiv).focus();
						if(keys===true) {
							$('#'+rowid,$t.grid.bDiv).bind("keydown",function(e) {
								if (e.keyCode === 27) { $($t).restoreRow(rowid); }
								if (e.keyCode === 13) {
									$($t).saveRow(rowid,succesfunc, url, extraparam, aftersavefunc,errorfunc);
								}
								e.stopPropagation();
							});
						}
						if( typeof oneditfunc === "function") { oneditfunc(rowid); }
					}
				}
			}
		});
	},
	saveRow : function(rowid, succesfunc, url, extraparam, aftersavefunc,errorfunc) {
		return this.each(function(){
		var $t = this, nm, tmp={}, tmp2, editable, fr;
		if (!$t.grid ) { return; }
		editable = $('#'+rowid,$t.grid.bDiv).attr("editable");
		url = url ? url : $t.p.editurl;
		if (editable==="1" && url) {
			$('#'+rowid+" td",$t.grid.bDiv).each(function(i) {
				nm = $t.p.colModel[i].name;
				if ( nm !== 'cb' && nm !== 'subgrid' && $t.p.colModel[i].editable===true) {
					if( $t.p.colModel[i].hidden===true) { tmp[nm] = $(this).html(); }
					else {
						switch ($t.p.colModel[i].edittype) {
							case "checkbox":
								tmp[nm]=  $("input",this).attr("checked") ? 1 : 0; 
								break;
							case 'text':
							case 'password':
								tmp[nm]= $("input",this).val();
								break;
							case 'textarea':
								tmp[nm]= $("textarea",this).val();
								break;
							case 'select':
								if(!$t.p.colModel[i].editoptions.multiple) {
									tmp[nm] = $("select>option:selected",this).val();
								} else {
									var sel = $("select",this);
									tmp[nm] = $(sel).val();
								}
								break;
						}
					}
				}
			});
			if(tmp) { tmp["id"] = rowid; if(extraparam) { $.extend(tmp,extraparam);} }
			if(!$t.grid.hDiv.loading) {
				$t.grid.hDiv.loading = true;
				$("div.loading",$t.grid.hDiv).fadeIn("fast");
				$.ajax({url:url,
					data: tmp,
					type: "POST",
					complete: function(res,stat){
						if (stat === "success"){
							var ret;
							if( typeof succesfunc === "function") { ret = succesfunc(res); }
							else ret = true;
							if (ret===true) {
								$('#'+rowid+" td",$t.grid.bDiv).each(function(i) {
									nm = $t.p.colModel[i].name;
									if ( nm !== 'cb' && nm !== 'subgrid' && $t.p.colModel[i].editable===true) {
										switch ($t.p.colModel[i].edittype) {
											case "select":
												if(!$t.p.colModel[i].editoptions.multiple) {
													tmp2 = $("select>option:selected", this).text();
												} else if( $t.p.colModel[i].editoptions.multiple ===true) {
													var selectedText = [];
													$("select > option:selected",this).each(
														function(i,selected){
															selectedText[i] = $(selected).text();
														}
													);
													tmp2= selectedText.join(",");
												}
												break;
											case "checkbox":
												var cbv = $t.p.colModel[i].editoptions.value.split(":") || ["Yes","No"];
												tmp2 = $("input",this).attr("checked") ? cbv[0] : cbv[1];
												break;
											case "password":
											case "text":
											case "textarea":
												tmp2 = $("input, textarea", this).val();
												break;
										}
										$(this).empty();
										$(this).html(tmp2 || "&nbsp;");
									}
								});
								$('#'+rowid,$t.grid.bDiv).attr("editable","0");
								for( var k=0;k<$t.p.savedRow.length;k++) {
									if( $t.p.savedRow[k].id===rowid) {fr = k; break;}
								};
								if(fr >= 0) { $t.p.savedRow.splice(fr,1); }
								if( typeof aftersavefunc === "function") { aftersavefunc(rowid,res.responseText); }
							} else { $($t).restoreRow(rowid); }
						}
					},
					error:function(res,stat){
						if(typeof errorfunc == "function") {
							errorfunc(res,stat)
						} else {
							alert("Error Row: "+rowid+" Result: " +res.status+":"+res.statusText+" Status: "+stat);
						}
					}
				});
				$t.grid.hDiv.loading = false;
				$("div.loading",$t.grid.hDiv).fadeOut("fast");
				$("#"+rowid,$t.grid.bDiv).unbind("keydown");
			}
		}
		});
	},
	restoreRow : function(rowid) {
		return this.each(function(){
			var $t= this, nm, fr;
			if (!$t.grid ) { return; }
			for( var k=0;k<$t.p.savedRow.length;k++) {
				if( $t.p.savedRow[k].id===rowid) {fr = k; break;}
			};
			if(fr >= 0) {
				$('#'+rowid+" td",$t.grid.bDiv).each(function(i) {
					nm = $t.p.colModel[i].name;
					if ( nm !== 'cb' && nm !== 'subgrid') {
						$(this).empty();
						$(this).html($t.p.savedRow[fr][nm] || "&nbsp;");
					}
				});
				$('#'+rowid,$t.grid.bDiv).attr("editable","0");
				$t.p.savedRow.splice(fr,1);
			}
		});
	}
//end inline edit
});
})(jQuery);
;(function($){
/**
 * jqGrid extension for custom methods
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
$.fn.extend({
	getColProp : function(colname){
		var ret ={}, $t = this[0];
		if ( !$t.grid ) { return; }
		var cM = $t.p.colModel;
		for ( var i =0;i<cM.length;i++ ) {
			if ( cM[i].name == colname ) {
				ret = cM[i];
				break;
			}
		};
		return ret;
	},
	setColProp : function(colname, obj){
		//do not set width will not work
		return this.each(function(){
			if ( this.grid ) {
				if ( obj ) {
					var cM = this.p.colModel;
					for ( var i =0;i<cM.length;i++ ) {
						if ( cM[i].name == colname ) {
							$.extend(this.p.colModel[i],obj);
							break;
						}
					}
				}
			}
		});
	},
	sortGrid : function(colname,reload){
		return this.each(function(){
			var $t=this,idx=-1;
			if ( !$t.grid ) { return;}
			if ( !colname ) { colname = $t.p.sortname; }
			for ( var i=0;i<$t.p.colModel.length;i++ ) {
				if ( $t.p.colModel[i].index == colname || $t.p.colModel[i].name==colname ) {
					idx = i;
					break;
				}
			}
			if ( idx!=-1 ){
				var sort = $t.p.colModel[idx].sortable;
				if ( typeof sort !== 'boolean' ) { sort =  true; }
				if ( typeof reload !=='boolean' ) { reload = false; }
				if ( sort ) { $t.sortData(colname, idx, reload); }
			}
		});
	},
	GridDestroy : function () {
		return this.each(function(){
			if ( this.grid ) { 
				if ( this.p.pager ) {
					$(this.p.pager).remove();
				}
				$("#lui_"+this.id).remove();
				$(this.grid.bDiv).remove();
				$(this.grid.hDiv).remove();
				$(this.grid.cDiv).remove();
				if(this.p.toolbar[0]) { $(this.grid.uDiv).remove(); }
				this.p = null;
				this.grid =null;
			}
		});
	},
	GridUnload : function(){
		return this.each(function(){
			if ( !this.grid ) {return;}
			var defgrid = {id: $(this).attr('id'),cl: $(this).attr('class')};
			if (this.p.pager) {
				$(this.p.pager).empty();
			}
			var newtable = document.createElement('table');
			$(newtable).attr({id:defgrid['id']});
			newtable.className = defgrid['cl'];
			$("#lui_"+this.id).remove();
			if(this.p.toolbar[0]) { $(this.grid.uDiv).remove(); }
			$(this.grid.cDiv).remove();
			$(this.grid.bDiv).remove();
			$(this.grid.hDiv).before(newtable).remove();
			this.p = null;
			this.grid =null;
		});
	},
	filterGrid : function(gridid,p){
		p = $.extend({
			gridModel : false,
			gridNames : false,
			gridToolbar : false,
			filterModel: [], // label/name/stype/defval/surl/sopt
			formtype : "horizontal", // horizontal/vertical
			autosearch: true, // if set to false a serch button should be enabled.
			formclass: "filterform",
			tableclass: "filtertable",
			buttonclass: "filterbutton",
			searchButton: "Search",
			clearButton: "Clear",
			enableSearch : false,
			enableClear: false,
			beforeSearch: null,
			afterSearch: null,
			beforeClear: null,
			afterClear: null,
			url : '',
			marksearched: true
		},p  || {});
		return this.each(function(){
			var self = this;
			this.p = p;
			if(this.p.filterModel.length == 0 && this.p.gridModel===false) { alert("No filter is set"); return;}
			if( !gridid) {alert("No target grid is set!"); return;}
			this.p.gridid = gridid.indexOf("#") != -1 ? gridid : "#"+gridid;
			var gcolMod = $(this.p.gridid).getGridParam('colModel');
			if(gcolMod) {
				if( this.p.gridModel === true) {
					var thegrid = $(this.p.gridid)[0];
					// we should use the options search, edittype, editoptions
					// additionally surl and defval can be added in grid colModel
					$.each(gcolMod, function (i,n) {
						var tmpFil = [];
						this.search = this.search ===false ? false : true;
						if( this.search === true && !this.hidden) {
							if(self.p.gridNames===true) {
								tmpFil.label = thegrid.p.colNames[i];
							} else {
								tmpFil.label = '';
							}
							tmpFil.name = this.name;
							tmpFil.index = this.index || this.name;
							// we support only text and selects, so all other to text
							tmpFil.stype = this.edittype || 'text';
							if(tmpFil.stype != 'select' || tmpFil.stype != 'select') {
								tmpFil.stype = 'text';
							}
							tmpFil.defval = this.defval || '';
							tmpFil.surl = this.surl || '';
							tmpFil.sopt = this.editoptions || {};
							tmpFil.width = this.width;
							self.p.filterModel.push(tmpFil);
						}
					});
				} else {
					$.each(self.p.filterModel,function(i,n) {
						for(var j=0;j<gcolMod.length;j++) {
							if(this.name == gcolMod[j].name) {
								this.index = gcolMod[j].index || this.name;
								break;
							}
						}
						if(!this.index) {
							this.index = this.name;
						}
					});
				}
			} else {
				alert("Could not get grid colModel"); return;
			}
			var triggerSearch = function() {
				var sdata={}, j=0, v;
				var gr = $(self.p.gridid)[0];
				if($.isFunction(self.p.beforeSearch)){self.p.beforeSearch();}
				$.each(self.p.filterModel,function(i,n){
					switch (this.stype) {
						case 'select' :
							v = $("select[@name="+this.name+"]",self).val();
							if(v) {
								sdata[this.index] = v;
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).addClass("dirty-cell");
								}
								j++;
							} else {
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).removeClass("dirty-cell");
								}
								// remove from postdata
								try {
									delete gr.p.postData[this.index];
								} catch(e) {}
							}
							break;
						default:
							v = $("input[@name="+this.name+"]",self).val();
							if(v) {
								sdata[this.index] = v;
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).addClass("dirty-cell");
								}
								j++;
							} else {
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).removeClass("dirty-cell");
								}
								// remove from postdata
								try {
									delete gr.p.postData[this.index];
								} catch (e) {}
							}
					}
				});
				var sd =  j>0 ? true : false;
				gr.p.postData = $.extend(gr.p.postData,sdata);
				var saveurl;
				if(self.p.url) {
					saveurl = $(gr).getGridParam('url');
					$(gr).setGridParam({url:self.p.url});
				}
				$(gr).setGridParam({search:sd,page:1}).trigger("reloadGrid");
				if(saveurl) {$(gr).setGridParam({url:saveurl});}
				if($.isFunction(self.p.afterSearch)){self.p.afterSearch();}
			};
			var clearSearch = function(){
				var sdata={}, v, j=0;
				var gr = $(self.p.gridid)[0];
				if($.isFunction(self.p.beforeClear)){self.p.beforeClear();}
				$.each(self.p.filterModel,function(i,n){
					v = (this.defval) ? this.defval : "";
					if(!this.stype){this.stype=='text';}
					switch (this.stype) {
						case 'select' :
							$("select[@name="+this.name+"]",self).val(v);
							if(v) {
								sdata[this.index] = v;
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).addClass("dirty-cell");
								}
								j++;
							} else {
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).removeClass("dirty-cell");
								}
								// remove from postdata
								try {
									delete gr.p.postData[this.index];
								} catch(e) {}
							}
							break;
						case 'text':
							$("input[@name="+this.name+"]",self).val(v);
							if(v) {
								sdata[this.index] = v;
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).addClass("dirty-cell");
								}
								j++;
							} else {
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).removeClass("dirty-cell");
								}
								// remove from postdata
								try {
									delete gr.p.postData[this.index];
								} catch(e) {}
							}
					}
				});
				var sd =  j>0 ? true : false;
				gr.p.postData = $.extend(gr.p.postData,sdata);
				var saveurl;
				if(self.p.url) {
					saveurl = $(gr).getGridParam('url');
					$(gr).setGridParam({url:self.p.url});
				}
				$(gr).setGridParam({search:sd,page:1}).trigger("reloadGrid");
				if(saveurl) {$(gr).setGridParam({url:saveurl});}
				if($.isFunction(self.p.afterClear)){self.p.afterClear();}
			};
			var formFill = function(){
				var tr = document.createElement("tr");
				var tr1, sb, cb,tl,td, td1;
				if(self.p.formtype=='horizontal'){
					$(tbl).append(tr);
				}
				$.each(self.p.filterModel,function(i,n){
					tl = document.createElement("td");
					$(tl).append("<label for='"+this.name+"'>"+this.label+"</label>");
					td = document.createElement("td");
					var $t=this;
					if(!this.stype) { this.stype='text';}
					switch (this.stype)
					{
					case "select":
						if(this.surl) {
							// data returned should have already constructed html select
							$(td).load(this.surl,function(){
								if($t.defval) $("select",this).val($t.defval);
								$("select",this).attr({name:$t.name, id: "sg_"+$t.name});
								if($t.sopt) $("select",this).attr($t.sopt);
								if(self.p.gridToolbar===true && $t.width) {
									$("select",this).width($t.width);
								}
								if(self.p.autosearch===true){
									$("select",this).change(function(e){
										triggerSearch();
										return false;
									});
								}
							});
						} else {
							// sopt to construct the values
							if($t.sopt.value) {
								var so = $t.sopt.value.split(";"), sv, ov;
								var elem = document.createElement("select");
								$(elem).attr({name:$t.name, id: "sg_"+$t.name}).attr($t.sopt);
								for(var k=0; k<so.length;k++){
									sv = so[k].split(":");
									ov = document.createElement("option");
									ov.value = sv[0]; ov.innerHTML = sv[1];
									if (sv[1]==$t.defval) ov.selected ="selected";
									elem.appendChild(ov);
								}
								if(self.p.gridToolbar===true && $t.width) {
									$(elem).width($t.width);
								}
								$(td).append(elem);
								if(self.p.autosearch===true){
									$(elem).change(function(e){
										triggerSearch();
										return false;
									});
								}
							}
						}
						break;
					case 'text':
						var df = this.defval ? this.defval: "";
						$(td).append("<input type='text' name='"+this.name+"' id='sg_"+this.name+"' value='"+df+"'/>");
						if($t.sopt) $("input",td).attr($t.sopt);
						if(self.p.gridToolbar===true && $t.width) {
							if($.browser.msie) {
								$("input",td).width($t.width-4);
							} else {
								$("input",td).width($t.width-2);
							}
						}
						if(self.p.autosearch===true){
							$("input",td).keypress(function(e){
								var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
								if(key == 13){
									triggerSearch();
									return false;
								}
								return this;
							});
						}
						break;
					}
					if(self.p.formtype=='horizontal'){
						if(self.p.grodToolbar===true && self.p.gridNames===false) {
							$(tr).append(td);
						} else {
							$(tr).append(tl).append(td);
						}
						$(tr).append(td);
					} else {
						tr1 = document.createElement("tr");
						$(tr1).append(tl).append(td);
						$(tbl).append(tr1);
					}
				});
				td = document.createElement("td");
				if(self.p.enableSearch === true){
					sb = "<input type='button' id='sButton' class='"+self.p.buttonclass+"' value='"+self.p.searchButton+"'/>";
					$(td).append(sb);
					$("input#sButton",td).click(function(){
						triggerSearch();
						return false;
					});
				}
				if(self.p.enableClear === true) {
					cb = "<input type='button' id='cButton' class='"+self.p.buttonclass+"' value='"+self.p.clearButton+"'/>";
					$(td).append(cb);
					$("input#cButton",td).click(function(){
						clearSearch();
						return false;
					});
				}
				if(self.p.enableClear === true || self.p.enableSearch === true) {
					if(self.p.formtype=='horizontal') {
						$(tr).append(td);
					} else {
						tr1 = document.createElement("tr");
						$(tr1).append("<td>&nbsp;</td>").append(td);
						$(tbl).append(tr1);
					}
				}
			};
			var frm = $("<form name='SearchForm' style=display:inline;' class='"+this.p.formclass+"'></form>");
			var tbl =$("<table class='"+this.p.tableclass+"' cellspacing='0' cellpading='0' border='0'><tbody></tbody></table>");
			$(frm).append(tbl);
			formFill();
			$(this).append(frm);
			this.triggerSearch = function () {triggerSearch();};
			this.clearSearch = function () {clearSearch();};
		});
	}
});
})(jQuery);
;(function($){
/**
 * jqGrid extension
 * Paul Tiseo ptiseo@wasteconsultants.com
 * 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
$.fn.extend({
	getPostData : function(){
		var $t = this[0];
		if(!$t.grid) { return; }
		return $t.p.postData;
	},
	setPostData : function( newdata ) {
		var $t = this[0];
		if(!$t.grid) { return; }
		// check if newdata is correct type
		if ( typeof(newdata) === 'object' ) {
			$t.p.postData = newdata;
		}
		else {
			alert("Error: cannot add a non-object postData value. postData unchanged.");
		}
	},
	appendPostData : function( newdata ) { 
		var $t = this[0];
		if(!$t.grid) { return; }
		// check if newdata is correct type
		if ( typeof(newdata) === 'object' ) {
			$.extend($t.p.postData, newdata);
		}
		else {
			alert("Error: cannot append a non-object postData value. postData unchanged.");
		}
	},
	setPostDataItem : function( key, val ) {
		var $t = this[0];
		if(!$t.grid) { return; }
		$t.p.postData[key] = val;
	},
	getPostDataItem : function( key ) {
		var $t = this[0];
		if(!$t.grid) { return; }
		return $t.p.postData[key];
	},
	removePostDataItem : function( key ) {
		var $t = this[0];
		if(!$t.grid) { return; }
		delete $t.p.postData[key];
	},
	getUserData : function(){
		var $t = this[0];
		if(!$t.grid) { return; }
		return $t.p.userData;
	},
	getUserDataItem : function( key ) {
		var $t = this[0];
		if(!$t.grid) { return; }
		return $t.p.userData[key];
	}
});
})(jQuery);
;(function($){
/**
 * jqGrid extension for manipulating columns properties
 * Piotr Roznicki roznicki@o2.pl
 * http://www.roznicki.prv.pl
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/
$.fn.extend({
	setColumns : function(p) {
		p = $.extend({
			top : 0,
			left: 0,
			width: 200,
			height: 195,
			modal: false,
			drag: true,
			closeicon: 'ico-close.gif',
			beforeShowForm: null,
			afterShowForm: null,
			afterSubmitForm: null
		}, $.jgrid.col, p ||{});
		return this.each(function(){
			var $t = this;
			if (!$t.grid ) { return; }
			var onBeforeShow = typeof p.beforeShowForm === 'function' ? true: false;
			var onAfterShow = typeof p.afterShowForm === 'function' ? true: false;
			var onAfterSubmit = typeof p.afterSubmitForm === 'function' ? true: false;			
			if(!p.imgpath) { p.imgpath= $t.p.imgpath; } // Added From Tony Tomov
			var gID = $("table:first",$t.grid.bDiv).attr("id");
			var IDs = {themodal:'colmod'+gID,modalhead:'colhd'+gID,modalcontent:'colcnt'+gID};
			var dtbl = "ColTbl_"+gID;
			if ( $("#"+IDs.themodal).html() != null ) {
				if(onBeforeShow) { p.beforeShowForm($("#"+dtbl)); }
				viewModal("#"+IDs.themodal,{modal:p.modal});
				if(onAfterShow) { p.afterShowForm($("#"+dtbl)); }
			} else {
				var tbl =$("<table id='"+dtbl+"' class='ColTable'><tbody></tbody></table>");
				for(i=0;i<this.p.colNames.length;i++){
					if(!$t.p.colModel[i].hidedlg) { // added from T. Tomov
						$(tbl).append("<tr><td ><input type='checkbox' id='col_" + this.p.colModel[i].name + "' class='cbox' value='T' " + 
						((this.p.colModel[i].hidden==undefined)?"checked":"") + "/>" +  "<label for='col_" + this.p.colModel[i].name + "'>" + this.p.colNames[i] + "(" + this.p.colModel[i].name + ")</label></td></tr>");
					}
				}
				var bS  ="<input id='dData' type='button' value='"+p.bSubmit+"'/>";
				var bC  ="<input id='eData' type='button' value='"+p.bCancel+"'/>";
				$(tbl).append("<tr><td class='ColButton'>"+bS+"&nbsp;"+bC+"</td></tr>");
				createModal(IDs,tbl,p,$t.grid.hDiv,$t.grid.hDiv);
				if( p.drag) { DnRModal("#"+IDs.themodal,"#"+IDs.modalhead+" td.modaltext"); }
				$("#dData","#"+dtbl).click(function(e){
					for(i=0;i<$t.p.colModel.length;i++){
						if(!$t.p.colModel[i].hidedlg) { // added from T. Tomov
							if($("#col_" + $t.p.colModel[i].name).attr("checked")) {
								$($t).showCol($t.p.colModel[i].name);
								$("#col_" + $t.p.colModel[i].name).attr("defaultChecked",true); // Added from T. Tomov IE BUG
							} else {
								$($t).hideCol($t.p.colModel[i].name);
								$("#col_" + $t.p.colModel[i].name).attr("defaultChecked",""); // Added from T. Tomov IE BUG
							}
						}
					}
					$("#"+IDs.themodal).jqmHide();
					if (onAfterSubmit) { p.afterSubmitForm($("#"+dtbl)); }
					return false;
				});
				$("#eData", "#"+dtbl).click(function(e){
					$("#"+IDs.themodal).jqmHide();
					return false;
				});
				if(onBeforeShow) { p.beforeShowForm($("#"+dtbl)); }
				viewModal("#"+IDs.themodal,{modal:p.modal});
				if(onAfterShow) { p.afterShowForm($("#"+dtbl)); }
			}
		});
	}
});
})(jQuery);
;(function($){
/**
 * jqGrid extension for SubGrid Data
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/
$.fn.addSubGrid = function(t,row,pos,rowelem) {
	return this.each(function(){
		var ts = this;
		if (!ts.grid ) { return; }
		var td, res,_id, pID;
		td = document.createElement("td");
		$(td,t).html("<img src='"+ts.p.imgpath+"plus.gif'/>")
			.toggle( function(e) {
				$(this).html("<img src='"+ts.p.imgpath+"minus.gif'/>");
				pID = $("table:first",ts.grid.bDiv).attr("id");
				res = $(this).parent();
				var atd= pos==1?'<td></td>':'';
				_id = $(res).attr("id");
				var nhc = 0;
				$.each(ts.p.colModel,function(i,v){
					if(this.hidden === true) {nhc++;}
				});
				var subdata = "<tr class='subgrid'>"+atd+"<td><img src='"+ts.p.imgpath+"line3.gif'/></td><td colspan='"+parseInt(ts.p.colNames.length-1-nhc)+"'><div id="+pID+"_"+_id+" class='tablediv'>";
				$(this).parent().after( subdata+ "</div></td></tr>" );
				$(".tablediv",ts).css("width", ts.grid.width-20+"px");
				if( typeof ts.p.subGridRowExpanded === 'function') {
					ts.p.subGridRowExpanded(pID+"_"+ _id,_id);
				} else {
					populatesubgrid(res);
				}
			}, function(e) {
				if( typeof ts.p.subGridRowColapsed === 'function') {
					res = $(this).parent();
					_id = $(res).attr("id");
					ts.p.subGridRowColapsed(pID+"_"+_id,_id );
				};
				$(this).parent().next().remove(".subgrid");
				$(this).html("<img src='"+ts.p.imgpath+"plus.gif'/>");
			});
		row.appendChild(td);
		//-------------------------
		var populatesubgrid = function( rd ) {
			var res,sid,dp;
			sid = $(rd).attr("id");
			dp = {id:sid};
			if(!ts.p.subGridModel[0]) { return false; }
			if(ts.p.subGridModel[0].params) {
				for(var j=0; j < ts.p.subGridModel[0].params.length; j++) {
					for(var i=0; i<ts.p.colModel.length; i++) {
						if(ts.p.colModel[i].name == ts.p.subGridModel[0].params[j]) {
							dp[ts.p.colModel[i].name]= $("td:eq("+i+")",rd).text().replace(/\&nbsp\;/ig,'');
						}
					}
				}
			}
			if(!ts.grid.hDiv.loading) {
				ts.grid.hDiv.loading = true;
				$("div.loading",ts.grid.hDiv).fadeIn("fast");
				switch(ts.p.datatype) {
					case "xml":
					$.ajax({type:ts.p.mtype, url: ts.p.subGridUrl, dataType:"xml",data: dp, complete: function(sxml) { subGridJXml(sxml.responseXML, sid); } });
					break;
					case "json":
					$.ajax({type:ts.p.mtype, url: ts.p.subGridUrl, dataType:"json",data: dp, complete: function(JSON) { res = subGridJXml(JSON,sid); } });
					break;
				}
			}
			return false;
		};
		var subGridCell = function(trdiv,cell,pos){
			var tddiv;
			tddiv = document.createElement("div");
			tddiv.className = "celldiv";
			$(tddiv).html(cell);
			$(tddiv).width( ts.p.subGridModel[0].width[pos] || 80);
			trdiv.appendChild(tddiv);
		};
		var subGridJXml = function(sjxml, sbid){
			var trdiv, tddiv,result = "", i,cur, sgmap;
			var dummy = document.createElement("span");
			trdiv = document.createElement("div");
			trdiv.className="rowdiv";
			for (i = 0; i<ts.p.subGridModel[0].name.length; i++) {
				tddiv = document.createElement("div");
				tddiv.className = "celldivth";
				$(tddiv).html(ts.p.subGridModel[0].name[i]);
				$(tddiv).width( ts.p.subGridModel[0].width[i]);
				trdiv.appendChild(tddiv);
			}
			dummy.appendChild(trdiv);
			if (sjxml){
				if(ts.p.datatype === "xml") {
					sgmap = ts.p.xmlReader.subgrid;
					$(sgmap.root+">"+sgmap.row, sjxml).each( function(){
						trdiv = document.createElement("div");
						trdiv.className="rowdiv";
						if(sgmap.repeatitems === true) {
							$(sgmap.cell,this).each( function(i) {
								subGridCell(trdiv, this.textContent || this.text || '&nbsp;',i);
							});
						} else {
							var f = ts.p.subGridModel[0].mapping;
							if (f) {
								for (i=0;i<f.length;i++) {
									subGridCell(trdiv, $(f[i],this).text() || '&nbsp;',i);
								}
							}
						}
						dummy.appendChild(trdiv);
					});
				} else {
					sjxml = eval("("+sjxml.responseText+")");
					sgmap = ts.p.jsonReader.subgrid;
					for (i=0;i<sjxml[sgmap.root].length;i++) {
						cur = sjxml[sgmap.root][i];
						trdiv = document.createElement("div");
						trdiv.className="rowdiv";
						if(sgmap.repeatitems === true) {
							if(sgmap.cell) { cur=cur[sgmap.cell]; }
							for (var j=0;j<cur.length;j++) {
								subGridCell(trdiv, cur[j] || '&nbsp;',j);
							}
						} else {
							var f = ts.p.subGridModel[0].mapping;
							if(f.length) {
								for (var j=0;j<f.length;j++) {
									subGridCell(trdiv, cur[f[j]] || '&nbsp;',j);
								}
							}
						}
						dummy.appendChild(trdiv);
					}
				}
				var pID = $("table:first",ts.grid.bDiv).attr("id")+"_";
				$("#"+pID+sbid).append($(dummy).html());
				sjxml = null;
				ts.grid.hDiv.loading = false;
				$("div.loading",ts.grid.hDiv).fadeOut("fast");
			}
			return false;
		}
	});
};
})(jQuery);
/*
 Transform a table to a jqGrid.
 Peter Romianowski <peter.romianowski@optivo.de> 
 If the first column of the table contains checkboxes or
 radiobuttons then the jqGrid is made selectable.
*/
// Addition - selector can be a class or id
function tableToGrid(selector) {
$(selector).each(function() {
	if(this.grid) {return;} //Adedd from Tony Tomov
	// This is a small "hack" to make the width of the jqGrid 100%
	$(this).width("99%");
	var w = $(this).width();

	// Text whether we have single or multi select
	var inputCheckbox = $('input[type=checkbox]:first', $(this));
	var inputRadio = $('input[type=radio]:first', $(this));
	var selectMultiple = inputCheckbox.length > 0;
	var selectSingle = !selectMultiple && inputRadio.length > 0;
	var selectable = selectMultiple || selectSingle;
	var inputName = inputCheckbox.attr("name") || inputRadio.attr("name");

	// Build up the columnModel and the data
	var colModel = [];
	var colNames = [];
	$('th', $(this)).each(function() {
		if (colModel.length == 0 && selectable) {
			colModel.push({
				name: '__selection__',
				index: '__selection__',
				width: 0,
				hidden: true
			});
			colNames.push('__selection__');
		} else {
			colModel.push({
				name: $(this).html(),
				index: $(this).html(),
				width: $(this).width() || 150
			});
			colNames.push($(this).html());
		}
	});
	var data = [];
	var rowIds = [];
	var rowChecked = [];
	$('tbody > tr', $(this)).each(function() {
		var row = {};
		var rowPos = 0;
		data.push(row);
		$('td', $(this)).each(function() {
			if (rowPos == 0 && selectable) {
				var input = $('input', $(this));
				var rowId = input.attr("value");
				rowIds.push(rowId || data.length);
				if (input.attr("checked")) {
					rowChecked.push(rowId);
				}
				row[colModel[rowPos].name] = input.attr("value");
			} else {
				row[colModel[rowPos].name] = $(this).html();
			}
			rowPos++;
		});
	});

	// Clear the original HTML table
	$(this).empty();

	// Mark it as jqGrid
	$(this).addClass("scroll");

	$(this).jqGrid({
		datatype: "local",
		width: w,
		colNames: colNames,
		colModel: colModel,
		multiselect: selectMultiple
		//inputName: inputName,
		//inputValueCol: imputName != null ? "__selection__" : null
	});

	// Add data
	for (var a = 0; a < data.length; a++) {
		var id = null;
		if (rowIds.length > 0) {
			id = rowIds[a];
			if (id && id.replace) {
				// We have to do this since the value of a checkbox
				// or radio button can be anything 
				id = encodeURIComponent(id).replace(/[.\-%]/g, "_");
			}
		}
		if (id == null) {
			id = a + 1;
		}
		$(this).addRowData(id, data[a]);
	}

	// Set the selection
	for (var a = 0; a < rowChecked.length; a++) {
		$(this).setSelection(rowChecked[a]);
	}
});
};
;(function($) {
/*
**
 * jqGrid extension - Tree Grid
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
$.fn.extend({
	setTreeNode : function(rd, row){
		return this.each(function(){
			var $t = this;
			if(!$t.grid || !$t.p.treeGrid) { return; }
			var expCol=0,i=0;
			if(!$t.p.expColInd) {
				for (var key in $t.p.colModel){
					if($t.p.colModel[key].name == $t.p.ExpandColumn) {
						expCol = i;
						$t.p.expColInd = expCol;
						break;
					}
					i++;
				}
				if(!$t.p.expColInd ) {$t.p.expColInd = expCol;}
			} else {
				expCol = $t.p.expColInd;
			}
			var level = $t.p.treeReader.level_field;
			var expanded = $t.p.treeReader.expanded_field;
			var isLeaf = $t.p.treeReader.leaf_field;
			row.lft = rd[$t.p.treeReader.left_field];
			row.rgt = rd[$t.p.treeReader.right_field];
			row.level = rd[level];
			if(!rd[isLeaf]) {
				// NS Model
				rd[isLeaf] = (parseInt(row.rgt,10) === parseInt(row.lft,10)+1) ? 'true' : 'false';
			}
			var curExpand = (rd[expanded] && rd[expanded] == "true") ? true : false;
			var curLevel = parseInt(row.level,10);
			var ident,lftpos;
			if($t.p.tree_root_level === 0) {
				ident = curLevel+1;
				lftpos = curLevel;
			} else {
				ident = curLevel;
				lftpos = curLevel -1;
			}
			var twrap = document.createElement("div");
			$(twrap).addClass("tree-wrap").width(ident*18);
			var treeimg = document.createElement("div");
			$(treeimg).css("left",lftpos*18);
			twrap.appendChild(treeimg);

			if(rd[isLeaf] == "true") {
				$(treeimg).addClass("tree-leaf");
				row.isLeaf = true;
			} else {
				if(rd[expanded] == "true") {
					$(treeimg).addClass("tree-minus treeclick");
					row.expanded = true;
				} else {
					$(treeimg).addClass("tree-plus treeclick");
					row.expanded = false;
				}
			}
			if(parseInt(rd[level],10) !== parseInt($t.p.tree_root_level,10)) {                
				if(!$($t).isVisibleNode(row)){ 
					$(row).css("display","none");
				}
			}
			var mhtm = $("td:eq("+expCol+")",row).html();
			var thecell = $("td:eq("+expCol+")",row).html("<span>"+mhtm+"</span>").prepend(twrap);
			$(".treeclick",thecell).click(function(e){
				var target = e.target || e.srcElement;
				var ind =$(target,$t.rows).parents("tr:first")[0].rowIndex;
				if(!$t.rows[ind].isLeaf){
					if($t.rows[ind].expanded){
						$($t).collapseRow($t.rows[ind]);
						$($t).collapseNode($t.rows[ind]);
					} else {
						$($t).expandRow($t.rows[ind]);
						$($t).expandNode($t.rows[ind]);
					}
				}
				e.stopPropagation();
			});
		});
	},
	expandRow: function (record){
		this.each(function(){
			var $t = this;
			if(!$t.grid || !$t.p.treeGrid) { return; }
			var childern = $($t).getNodeChildren(record);
			//if ($($t).isVisibleNode(record)) {
			$(childern).each(function(i){
				$(this).css("display","");
				if(this.expanded) {
					$($t).expandRow(this);
				}
			});
			//}
		});
	},
	collapseRow : function (record) {
		this.each(function(){
			var $t = this;
			if(!$t.grid || !$t.p.treeGrid) { return; }
			var childern = $($t).getNodeChildren(record);
			$(childern).each(function(i){
				$(this).css("display","none");
				$($t).collapseRow(this);
			});
		});
	},
	// NS model
	getRootNodes : function() {
		var result = [];
		this.each(function(){
			var $t = this;
			if(!$t.grid || !$t.p.treeGrid) { return; }
			$($t.rows).each(function(i){
				if(parseInt(this.level,10) === parseInt($t.p.tree_root_level,10)) {
					result.push(this);
				}
			});
		});
		return result;
	},
	getNodeDepth : function(rc) {
		var ret = null;
		this.each(function(){
			if(!this.grid || !this.p.treeGrid) { return; }
			ret = parseInt(rc.level,10) - parseInt(this.p.tree_root_level,10);                
		});
		return ret;
	},
	getNodeParent : function(rc) {
		var result = null;
		this.each(function(){
			if(!this.grid || !this.p.treeGrid) { return; }
			var lft = parseInt(rc.lft,10), rgt = parseInt(rc.rgt,10), level = parseInt(rc.level,10);
			$(this.rows).each(function(){
				if(parseInt(this.level,10) === level-1 && parseInt(this.lft) < lft && parseInt(this.rgt) > rgt) {
					result = this;
					return false;
				}
			});
		});
		return result;
	},
	getNodeChildren : function(rc) {
		var result = [];
		this.each(function(){
			if(!this.grid || !this.p.treeGrid) { return; }
			var lft = parseInt(rc.lft,10), rgt = parseInt(rc.rgt,10), level = parseInt(rc.level,10);
			var ind = rc.rowIndex;
			$(this.rows).slice(1).each(function(i){
				if(parseInt(this.level,10) === level+1 && parseInt(this.lft,10) > lft && parseInt(this.rgt,10) < rgt) {
					result.push(this);
				}
			});
		});
		return result;
	},
	// End NS Model
	getNodeAncestors : function(rc) {
		var ancestors = [];
		this.each(function(){
			if(!this.grid || !this.p.treeGrid) { return; }
			var parent = $(this).getNodeParent(rc);
			while (parent) {
				ancestors.push(parent);
				parent = $(this).getNodeParent(parent);	
			}
		});
		return ancestors;
	},
	isVisibleNode : function(rc) {
		var result = true;
		this.each(function(){
			var $t = this;
			if(!$t.grid || !$t.p.treeGrid) { return; }
			var ancestors = $($t).getNodeAncestors(rc);
			$(ancestors).each(function(){
				result = result && this.expanded;
				if(!result) {return false;}
			});
		});
		return result;
	},
	isNodeLoaded : function(rc) {
		var result;
		this.each(function(){
			var $t = this;
			if(!$t.grid || !$t.p.treeGrid) { return; }
			if(rc.loaded !== undefined) {
				result = rc.loaded;
			} else if( rc.isLeaf || $($t).getNodeChildren(rc).length > 0){
				result = true;
			} else {
				result = false;
			}
		});
		return result;
	},
	expandNode : function(rc) {
		return this.each(function(){
			if(!this.grid || !this.p.treeGrid) { return; }
			if(!rc.expanded) {
				if( $(this).isNodeLoaded(rc) ) {
					rc.expanded = true;
					$("div.treeclick",rc).removeClass("tree-plus").addClass("tree-minus");
				} else {
					rc.expanded = true;
					$("div.treeclick",rc).removeClass("tree-plus").addClass("tree-minus");
					this.p.treeANode = rc.rowIndex;
					this.p.datatype = this.p.treedatatype;
					$(this).setGridParam({postData:{nodeid:rc.id,n_left:rc.lft,n_right:rc.rgt,n_level:rc.level}});
					$(this).trigger("reloadGrid");
					this.treeANode = 0;
					$(this).setGridParam({postData:{nodeid:'',n_left:'',n_right:'',n_level:''}})
				}
			}
		});
	},
	collapseNode : function(rc) {
		return this.each(function(){
			if(!this.grid || !this.p.treeGrid) { return; }
			if(rc.expanded) {
				rc.expanded = false;
				$("div.treeclick",rc).removeClass("tree-minus").addClass("tree-plus");
			}
		});
	},
	SortTree : function( newDir) {
		return this.each(function(){
			if(!this.grid || !this.p.treeGrid) { return; }
			var i, len,
			rec, records = [],
			roots = $(this).getRootNodes();
			// Sorting roots
			roots.sort(function(a, b) {
				if (a.sortKey < b.sortKey) {return -newDir;}
				if (a.sortKey > b.sortKey) {return newDir;}
				return 0;
			});
			// Sorting children
			for (i = 0, len = roots.length; i < len; i++) {
				rec = roots[i];
				records.push(rec);
				$(this).collectChildrenSortTree(records, rec, newDir);
			}
			var $t = this;
			$.each(records, function(index, row) {
				$('tbody',$t.grid.bDiv).append(row);
				row.sortKey = null;
			});
		});
	},
	collectChildrenSortTree : function(records, rec, newDir) {
		return this.each(function(){
			if(!this.grid || !this.p.treeGrid) { return; }
			var i, len,
			child, 
			children = $(this).getNodeChildren(rec);
			children.sort(function(a, b) {
				if (a.sortKey < b.sortKey) {return -newDir;}
				if (a.sortKey > b.sortKey) {return newDir;}
				return 0;
			});
			for (i = 0, len = children.length; i < len; i++) {
				child = children[i];
				records.push(child);
				$(this).collectChildrenSortTree(records, child,newDir); 
			}
		});
	},
	// experimental 
	setTreeRow : function(rowid, data) {
		var nm, success=false;
		this.each(function(){
			var t = this;
			if(!t.grid || !t.p.treeGrid) { return false; }
			if( data ) {
				var ind = $(t).getInd(t.rows,rowid);
				if(!ind) {return success;}
				success=true;
				$(this.p.colModel).each(function(i){
					nm = this.name;
					if(data[nm] !== 'undefined') {
						if(nm == t.p.ExpandColumn && t.p.treeGrid===true) {
							$("td:eq("+i+") > span:first",t.rows[ind]).html(data[nm]);
						} else {
							$("td:eq("+i+")",t.rows[ind]).html(data[nm]);
						}
						success = true;
					}
				});
			}
		});
		return success;
	},
	delTreeNode : function (rowid) {
		return this.each(function () {
			var $t = this;
			if(!$t.grid || !$t.p.treeGrid) { return; }
			var rc = $($t).getInd($t.rows,rowid,true);
			if (rc) {
				var dr = $($t).getNodeChildren(rc);
				if(dr.length>0){
					for (var i=0;i<dr.length;i++){
						$($t).delRowData(dr[i].id);
					}
				}
				$($t).delRowData(rc.id);
			}
		});
	}
});
})(jQuery);