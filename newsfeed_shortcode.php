<?php
require_once( 'com_shortcode.php' );

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 12/11/15
 * Time: 3:00 PM
 */
class newsfeed_shortcode extends com_shortcode {

	const name          = 'newsfeed'; // the text entered by the user (inside square brackets)
	const section_name  = 'newsfeed_settings'; //unique section id that organizes each setting
	const section_title = 'Newsfeed Listing [newsfeed]'; // Section header for this shortcode's settings

	const taxonomy_categories = 'news_category'; // the slug for the 'news' taxonomy, used in the 'news' custom post type.

	const tinymce_newsfeed_category   = 'category';
	const tinymce_hide_news   = 'hide_news'; // if set, the news list will not display (if you only want a slider, for example)
	const show_slider_default  = 'ucf_com_newsfeed_show_slider_default';
	const tinymce_show_slider   = 'show_slider';
	const news_count_default  = 'ucf_com_newsfeed_news_count_default';
	const tinymce_news_count = 'news_count'; // number of articles to show in list
	const slider_count_default  = 'ucf_com_newsfeed_slider_count_default';
	const tinymce_slider_count = 'slider_count'; // number of articles to include with the slider (show_slider must be true for this to matter)
	const tinymce_blog_name_or_id = 'blog'; // name or id of blog

	public function get_name() {
		return self::name;
	}

	public function get_css() {
		return '';
	}

	public function get_section_name() {
		return self::section_name;
	}

	public function get_section_title() {
		return self::section_title;
	}

	public function add_settings() {
		// settings that can have a global default. not all user-defined settings should have a default, so don't put them all here.
		$this->add_setting(
			self::show_slider_default,// ID used to identify the field throughout the theme
			'show_slider',                           // The label to the left of the option interface element
			'Set default for showing the slider. If blank, the slider will not show. If defined, the slider will show by default.'
		);
		$this->add_setting(
			self::news_count_default,// ID used to identify the field throughout the theme
			'news_count',                           // The label to the left of the option interface element
			'Set number of articles to show in news list by default. Must be a positive integer. If undefined, will show all articles.'
		);
		$this->add_setting(
			self::slider_count_default,// ID used to identify the field throughout the theme
			'slider_count',                           // The label to the left of the option interface element
			'Set number of articles to show in slider by default. Must be a positive integer. If undefined, will be equal to news_count.'
		);

		$this->add_setting_custom_fields_group();
	}

	public function replacement( $attrs = null ) {
		//return print_r($attrs);
		$newsfeed_category = $attrs[ self::tinymce_newsfeed_category ];
		$hide_news =  $attrs[ self::tinymce_hide_news ];
		$show_slider = ( ( $attrs[ self::tinymce_show_slider ] ) ? $attrs[ self::tinymce_show_slider ] : $this->get_database_settings_value( self::show_slider_default ) );
		$news_count = ( ( $attrs[ self::tinymce_news_count ] ) ? $attrs[ self::tinymce_news_count ] : $this->get_database_settings_value( self::news_count_default ) );
		$slider_count = ( ( $attrs[ self::tinymce_slider_count ] ) ? $attrs[ self::tinymce_slider_count ] : $this->get_database_settings_value( self::slider_count_default ) );
		$blog_id = ( ( $attrs[ self::tinymce_blog_name_or_id] ) ? $attrs[ self::tinymce_blog_name_or_id ] : get_current_blog_id() );


		if (!$slider_count){
			// if slider_count is undefined, default to the same as news_count
			$slider_count = $news_count;
		}
		$return = '';
		//$return .= '<div class="news-container white-box">';

		// get blog number if not numeric
		if (($blog_id) && (!(is_numeric($blog_id)))){
			$blog_id = get_id_from_blogname($blog_id);
		}

		if ($blog_id) {
			switch_to_blog($blog_id);
		}

		if ($show_slider) {
			global $newsHomeSlider; // the footer in the theme checks this global.
			$newsHomeSlider = true;
			$return .= $this->replacement_slider($newsfeed_category, $slider_count);
		}
		if (!($hide_news)){
			$return .= $this->replacement_news($newsfeed_category, $news_count);
		}

		if ($blog_id) {
			restore_current_blog();
		}

		//$return .= '</div>';
		return $return;

	}

	function replacement_slider($newsfeed_category, $slider_count){
		if ($newsfeed_category) {
			// get the category.

			// remove all non alpha-numerics characters from the user input.
			$newsfeed_category = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $newsfeed_category ) );

