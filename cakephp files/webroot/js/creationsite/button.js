$(document).ready(function(){
	$('.buttonset').buttonset();
});

function toggleDisplay(itemId) {
	var item = document.getElementById(itemId);
	if (item.className == 'hide') {
		item.className = 'show';
	} else {
		item.className = 'hide';
	}
}

function hideItem(itemId) {
	var item = document.getElementById(itemId);
	item.className = 'hide';
}

function showItem(itemId) {
	var item = document.getElementById(itemId);
	item.className = 'show';
}