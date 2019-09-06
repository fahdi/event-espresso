<?php use QuickPayHelpers\TransactionUtil; ?>

<p class="woocommerce-quickpay-<?php echo $status; ?>">
	<strong> <?php _e( 'Current payment state', 'ee-quickpay' ); ?>: <?php echo $status; ?></strong>
</p>

<?php if ( TransactionUtil::is_action_allowed( 'capture', $transaction ) ) : ?>
	<h4><strong><?php _e( 'Actions', 'ee-quickpay' ); ?></strong></h4>
<?php endif; ?>

	<ul class="order_action">

		<?php if ( TransactionUtil::is_action_allowed( 'capture', $transaction ) ) : ?>
			<li class="qp-full-width">
				<a class="button button-primary" data-action="capture" data-confirm="<?php _e( 'You are about to CAPTURE this payment', 'ee-quickpay' ); ?>">
					<?php printf( __( 'Capture Payment (%s)', 'ee-quickpay' ), $currency . ' ' . $formatted_remaining_balance ); ?>
				</a>
			</li>
		<?php endif; ?>

		<li class="qp-balance">
			<span class="qp-balance__label"><?php _e( 'Amount Captured', 'ee-quickpay' ); ?>:</span>
			<span class="qp-balance__amount">
				<span class='qp-balance__currency'><?php echo $currency; ?></span>
				<span><?php echo $balance; ?></span>
			</span>
		</li>

		<?php if ( TransactionUtil::is_action_allowed( 'cancel', $transaction ) ) : ?>
			<li class="qp-full-width">
				<a class="button" data-action="cancel" data-confirm="<?php _e( 'You are about to CANCEL this payment', 'ee-quickpay' ); ?>">
					<?php _e( 'Cancel', 'ee-quickpay' ); ?>
				</a>
			</li>
		<?php endif; ?>

	</ul>

<p>
	<small>
		<strong><?php _e( 'Transaction ID', 'ee-quickpay' ); ?></strong> <span><?php echo $transaction_id; ?></span>
		<span class="qp-meta-card"><img src="<?php echo $card_logo; ?> " /></span>
	</small>
</p>
