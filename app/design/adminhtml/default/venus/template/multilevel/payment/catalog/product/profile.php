<?php
/**
 * @var Venus_Multilevel_Block_Adminhtml_Payment_Catalog_Product_Fieldset_Profile $this
 */
?>

<?php if ($periodHtml = $this->getPeriodHtml()) : ?>
	<div id="product_composite_configure_fields_profile" class="<?php echo $this->getIsLastFieldset() ? 'last-fieldset' : '' ?>">
		<div class="product-options">
			<dl>
				<dt>
					<label for="<?php echo $this->getPeriodHtmlId() ?>"><?php echo $this->__('Billing Period') ?></label>

					<?php echo $periodHtml; ?>
				</dt>
			</dl>
		</div>
	</div>
<?php endif;
