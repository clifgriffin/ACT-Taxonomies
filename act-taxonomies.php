<?php
/*
Plugin Name: ACT Taxonomies
Plugin URI: http://cgd.io
Description:  Easily enable taxonomies for use with ACT templates.
Version: 1.0.2
Author: CGD Inc.
Author URI: http://cgd.io
GitHub URI: https://github.com/clifgriffin/ACT-Taxonomies

------------------------------------------------------------------------
Copyright 2009-2015 Clif Griffin Development Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

class ACT_Taxonomies {
	public function __construct() {
		add_action('act_admin_page_after_row', array($this, 'add_taxonomy_settings'), 10, 1 );
		add_action('init', array($this, 'register_template_taxonomies'), 20 ); // anything after priority 0 should be fine
	}

	function add_taxonomy_settings( $plugin ) {

		$taxonomies = get_taxonomies(null, 'objects');
		$current_taxonomies = $plugin->get_setting('taxonomies');
		?>
		<tr>
			<th scope="row" valign="top">Registered Taxonomies</th>
			<td>
				<input type="hidden" name="<?php echo $plugin->get_field_name('taxonomies'); ?>[]" value="" />
				<?php if ( ! empty($taxonomies) && is_array($taxonomies) ): ?>
					<?php foreach($taxonomies as $tax):
						if ( in_array($tax->name, array('nav_menu', 'link_category', 'post_tag', 'category', 'post_format') ) ) continue; ?>
						<p>
							<label>
								<input type="checkbox" name="<?php echo $plugin->get_field_name('taxonomies'); ?>[]" value="<?php echo $tax->name; ?>" <?php if ( in_array($tax->name, $current_taxonomies) ) echo "checked='checked'"; ?>> <?php echo $tax->label; ?>
							</label>
						</p>
					<?php endforeach; ?>
				<?php endif; ?>
				<p class="description">These taxonomies will be available to use with content templates.</p>
			</td>
		</tr>
		<?php
	}

	function register_template_taxonomies() {
		global $Advanced_Content_Templates;

		if ( empty($Advanced_Content_Templates) ) return;

		$taxonomies = $Advanced_Content_Templates->get_setting('taxonomies');
		if ( empty($taxonomies) || ! is_array($taxonomies) ) return;

		foreach($taxonomies as $tax) {
			if ( empty($tax) ) continue;
			register_taxonomy_for_object_type( $tax, $Advanced_Content_Templates->post_type );
		}
	}
}

$ACT_Taxonomies = new ACT_Taxonomies();
