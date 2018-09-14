<?php if ( is_active_sidebar('primary') ):
	$primary_span_class = 'span'.emarket_options()->getCpanelValue('sidebar_primary_expand');
?>
<aside id="primary" class="sidebar <?php echo esc_attr($primary_span_class); ?>">
	<?php dynamic_sidebar('primary'); ?>
</aside>
<?php endif; ?>