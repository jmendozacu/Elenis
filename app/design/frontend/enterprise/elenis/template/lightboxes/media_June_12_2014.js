<?php
$helper				= Mage::helper('lightboxes');
$rel                = $helper->getLightboxRel($helper->getConfig('lightbox_type'));
$class              = $helper->getLightboxClass($helper->getConfig('lightbox_type'));
$mainImageSize      = $helper->getMainImageSize();
$popUpImageSize     = $helper->getPopupImageSize();
$thumbnailSize      = $helper->getThumbnailImageSize();
$includeMainImage	= $helper->getConfig('include_mainimage_in_lightbox');

$mainImageRel		= $rel;
$mainImageClass		= $class;
if($helper->getConfig('lightbox_type') == 'piroboxextended' && !$includeMainImage){
	$mainImageRel	= 'single';
	$mainImageClass = 'pirobox';
}elseif($helper->getConfig('lightbox_type') == 'prettyphoto' && !$includeMainImage){
	$mainImageRel	= 'prettyPhoto';
	$mainImageClass = '';
}elseif(!in_array($helper->getConfig('lightbox_type'),array('piroboxextended', 'prettyphoto')) && !$includeMainImage){
	$mainImageRel	= $rel . '-main';
	$mainImageClass = $class . '-main';
}
?>
<!-- jQuery LightBoxes -->
    <?php if($helper->getConfig('include_jquery', 'lightboxes_confliction') && $helper->getConfig('include_jquery_in', 'lightboxes_confliction') == 'media'): 	?>
		<?php echo $this->getChildHtml('jquery_inclusion'); ?>
	<?php endif; ?>

    <?php if($helper->getConfig('include_lighboxes_in', 'lightboxes_confliction') == 'media'): ?>
		<?php echo $this->getChildHtml('lightboxes_inclusion'); ?>
	<?php endif; ?>

	<?php if($helper->getConfig('include_initialization_in', 'lightboxes_confliction') == 'media'): ?>
		<?php echo $this->getChildHtml('lightboxes_initialization'); ?>
	<?php endif; ?>
<!-- //jQuery LightBoxes -->
<?php
    $_product = $this->getProduct();
    $_helper = $this->helper('catalog/output');
?>
<?php if ($_product->getImage() != 'no_selection' && $_product->getImage()): ?>

<?php
//check if zoomer is disabled
if($helper->getConfig('disable_zoombar')):
?>
<p class="product-image">
    <?php
        $_img = '<img src="'.$this->helper('catalog/image')->init($_product, 'image')->resize($mainImageSize[0], $mainImageSize[1]).'" alt="'.$this->htmlEscape($this->getImageLabel()).'" title="'.$this->htmlEscape($this->getImageLabel()).'" />';
        if(empty($popUpImageSize[0]) || empty($popUpImageSize[0])):
            $popUpImage = $this->helper('catalog/image')->init($_product, 'image');
        else:
            $popUpImage = $this->helper('catalog/image')->init($_product, 'image')->resize($popUpImageSize[0], $popUpImageSize[1]);
        endif;

    ?>
    <a href="<?php echo $popUpImage; ?>" rel="<?php echo $mainImageRel; ?>" class="<?php echo $mainImageClass; ?>" title="<?php echo $this->htmlEscape($this->getImageLabel()) ?>"><?php echo $_helper->productAttribute($_product, $_img, 'image'); ?></a>
</p>
<?php else: ?>
<p class="product-image product-image-zoom">
    <?php
        $_img = '<img id="image" src="'.$this->helper('catalog/image')->init($_product, 'image').'" alt="'.$this->htmlEscape($this->getImageLabel()).'" title="'.$this->htmlEscape($this->getImageLabel()).'" />';
        echo $_helper->productAttribute($_product, $_img, 'image');
    ?>
</p>
<p class="zoom-notice" id="track_hint"><?php echo $this->__('Double click on above image to view full picture') ?></p>
<div class="zoom">
    <img id="zoom_out" src="<?php echo $this->getSkinUrl('images/slider_btn_zoom_out.gif') ?>" alt="<?php echo $this->__('Zoom Out') ?>" title="<?php echo $this->__('Zoom Out') ?>" class="btn-zoom-out" />
    <div id="track">
        <div id="handle"></div>
    </div>
    <img id="zoom_in" src="<?php echo $this->getSkinUrl('images/slider_btn_zoom_in.gif') ?>" alt="<?php echo $this->__('Zoom In') ?>" title="<?php echo $this->__('Zoom In') ?>" class="btn-zoom-in" />
</div>
<script type="text/javascript">
//<![CDATA[
    Event.observe(window, 'load', function() {
        product_zoom = new Product.Zoom('image', 'track', 'handle', 'zoom_in', 'zoom_out', 'track_hint');
    });
//]]>
</script>
<?php endif; ?>

<?php else: ?>
<p class="product-image">
    <?php
        $_img = '<img src="'.$this->helper('catalog/image')->init($_product, 'image')->resize(265).'" alt="'.$this->htmlEscape($this->getImageLabel()).'" title="'.$this->htmlEscape($this->getImageLabel()).'" />';
        echo $_helper->productAttribute($_product, $_img, 'image');
    ?>
</p>
<?php endif; ?>
<?php if (count($this->getGalleryImages()) > 0):
?>

<div class="more-views" id="more-views-div">
    <h2><?php echo $helper->getConfig('more_views_label') ?></h2>
    <ul>
    <?php $galleryData = $_product->getMediaGallery ();
    $totalNumberOfImages=0;
    foreach ( $galleryData ['images'] as $_image ) {
         if($_image['disabled_default']!=1){
        $totalNumberOfImages+=1;
         }
    }
    $i=0;
                    foreach ( $galleryData ['images'] as $_image ) :

   
          if($_image['disabled_default']==0){
   
            if(empty($popUpImageSize[0]) || empty($popUpImageSize[0])):
                $popUpImage = $this->helper('catalog/image')->init($this->getProduct(), 'image', $_image['file']);
            else:
                $popUpImage = $this->helper('catalog/image')->init($this->getProduct(), 'image', $_image['file'])->resize($popUpImageSize[0], $popUpImageSize[1]);
            endif;
            if($i<3){
		if($totalNumberOfImages>3)
		{
			$totalNumberOfImages=3;
		}
   
    if(($totalNumberOfImages-1)==$i)
    { ?>
		<li id="<?php echo $i; ?>" class="last">
           <a href="<?php echo $popUpImage; ?>" rel="<?php echo $rel; ?>" class="<?php echo $class; ?>" title="<?php echo $this->htmlEscape($label) ?>"><img src="<?php echo $this->helper('catalog/image')->init($this->getProduct(), 'thumbnail', $_image['file'])->resize($thumbnailSize[0], $thumbnailSize[1]); ?>" alt="<?php echo $this->htmlEscape($label) ?>" /></a>
        </li>
		<?php
    }else
    {
   
        ?>
    <li id="<?php echo $i; ?>">
            <a href="<?php echo $popUpImage; ?>" rel="<?php echo $rel; ?>" class="<?php echo $class; ?>" title="<?php echo $this->htmlEscape($label) ?>"><img src="<?php echo $this->helper('catalog/image')->init($this->getProduct(), 'thumbnail', $_image['file'])->resize($thumbnailSize[0], $thumbnailSize[1]); ?>" alt="<?php echo $this->htmlEscape($label) ?>" /></a>
        </li>
<?php    }  ?>

    <?php $i++;}
     } endforeach; ?>
    </ul>
</div>
<?php endif; ?>


 
