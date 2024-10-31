<?php

/*
 Plugin Name: Restrict Contributors from Scheduled Posts
 Description: Restricts contributors from editing their posts once those posts are scheduled to publish.
 Version: 1.1
 Author: Johnathon Williams (Odd Jar)
 Author URI: http://oddjar.com/
 */

/**
 * Copyright (c) 2012 Johnathon Williams (Odd Jar). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */


add_action( 'admin_print_styles', 'oj_hide_scheduled' );
add_filter( 'pre_get_posts', 'oj_restrict_scheduled_posts' );

function oj_restrict_scheduled_posts( $query ) {
	if ( is_admin() && ! current_user_can( 'edit_published_posts' ) ) {
		if ( isset( $query->query_vars['post_type'] ) ) {

			if ( $query->query_vars['post_type'] == 'post' ) {

				if ( ! isset( $query->query_vars['post_status'] ) ) {
					// return all but scheduled
					$query->set( 'post_status', array( 'pending', 'draft', 'publish' ) );
					return $query;
				}

				if ( $query->query_vars['post_status'] == 'pending' ) {
					// return pending
					$query->set( 'post_status', array( 'pending' ) );
				}

				if ( $query->query_vars['post_status'] == 'draft' ) {
					// return draft
					$query->set( 'post_status', array( 'draft' ) );
				}


				if ( $query->query_vars['post_status'] == 'publish' ) {
					// return published
					$query->set( 'post_status', array( 'publish' ) );
				}

				if ( $query->query_vars['post_status'] == 'future' ) {
					// return none if scheduled requested
					$query->set( 'post_status', array( 'draft' ) );
				}

			}
		}
	}

	return $query;
}

function oj_hide_scheduled() {
	if ( ! current_user_can( 'edit_published_posts' ) ) {
?>
			<style type="text/css">
				ul.subsubsub li.future {
					display:none !important;
				}
			</style>

		<?php
	}
}
?>