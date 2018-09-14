<?php if ( is_active_sidebar('right') ):
	$emarket_right_span_class = 'col-lg-'.emarket_options()->getCpanelValue('sidebar_right_expand');
	$emarket_right_span_class .= ' col-md-'.emarket_options()->getCpanelValue('sidebar_right_expand_md');
	$emarket_right_span_class .= ' col-sm-'.emarket_options()->getCpanelValue('sidebar_right_expand_sm');
?>
<aside id="right" class="sidebar <?php echo esc_attr($emarket_right_span_class); ?>">
	<?php dynamic_sidebar('right'); ?>
</aside>
<?php endif; ?>