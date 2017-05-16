<?php if (have_posts()) : ?>

	<?php do_action('wpforge_before_pagination'); ?>

<?php endif; ?>

<?php
if (function_exists("emm_paginate")) {
	emm_paginate();
} ?>

<?php do_action('wpforge_after_content'); ?>