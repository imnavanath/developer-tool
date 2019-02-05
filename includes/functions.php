<?php
/**
 * Developer Tools functions file.
 *
 * @package Developer Tool
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read bebug file.
 *
 * @param type $fileName
 * @since 1.0.0
 */
function read_debug_log_file( $fileName ) {

    $filePath = get_home_path() . $fileName;

    if ( file_exists( $filePath ) ) {

        $file = fopen( $filePath, "r" );
        $responce = '';
        fseek( $file, -1048576, SEEK_END );

        while ( !feof( $file ) ) {
            $responce .= fgets($file);
        }

        fclose( $file );
        return $responce;
    }

    return false;
}

/**
 * Write file content in Debug file
 * 
 * @param type $content
 * @param type $fileName
 * @since 1.0.0
 */
function write_debug_log_file( $content, $fileName ) {

    $output = error_log( '/*test*/', '3', get_home_path() . $fileName );

    if ( $output ) {
        unlink( get_home_path() . $fileName );
        error_log( $content, '3', get_home_path() . $fileName );
        chmod( get_home_path() . $fileName, 0600 );
    }

    return $output;
}

/**
 * Unlink debug.log file
 * 
 * @since 1.0.0
 */
function clear_debug_log() {

    $filePath = get_home_path() . 'wp-content/debug.log';
    $result['class'] = 'error';
    $result['message'] = __( 'Debug log not cleared.', 'developer-tool' );

    if ( file_exists( $filePath ) ) {

        $status = unlink( $filePath );

        if ($status) {
            $result['class'] = 'updated';
            $result['message'] = __( 'Debug log cleared successfully.', 'developer-tool' );
        }
    }

    return $result;
}

/**
 * Save developer tool's settings.
 *
 * @since 1.0.0
 */
function developer_tool_update_settings() {

    if ( isset( $_POST['developer-tool-settings'] ) && ! empty( $_POST['developer-tool-settings'] ) ) {

        $is_update = 1;
        $error_reporting = isset( $_POST['error_reporting'] ) ? trim( $_POST['error_reporting'] ) : '0';
        $error_log = isset( $_POST['error_log'] ) ? trim( $_POST['error_log'] ) : '0';
        $display_error = isset( $_POST['display_error'] ) ? trim( $_POST['display_error'] ) : '0';
        $error_script = isset( $_POST['error_script'] ) ? trim( $_POST['error_script'] ) : '0';
        $fileName = 'wp-config.php';
        $fileContent = read_debug_log_file( $fileName );
        $fileContent = debug_add_option( $error_reporting, 'WP_DEBUG', $fileContent );
        $fileContent = debug_add_option( $error_log, 'WP_DEBUG_LOG', $fileContent );
        $fileContent = debug_add_option( $display_error, 'WP_DEBUG_DISPLAY', $fileContent );
        $fileContent = debug_add_option( $error_script, 'SCRIPT_DEBUG', $fileContent );

        if ( write_debug_log_file( $fileContent, $fileName ) ) { ?>

            <script>
                window.location = '<?php echo admin_url( 'admin.php?page=' . trim($_GET['page']) . '&update=' . $is_update ); ?>';
            </script>
            <?php
        } else {
            echo '<div class="error settings-error">';
            echo '<p><strong>' . __( 'Your wp-config file not updated. Copy and paste following code in your wp-config.php file.', 'developer-tool' ) . '</strong></p>';
            echo '</div>';
            echo '<textarea style="width:100%; height:400px">' . htmlentities( $fileContent ) . '</textarea>';
        }
    } elseif ( isset( $_GET['update'] ) && $_GET['page'] == 'debug_settings' ) {
        $output['status'] = 'updated';
        $output['message'] = 'Setting saved successfully.';
        if ($_GET['update'] == 0) {
            $output['status'] = 'error';
        }
        ?>

        <div class="<?php echo $output['status']; ?> settings-error"> 
            <p><strong><?php _e($output['message'], 'developer-tool'); ?></strong></p>
        </div>
        <?php
    }
}

/**
 * Allow the file to download
 *
 * @param type $path
 * @since 1.0.0
 */
function download_wp_config_file( $path ) {
    $content = read_debug_log_file( $path );
    header( 'Content-type: application/octet-stream', true );
    header( 'Content-Disposition: attachment; filename="' . basename($path) . '"', true );
    header( "Pragma: no-cache" );
    header( "Expires: 0" );
    echo $content;
    exit();
}

/**
 * Allow to download wp-config file for backup.
 *
 * @since 1.0.0
 */
function developer_tool_config() {

    if( is_super_admin() ){
        download_wp_config_file( 'wp-config.php' );
    }
}
