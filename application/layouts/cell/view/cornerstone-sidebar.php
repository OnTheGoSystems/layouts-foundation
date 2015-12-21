<?php do_action( 'cornerstone_before_sidebar' ); ?>
<?php if ( is_active_sidebar( 'right_sidebar' ) ) : ?>
    <?php dynamic_sidebar( 'right_sidebar' ); ?>
<?php endif; ?>
<?php do_action( 'cornerstone_after_sidebar' ); ?>