   /*
    *
    *   Javascript Functions
    *   ------------------------------------------------
    *   WP Mobile Booster
    *   Copyright Mobile Booster 2018 - http://www.wpmobilemenu.com/mobile-booster
    *
    *
    */

   "use strict";

   (function ($) {
      $( document ).ready( function($) {
       
        if ( $( '.woocommerce-message' ).length > 0 ) {
          setTimeout(function(){ 
            $( '.woocommerce-message' ).addClass( 'mb-hide-in-mobile' );
          }, 6000);
        }
      });
    }(jQuery));