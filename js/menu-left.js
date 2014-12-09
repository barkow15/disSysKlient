/* --- MENU LEFT START --- */
	( function( window ) {

	'use strict';

	var hasClass, addClass, removeClass;

	if ( 'classList' in document.documentElement ) {
	  hasClass = function( elem, c ) {
	    return elem.classList.contains( c );
	  };
	  addClass = function( elem, c ) {
	    elem.classList.add( c );
	  };
	  removeClass = function( elem, c ) {
	    elem.classList.remove( c );
	  };
	}
	else {
	  hasClass = function( elem, c ) {
	    return classReg( c ).test( elem.className );
	  };
	  addClass = function( elem, c ) {
	    if ( !hasClass( elem, c ) ) {
	      elem.className = elem.className + ' ' + c;
	    }
	  };
	  removeClass = function( elem, c ) {
	    elem.className = elem.className.replace( classReg( c ), ' ' );
	  };
	}

	function toggleClass( elem, c ) {
	  var fn = hasClass( elem, c ) ? removeClass : addClass;
	  fn( elem, c );
	}

	window.classie = {
	  // full names
	  hasClass: hasClass,
	  addClass: addClass,
	  removeClass: removeClass,
	  toggleClass: toggleClass,
	  // short names
	  has: hasClass,
	  add: addClass,
	  remove: removeClass,
	  toggle: toggleClass
	};

	})( window );

		var menuLeft = document.getElementById( 'left-spmenu-s1' ),
			menuRight = document.getElementById( 'left-spmenu-s2' ),
			showLeftPush = document.getElementById( 'showLeftPush' ),
			showLeftBack = document.getElementById( 'showLeftBack' ),
			showRightPush = document.getElementById( 'showRightPush' ),
			showRightBack = document.getElementById( 'showRightBack' ),
			body = document.body;
	
		showLeftPush.onclick = function() {
			classie.toggle( this, 'active' );
			classie.toggle( menuLeft, 'left-spmenu-open' );
		};	

		showLeftBack.onclick = function() {
			classie.toggle( this, 'active' );
			classie.toggle( menuLeft, 'left-spmenu-open' );
		};			

		showRightPush.onclick = function() {
			classie.toggle( this, 'active' );
			classie.toggle( menuRight, 'left-spmenu-open' );
		};	

		showRightBack.onclick = function() {
			classie.toggle( this, 'active' );
			classie.toggle( menuRight, 'left-spmenu-open' );
		};	
/* --- MENU LEFT END --- */