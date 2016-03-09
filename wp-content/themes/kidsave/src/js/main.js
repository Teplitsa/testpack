/* Scripts */
jQuery(document).ready(function($){
	
	
	/** Has js **/
	$('html').removeClass('no-js').addClass('js');
	
    /** Window width **/
	var windowWidth = $('#top').width();
	
	
	
	/** Map **/
	//center: [56.2519, 94.1420],
    var $map_div = $('#map');

    if($map_div.length) {

        var map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: false,
            center: [56.2519, 60.1420],
            zoom: 4
        });

        L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            attribution: 'Карта &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18,
            minZoom: 3
        }).addTo(map);

		for(var i=0; i<points.length; i++) {

			L.marker([points[i].lat, points[i].lng], {
				title: points[i].title,
				alt: points[i].title,
				icon: L.divIcon({className: 'mymap-icon fa fa-map-marker', iconSize: [36, 36]})
			}).addTo(map).bindPopup(points[i].popup_text);
		}
    }
	
	/* Partners */
	imagesLoaded('.eqh-container', function(){
		$('.eqh-el').responsiveEqualHeightGrid().find('.thumbnail-link').css('height', '100%');
		logo_vertical_center();
	});

	$(window).resize(function(){
		$('.eqh-container .eqh-el').responsiveEqualHeightGrid();
		logo_vertical_center();
	});
	
	function logo_vertical_center() {
		
		var logos = $('.partners .thumbnail-link'),
			logoH = logos.eq(0).parents('li').height() - 3;
			
		logos.css({'line-height' : logoH + 'px'});
	}

	//Header scroll
	var position = $(window).scrollTop(), //store intitial scroll position
		scrollTopLimit;
	
	
		
	$(window).scroll(function () {
		var scroll = $(window).scrollTop(),
			winW = $('#top').width();
		
		//limit
		scrollTopLimit = 250;
		if (winW > 480) {
			scrollTopLimit = 283;
		}
		else if (winW > 767) {
			scrollTopLimit = 200;
		}
		
		//scroll tolerance 3px and ignore out of boundaries scroll
		if((Math.abs(scroll-position) < 3) || tst_scroll_outOfBounds(scroll))
			return true;
		

		if (scroll > scrollTopLimit ) {
			$('#site_header').addClass('fixed');
		}
		else {
			$('#site_header').removeClass('fixed'); 
		}
			
	});
	
	
	//determines if the scroll position is outside of document boundaries
	function tst_scroll_outOfBounds(scroll) { 
		var	documentH = $(document).height(),
			winH = $(window).height();		
		
		if (scroll < 0 || scroll > (documentH+winH)) 
			return true;
		
		return false;
	}

}); //jQuery
