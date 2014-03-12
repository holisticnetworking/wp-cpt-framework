<?php
/**
 * The template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); $citation = get_post_meta( $post->ID, 'wpcptf_headline_cite', true ); ?>
				<div class="post">
					<h1><?php the_title(); ?></h1>
					<div class="post-content">
						<?php the_content(); ?>
						<a href="<?php echo $citation['cite_url']; ?>"><h3><?php echo $citation['cite_title']; ?></h3></a>
						<blockquote><?php echo $citation['cite_description']; ?></blockquote>
					</div>
				</div>
			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
