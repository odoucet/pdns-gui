/*
 * MultiSelectTreePanel v 1.0 (Initial release)
 *
 * This work is derivative of Ext-JS 2.2. Much of the code is modified versions of default code.
 * Refer to Ext-JS 2.2 licencing for more information. http://extjs.com/license
 *
 * Any and all original code is made available as is for whatever purpose you see fit.
 *
 * Should be a largely drop in replacement for ordinary TreePanel when you require multiselect
 * with drag and drop. Overrides most of the methods and events to pass a nodelist rather than
 * a single node.
 *
 * Note that the code is provided as-is and should be considered experimental and likely to contain
 * bugs, especially when combined with other extensions or modifications to the default library.
 *
 * It has been tested against Ext-JS 2.2 and 2.2.1 with:
 *
 * Firefox 3, Opera 9.5, Safari 3.1, MSIE 6 & 7.
 *
 * Usage:
 *
 * Add the following CSS to make the floating "drag" version of the tree indent prettily..

.x-dd-drag-ghost .x-tree-node-indent,.x-dd-drag-ghost .x-tree-ec-icon {display: inline !important;}

 *
 * If you are using Ext-JS 2.2.1 or earlier you need to add this override! (reported as a bug)
 
Ext.override(Ext.tree.TreeDropZone, {
    completeDrop : function(de){
        var ns = de.dropNode, p = de.point, t = de.target;
        if(!Ext.isArray(ns)){
            ns = [ns];
        }
        var n, node, ins = false;
        if (p != 'append'){
            ins = true;
            node = (p == 'above') ? t : t.nextSibling;
        }
        for(var i = 0, len = ns.length; i < len; i++){
            n = ns[i];
            if (ins){
                t.parentNode.insertBefore(n, node);
            }else{
                t.appendChild(n);
            }
	        if(Ext.enableFx && this.tree.hlDrop){
	           n.ui.highlight();
	        }
        }
	    ns[0].ui.focus();
        t.ui.endDrop();
        this.tree.fireEvent("nodedrop", de);
    }
    
}); 
 
 *
 * Instantiate like a normal tree (except DD stuff is enabled by default)
 
    var tree = new Ext.ux.MultiSelectTreePanel({
        autoScroll:true,
        width:400,
        height:500,
        animate:true,
        containerScroll: true,
        root: new Ext.tree.AsyncTreeNode({
        	text: 'A Book',
        	draggable:false,
        	id:'node0'
    	}),
        loader: new Ext.tree.TreeLoader({
            dataUrl:'bookdata.json'
        })
    });
 	tree.render("target");

 *
 * When listening for DND events look for dragdata.nodes instead of dragdata.node
 *
 * Use ctrl-click to select multiple nodes.
 * Use shift-click to select a range of nodes.
 *
 * Enjoy
 */

