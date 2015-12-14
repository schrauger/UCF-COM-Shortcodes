<?php
/*
Description:    Wordpress script to grab custom posts from a taxonomy and pass it into a flexible widget which returns as a cached feed.
Author:         Rob DiVincenzo <rob.divincenzo@gmail.com>
Parameters:		Same as the current args[] you would pass in Wordpress core EXCEPT YOU MUST ADD
				widget=>"" (string) The widget shell into which you wish to load your posts.
Version:		1.0.0
Notes:			define('TRANSIENT_EXPIRES') in wp-config to enable transient caching
				[posts_to_feeds] shortcode included. 

Shortcode Instructions:
Use | to declare separate arrays within string (i.e. multiple taxonomies for tax_queries) 
Use , to split string into a single array (i.e. multiple terms for one taxonomy)
Will split and generate tax_query based upon order.
To not switch back to blog(1) use switch_to_main="false"
To use multiple taxonomies you must include relation="" where relation is the relationship between the two (i.e. OR, AND, etc.)

Examples:
[posts_to_feeds widget="news-thumbnail" post_type="news" posts_per_page="4" feed_header="Recent News" operator="IN" taxonomy="news_category" field="slug" terms="global-health"]

means

[posts_to_feeds
	widget="news-thumbnail" (widget shell to load posts in)
	post_type="news" (news custom post type)
	posts_per_page="4" (4 posts per page)
	feed_header="Recent News" (Header to appear in feed. Default is false)
	operator="IN" (Operator for tax_query)
	taxonomy="news_category" (taxonomy for tax_query)
	field="slug" (field for tax_query)
	terms="global-health" (term for tax_query)
]

Multiple terms for per taxonomy looks like this
	terms="global-health,college-of-medicine"

Multiple taxonomies looks like this
	taxonomy="news_category|event" (Use | to seperate tax queries)
	field="slug|slug"
	terms="global-health,college-of-medicine|expired"
	operator="IN|NOT IN"
	relation="OR" (Only one value for this field)
*/

function posts_to_feeds( $params = array() ){
	// If the feed is not cached, get the feed
	if( false === ( $feed = get_transient( $params['posts_per_page'].'_'.$params['post_type'].'_'.$params['taxonomy'].'_'.$params['terms'] ) ) ):
	
	// Some mandatory custom defaults that are necessary for the rest of the function before WP_Query is constructed
	$custom_defaults = array(
				'widget' => '',
				'post_type' => '',
				'taxonomy' => '',
				'switch_to_main' => true,
				'feed_header' => false,
				'posts_per_page' => 5
			);
	// Merge them into the params array
	$params = array_merge($custom_defaults, $params);

	// Overwrite requests for non-published posts in case someone wants to get smart with us
	$params['post_status'] = 'publish';
	
	// So you want to do tax queries through a short code...? Oh, you fancy huh?
	if( isset( $params['terms'] ) ) {
		//split taxonomies into different arrays, get rid of any whitespace in case people aren't being exact
		$taxonomy_array = explode("|",$params['taxonomy']);
		foreach($taxonomy_array as $key=>$value){
			$taxonomy_array[$key] = trim($value);
		}

		//split terms into different arrays, get rid of any whitespace in case people aren't being exact
		$terms_array = explode("|",$params['terms']);
		foreach($terms_array as $key => $value){
			$terms_array[$key] = trim($value);
			$terms_array[$key] = explode(",",$value);
			foreach($terms_array[$key] as $term_key => $term_value){
				$terms_array[$key][$term_key] = trim($term_value);
			}
		}
				
		//split fields into different arrays, get rid of any whitespace in case people aren't being exact
		$field_array = explode("|",$params['field']);
		foreach($field_array as $key=>$value){
			$field_array[$key] = trim($value);
		}
				
		//split operators into different arrays, get rid of any whitespace in case people aren't being exact
		$operator_array = explode("|",$params['operator']);
		foreach($operator_array as $key=>$value){
			$operator_array[$key] = trim($value);
		}
		
		//Set the tax query relation if multiple tax queries, and therefore necessary
		if( count( $taxonomy_array ) > 1 ){
			$params['tax_query'] = array( 'relation' => $params['relation'] );
		}
		
		$count = 0;
		// Build our tax_query
		foreach( $taxonomy_array as $taxonomy ){
			$params['tax_query'][$count] = array(
					'taxonomy' => $taxonomy,
					'field' => $field_array[$count],
					'terms' => $terms_array[$count],
					'operator' => $operator_array[$count]
					);
			$count++;
		}
	}
	
	$switched = false;
	// Check if we should switch to the main blog or not
	if ( $params['switch_to_main'] && ( get_current_blog_id() != 1 ) ){
		$switched = true;
		switch_to_blog(1);
	}

	//Initialize our query
	$the_query = new WP_Query( $params );
	
	//Start the output buffer so we can return the output html and cache it
	ob_start();	
	
	//Load different widgets depending on what you're sending to the function
/*	switch( $params['widget'] ):
	case 'news-thumbnail':
		require(TEMPLATEPATH.'/includes/post_feeds/widgets/news-thumbnail.php');
		break;
	case 'news-large':
		require(TEMPLATEPATH.'/includes/post_feeds/widgets/news-large.php');
		break;
	case 'events-mini':
		require(TEMPLATEPATH.'/includes/post_feeds/widgets/events-mini.php');
		break;
	case 'events-full':
		require(TEMPLATEPATH.'/includes/post_feeds/widgets/events-full.php');
		break;
	default:
		break;
	endswitch;*/

	//Store the feed from the output buffer in a variable
	$feed = ob_get_contents();
	//End the output buffer and clean it
	ob_end_clean();
	// if we are expiring transients as declared through declare('TRANSIENT_EXPIRES') in wp-config.php, else I probably shouldn't
	if(defined('TRANSIENT_EXPIRES')){
		set_transient( $params['posts_per_page'].'_'.$params['post_type'].'_'.$params['taxonomy'], $feed, TRANSIENT_EXPIRES );
	}
	//Reset the post
	wp_reset_postdata();
	//If we switched blogs, switch back
	if ( $switched ){
		restore_current_blog();
	}
	endif;
	//Return our feed
	return $feed;
}// End posts_to_feeds

//Add shortcode capability
add_shortcode( 'posts_to_feeds', 'posts_to_feeds' );

//Feed Header include
function feed_header( $feed_header, $html_tag = "h2" ){
	if ( $feed_header != false ){
		return "<".$html_tag.">".$feed_header."</".$html_tag.">";
	}	
}
?>