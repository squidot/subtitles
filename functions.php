<?php

/* -------------------------------------------------------------------------*
 * 							Intro Text										                              *
 * -------------------------------------------------------------------------*/
if(is_admin()){
	if(!function_exists('squidot_add_intro_text')){
		function squidot_add_intro_text( $post ) {
			$screen = get_current_screen();
			if ( $screen->post_type == 'post') {
				$intro_text = get_post_meta($post->ID, '_squidot_intro_text', true);
				wp_nonce_field( 'squidot_intro_text_nonce', '_squidot_intro_text_nonce' );
				?>
				<div id="squidot_intro_div">
					<div id="squidot_intro_wrap">
						<?php $intro__placeholder = apply_filters( 'enter_title_here', __( 'Enter Intro text here' , 'newtown'), $post );?>
						<label class="screen-reader-text" id="squidot_intro_text-prompt-text" for="squidot_intro_text"><?php echo $intro__placeholder; ?></label>
						<input type="text" name="post_intro_text" size="30" value="<?php echo esc_attr( $intro_text ); ?>" id="squidot_intro_text" spellcheck="true" autocomplete="off" placeholder="<?php _e('Enter Intro Text here', 'newtown'); ?>" />
					</div>
				</div>
				<?php
			}
		}
		// add the action 
		add_action( 'edit_form_after_title', 'squidot_add_intro_text', 10, 1 ); 
	}
	if(!function_exists('squidot_save_post_intro_text')){
		function squidot_save_post_intro_text(){
			global $post;
			
			// Verify if this is an auto save routine. 
			// If it is our form has not been submitted, so we dont want to do anything
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			
			if ( !isset( $_POST[ '_squidot_intro_text_nonce' ] )) {
				return;
			}
			
			// Verify nonce
			if ( !wp_verify_nonce( $_POST[ '_squidot_intro_text_nonce' ], 'squidot_intro_text_nonce' ) ) {
				return;
			}
			
			// Check data and save
			if ( isset( $_POST['post_intro_text'] ) ) {
				$new_value = sanitize_text_field($_POST['post_intro_text']);
				update_post_meta($post->ID, '_squidot_intro_text', $new_value);
			}
		}
		add_action( 'post_updated', 'squidot_save_post_intro_text', 9 );
		add_action( 'save_post', 'squidot_save_post_intro_text' );
	}
  if(!function_exists('squidot_the_intro_text')){
    function squidot_save_post_intro_text($before='', $after='', $echo=true, $post_ID=null){
      if($post_ID==null){
          $post_ID=get_the_ID();
      }
      if($echo){
        echo $before . get_post_meta($post_ID, '_squidot_intro_text') . $after;
      }
      else{
        return get_post_meta($post_ID, '_squidot_intro_text');
      }
    }
  }
}
