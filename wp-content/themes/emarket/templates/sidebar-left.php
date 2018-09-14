<?php if ( is_active_sidebar('left') ):
	$emarket_left_span_class = 'col-lg-'.emarket_options()->getCpanelValue('sidebar_left_expand');
	$emarket_left_span_class .= ' col-md-'.emarket_options()->getCpanelValue('sidebar_left_expand_md');
	$emarket_left_span_class .= ' col-sm-'.emarket_options()->getCpanelValue('sidebar_left_expand_sm');
?>
<aside id="left" class="sidebar <?php echo esc_attr($emarket_left_span_class); ?>">
	<?php dynamic_sidebar('left'); ?>
</aside>
<?php endif; ?>