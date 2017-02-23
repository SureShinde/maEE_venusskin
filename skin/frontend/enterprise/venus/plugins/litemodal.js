/**
 * @author Oles Tourko
 */

/* 
* TODO: 
*	Add callbacks and events (onOpen, onClose, etc...) 
*	Type checking for params
*/ 
function LiteModal(params) {
	var _this = this;

	var overlayClose = params["overlayClose"];
	var buttonClose = params["buttonClose"];
	var containerClass = params["containerClass"];
	var content = params["content"];
	this.modalDiv = document.createElement('div');

	this.modalContainer = document.createElement('div');
	this.modalContainer.className = "lite-modal-container"
	if(containerClass) {
		this.modalContainer.className += " " + containerClass;
	}
	
	if(overlayClose) {
		this.modalContainer.addEventListener("click", function() {
			_this.close(_this);
		});
	}
		
	this.modalContainer.appendChild(this.modalDiv);
	this.modalDiv.className = "lite-modal";

	if(typeof content == "object") {
		this.modalDiv.appendChild(content);
	} else {
		this.modalDiv.innerHTML = content;
	}
	
	
	if(buttonClose) {
		var buttonDiv = document.createElement('div');
		buttonDiv.className = "lite-modal-button-close";
		buttonDiv.innerHTML = '';
		this.modalDiv.appendChild(buttonDiv);
		buttonDiv.addEventListener("click", function() {
			_this.close(_this);
		});
	}
	
	this.open();
}

/* Static creation */
LiteModal.create = function(params) {
	new LiteModal(params);
}

LiteModal.prototype = {
	open: function() {
		document.getElementsByTagName('body')[0].appendChild(this.modalContainer);
	},
	close: function(_this) {
		if(!_this) {
			_this = this;
		}
		document.getElementsByTagName('body')[0].removeChild(_this.modalContainer);
	}
}