			// get all categories.
			$all_newsfeed_categories = get_terms( self::taxonomy_categories );
			// loop through each category and check for match
			foreach ( $all_newsfeed_categories as $category ) {
				//  remove all non-alpha-numeric characters from slug and name.
				$category_normalized_slug = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $category->slug ) );
				$category_normalized_name = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $category->name ) );

				// match on either slug or name. NOTE: this does allow for the possibility of overlap, but the ability to not worry
				// about capitalization or other symbols outweighs the possibility of duplicate names. If this becomes a problem in the
				// future, just remove the normalization code and check explicit characters for a match.
				if ( ( $newsfeed_category == $category_normalized_name ) || ( $newsfeed_category == $category_normalized_slug ) ) {
					// if match found, set the newsfeed_category to the slug of the matching category. use this for the sql query.
					$newsfeed_category = $category->slug;

				}
			}
			if ( ! ( $newsfeed_category ) ) {
				// if no match found, set the newsfeed_category to null. this will return all profiles and subtly let the user know
				// that their category is incorrect.

				// NOTE: may wish to do the opposite and return 0 profiles. this way, the user knows for sure they entered a wrong
				// category, rather than glancing to see they have profiles returning and not checking they are the correct ones.

				$newsfeed_category = '!no_category'; // cause the query to return no results (there shouldn't exist a slug with exclamation points)
			}

			$args = array(
				'widget' => 'news-large',
				'switch_to_main' => false, // don't switch inside the include; we handle any required switching prior to this function call
				'post_type' => 'news',
				'posts_per_page' => $slider_count,
				'tax_query' => array(
					array(
						'taxonomy' => self::taxonomy_categories,
						'field' => 'slug',
						'terms' => $newsfeed_category,
						'operator' => 'IN'
					)
				)
			);
		} else {
			// no category specified by user. set to blank, so that all profiles are returned.
			$newsfeed_category = '';

			$args = array(
				'widget' => 'news-large',
				'switch_to_main' => false,
				'post_type' => 'news',
				'posts_per_page' => $slider_count,
			);
		}

		$return = '';
		$return .= "<div id='news-home-slider' class='main-feed-container'><div>";
		$return .= posts_to_feeds($args);
		$return .= "</div></div>";
		return $return;
	}
	function replacement_news($newsfeed_category, $news_count){
		if ($newsfeed_category) {

			// get the category.

			// remove all non alpha-numerics characters from the user input.
			$newsfeed_category = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $newsfeed_category ) );

			// get all categories.
			$all_newsfeed_categories = get_terms( self::taxonomy_categories );
			// loop through each category and check for match
			foreach ( $all_newsfeed_categories as $category ) {
				//  remove all non-alpha-numeric characters from slug and name.
				$category_normalized_slug = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $category->slug ) );
				$category_normalized_name = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $category->name ) );

				// match on either slug or name. NOTE: this does allow for the possibility of overlap, but the ability to not worry
				// about capitalization or other symbols outweighs the possibility of duplicate names. If this becomes a problem in the
				// future, just remove the normalization code and check explicit characters for a match.
				if ( ( $newsfeed_category == $category_normalized_name ) || ( $newsfeed_category == $category_normalized_slug ) ) {
					// if match found, set the newsfeed_category to the slug of the matching category. use this for the sql query.
					$newsfeed_category = $category->slug;

				}
			}
			if ( ! ( $newsfeed_category ) ) {
				// if no match found, set the newsfeed_category to null. this will return all profiles and subtly let the user know
				// that their category is incorrect.

				// NOTE: may wish to do the opposite and return 0 profiles. this way, the user knows for sure they entered a wrong
				// category, rather than glancing to see they have profiles returning and not checking they are the correct ones.

				$newsfeed_category = '!no_category'; // cause the query to return no results (there shouldn't exist a slug with exclamation points)
			}

			$args = array(
				'post_type' => 'news',
				'posts_per_page' => $news_count,
				'tax_query' => array(
					array(
						'taxonomy' => self::taxonomy_categories,
						'field' => 'slug',
						'terms' => $newsfeed_category,
						'operator' => 'IN'
					)
				)
			);
		} else {
			// no category specified by user. set to blank, so that all profiles are returned.
			$newsfeed_category = '';

			$args = array(
				'post_type' => 'news',
				'posts_per_page' => $news_count,
			);
		}
		$return = '';
		$return .= "<div class='text-feed-container'><div>";
		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) {
			$loop->the_post();
			$return .= '<p><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></p>';
		};
		wp_reset_postdata();
		$return .= "</div></div>";
		return $return;
	}
}

