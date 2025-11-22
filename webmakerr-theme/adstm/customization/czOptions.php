<?php

namespace ads\customization;

class czOptions {
    public static function getTemplateField( $template_name ) {
        $template_name = \sanitize_key( $template_name );
        if ( empty( $template_name ) ) {
            return '';
        }

        $template_path = __DIR__ . '/defaults_template/' . $template_name . '.php';

        if ( ! file_exists( $template_path ) ) {
            return '';
        }

        ob_start();
        include $template_path;

        return ob_get_clean();
    }
}
