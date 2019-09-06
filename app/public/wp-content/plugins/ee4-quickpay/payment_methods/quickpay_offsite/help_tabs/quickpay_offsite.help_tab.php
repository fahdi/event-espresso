<p><strong><?php _e( 'QuickPay', 'ee-quickpay' ); ?></strong></p>

<p><strong><?php _e( 'QuickPay Settings', 'ee-quickpay' ); ?></strong></p>
<ul>
	<li>
		<strong><?php _e( 'Button Image URL', 'ee-quickpay' ); ?></strong><br />
		<?php _e( 'Change the image that is used for this payment gateway.', 'ee-quickpay' ); ?>
	</li>

	<li>
		<strong><?php _e( 'Private key', 'ee-quickpay' ); ?></strong><br />
		<?php _e( 'Your agreement private key. Found in the "Integration" tab inside the QuickPay manager.', 'ee-quickpay' ); ?>
	</li>

	<li>
		<strong><?php _e( 'API key', 'ee-quickpay' ); ?></strong><br />
		<?php _e( 'Your API User\'s key. Create a separate API user in the "Users" tab inside the QuickPay manager.', 'ee-quickpay' ); ?>
	</li>

	<li>
		<strong><?php _e( 'Text on Statement', 'ee-quickpay' ); ?></strong><br />
		<?php _e( 'Text that will be placed on cardholder’s bank statement (currently only supported by Clearhaus).', 'ee-quickpay' ); ?>
	</li>

	<li>
		<strong><?php _e( 'Branding ID', 'ee-quickpay' ); ?></strong><br />
		<?php _e( 'The ID of your custom branding template. Leave empty if you have no custom branding options.', 'ee-quickpay' ); ?>
	</li>

	<li>
		<?php $docs_payment_methods = 'http://tech.quickpay.net/appendixes/payment-methods/'; ?>
		<strong><?php _e( 'Payment Methods', 'ee-quickpay' ); ?></strong><br />
		<?php _e( 'Default: creditcard. Type in the cards you wish to accept (comma separated). See the valid payment types here: ', 'ee-quickpay' ); ?>
		<a href="<?php echo $docs_payment_methods; ?>" target="_blank"><strong><?php echo $docs_payment_methods; ?></strong></a>
	</li>

	<li>
		<strong><?php _e( 'Auto Capture', 'ee-quickpay' ); ?></strong><br />
		<?php _e( 'Automatically capture a payment when authorized.', 'ee-quickpay' ); ?>
	</li>
</ul>