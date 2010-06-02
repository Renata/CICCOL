;(function ($) {
$.fn.jqTree = function( url, p ) {
	p = $.extend({
		useDblClicks : false, //not used yet
		saveNodesStateInCookies : true,
		expandedClassName : "",
		collapsedClassName: "collapsed",
		selectedClassName : "selected",
		plusMinusClassName : "plusminus",
		treeClass : "tree",
		imgpath: "",
		collapsedImage : "plus.gif",
		expandedImage : "minus.gif",
		noChildrenImage : "nochild.gif",
		defaultImage : "folder.png",
		xmlCaption : "caption",
		xmlUrl : "url",
		xmlId : "id",
		xmlRetreiveUrl : "retreiveUrl",
		xmlIcon : "icon",
		xmlExpanded : "expanded",
		loadingText : "Loading ...",
		onSelectNode : null,
        params: {}
	}, p || {});

return this.each( function( ) {

	Tree = function() {}

/*
  Private members
*/
	Tree.obj = null;
	Tree.instanceCount = 0;
	Tree.instancePrefix = "alder";
	Tree.cookiePrefix = "alder";
	Tree.dwnldQueue = new Array;
	Tree.dwnldCheckTimeout = 100;

/*
  Interval handler. Ckecks for new nodes loaded.
  Adds loaded nodes to the tree.
*/
	Tree.checkLoad = function () {
	var i, httpReq;
	for (i = 0; i<Tree.dwnldQueue.length; i++)
		if ((httpReq = Tree.dwnldQueue[i][0]).readyState == 4 /*COMPLETED*/)
		{
			var node = Tree.dwnldQueue[i][1];
		// unqueue loaded item
			Tree.dwnldQueue.splice(i, 1);
			Tree.appendLoadedNode(httpReq, node);
			if ($t.p.saveNodesStateInCookies)
				Tree.openAllSaved(Tree.getId(node));
		}
		// will call next time, not all nodes were loaded
	if (Tree.dwnldQueue.length != 0)
		window.setTimeout(Tree.checkLoad, Tree.dwnldCheckTimeout);
	}
/*
  Adds loaded node to tree.
*/
  Tree.appendLoadedNode = function (httpReq, node) {
    // create DomDocument from loaded text
    var xmlDoc = Tree.loadXml(httpReq.responseText);
    // create tree nodes from xml loaded
    var newNode = Tree.convertXml2NodeList(xmlDoc.documentElement);
    // Add loading error handling here must be added
    node.replaceChild(newNode, Tree.getNodeSpan(node));
  }
/*
  Event handler when node is clicked.
  Navigates node link, and makes node selected.
*/
Tree.NodeClick = function (node)
{
//  var node = event.srcElement /*IE*/ || event.target /*DOM*/;
  // <li><a><img> - <img> is capturing the event
//  alert($(node).attr("id"))
//  if ($t.p.onSelectNode) $t.p.onSelectNode( $(node).attr("id"), $(node).attr("title"), node.parentNode.empty )
  while (node.tagName != "A")
    node = node.parentNode;
  node.blur();
  node = node.parentNode;
  Tree.obj = Tree.getObj(node);
  Tree.expandNode(node);
  Tree.selectNode(node);
}

/*
  Event handler when plus/minus icon is clicked.
  Desides whenever node should be expanded or collapsed.
*/
Tree.ExpandCollapseNode = function (event)
{
  var anchorClicked = event.srcElement /*IE*/ || event.target /*DOM*/;
  // <li><a><img> - <img> is capturing the event
  while (anchorClicked.tagName != "A")
    anchorClicked  = anchorClicked.parentNode;
  anchorClicked.blur();
  var node = anchorClicked.parentNode;
  // node has no children, and cannot be expanded or collapsed
  if (node.empty)
    return;
  Tree.obj = Tree.getObj(node);
  if (Tree.isNodeCollapsed(node))
    Tree.expandNode(node);
  else
    Tree.collapseNode(node);
  // cancelling the event to prevent navigation.
  if (event.preventDefault == undefined)
  { // IE
    event.cancelBubble = true;
    event.returnValue = false;
  } // if
  else
  { // DOM
    event.preventDefault();
    event.cancelBubble = true;
  } // else
}

/*
  Determines if specified node is selected.
*/
Tree.isNodeSelected = function (node)
{
  return (node.isSelected == true) || (Tree.obj.selectedNode == node);
}

/*
  Determines if specified node is expanded.
*/
Tree.isNodeExpanded = function (node)
{
  return ($t.p.expandedClassName == node.className) || (node.expanded == true);
}

/*
  Determines if specified node is collapsed.
*/
Tree.isNodeCollapsed = function (node)
{
  return ($t.p.collapsedClassName == node.className) || (node.collapsed == true);
}

/*
  Determines if node currently selected is at same
  level as node specified (has same root).
*/
Tree.isSelectedNodeAtSameLevel = function (node)
{
  if (Tree.obj.selectedNode == null) // no node currently selected
    return false;
  var i, currentNode, children = node.parentNode.childNodes; // all nodes at same level (li->ul->childNodes)
  for (i = 0; i < children.length; i++)
    if ((currentNode = children[i]) != node && Tree.isNodeSelected(currentNode))
      return true;
  return false;
}

/*
  Mark node as selected and unmark prevoiusly selected.
  Node is marked with attribute and <a> is marked with css style
  to avoid mark <li> twise with css style expanded and selected.
*/
Tree.selectNode = function (node)
{
  if (Tree.isNodeSelected(node)) // already marked
    return;
  if (Tree.obj.selectedNode != null)
  {// unmark previously selected node.
    Tree.obj.selectedNode.isSelected = false;
    // remove css style from anchor
    $("A",Tree.obj.selectedNode).get(1).className = "";
  } // if
  // collapse selected node if at same level
  if (Tree.isSelectedNodeAtSameLevel(node))
    Tree.collapseNode(Tree.obj.selectedNode);
  // mark node as selected
  Tree.obj.selectedNode = node;
  node.isSelected = true;
  $("A",node).get(1).className = $t.p.selectedClassName;
  
}

/*
  Expand collapsed node. Loads children nodes if needed.
*/
Tree.expandNode = function (node, avoidSaving)
{
  if (node.empty)
    return;
  $("IMG",node).get(0).src = $t.p.expandedImage;
  node.className = $t.p.expandedClassName;
  node.expanded = true;
  node.collapsed = false;
  if (Tree.getNodeSpan(node) != null)
    Tree.loadChildren(node);
  if ($t.p.saveNodesStateInCookies && !avoidSaving)
    Tree.saveOpenedNode(node);
}

/*
  Collapse expanded node.
*/
Tree.collapseNode = function (node, avoidSaving)
{
  if (node.empty)
    return;
  $("IMG",node).get(0).src = $t.p.collapsedImage;
  node.className = $t.p.collapsedClassName;
  node.collapsed = true;
  node.expanded = false;
  if ($t.p.saveNodesStateInCookies && !avoidSaving)
    Tree.saveClosedNode(node);
}

/*
  Cancel loading children nodes.
*/
Tree.CancelLoad = function (event)
{ 
  var i, node = event.srcElement /*IE*/ || event.target /*DOM*/;
  while (node.tagName != "LI")
    node = node.parentNode;
  // search node in queue
  for (i = 0; i<Tree.dwnldQueue.length; i++)
    if (Tree.dwnldQueue[i][1] == node)
    {
      // remove from queue
      Tree.dwnldQueue.splice(i, 1);
      // collapse node
      Tree.collapseNode(node);
    } // if
}

/*
  Loads text from url specified and returns it as result.
*/
Tree.loadUrl = function (purl, pasync)
{
  var ret = $.ajax({async:pasync,
					type:"GET",
					url: purl,
					dataType:"xml",
					data:$t.p.params,
					error: function(req,err,x){
						alert(req.responseText + " Error Type: "+err+":"+x  );
					}
			});
  return pasync == true ? ret : ret.responseText;
}

/*
  Creates XmlDom document from xml text string.
*/
Tree.loadXml = function (xmlString)
{
  var xmlDoc;
  if (window.DOMParser) /*Mozilla*/
    xmlDoc = new DOMParser().parseFromString(xmlString, "text/xml");
  else
  {
    if (document.implementation && document.implementation.createDocument)
      xmlDoc = document.implementation.createDocument("","", null); /*Konqueror*/
    else
      xmlDoc = new ActiveXObject("Microsoft.XmlDom"); /*IE*/
    
    xmlDoc.async = false;
    xmlDoc.loadXML(xmlString);
  } // else
  return xmlDoc;
}

/*
  Finds loading span for node.
*/
Tree.getNodeSpan = function (node)
{
  var span = $("span",node);
  return (span.length > 0 && (span = span[0]).parentNode == node) ? span : null;
}

/*
  Enqueue load of children nodes for node specified.
*/
Tree.loadChildren = function (node)
{
  // get url with children  - Opera do not like #
  var url = $("A",node)[0].href
  url = url != null && url.length != 0 && url != "#" ? url : "javascript:void(0)"; 
  // retreive xml text from url
  var httpReq = Tree.loadUrl(url, true);
  // enqueue node loading
  if (Tree.dwnldQueue.push(new Array (httpReq, node)) == 1){
    window.setTimeout(Tree.checkLoad, Tree.dwnldCheckTimeout);
  }
}

/*
  Creates HTML nodes list from XML nodes.
*/
Tree.convertXml2NodeList = function (xmlElement)
{
  var ul = document.createElement("UL");
  var index = 0;
  $(xmlElement.childNodes).each(function () { 
    if (this.nodeType == 1 ) /* ELEMENT_NODE */ {
      ul.appendChild(Tree.convertXml2Node(this)).nodeIndex = index++;
    }
  });
  return ul;
}

/*
  Creates HTML tree node (<li>) from xml element.
*/
Tree.convertXml2Node = function (xmlElement)
{
  var li = document.createElement("LI");
  var a1 = document.createElement("A");
  var a2 = document.createElement("A");
  var i1 = document.createElement("IMG");
  var i2 = document.createElement("IMG");
  var hasChildNodes =  false;
  $(xmlElement.childNodes).each(function () { 
    if (this.nodeType === 1 ) {/* ELEMENT_NODE */ 
      hasChildNodes = true;
      return false;
    }
  });
  
  var retreiveUrl = $(xmlElement).attr($t.p.xmlRetreiveUrl);
  
  // plus/minus icon
  i1.className = $t.p.plusMinusClassName;
  a1.appendChild(i1);
  $(a1).click( function(e) {Tree.ExpandCollapseNode(e);} )
  
  // plus/minus link
  a1.href = retreiveUrl != null && retreiveUrl.length != 0 && retreiveUrl != "#" ? retreiveUrl : "javascript:void(0)";
  li.appendChild(a1);
  
  // node icon
  var icoimg = $.trim($(xmlElement).attr($t.p.xmlIcon));
  if ( icoimg && icoimg.length !=0) { i2.src = $t.p.imgpath+icoimg; } else { i2.src = $t.p.defaultImage;}  
//  i2.src = $(xmlElement).attr(p.xmlIcon) || p.defaultImage;
  a2.appendChild(i2);
  
  // node link
  var lnk = $(xmlElement).attr($t.p.xmlUrl);
  a2.href = lnk != null && lnk.length !=0 && lnk != "#" ? $(xmlElement).attr($t.p.xmlUrl) : "javascript:void(0)";
  a2.id = $(xmlElement).attr($t.p.xmlId) || "none";
  a2.title = $(xmlElement).attr($t.p.xmlCaption);
  a2.appendChild(document.createTextNode($(xmlElement).attr($t.p.xmlCaption)));
  $(a2).click( function(e) {
	var node = e.srcElement /*IE*/ || e.target;
	Tree.NodeClick(node);
	var isLeaf = false;
	if( this.parentNode.empty ) {
		isLeaf = true;
	} else {
		if(this.parentNode.childNodes[2].childNodes.length == 0 )
			isLeaf = true;
	}
	if ($t.p.onSelectNode) $t.p.onSelectNode( $(this).attr("id"), $(this).attr("title"), isLeaf );
	//alert(Tree.obj);
	});
  li.appendChild(a2);
  // loading span
  if (!hasChildNodes && retreiveUrl != null && retreiveUrl.length != 0)
  {
    var span = document.createElement("SPAN");
    $(span).html($t.p.loadingText);
    $(span).click( function(e) {Tree.CancelLoad(e);} )

    li.appendChild(span);
  } // if
  
  // add children
  if (hasChildNodes)
    li.appendChild(Tree.convertXml2NodeList(xmlElement));
  if (hasChildNodes || retreiveUrl != null && retreiveUrl.length != 0)
  {
    if ($(xmlElement).attr($t.p.xmlExpanded)=== "true")
      Tree.expandNode(li, true);
    else
      Tree.collapseNode(li, true);
  } // if
  else
  {
    i1.src = $t.p.noChildrenImage; // no children
    li.empty = true;
  } // else

  return li;
}

/*
  Retreives current tree object.
*/
Tree.getObj = function (node)
{
  var obj = node;
  while (obj != null && obj.tagName != "DIV")
    obj = obj.parentNode;
  return obj;
}

Tree.getId = function (node)
{
  var obj = Tree.getObj(node);
  if (obj)
    return obj.id;
  return "";
}

/*
  Retreives unique id for tree node.
*/
Tree.getNodeId = function (node)
{
  var id = "";
  var obj = node;
  while (obj != null && obj.tagName != "DIV")
  {
    if (obj.tagName == "LI" && obj.nodeIndex != null)
      id = "_" + obj.nodeIndex + id;
    obj = obj.parentNode;
  } // while
//  if (obj != null && obj.tagName == "DIV")
//    id = obj.id + "_" + id;
  return id;
}

/*
  Saves node as opened for reload.
*/
Tree.saveOpenedNode = function (node)
{
  var treeId = Tree.getId(node);
  var state = Tree.getAllNodesSavedState(treeId);
  var nodeState = Tree.getNodeId(node) + ",";
  if (state.indexOf(nodeState) == -1)
  {
    state += nodeState;
    Tree.setAllNodesSavedState(treeId, state);
  } // if
}

/*
  Saves node as closed for reload.
*/
Tree.saveClosedNode = function (node)
{
  var treeId = Tree.getId(node);
  var state = Tree.getAllNodesSavedState(treeId);
  state = state.replace(new RegExp(Tree.getNodeId(node) + ",", "g"), "");
  Tree.setAllNodesSavedState(treeId, state);
}

Tree.getAllNodesSavedState = function (treeId)
{
  var state = Tree.getCookie(Tree.cookiePrefix + "_" + treeId);
  return state == null ? "" : state;
}

Tree.setAllNodesSavedState = function (treeId, state)
{
  Tree.setCookie(Tree.cookiePrefix + "_" + treeId, state);
}

/*
  Enques list of all opened nodes
*/
Tree.openAllSaved = function(treeId)
{
  var nodes = Tree.getAllNodesSavedState(treeId).split(",");
  var i;
  for (i=0; i<nodes.length; i++)
  {
    var node = Tree.getNodeById(treeId, nodes[i]);
    if (node && Tree.isNodeCollapsed(node))
      Tree.expandNode(node);
  } // for
}

Tree.getNodeById = function(treeId, nodeId)
{
  var node = document.getElementById(treeId);
  if (!node)
    return null;
  var path = nodeId.split("_");
  var i;
  for (i=1; i<path.length; i++)
  {
    if (node != null)
    {
      node = node.firstChild;
      while (node != null && node.tagName != "UL")
        node = node.nextSibling;
    } // if
    if (node != null)
      node = node.childNodes[path[i]];
    else
      break;
  } // for
  return node;
}

Tree.setCookie = function(sName, sValue)
{
  document.cookie = sName + "=" + escape(sValue) + ";";
}

Tree.getCookie = function(sName)
{
  var a = document.cookie.split("; ");
  for (var i=0; i < a.length; i++)
  {
    var aa = a[i].split("=");
    if (sName == aa[0]) 
      return unescape(aa[1]);
  } // for
  return null;
}

if(p.imgpath !== "" ) {
  p.collapsedImage = p.imgpath+p.collapsedImage;
  p.expandedImage = p.imgpath+p.expandedImage;
  p.noChildrenImage = p.imgpath+p.noChildrenImage;
  p.defaultImage = p.imgpath+p.defaultImage;
}

	this.p = p;
	
	var $t = this;
	var div = document.createElement("DIV");
	div.id = Tree.instancePrefix + this.id;
	//Tree.instanceCount++;
	div.className = $t.p.treeClass;
		//if(typeof $t.p.onSelectNode !== 'function') {$t.p.onSelectNode=false;}  
	var xml = Tree.loadUrl(url, false);
	var xmlDoc = Tree.loadXml(xml);
	var newNode = Tree.convertXml2NodeList(xmlDoc.documentElement);
	xml = null;
	div.appendChild(newNode);
	if (this != undefined)
	{
	  if (this.appendChild) // is node
	    this.appendChild(div);
	  else if ($(this)) // is node id
	    $(this).appendChild(div);
	} // if
	if ($t.p.saveNodesStateInCookies)
	this.Tree.openAllSaved(div.id);
	$(window).unload( function() { this.Tree = null;} )
});
}
})(jQuery);
