function adjustBodyHeight()
{
	height = $(window).height() - $('#header').height() - $('#footer').height();
	$('#body').height(height);
}

$(document).ready(adjustBodyHeight);
$(window).resize(adjustBodyHeight);