Ext.ux.FixedMultiSelectionModel = Ext.extend(Ext.tree.MultiSelectionModel, {
	// disabled tracking of mouse clicks because it doubles up drag selection...
	onNodeClick : function(node, e){
		if (e.shiftKey) e.preventDefault();
		// this.select(node);
	},

	// private
	sortSelNodes: function() {
		if (this.selNodes.length > 0) {
			if (!this.selNodes[0].ui.elNode)
			// sort nodes into document order.. (taken from quirksmode)
			if (this.selNodes[0].ui.elNode.sourceIndex) {
				// IE source index method
				this.selNodes.sort(function (a,b) {
					return a.ui.elNode.sourceIndex - b.ui.elNode.sourceIndex;
				});
			} else if (this.selNodes[0].ui.elNode.compareDocumentPosition) {
				// W3C DOM lvl 3 method (Gecko)
				this.selNodes.sort(function (a,b) {
					return 3 - (a.ui.elNode.compareDocumentPosition(b.ui.elNode) & 6);
				});
			}
		}
	},

	// overwritten from MultiSelectionModel to fix unselecting...
	select : function(node, e, keepExisting){
		// Add in setting an array as selected... (for multi-selecting D&D nodes)
		if(node instanceof Array){
			for (var c=0;c<node.length;c++) {
				this.selMap[node[c].id] = node[c];
				this.selNodes.push(node[c]);
				node[c].ui.onSelectedChange(true);
			}
			this.sortSelNodes();
			this.fireEvent("selectionchange", this, this.selNodes, this.lastSelNode);
			return node;
		}
		// Shift Select to select a range
		// NOTE: Doesn't change lastSelNode
		// EEK has to be a prettier way to do this
		if (e && e.shiftKey && this.selNodes.length > 0) {
			this.lastSelNode = this.lastSelNode || this.selNodes[0];
			var before = false;
			if (this.lastSelNode == node) {
				// check dom node ordering (from ppk of quirksmode.org)
			} else if (node.ui.elNode.sourceIndex) {
				// IE source index method
				before = (this.lastSelNode.ui.elNode.sourceIndex - node.ui.elNode.sourceIndex) > 0;
			} else if (node.ui.elNode.compareDocumentPosition) {
				// W3C DOM lvl 3 method (Gecko)
				var rel = this.lastSelNode.ui.elNode.compareDocumentPosition(node.ui.elNode);
				before = !!(rel & 2);
			} else {
				// Safari doesn't support compareDocumentPosition or sourceIndex
				// from http://code.google.com/p/doctype/wiki/ArticleNodeCompareDocumentOrder

				var range1 = document.createRange();
				range1.selectNode(this.lastSelNode.ui.elNode);
				range1.collapse(true);

				var range2 = document.createRange();
				range2.selectNode(node.ui.elNode);
				range2.collapse(true);

				before = range1.compareBoundaryPoints(Range.START_TO_END, range2) > 0;
			}
			this.clearSelections(true);
			var cont = true;
			var inside = false;
			var parent = this.lastSelNode;
			// ummm... yeah don't read this bit...
			do {
				for (var next=parent;next!=null;next=(before?next.previousSibling:next.nextSibling)) {
					// hack to make cascade work the way I want it to
					inside = inside || (before && (next == node || next.contains(node)));
					if (next.isExpanded()) {
						next.cascade(function(n) {
							if (cont != inside) {
								this.selNodes.push(n);
								this.selMap[n.id] = n;
								n.ui.onSelectedChange(true);
							}
							cont = (cont && n != node);
							return true;
						}, this);
					} else {
						this.selNodes.push(next);
						this.selMap[next.id] = next;
						next.ui.onSelectedChange(true);
						cont = (next != node);
					}
					if (!cont) break;
				}
				if (!cont) break;
				while ((parent = parent.parentNode) != null) {
					if (before) {
						this.selNodes.push(parent);
						this.selMap[parent.id] = parent;
						parent.ui.onSelectedChange(true);
					}
					cont = (cont && parent != node);
					if (before && parent.previousSibling) {
						parent = parent.previousSibling;
						break;
					}
					if (!before && parent.nextSibling) {
						parent = parent.nextSibling;
						break;
					}
				}
				if (!cont) break;
			} while (parent != null);
			if (!node.isSelected()) {
				this.selNodes.push(node);
				this.selMap[node.id] = node;
				node.ui.onSelectedChange(true);
			}
			this.sortSelNodes();
			this.fireEvent("selectionchange", this, this.selNodes, node);
			e.preventDefault();
			return node;
		} else if(keepExisting !== true) {
			this.clearSelections(true);
		}
		if(this.isSelected(node)) {
			// handle deselect of node...
			if (keepExisting === true) {
				this.unselect(node);
				if (this.lastSelNode === node) {
					this.lastSelNode = this.selNodes[0];
				}
				return node;
			}
			this.lastSelNode = node;
			return node;
		}
		// save a resort later on...
		this.selNodes.push(node);
		this.selMap[node.id] = node;
		node.ui.onSelectedChange(true);
		this.sortSelNodes();
		this.lastSelNode = node;
		this.fireEvent("selectionchange", this, this.selNodes, this.lastSelNode);
		return node;
	},
	// returns selected nodes precluding children of other selected nodes...
	// used for multi drag and drop...
	getUniqueSelectedNodes: function() {
		var ret = [];
		for (var c=0;c<this.selNodes.length;c++) {
			var parent = this.selNodes[c];
			ret.push(parent);
			// nodes are sorted(?) so skip over subsequent nodes inside this one..
			while ((c+1)<this.selNodes.length && parent.contains(this.selNodes[c+1])) c++;
		}
		return ret;
	}
});
/*
	Enhanced to support dragging multiple nodes...
	
	for extension refer to data.nodes instead of data.node
	
*/
Ext.ux.MultiSelectTreeDragZone = Ext.extend(Ext.tree.TreeDragZone, {
	onBeforeDrag : function(data, e){
		if (data.nodes && data.nodes.length > 0) {
			for (var c=0;c<data.nodes.length;c++) {
				n = data.nodes[c];
				if (n.draggable === false || n.disabled) return false
			}
			return true;
		}
		return false;
		
	},
	// what a mess!!!
	// fixed to handle multiSelectionModel, however the result is very hacky
	getDragData : function(e) {
		// use tree selection model..
		var selModel = this.tree.getSelectionModel();
		// get event target
		var target = Ext.dd.Registry.getHandleFromEvent(e);
		// if no target (die)
		if (target == null) return;
		if (target.node.isSelected() && e.ctrlKey) {
			selModel.unselect(target.node);
			return;
		}
		var selNodes = [];
		if (!selModel.getSelectedNodes) {
			// if not multiSelectionModel.. just use the target...
			selNodes = [target.node];
		} else {
			// if target not selected select it...
			if (!target.node.isSelected() || e.shiftKey) {
				selModel.select(target.node, e, e.ctrlKey);
			}
			// get selected nodes - nested nodes...
			selNodes = selModel.getUniqueSelectedNodes();
		}
		// if no nodes selected stop now...
		if (!selNodes || selNodes.length < 1) return;
		var dragData = { nodes: selNodes };
		// create a container for the proxy...
		var div = document.createElement('ul'); // create the multi element drag "ghost"
		// add classes to keep is pretty...
		div.className = 'x-tree-node-ct x-tree-lines';
		// add actual dom nodes to div (instead of tree nodes)
		//var height = 0;
		for(var i = 0, len = selNodes.length; i < len; i++) {
			// height += Ext.fly(selNodes[i].ui.elNode.parentNode).getHeight();
			// add entire node to proxy
			div.appendChild(selNodes[i].ui.elNode.parentNode.cloneNode(true));
			// limit proxy height to around 150px (need setting for this really)
			// removed because the height varies so much anyways...
			//if (height>150 && (i+1)<selNodes.length) {
			//	var elipsis = document.createElement("div");
			//	elipsis.innerHTML = "<b>...</b>";
			//	div.appendChild(elipsis);
			//	break;
			//}
		}
		// fix extra indenting by removing extra spacers
		// should really modify UI rendering code to render a duplicate subtree but this is simpler...
		// no idea if this really gets all nodes or not...
		var nodes = Ext.query(".x-tree-node-el", div);
		for (var c=0;c<nodes.length;c++) {
			// remove highlighting...
			Ext.fly(nodes[c]).removeClass(['x-tree-selected','x-tree-node-over']);
			// start at 1 to leave in folder/user icon
			var depth = 1;
			// calculate indenting required in proxy
			for (var node=nodes[c].parentNode.parentNode;node!=null && node.parentNode!=null;node=node.parentNode.parentNode) {
				depth++;
			}
			var spacers = Ext.query("img", nodes[c]);
			for (var r=0;r<spacers.length&&r<spacers.length-depth;r++) {
				spacers[r].parentNode.removeChild(spacers[r]);
			}
		}
		dragData.ddel = div;
		return dragData;
	},
	// fix from TreeDragZone (references dragData.node instead of dragData.nodes)
	onInitDrag : function(e){
		var data = this.dragData;
		this.tree.eventModel.disable();
		this.proxy.update("");
		this.proxy.ghost.dom.appendChild(data.ddel);
		this.tree.fireEvent("startdrag", this.tree, data.nodes, e);
	},
	// Called from TreeDropZone (looks like hack for handling multiple tree nodes)
	getTreeNode: function() {
		return this.dragData.nodes;
	},
	// fix from TreeDragZone (refers to data.node instead of data.nodes)
	// Don't know what this does, so leaving as first node.
	getRepairXY : function(e, data){
		return data.nodes[0].ui.getDDRepairXY();
	},

	// fix from TreeDragZone (refers to data.node instead of data.nodes)
	onEndDrag : function(data, e){
		this.tree.eventModel.enable.defer(100, this.tree.eventModel);
		this.tree.fireEvent("enddrag", this.tree, data.nodes, e);
	},

	// fix from TreeDragZone (refers to dragData.node instead of dragData.nodes)
	onValidDrop : function(dd, e, id){
		this.tree.fireEvent("dragdrop", this.tree, this.dragData.nodes, dd, e);
		this.hideProxy();
	},

	// fix for invalid Drop
	beforeInvalidDrop : function(e, id){
		// this scrolls the original position back into view
		var sm = this.tree.getSelectionModel();
		sm.clearSelections();
		sm.select(this.dragData.nodes, e, true);
	}

});

