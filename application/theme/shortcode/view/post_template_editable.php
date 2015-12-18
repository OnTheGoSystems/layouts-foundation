<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Cornerstone
 * @since Cornerstone 2.2.2
 */
?>

<article id="post-[wpv-post-id]" class="[wpv-post-class]" itemscope="" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">

    <header class="entry-header">
        <a href="[wpv-post-url]" rel="bookmark">
            [wpv-post-title]
        </a>
    </header>

        <div class="entry-content" itemprop="text">
            [wpv-post-body view_template="None"]
        </div>

    <footer class="entry-footer">
        <p class="entry-meta">
            [wpv-conditional if="'[wpv-post-taxonomy type=category format=name]' != ''"]
			<span class="entry-categories">
				Filed Under:
				[wpv-post-taxonomy type="category"]
			</span>
            [/wpv-conditional]

            [wpv-conditional if="'[wpv-post-taxonomy type=post_tag format=name]' != ''"]
			<span class="entry-tags">Tagged With:
				[wpv-post-taxonomy type="post_tag"]
			</span>
            [/wpv-conditional]
        </p>
    </footer>

</article>