<?php
require_once( 'com_shortcode.php' );

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 12/09/15
 * Time: 3:00 PM
 */
class staff_shortcode extends com_shortcode {

	const name          = 'staff'; // the text entered by the user (inside square brackets)
	const section_name  = 'staff_settings'; //unique section id that organizes each setting
	const section_title = 'Staff Listing [staff]'; // Section header for this shortcode's settings

	const taxonomy_categories = 'profiles_category'; // the slug for the 'categories' taxonomy, used in the 'profiles' custom post type.

	const tinymce_staff_category   = 'category'; // if unset, show all profiles. otherwise, limit to profiles in this category name or slug.
	const tinymce_no_image   = 'hide_photo'; // if set to anything, will cause profile photos to hide via css class 'no-img-card'.

	const contact_card_file_standard = 'contact-card.php';
	const contact_card_meta_key_standard = 'last_name';
	const contact_card_file_resident = 'contact-card-resident.php';
	const contact_card_meta_key_resident = 'res_last_name';

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
		$this->add_setting_custom_fields_group();
	}

	public function replacement( $attrs = null ) {
		$include_file = self::contact_card_file_standard;
		$meta_key = self::contact_card_meta_key_standard;

		$staff_category = $attrs[ self::tinymce_staff_category ];
		$no_image = $attrs[ self::tinymce_no_image ];

		if ($no_image) {
			$page_class = 'content no-img-card';
		} else {
			$page_class = 'content';
		}

		if ($staff_category) {

			// get the category.

			// remove all non alpha-numerics characters from the user input.
			$staff_category = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $staff_category ) );

			// get all categories.
			$all_staff_categories = get_terms( self::taxonomy_categories );
			// loop through each category and check for match
			foreach ( $all_staff_categories as $category ) {
				//  remove all non-alpha-numeric characters from slug and name.
				$category_normalized_slug = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $category->slug ) );
				$category_normalized_name = strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $category->name ) );

				// match on either slug or name. NOTE: this does allow for the possibility of overlap, but the ability to not worry
				// about capitalization or other symbols outweighs the possibility of duplicate names. If this becomes a problem in the
				// future, just remove the normalization code and check explicit characters for a match.
				if ( ( $staff_category == $category_normalized_name ) || ( $staff_category == $category_normalized_slug ) ) {
					// if match found, set the staff_category to the slug of the matching category. use this for the sql query.
					$staff_category = $category->slug;

				}
			}
			if ( ! ( $staff_category ) ) {
				// if no match found, set the staff_category to null. this will return all profiles and subtly let the user know
				// that their category is incorrect.

				// NOTE: may wish to do the opposite and return 0 profiles. this way, the user knows for sure they entered a wrong
				// category, rather than glancing to see they have profiles returning and not checking they are the correct ones.

				$staff_category = '!no_category'; // cause the query to return no results (there shouldn't exist a slug with exclamation points)
			}

			if ( $staff_category == 'residents') {
				// residents have different fields. use a specific template for them.
				$include_file = self::contact_card_file_resident;
				$meta_key = self::contact_card_meta_key_resident;
			}

			$args = array(
				'post_type' => 'profiles',
				'posts_per_page' => -1,
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'meta_key' => $meta_key,
				'tax_query' => array(
					array(
						'taxonomy' => self::taxonomy_categories,
						'field' => 'slug',
						'terms' => $staff_category,
						'operator' => 'IN'
					)
				)
			);
		} else {
			// no category specified by user. set to blank, so that all profiles are returned.
			$staff_category = '';

			$args = array(
				'post_type' => 'profiles',
				'posts_per_page' => -1,
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'meta_key' => $meta_key
			);
		}

		$return = '';

		// next, query the database for all profiles in the specified category.

		$the_query = new WP_Query( $args );

		while ( $the_query->have_posts() ) {
			// get each profile.
			$the_query->the_post();
			$return .= "<h4 class='toggle'>" . get_the_title() . "</h4>";
			$return .= "<div class='$page_class'>" . $this->include_file_return_output(plugin_dir_path( __FILE__ ) . $include_file ) . "</div>";
		}
		wp_reset_postdata();
		return $return;

	}
}