/*

MultiSelectTreeDropZone

Contains following fixups

- modified functions to handle multiple nodes in dd operation
	isValidDropPoint
	afterRepair
- modified getDropPoint such that isValidDropPoint can simulate leaf style below inserting.
	Overriding isValidDropPoint affects getDropPoint affects onNodeOver and onNodeDrop

Refer to data.nodes instead of data.node for events..

*/
Ext.ux.MultiSelectTreeDropZone = Ext.extend(Ext.tree.TreeDropZone, {

	// fix from TreeDropZone (referred to data.node instead of data.nodes)
	isValidDropPoint : function(n, pt, dd, e, data){
		if(!n || !data) { return false; }
		var targetNode = n.node;
		var dropNodes = data.nodes?data.nodes:[data.node];
		// default drop rules
		if(!(targetNode && targetNode.isTarget && pt)){
			return false;
		}
		if(pt == "append" && targetNode.allowChildren === false){
			return false;
		}
		if((pt == "above" || pt == "below") && (targetNode.parentNode && targetNode.parentNode.allowChildren === false)){
			return false;
		}
		// don't allow dropping a treenode inside itself...
		for (var c=0;c<dropNodes.length;c++) {
			if(dropNodes[c] && (targetNode == dropNodes[c] || dropNodes[c].contains(targetNode))){
				return false;
			}
		}
		// reuse the object
		var overEvent = this.dragOverData;
		overEvent.tree = this.tree;
		overEvent.target = targetNode;
		overEvent.data = data;
		overEvent.point = pt;
		overEvent.source = dd;
		overEvent.rawEvent = e;
		overEvent.dropNode = dropNodes;
		overEvent.cancel = false;
		var result = this.tree.fireEvent("nodedragover", overEvent);
		return overEvent.cancel === false && result !== false;
	},

	// override to allow insert "below" when leaf != true...
	getDropPoint : function(e, n, dd, data){
		var tn = n.node;
		if(tn.isRoot){
			return this.isValidDropPoint(n, "append", dd, e, data)? "append" : false;
		}
		var dragEl = n.ddel;
		var t = Ext.lib.Dom.getY(dragEl), b = t + dragEl.offsetHeight;
		var y = Ext.lib.Event.getPageY(e);
		var noAppend = tn.allowChildren === false || tn.isLeaf() || !this.isValidDropPoint(n, "append", dd, e, data);
		if(!this.appendOnly && tn.parentNode.allowChildren !== false){
			var noBelow = false;
			if(!this.allowParentInsert){
				noBelow = tn.hasChildNodes() && tn.isExpanded();
			}
			var q = (b - t) / (noAppend ? 2 : 3);
			if(y >= t && y < (t + q) && this.isValidDropPoint(n, "above", dd, e, data)){
				return "above";
			}else if(!noBelow && (noAppend || y >= b-q && y <= b) && this.isValidDropPoint(n, "below", dd, e, data)){
				return "below";
			}
		}
		return noAppend? false: "append";
	},

	// Override because it calls getDropPoint and isValidDropPoint
	onNodeOver : function(n, dd, e, data){
		var pt = this.getDropPoint(e, n, dd, data);
		var node = n.node;


		if(!this.expandProcId && pt == "append" && node.hasChildNodes() && !n.node.isExpanded()){
			this.queueExpand(node);
		}else if(pt != "append"){
			this.cancelExpand();
		}

		var returnCls = this.dropNotAllowed;
		if(pt){
			var el = n.ddel;
			var cls;
			if(pt == "above"){
				returnCls = n.node.isFirst() ? "x-tree-drop-ok-above" : "x-tree-drop-ok-between";
				cls = "x-tree-drag-insert-above";
			}else if(pt == "below"){
				returnCls = n.node.isLast() ? "x-tree-drop-ok-below" : "x-tree-drop-ok-between";
				cls = "x-tree-drag-insert-below";
			}else{
				returnCls = "x-tree-drop-ok-append";
				cls = "x-tree-drag-append";
			}
			if(this.lastInsertClass != cls){
				Ext.fly(el).replaceClass(this.lastInsertClass, cls);
				this.lastInsertClass = cls;
			}
		}
		return returnCls;
	},

	// Override because it calls getDropPoint and isValidDropPoint
	onNodeDrop : function(n, dd, e, data){
		var point = this.getDropPoint(e, n, dd, data);
		var targetNode = n.node;
		targetNode.ui.startDrop();
		if(point === false) {
			targetNode.ui.endDrop();
			return false;
		}

		var dropNode = data.node || (dd.getTreeNode ? dd.getTreeNode(data, targetNode, point, e) : null);
		var dropEvent = {
			tree : this.tree,
			target: targetNode,
			data: data,
			point: point,
			source: dd,
			rawEvent: e,
			dropNode: dropNode,
			cancel: !dropNode,
			dropStatus: false
		};
		var retval = this.tree.fireEvent("beforenodedrop", dropEvent);
		if(retval === false || dropEvent.cancel === true || !dropEvent.dropNode){
			targetNode.ui.endDrop();
			return dropEvent.dropStatus;
		}

		targetNode = dropEvent.target;
		if(point == "append" && !targetNode.isExpanded()){
			targetNode.expand(false, null, function(){
				this.completeDrop(dropEvent);
			}.createDelegate(this));
		}else{
			this.completeDrop(dropEvent);
		}
		return true;
	},

	// fix from TreeDropZone (referred to data.node instead of data.nodes)
	afterRepair : function(data){
		if(data && Ext.enableFx){
			for (var c=0;c<data.nodes.length;c++) {
				data.nodes[c].ui.highlight();
			}
		}
		this.hideProxy();
	}

});

/*

	MultiSelectTreePanel

	sets up using FixedMultiSelectionModel
	and initing with extended DragZone and DropZone by default

*/

Ext.ux.MultiSelectTreePanel = Ext.extend(Ext.tree.TreePanel, {
	enableDD: true,

	getSelectionModel : function(){
		if(!this.selModel){
			this.selModel = new Ext.ux.FixedMultiSelectionModel();
		}
		return this.selModel;
	},

	initEvents: function() {
		this.dragZone = new Ext.ux.MultiSelectTreeDragZone(this, {
								ddGroup: this.ddGroup || "TreeDD",
								scroll: this.ddScroll
							});
		this.dropZone = new Ext.ux.MultiSelectTreeDropZone(this, this.dropConfig || {
								ddGroup: this.ddGroup || "TreeDD",
								appendOnly: this.ddAppendOnly === true
							});
		Ext.ux.MultiSelectTreePanel.superclass.initEvents.apply(this, arguments);

	}
});

Ext.reg('multiselecttreepanel', Ext.ux.MultiSelectTreePanel);
