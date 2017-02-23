<?php
$installer = $this;
$installer->startSetup();

$content = <<<CONTENT
<div class="patients-info">

<img class="patients-top-banner fullwidth-banner" src="{{skin url='images/patients/top_banner.jpg'}}" title="" alt="" />

<div class="main-inner">
<div class="banner-text">
<h2>BENEFITS FOR PATIENTS</h2>
<hr />
</div>

<div class="col3-set">
	<div class="col-1">
		<div class="info-panel">
			<img src="{{skin url="images/assets/patients-icons/medical_icon.png"}}" alt="Medical" />
			<h4>MEDICAL GRADE SKINCARE LINE</h4>
			<p>Scientifically and clinically proven products</p>
		</div>
		<div class="info-panel">
			<img src="{{skin url="images/assets/patients-icons/botanical_icon.png"}}" alt="Botanical" />
			<h4>ACTIVE BOTANICAL INGREDIENTS</h4>
			<p>All natural high quality raw ingredients</p>
		</div>
	</div>
	<div class="col-2">
		<div class="info-panel">
			<img src="{{skin url="images/assets/patients-icons/formula_icon.png"}}" alt="Regimen" />
			<h4>UNIQUE COCKTAIL BASED REGIMEN</h4>
			<p>Unique formulation which has a 3 minute application time</p>
		</div>
		<div class="info-panel">
			<img src="{{skin url="images/assets/patients-icons/rewards_icon.png"}}" alt="Rewards" />
			<h4>PARTICIPATE IN REWARDS PROGRAM</h4>
			<p>Refer-a-friend program</p>
		</div>
	</div>
	<div class="col-3">
		<div class="info-panel">
		        <img src="{{skin url="images/assets/patients-icons/concerns_icon.png"}}" alt="Concerns" />
			<h4>ADDRESS SKIN CONCERNS</h4>
			<p>Targets acne, pigmentation and anti aging concerns</p>
		</div>
		<div class="info-panel">
			<img src="{{skin url="images/assets/patients-icons/paraben_icon.png"}}" alt="Paraben" />
			<h4>PARABEN FREE</h4>
			<p>Small batch production also free of glycols and sulfates</p>
		</div>
	</div>
</div>
</div>
</div>

<div class="fullwidth-banner members-banner">
	<div class="fullwidth-banner-inner clearfix">
		<div class="image">
			<img src="{{skin url='images/patients/VIP_img.png'}}" alt="">
		</div>
		<div class="info-panel clearfix">
			<div class="faq-header">
				<h4>Are You a VIP Member?</h4>
			</div>
			<ul>
				<li>Never run out of your favorite products!</li>
				<li>Automatic 30, 45, 60, or 90 day delivery options.</li>
				<li>Special "Members-Only" pricing as a valued member of our program.</li>
				<li>Exclusive access to promotions and insider information.</li>
				<li><strong>Free to join, cancel at any time!</strong></li>
			</ul>
		</div>
	</div>
</div>

<div class="main-inner">
	{{block type="theme/catalog_product_featured_list" name="catalog.product.slider" template="catalog/product/carousel/full.phtml"}}
</div>

<div class="fullwidth-banner signup-banner">
	<div class="fullwidth-banner-inner">
		<div class="banner-text">
			<h2>Sign up Today</h2>
			<hr />
			<p>Isn't it time you started using skincare products that fit your lifestyle?
			Sign up today to take advantage of these amazing products.</p>
			<a href="{{store url='customer/account/create'}}" class="button">Sign Up Now</a>
		</div>
	</div>
</div>

<div class="fullwidth-banner get-give-banner" id="give-get-banner">
	<div class="fullwidth-banner-inner">
		<div class="faq-header">
			<h4>Give $100, Get $100</h4>
			<hr>
			<p>
Good friends share beauty secrets – that’s why we’ve created “Give $100 Get $100”, a program that rewards you and your friends for using our products.  It’s as simple as it sounds! Refer a friend to VenusSkin and when they sign up as a VIP member and place an order you’ll receive a $100 credit toward a future order.  Your friend will also receive a $100 credit!
			</p>
<a href="{{store url='invitation'}}" class="button tell-friends">Tell Your Friends</a>
		</div>
	</div>
</div>
CONTENT;

$page = Mage::getModel('cms/page')->load('forpatients', 'identifier');
$page->setContent($content)
     ->save();

$productsContent = <<<CONTENT
<div class="fullwidth-banner vip-banner">
	<div class="fullwidth-banner-inner">
		<h2>Are You a <strong>VIP</strong> Member?</h2>
		<a class="button transparent learn-more">Learn More</a>
	</div>
</div>

<div class="main-inner">
	<div class="product-kits">
		<div class="faq-header">
			<h4>Skincare Kits</h4>
			<hr>
		</div>
		{{block type="theme/catalog_product_kit_list" name="catalog.product.list" template="catalog/product/list.phtml" showTopToolbar="0" showBottomToolbar="0" columnCount="3"}}
	</div>

	<div class="single-products">
		<div class="faq-header">
			<h4>Single Products & Travel Kits</h4>
			<hr>
		</div>
		{{block type="theme/catalog_product_kit_list" name="catalog.product.list" template="catalog/product/carousel/list.phtml" showTopToolbar="0" showBottomToolbar="0" columnCount="3" isKit="0"}}
	</div>
</div>

{{block type="cms/block" block_id="products_page_text_slider"}}

<div class="fullwidth-banner get-give-banner" id="give-get-banner">
	<div class="fullwidth-banner-inner">
		<div class="faq-header">
			<h4>Give $100, Get $100</h4>
			<hr>
			<p>
Good friends share beauty secrets – that’s why we’ve created “Give $100 Get $100”, a program that rewards you and your friends for using our products.  It’s as simple as it sounds! Refer a friend to VenusSkin and when they sign up as a VIP member and place an order you’ll receive a $100 credit toward a future order.  Your friend will also receive a $100 credit!
			</p>
<a href="{{store url='invitation'}}" class="button tell-friends">Tell Your Friends</a>
		</div>
	</div>
</div>

<div class="modal-content">
	<div class="vip-modal">
		<h2>Are You a <strong>VIP</strong> Member?</h2>
		<hr>
		<p>Never run out of your favourite products!</p>
		<p>Automatic 30, 45, 60, or 90 day delivery options.</p>
		<p>Special "Members-Only" pricing as a valued member of our program.</p>
		<p>Exclusive access to promotions and insider information.</p>
		<p><strong>Free to join, cancel at any time!</strong></p>
		<a href="{{store url='customer/account/create'}}" class="button transparent join-now">Join Now</a>
	</div>
</div>

<script type="text/javascript">
jQuery(function($) {
		$('.vip-banner, .vip-banner-button').on('click', function() {
		LiteModal.create({
			overlayClose: true,
			buttonClose: true,
			content: $('.modal-content').html()
		});
	})
});
</script>
CONTENT;

$productsPage = Mage::getModel('cms/page')->load('products', 'identifier');
$productsPage->setContent($productsContent)
             ->save();

$installer->endSetup();
