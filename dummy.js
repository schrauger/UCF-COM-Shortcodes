/**
 * Created by stephen on 11/3/14.
 * This file simply exists so that WordPress can use wp_localize_script in order to
 * set javascript variables. The plugin.js script will read these global variables
 * and use them in its logic. However, the plugin.js file is loaded as an external
 * plugin for tinymce, and we don't want to load that file twice (duplicate functions).
 * Therefore, this file was created.
 *
 * It is loaded with wp_register_script, then used in wp_localize_script in order to set
 * global javascript variables (which get used by plugin.js).
 */
