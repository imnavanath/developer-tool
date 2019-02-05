<?php
/**
 * Developer Tools settings form file.
 *
 * @package Developer Tool
 */

defined( 'ABSPATH' ) || exit;
?>

<style>
    #debug-log{
        width: 100%;
        padding: 10px;
        word-wrap: break-word;
        background: black;
        font-family: Courier New !important;
        color: #fff;
        border-radius: 5px;
        height: 400px;
        overflow-y: auto;
    }
</style>

<div class="wrap">
    <h2> <?php _e( 'Error Log','developer-tool' );?> </h2>
    <?php
    if ( isset( $_POST['clearlog'] ) ) {
        $responce = clear_debug_log();
        ?>
        <div class="<?php echo $responce['class'];?> settings-error"> 
            <p> <strong> <?php echo $responce['message']; ?> </strong> </p>
        </div>
        <?php
    }

    $content = read_debug_log_file( 'wp-content/debug.log' );

    if ( ! empty( $content ) ) { ?>
        <style type="text/css">
            li#wp-admin-bar-developer-tool {
                background: red !important;
            }
        </style>
    <?php }

    if( $content !== false ) {

        echo '<form method="post" action="">';
        echo '<p style="  float: right;margin-top: -32px;"><input type="submit" name="clearlog" id="clearlog" class="button button-primary" value="'.__( 'Clear Log','developer-tool' ).'">';
        echo '</p></form>';
        echo '<textarea id="debug-log">' . htmlentities( $content ) . '</textarea>';
    } else {

        echo '<div class="notice settings-error">';
        echo '<p class="description"> Congratulations!!! You do not have any error log. </p>';
        echo '</div>';
    }
    ?>
</div>

<div class="wrap">
    <h2><?php _e( 'Debug Settings','developer-tool' );?></h2>
    <form method="post" action="">
        <table class="form-table">
            <tbody>
                <tr>
                    <p class="description">
                        <?php _e( '( These settings will overwrite wp-config.php file. Please make sure to backup first. )','developer-tool' );?>
                    </p>
                    <th>                        
                        <?php _e( 'wp-config.php File Backup','developer-tool' );?>
                    </th>
                    <td>
                        <input type="submit" name="download_config_file" id="download_config_file" class="button button-primary" value="<?php _e( 'Download','developer-tool' );?>">
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Enable Debug Settings','developer-tool' );?></th>
                    <td>
                        <fieldset>
                            <div>
                                <label for="error_reporting">
                                    <input name="error_reporting" type="checkbox" id="error_reporting" value="1" <?php if ( defined('WP_DEBUG' ) && WP_DEBUG == true) { ?>checked="checked"<?php } ?>>
                                    <?php _e( 'Enable WP_DEBUG mode => ','developer-tool' );?>
                                </label>
                                <span class="description">
                                    <?php _e( 'define(WP_DEBUG, true);','developer-tool' );?>
                                </span>    
                            </div>

                            <div>
                                <label for="error_log">
                                    <input name="error_log" type="checkbox" id="error_log" value="1" <?php if (defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG == true) { ?>checked="checked"<?php } ?>>
                                    <?php _e( 'Enable debug logging to the /wp-content/debug.log file => ','developer-tool' );?> 
                                </label>
                                <span class="description">
                                    <?php _e( 'define(WP_DEBUG_LOG, true);','developer-tool' );?>
                                </span> 
                            </div>

                            <div>
                                <label for="display_error">
                                    <input name="display_error" type="checkbox" id="display_error" value="1" <?php if (defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY == true) { ?>checked="checked"<?php } ?>>
                                    <?php _e( 'Enable display of errors and warnings to frontend => ','developer-tool' );?>
                                </label>
                                <span class="description">
                                    <?php _e( 'define(WP_DEBUG_DISPLAY, true);','developer-tool' );?>
                                </span>
                            </div>

                            <div>
                                <label for="error_script">
                                    <input name="error_script" type="checkbox" id="error_script" value="1" <?php if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true ) { ?>checked="checked"<?php } ?>>
                                    <?php _e( 'Enable Script Debug - only needed if you are modifying these core files => ','developer-tool' );?> 
                                </label>
                                <span class="description">
                                    <?php _e( 'define( SCRIPT_DEBUG, true );','developer-tool' );?>
                                </span>
                            </div>

                        </fieldset>

                        <p class="submit">
                            <input type="submit" name="developer-tool-settings" id="submit" class="button button-primary" value="<?php _e( 'Save Changes','developer-tool' );?>">
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>        
    </form>
</div>
