( function( $ ) {
	"use strict";

	QuickPay.prototype.init = function() {
		// Add event handlers
		this.actionBox.on( 'click', '[data-action]', $.proxy( this.callAction, this ) );
	};

	QuickPay.prototype.callAction = function( e ) {
		e.preventDefault();
		var target = $( e.target );
		var action = target.attr( 'data-action' );

		if( typeof this[action] !== 'undefined' ) {
			var message = target.attr('data-confirm') || 'Are you sure you want to continue?';
			if( confirm( message ) ) {
				this[action]();
			}
		}
	};

	QuickPay.prototype.capture = function() {
		var request = this.request( {
			quickpay_action : 'capture'
		} );
	};

	QuickPay.prototype.captureAmount = function () {
		var request = this.request({
			quickpay_action: 'capture',
			quickpay_amount: $('#qp-balance__amount-field').val()
		} );
	};

	QuickPay.prototype.cancel = function() {
		var request = this.request( {
			quickpay_action : 'cancel'
		} );
	};

	QuickPay.prototype.refund = function() {
		var request = this.request( {
			quickpay_action : 'refund'
		} );
	};

	QuickPay.prototype.split_capture = function() {
		var request = this.request( {
			quickpay_action : 'splitcapture',
			amount : parseFloat( $('#quickpay_split_amount').val() ),
			finalize : 0
		} );
	};

	QuickPay.prototype.split_finalize = function() {
		var request = this.request( {
			quickpay_action : 'splitcapture',
			amount : parseFloat( $('#quickpay_split_amount').val() ),
			finalize : 1
		} );
	};

	QuickPay.prototype.request = function( dataObject ) {
		var that = this;
		var request = $.ajax( {
			type : 'POST',
			url : ajaxurl,
			dataType: 'json',
			data : $.extend( {}, { action : 'quickpay_manual_transaction_actions', txn_id : this.TXN_ID.val() }, dataObject ),
			beforeSend : $.proxy( this.showLoader, this, true ),
			success : function() {
				$.get( window.location.href, function( data ) {
					var newData = $(data).find( '#' + that.actionBox.attr( 'id' ) + ' .inside' ).html();
					that.actionBox.find( '.inside' ).html( newData );
					that.showLoader( false );
				} );
			}
		} );

		return request;
	};

	QuickPay.prototype.showLoader = function( e, show ) {
		if( show ) {
			this.actionBox.append( this.loaderBox );
		} else {
			this.actionBox.find( this.loaderBox ).remove();
		}
	};


	// DOM ready
	$(function() {
		new QuickPay().init();
	});

	function QuickPay() {
		this.actionBox 	= $( '#quickpay-payment-actions' );
		this.TXN_ID		= $( '#txn-admin-payment-txn-id-inp' );
		this.loaderBox 	= $( '<div class="loader"></div>');
	}

})(jQuery);