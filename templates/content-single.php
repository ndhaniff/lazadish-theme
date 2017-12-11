<?php
/**
 * standard single post
 */
?>
<article>
	<div class="post-image">
	<img width="100%" height="450rem" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ); ?>" onerror="style='display:none'">
	</div>
	<h1 class="my-2"><?php the_title(); ?></h1>
	<i><?php the_category( ', ' ); ?>&nbsp;&nbsp;&bullet;&nbsp;&nbsp;<?php the_date()?>&nbsp;&nbsp;&bullet;&nbsp;&nbsp; Posted By : <a href="<?php the_author_link() ?>"><?php the_author(); ?></a></i>
	<div class="excerpt my-5">
	<div class="single-content">
		<?php the_content(); ?>
	</div>
	<?php comments_template(); ?> 
	</div>
</article>