// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var Sequence = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.$elem.addClass('unselect');

			var item, is_run = false;
			Event.setPlugin( self.$elem, 'sortable', {
				onChange: function(){

					if( typeof options.onChange === 'function') {

						
						options.onChange( true );
					}
					
					// self.top5Sort();
				}
			} );

			/*var $item;
			self.$elem.find('li').on("mousedown", function(e) {
				$item = $(this).clone();
			});*/

			/*self.$elem.on('dragenter', function(){
		        $(this).preventDefault();
		    });*/

		    var curr = '';
			self.$elem.delegate('.seq-item', "mousedown", function(e) {

				curr = $(this);
				$(this).addClass('avtive');
			});

			$(window).mouseup( function(e) {

				if( curr ){
					curr.removeClass('avtive');
				}				
			});

		}
	}

	$.fn.sequence = function( options ) {
		return this.each(function() {
			var $this = Object.create( Sequence );
			$this.init( options, this );
			$.data( this, 'sequence', $this );
		});
	};
	
})( jQuery, window, document );