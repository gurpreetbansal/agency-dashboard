var BASE_URL = $('.base_url').val();


$(document).ready(function () {

	sidebar();
	$('.view-ajax-tabs').removeClass('ajax-loader');
	
});


function sidebar() {
	var dashType = 'seo';
	$('.view-sidebar').load('/project-detail/sidebar/' + $('#encriptkey').val()+'/'+dashType);

	$('.view-sidebar').find('ul li a').removeClass('ajax-loader');

}

function dasboardTabs() {
	var dashType = 'seo';
	$('.view-ajax-tabs').load('/project-detail/tabs/' + $('#encriptkey').val()+'/'+dashType);
}

function header() {
	
}

function breadcrumb() {
	
}