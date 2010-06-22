/**
 * Ajax Queue for jQuery
 * Author: BYVoid
 */

$.ajaxQueue = 
{
	errorSkip: true,
	doing: false,
	ajaxQueue: new Array(),
	
	add: function(request)
	{
		this.ajaxQueue.push(request);
	},
	
	process: function(global)
	{
		this.global = global;
		if (!this.doing)
		{
			this.doing = true;
			this.doNext();
		}
	},
	
	run: function(request,global)
	{
		this.add(request);
		this.process(global);
	},
	
	doNext:function()
	{
		if (this.ajaxQueue.length == 0)
		{
			$.ajaxQueue.doing = false;
			$.ajaxQueue.global.complete();
			return;
		}
		var request = this.ajaxQueue[0];
		request.userComplete = request.complete;
		request.complete = this.complete;
		$.ajax(request);
	},
	
	complete: function(XMLHttpRequest, textStatus)
	{
		request = $.ajaxQueue.ajaxQueue.shift();
		if (typeof(request.userComplete) == 'function')
			request.userComplete(XMLHttpRequest, textStatus);
		if (($.ajaxQueue.ajaxQueue.length == 0) || (textStatus!='success' && !$.ajaxQueue.errorSkip))
		{
			$.ajaxQueue.doing = false;
			$.ajaxQueue.global.complete(textStatus);
		}
		else
		{
			$.ajaxQueue.doNext();
		}
	}
};