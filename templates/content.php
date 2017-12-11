<?php
/**
 * standard post
 */
?>
<article class="post-list pb-3">
	<div class="post-image">
	<img width="100%" height="450rem" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ); ?>" onerror="src='http://dummyimage.com/800x400/747474/ffffff&text=No+Image'">
	</div>
	<h1 class="my-2"><?php the_title(); ?></h1>
	<i><?php the_category( ', ' ); ?>&nbsp;&nbsp;&bullet;&nbsp;&nbsp;<?php the_date()?>&nbsp;&nbsp;&bullet;&nbsp;&nbsp; Posted By : <a href="<?php the_author_link() ?>"><?php the_author(); ?></a></i>
	<div class="excerpt my-2">
	<?php the_excerpt(); ?>
	<button class="btn btn-secondary my-2"><a href="<?php the_permalink(); ?>">Read More</a></button>
	</div>
</article>