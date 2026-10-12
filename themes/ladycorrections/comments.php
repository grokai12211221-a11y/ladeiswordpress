<?php
/**
 * Comments template — basic and safe.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="lc-comments">

	<?php if ( have_comments() ) : ?>
		<h3>
			<?php
			$count = get_comments_number();
			/* translators: %s: comment count. */
			printf( esc_html( _n( '%s Comment', '%s Comments', $count, 'ladycorrections' ) ), esc_html( number_format_i18n( $count ) ) );
			?>
		</h3>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => '&larr; ' . esc_html__( 'Older comments', 'ladycorrections' ),
				'next_text' => esc_html__( 'Newer comments', 'ladycorrections' ) . ' &rarr;',
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p><?php esc_html_e( 'Comments are closed.', 'ladycorrections' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'         => esc_html__( 'Leave a reply', 'ladycorrections' ),
			'label_submit'        => esc_html__( 'Post comment', 'ladycorrections' ),
			'comment_field'       => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'ladycorrections' ) . '</label><textarea id="comment" name="comment" rows="5" required></textarea></p>',
			'submit_button'       => '<button name="%1$s" type="submit" id="%2$s" class="%3$s lc-btn lc-btn--primary">%4$s</button>',
			'class_submit'        => '',
		)
	);
	?>

</div>
