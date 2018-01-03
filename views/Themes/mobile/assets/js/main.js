var __ui = {
	itemTourChecked: function ( post ) {
		
		var data = post || {}, $image = '';

		var li = $('<li>', {class: 'ui-list-item', 'data-id': data.id });
		var anchor = $('<div>', {class: 'anchor clearfix'});

		if( data.image_cover_url ){
			$image = $('<img>', {src: data.image_cover_url, alt: data.name});

			anchor.append( $('<div>', {class: 'mediaWrapper lfloat mrm js-profile'}).html( $image ) );
		}

		var massages = $('<div>', {class: 'massages js-profile'})

		var content = $('<div>', {class: 'content'});
		content.append( $('<div>', {class: 'spacer'}), massages );
		anchor.append( content );

		var $meta = $('<table>', {class: 'table-meta'});
		// var $tr = $('<tr>', {class: 'table-meta'});
		// $meta.append( $tr );
		$meta.append( $('<tr>').append(
			$('<td>', {class: 'label', text: 'วันเดินทาง'}), 
			$('<td>', {class: 'data', text: data.days_str})
		));


		if( data.airline ){
			$meta.append( $('<tr>').append(
				$('<td>', {class: 'label', text: 'สายการบิน'}), 
				$('<td>', {class: 'data', text: data.airline})
			));
		}

		if( data.plans_total ){
			$meta.append( $('<tr>').append(
				$('<td>', {class: 'label', text: 'การเดินทาง'}), 
				$('<td>', {class: 'data', text: data.plans_total + ' ช่วงเวลา'})
			));
		}

		if( parseInt(data.price) > 0  ){
			$meta.append( $('<tr>').append(
				$('<td>', {class: 'label', text: 'ราคาเริ่มต้น'}), 
				$('<td>', {class: 'data', text: PHP.number_format( data.price, 0) })
			));
		}

		massages.append( $('<div>', {class: 'title'}).append( 
			$('<span>', {class:'fwb mrs', text: data.name}), 
			__ui.star( data.star ) 
		) );
		// massages.append( $('<div>', {class: 'heade'}).html( __ui.star( data.star ) ) );
		massages.append( $('<div>', {class: 'subtext fsm fcg mts'}).html( $meta ) );

		var $checked = $('<div>', {class: 'btn-checked js-checked'});
		$checked.append(
			$('<i>', {class: 'icon-check'})
		)
		li.append( anchor, $checked );

		if( data.checked ){
			li.addClass('checked');
		}

		li.data( data );

		return li;
	},

	star: function ($score=0, $number='', $is_show=true) {
		
		var $li = $('<div>', {class: 'ui-star'});
		$n = Math.round($score);

		for ($i=1; $i <= 5; $i++) { 

			if( $n >= $i){
				if( $n==$i && ($n-1)==Math.floor($score) ){
					$li.append( $('<i>', {class: 'icon-star-half-o'}) );
				}
				else{
					$li.append( $('<i>', {class: 'icon-star'}) );
				}
			}
			else{
				$li.append( $('<i>', {class: 'icon-star-o'}) );
			}
		}

		if( $is_show ){
			$li.append( $('<span>', {class: 'fcg', text: '('+ ($number!=''? $number:$score) + ')' }) );
		}

		return $li;
	}
}

// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var WebChoose = {
		init: function (options, elem) {
			var self = this;

			// set Elem
			self.$elem = $(elem);
			self.$listsbox = self.$elem.find('[role=listsbox]');
			self.$listsboxWrap = self.$listsbox.parent();

			// set Data
			self.url = options.url;
			self.save_url = options.save_url;
			self.profile_url = options.profile_url;
			self.data = {
				options: {
					pager: 1
				}
			};
			self.checked = {};
			self.dataSection();
			self.prev = '';

			// actions
			self.refresh( 1 );

			self.$elem.delegate('.js-checked','click',function () {
				var $this = $(this);
				var $parent = $this.closest('[data-id]');
				var checked = $parent.hasClass('checked');

				var data = $parent.data();
				if( checked ){
					$parent.removeClass('checked');
					delete self.checked[data.id];

					data.checked = false;
				}
				else{
					$parent.addClass('checked');
					self.checked[data.id] = data;

					data.checked = true;
				}

				self.summary();
			} );

			self.$elem.find('.js-summary').click( function () {
				self.prev = self.curr;
				self.changeSection( 'summary' );
			});

			$('.m-menu-toggle').addClass('not-active');
			$('.m-menu-toggle').click(function (e) {
				

				Event.hideMsg();
				if( self.curr=='summary' && Object.keys( self.checked ).length>0 ){
					self.changeSection( self.prev || 'listsbox' );
					self.prev = 'listsbox';
					e.preventDefault();
				}
				else if( self.curr=='profile' ){
					self.changeSection( self.prev || 'listsbox' );
					self.prev = 'listsbox';
					e.preventDefault();
				}
			});

			self.$elem.delegate('.js-profile','click',function () {

				self.prev = self.curr;
				self.setProfile( $(this).closest('[data-id]').data() );
			});

			self.$elem.find('.js-save').click( function () {

				self.save();
			});

			self.$listsboxWrap.find('.js-more, .empty-error').click(function () {
				if( $(this).hasClass('js-more') ){
					self.data.options.pager ++;
				}
				self.refresh( 1 );
			});

			self.$elem.delegate('.js-add','click',function () {

				var $this = $(this);
				var $parent = self.$listsbox.find('[data-id='+ self.currData.id +']');
				var checked = $parent.hasClass('checked');
				var data = $parent.data();

				if( checked ){
					$parent.removeClass('checked');
					delete self.checked[data.id];

					data.checked = false;
				}
				else{
					$parent.addClass('checked');
					self.checked[data.id] = data;

					data.checked = true;
				}

				self.summary();

				if( Object.keys( self.checked ).length==0 ){
					self.prev = 'listsbox';
				}

				self.changeSection( self.prev );
			});
		},

		refresh: function ( length ) {
			var self = this;

			self.$listsboxWrap.addClass('has-loading');
			if( self.$listsboxWrap.hasClass('has-empty') ){
				self.$listsboxWrap.removeClass('has-empty');
			}

			if( self.$listsboxWrap.hasClass('has-error') ){
				self.$listsboxWrap.removeClass('has-error');
			}

			setTimeout(function () {

				self.fetch().done(function( results ) {

					self.$elem.find('[view-text=total]').text( PHP.number_format(results.total) );

					self.data.options = $.extend( {}, self.data.options, results.options );

					self.$listsboxWrap.toggleClass('has-more', self.data.options.more );
					

					if( results.total==0 ){
						self.$listsboxWrap.find('.empty-text').text('ไม่พบผลลัพธ์');
						self.$listsboxWrap.addClass('has-empty');
					}
					
					if( results.error ){

						self.$listsboxWrap.addClass('has-empty');
						if( results.message ){
							self.$listsboxWrap.find('.empty-text').text( results.message );
						}
						return false;
					}

					
					self.buildFrag( results.lists );
					self.display();
				});

			}, length || 800);
		},
		fetch: function () {
			var self = this;

			return $.ajax({
				url: self.url,
				data: self.data.options,
				dataType: 'json'
			})
			.always(function() {
				self.$listsboxWrap.removeClass('has-loading');
			})
			.fail(function() {

				self.$listsboxWrap.find('.empty-error').text('การเชื่อมต่อล้มเหลว!');
				self.$listsboxWrap.addClass('has-error');
			});	
		},
		buildFrag: function ( results ) {
			var self = this;
			self.$items = $.map( results, function (obj) {

				obj.checked = self.checked[ obj.id ] ? true: false;
				return __ui.itemTourChecked( obj )[0];
			} );
		},

		display: function () {
			var self = this;

			self.$listsbox.append( self.$items );
		},

		summary: function () {
			var self = this;

			var length = Object.keys( self.checked ).length;
			self.$elem.find('[summary=countval]').text( length );
			self.$elem.toggleClass( 'has-checked', length>0 );

			self.$elem.find('.js-save').toggleClass('disabled', length==0).prop('disabled', length==0);
		},

		changeSection: function ( index ) {
			var self = this;

			var $elem = self.$elem.find('[data-ref='+ index +']');
			$elem.addClass('active').siblings().removeClass('active');

			// 
			$('body').scrollTop(0);

			self.dataSection();

			if( self.curr=='summary' ){

				var listsbox = $elem.find('.ui-list');
				listsbox.empty();

				$.each( self.checked, function (i, obj) {
					listsbox.append( __ui.itemTourChecked( obj ) );
				} );
			}
		},

		dataSection: function () {
			var self = this;

			var $elem = self.$elem.find('.active[data-ref]');
			self.curr = $elem.data('ref');
			self.$elem.toggleClass( 'is_summary', self.curr=='summary' );
		},

		setProfile: function ( data ) {
			var self = this;

			var $elem = self.$elem.find('[data-ref=profile]');
			$.get( self.profile_url + data.id, function (res) {

				var $body = $( res );
				$elem.html( $body );
				self.changeSection( 'profile' );

				Event.plugins( $elem );
				var $add = $elem.find('.js-add');

				if( self.checked[ data.id ] ){
					$add.text('ยกเลิกการเลือกโปรแกรมทัวร์');
				}
				else{
					$add.text('เลือกโปรแกรมทัวร์');					
				}

				self.currData = data;
			});
			
			// 
		},

		save: function () {
			var self = this;

			var $save = self.$elem.find('.js-save');
			Event.showMsg({ load: true });
			if( $save.hasClass('btn-error') ){
				$save.removeClass('btn-error');
			}

			$save.addClass('disabled').prop('disabled', true);

			var ids = $.map( self.checked, function (obj, id) { return id; } );
			$.post( self.save_url, {ids: ids},function(result) {

				$save.removeClass('disabled').prop('disabled', false);

				if( result.message ){
					Event.showMsg({ text: result.message, load: true, auto: true });
				}

				if( result.error ){
					$save.addClass('btn-error');
					return false;
				}

				if( result.url=="refresh" ){
					result.url = window.location.href;
				}


				if( result.message ){
					if( result.url ){
						setTimeout( function () {
							window.location = result.url;
						}, 800);
					}
				}
				else if( result.url ){
					window.location = result.url;
				}
				

			}, 'json');
		}
	};
	$.fn.webchoose = function( options ) {
		return this.each(function() {
			var $this = Object.create( WebChoose );
			$this.init( options, this );
			$.data( this, 'webchoose', $this );
		});
	};

	var DataListsbox = {
		init: function (options, elem) {
			var self = this;

			// set Elem
			self.$elem = $(elem);
			self.$listsbox = self.$elem.find('[role=listsbox]');
			self.$listsboxWrap = self.$listsbox.parent();

			// set Data
			self.url = options.url;
			self.data = {
				options: {
					pager: 1
				}
			};

			// actions
			self.refresh( 1 );

			self.$listsboxWrap.find('.js-more, .js-refresh').click(function () {

				// console.log( $(this).hasClass('.js-more') );
				if( $(this).hasClass('js-more') ){
					self.data.options.pager ++;
				}
				self.refresh( 1 );
			});
		},



		refresh: function ( length ) {
			var self = this;

			self.$listsboxWrap.addClass('has-loading');
			if( self.$listsboxWrap.hasClass('has-empty') ){
				self.$listsboxWrap.removeClass('has-empty');
			}

			if( self.$listsboxWrap.hasClass('has-error') ){
				self.$listsboxWrap.removeClass('has-error');
			}

			setTimeout(function () {

				self.fetch().done(function( results ) {

					self.$elem.find('[view-text=total]').text( PHP.number_format(results.total) );

					self.data.options = $.extend( {}, self.data.options, results.options );

					self.$listsboxWrap.toggleClass('has-more', self.data.options.more );
					

					if( results.total==0 ){
						self.$listsboxWrap.find('.empty-text').text('ไม่พบผลลัพธ์');
						self.$listsboxWrap.addClass('has-empty');
					}
					
					if( results.error ){

						self.$listsboxWrap.addClass('has-empty');
						if( results.message ){
							self.$listsboxWrap.find('.empty-text').text( results.message );
						}
						return false;
					}

					if( results.$lis ){
						self.$items = results.$lis;
					}
					else{
						self.buildFrag( results.lists, results.theme );
					}
					
					self.display();
				});

			}, length || 800);
		},
		fetch: function () {
			var self = this;

			return $.ajax({
				url: self.url,
				data: self.data.options,
				dataType: 'json'
			})
			.always(function() {
				self.$listsboxWrap.removeClass('has-loading');
			})
			.fail(function() {

				self.$listsboxWrap.find('.empty-error').text('การเชื่อมต่อล้มเหลว!');
				self.$listsboxWrap.addClass('has-error');
			});	
		},
		buildFrag: function ( results, theme ) {
			var self = this;
			self.$items = $.map( results, function (obj) {

				obj.checked = self.checked[ obj.id ] ? true: false;
				return __ui.itemTourChecked( obj )[0];
			} );
		},
		display: function () {
			var self = this;

			self.$listsbox.append( self.$items );
		},
	}
	$.fn.datalistsbox = function( options ) {
		return this.each(function() {
			var $this = Object.create( DataListsbox );
			$this.init( options, this );
			$.data( this, 'datalistsbox', $this );
		});
	};

	var CategoryList = {
		init: function (options, elem) {
			var self = this;

			// set Elem
			self.$elem = $(elem);
			self.options = $.extend( {}, $.fn.CategoryList.options, options );

			self.setElem();
		},
		setElem: function (){
			
		}
	}
	$.fn.CategoryList = function( options ) {
		return this.each(function() {
			var $this = Object.create( CategoryList );
			$this.init( options, this );
			$.data( this, 'CategoryList', $this );
		});
	};

	var formOrder = {
		init: function (options, elem){
			var self = this;

			self.$elem = $(elem);
			self.options = $.extend( {}, $.fn.formOrder.options, options );

			self.category = options.category;
			self.customer = options.customer;
			self.key = decodeURI(options.key);

			self.$topMenu = self.$elem.find('.scrollmenu');
			self.$subMenu = self.$elem.find('.subscrollmenu');

			self.setElem();
			self.setEvent();
		},
		setElem: function(){
			var self = this;
			$.get( Event.URL + 'orders/setProducts', {cate:self.category, cus:self.customer, key:self.key}, function(res) {
				$("#productsLists").html( res );
				Event.plugins( $('#productsLists') );
			});
			self.$topMenu.find('a[data-id='+self.category+']').addClass('active');
		},
		setEvent: function(){
			var self = this;
			self.$elem.delegate('.getProducts', 'click', function(){
				self.$topMenu.find('a.active').removeClass('active');
				$(this).addClass('active');

				self.setSubMenu($(this).data("id"));
				self.setProducts($(this).data("id"));
			});

			self.$elem.delegate('.getProducts2', 'click', function(){
				self.$subMenu.find('a.active').removeClass('acitve');
				$(this).addClass('acitve');

				self.setProducts($(this).data("id"));
			});

			self.$elem.delegate('.js-plus', 'click', function(){
				var $input = $(this).parents('.form-flex').find('.js-number');
				var $button = $(this).parents('.js-submit-form').find('.js-submit');
				var $value = parseInt($input.val());

				$button.removeAttr('disabled');

				$value = $value+1;
				$input.val( parseInt($value) );
			});

			self.$elem.delegate('.js-remove', 'click', function(){
				var $input = $(this).parents('.form-flex').find('.js-number');
				var $button = $(this).parents('.js-submit-form').find('.js-submit');
				var $value = parseInt($input.val());

				if( $value !== 0 ){
					$value = $value-1;
					$input.val( parseInt($value) );
					if( $value === 0 ){
						$button.attr('disabled', true);
					}
				}
				else{
					return false;
				}
			});

			self.$elem.delegate('.js-submit', 'click', function(){
				$(this).submit();

				var $input = $(this).parents('.js-submit-form').find('.js-number');
				var $val = $input.val();
				var $number = $('span#total');

				var total = parseInt( $val ) + parseInt( $number.text() );
				$number.empty();
				$number.text( total ); 

				$(this).addClass('disabled');
				$(this).attr('disabled', true);
				$input.val(0); 
			});

			self.$elem.delegate('.js-number', 'change', function(){
				var val = $(this).val();
				var $button = $(this).parents('.js-submit-form').find('.js-submit');
				if( val > 0 ){
					$button.removeAttr('disabled');
				}
				else{
					$button.attr('disabled', true);
				}
			});
		},
		setSubMenu: function(id){
			var self = this;
			$.get( Event.URL + 'orders/setsubMenu/', {id:id}, function(res){
				self.$elem.find("#subMenu").html( res );
			});
		},
		setProducts: function(id){
			var self = this;
			$.get( Event.URL + 'orders/setProducts', {cate:id, cus:self.customer}, function(res) {
				self.$elem.find("#productsLists").html( res );
				Event.plugins( $('#productsLists') );
			});
		}
	}
	$.fn.formOrder = function( options ){
		return this.each(function() {
			var $this = Object.create( formOrder );
			$this.init( options, this );
			$.data( this, 'formOrder', $this );
		});
	};

	var formCheckout = {
		init: function(options, elem){
			var self = this;

			self.$elem = $(elem);
			self.options = $.extend( {}, $.fn.formOrder.options, options );

			self.$submit = self.$elem.find('.submit');
			self.$plus = self.$elem.find('.js-plus');
			self.$remove = self.$elem.find('.js-remove');
			self.$del = self.$elem.find('.js-del');

			self.setEvent();
		},
		setEvent: function(){
			var self = this;

			self.$submit.click(function(){
				var form = $(this).parents('.js-form');
				var id = form.data("id");
				var data = form.serialize();

				$.ajax({
					type: "POST",
					url: Event.URL + 'orders/updateItemCusOrder/' + id,
					data: data,
					success: function(res){

						res = JSON.parse(res);

						self.$elem.find('#total').text( PHP.number_format( res.total ) );
						self.$elem.find('#discount').text( PHP.number_format( res.discount ) );
						self.$elem.find('#amount').text( PHP.number_format( res.amount ) );
						Event.showMsg({ text: "อัพเดตเรียบร้อย",load: true , auto: true });
					}
				});

				Dialog.close();
			});

			self.$plus.click(function(){
				var $input = $(this).parents('.form-flex').find('.js-number');
				var $button = $(this).parents('.js-form').find('.submit');
				var $value = parseInt($input.val());

				$button.removeAttr('disabled');

				$value = $value+1;
				$input.val( parseInt($value) );
			});
			self.$remove.click(function(){
				var $input = $(this).parents('.form-flex').find('.js-number');
				var $button = $(this).parents('.js-form').find('.submit');
				var $value = parseInt($input.val());

				if( $value !== 0 ){
					$value = $value-1;
					$input.val( parseInt($value) );
					if( $value === 0 ){
						$button.attr('disabled', true);
					}
				}
				else{
					return false;
				}
			});
			self.$elem.find('.js-number').change(function(){
				var val = $(this).val();
				var $button = $(this).parents('.js-form').find('.submit');
				if( val > 0 ){
					$button.removeAttr('disabled');
				}
				else{
					$button.attr('disabled', true);
				}
			});
			self.$del.click(function(){
				var id = $(this).parents('.js-form').data("id");
				// var $prices = $(this).attr('data-prices');
				self.$elem.find('[data-id='+id+']').remove();

				 $.ajax({
					type: "POST",
					url: $(this).data("url"),
					data: {id, id},
					success: function(res){
						res = JSON.parse(res);

						self.$elem.find('#total').text( PHP.number_format( res.total ) );
						self.$elem.find('#discount').text( PHP.number_format( res.discount ) );
						self.$elem.find('#amount').text( PHP.number_format( res.amount ) );
						Event.showMsg({ text: "ยกเลิกรายการเรียบร้อย",load: true , auto: true });
					}
				});

				/* var price = parseInt( self.$elem.find('#price').text().replace(/,/g,"") ) - parseInt( $prices.replace(/,/g,"") );
				var total = parseInt( self.$elem.find('#total').text().replace(/,/g,"") ) - parseInt( $prices.replace(/,/g,"") );

				self.$elem.find('#price').text( PHP.number_format(price) );
				self.$elem.find('#total').text( PHP.number_format(total) ); */

				Dialog.close();
			});
		}
	}
	$.fn.formCheckout = function( options ){
		return this.each(function() {
			var $this = Object.create( formCheckout );
			$this.init( options, this );
			$.data( this, 'formCheckout', $this );
		});
	};

})( jQuery, window, document );

$(function () {

	$('.js-navigation-trigger').click(function () {

		$('body').toggleClass('is-pushed-left', $('body').hasClass('is-pushed-left') ? false:true);
	});

});