<?php
/*
	Plugin Name: Mandrill Send From Subaccount
	Description: Send Mandrill Emails From Subaccount. Please ensure to enable wpMandrill prior to enabling this plugin.
	Author: Soji Jacob
	Version: 1.0.0
*/
add_action( 'admin_init', 'mchild_plugin_has_parent_plugin' );
function mchild_plugin_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'wpmandrill/wpmandrill.php' ) ) {
        add_action( 'admin_notices', 'mchild_plugin_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function mchild_plugin_notice(){
    ?><div class="error"><p>Sorry, but Mandrill Subaccount Plugin requires the wpMandrill plugin to be installed and active.</p></div><?php
}

add_action('admin_menu', function() {
    add_options_page( 'Mandrill Subaccount Setting', 'Mandrill Subaccount', 'manage_options', 'mandrill-subaccount-plugin', 'msubaccount_plugin_page' );
});

add_action( 'admin_init', function() {
    register_setting( 'msubaccount-plugin-settings', 'mandrill_subaccount' );
});

function msubaccount_plugin_page() {
 ?>
   <div class="wrap">
     <form action="options.php" method="post">
       <?php
       settings_fields( 'msubaccount-plugin-settings' );
       do_settings_sections( 'msubaccount-plugin-settings' );
       ?>
       <h3>Mandrill Sub-Account ID:</h3>
       <table>
             
            <tr>
                <th>Subaccount ID:</th>
                <td><input type="text" placeholder="ex: soji" name="mandrill_subaccount" value="<?php echo esc_attr( get_option('mandrill_subaccount') ); ?>" size="50" /></td>
            </tr>
            <tr>
                <td><?php submit_button(); ?></td>
            </tr>
        </table>
     </form>
   </div>
 <?php
}
function msub_add_mandrill_subaccount( $message ) {
	$subaccount_id = get_option('mandrill_subaccount');
	if ( $subaccount_id ) {
		$message['subaccount'] = $subaccount_id;
	}
	return $message;
}
if(get_option('mandrill_subaccount')) {
add_filter( 'mandrill_payload', 'msub_add_mandrill_subaccount' );
}
