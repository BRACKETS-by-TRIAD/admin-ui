/*****
 * CONFIGURATION
 */
//Main navigation
$.navigation = $('ul.nav');

$.panelIconOpened = 'icon-arrow-up';
$.panelIconClosed = 'icon-arrow-down';

//Default colours
$.brandPrimary =  '#20a8d8';
$.brandSuccess =  '#4dbd74';
$.brandInfo =     '#63c2de';
$.brandWarning =  '#f8cb00';
$.brandDanger =   '#f86c6b';

$.grayDark =      '#2a2c36';
$.gray =          '#55595c';
$.grayLight =     '#818a91';
$.grayLighter =   '#d1d4d7';
$.grayLightest =  '#f8f9fa';

'use strict';

/****
 * MAIN NAVIGATION
 */

$(document).ready(function($){

	// Add class .active to current link
	$.navigation.find('a').each(function(){

		var cUrl = String(window.location).split('?')[0];

		if (cUrl.substr(cUrl.length - 1) == '#') {
			cUrl = cUrl.slice(0,-1);
		}

		if (cUrl.includes($($(this))[0].href)) {
			$(this).addClass('active');
		}
	});

	// Dropdown Menu
	$('.dropdown-toggle').on('click', function() {
		$(this).parent().toggleClass('open');
		resizeBroadcast();
	});

	$('.dropdown-item').on('click', function() {
		$(this).closest('.open').removeClass('open');
	});

	function resizeBroadcast() {

		var timesRun = 0;
		var interval = setInterval(function(){
			timesRun += 1;
			if(timesRun === 5){
				clearInterval(interval);
			}
			window.dispatchEvent(new Event('resize'));
		}, 62.5);
	}

	/* ---------- Main Menu Open/Close, Min/Full ---------- */
	$('.navbar-toggler').click(function(){

		if ($(this).hasClass('sidebar-toggler')) {
			$('body').toggleClass('sidebar-hidden');
			resizeBroadcast();
		}

		if ($(this).hasClass('sidebar-minimizer')) {
			$('body').toggleClass('sidebar-minimized');
			resizeBroadcast();
		}

		if ($(this).hasClass('aside-menu-toggler')) {
			$('body').toggleClass('aside-menu-hidden');
			resizeBroadcast();
		}

		if ($(this).hasClass('mobile-sidebar-toggler')) {
			$('body').toggleClass('sidebar-mobile-show');
			resizeBroadcast();
		}

	});

	$('.sidebar-collapse').on('click', function(){
		$('body').toggleClass('sidebar-mini');
		resizeBroadcast();
	});

	$('.dropdown-menu').on('mouseleave', function(){
		$(this).parent('.dropdown').removeClass('open');
	});

	$('.sidebar-close').click(function(){
		$('body').toggleClass('sidebar-opened').parent().toggleClass('sidebar-opened');
	});

	/* ---------- Disable moving to top ---------- */
	$('a[href="#"][data-top!=true]').click(function(e){
		e.preventDefault();
	});

});

/****
 * CARDS ACTIONS
 */

$(document).on('click', '.card-actions a', function(e){
	e.preventDefault();

	if ($(this).hasClass('btn-close')) {
		$(this).parent().parent().parent().fadeOut();
	} else if ($(this).hasClass('btn-minimize')) {
		var $target = $(this).parent().parent().next('.card-block');
		if (!$(this).hasClass('collapsed')) {
			$('i',$(this)).removeClass($.panelIconOpened).addClass($.panelIconClosed);
		} else {
			$('i',$(this)).removeClass($.panelIconClosed).addClass($.panelIconOpened);
		}

	} else if ($(this).hasClass('btn-setting')) {
		$('#myModal').modal('show');
	}

});

function capitalizeFirstLetter(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

function init(url) {

	/* ---------- Tooltip ---------- */
	$('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});

	/* ---------- Popover ---------- */
	$('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();

}

function recalculateTables() {
	var tableMaxWidth = $('.card-block').width();
	$('td:nth-of-type(2)').css({'max-width':tableMaxWidth-95+'px'});
}

recalculateTables();
$(window).on('resize', function(){
	if ($(this).width() <= 991) {
		recalculateTables();

	}
});

$(function(){
	$('.btn-spinner').on('click', function(e){
        if (!(e.shiftKey || e.ctrlKey || e.metaKey)) {
            $(this).css({'pointer-events':'none'});
            $(this).find('i').removeClass().addClass('fa fa-spinner');
        }
	});

    $('.nav-title').filter(function() {
        return !$(this).next().hasClass('nav-item');
    }).hide();

});