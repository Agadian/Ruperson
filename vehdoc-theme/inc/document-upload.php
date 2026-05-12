<?php
/**
 * Vehdoc Document Upload System
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Allowed Document Types
 */
function vehdoc_allowed_document_types() {
    return array(
        'application/pdf'  => 'pdf',
        'image/jpeg'       => 'jpg',
        'image/png'        => 'png',
        'image/gif'        => 'gif',
    );
}

/**
 * Max Upload Size (10MB)
 */
function vehdoc_max_upload_size() {
    return 10 * 1024 * 1024;
}

/**
 * AJAX: Upload Documents for Order
 */
function vehdoc_ajax_upload_documents() {
    check_ajax_referer('wp_rest', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Not authenticated'));
    }

    $order_id = intval($_POST['order_id'] ?? 0);
    $order    = get_post($order_id);

    if (!$order || get_post_meta($order->ID, '_vehdoc_order_user', true) != get_current_user_id()) {
        wp_send_json_error(array('message' => 'Order not found'));
    }

    if (empty($_FILES['documents'])) {
        wp_send_json_error(array('message' => 'No files uploaded'));
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $allowed_types = vehdoc_allowed_document_types();
    $max_size      = vehdoc_max_upload_size();
    $existing_docs = get_post_meta($order->ID, '_vehdoc_documents', true) ?: array();
    $uploaded      = array();
    $errors        = array();

    $files = $_FILES['documents'];
    $count = is_array($files['name']) ? count($files['name']) : 1;

    for ($i = 0; $i < $count; $i++) {
        $name     = is_array($files['name']) ? $files['name'][$i] : $files['name'];
        $type     = is_array($files['type']) ? $files['type'][$i] : $files['type'];
        $tmp_name = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
        $error    = is_array($files['error']) ? $files['error'][$i] : $files['error'];
        $size     = is_array($files['size']) ? $files['size'][$i] : $files['size'];

        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = $name . ': Upload error';
            continue;
        }

        if (!array_key_exists($type, $allowed_types)) {
            $errors[] = $name . ': Invalid file type. Allowed: PDF, JPG, PNG';
            continue;
        }

        if ($size > $max_size) {
            $errors[] = $name . ': File too large. Max 10MB';
            continue;
        }

        $_FILES['upload_doc'] = array(
            'name'     => sanitize_file_name($name),
            'type'     => $type,
            'tmp_name' => $tmp_name,
            'error'    => $error,
            'size'     => $size,
        );

        $attachment_id = media_handle_upload('upload_doc', $order->ID);

        if (!is_wp_error($attachment_id)) {
            $existing_docs[] = $attachment_id;
            $uploaded[] = array(
                'id'       => $attachment_id,
                'url'      => wp_get_attachment_url($attachment_id),
                'filename' => $name,
                'type'     => $type,
            );

            update_post_meta($attachment_id, '_vehdoc_document_order', $order->ID);
            update_post_meta($attachment_id, '_vehdoc_document_user', get_current_user_id());
        } else {
            $errors[] = $name . ': ' . $attachment_id->get_error_message();
        }
    }

    update_post_meta($order->ID, '_vehdoc_documents', $existing_docs);

    if (!empty($uploaded)) {
        $status = get_post_meta($order->ID, '_vehdoc_order_status', true);
        if ($status === 'pending') {
            update_post_meta($order->ID, '_vehdoc_order_status', 'documents_received');
            vehdoc_log_status_change($order->ID, 'pending', 'documents_received');
        }
    }

    wp_send_json_success(array(
        'message'   => count($uploaded) . ' document(s) uploaded successfully',
        'documents' => $uploaded,
        'errors'    => $errors,
    ));
}
add_action('wp_ajax_vehdoc_upload_documents', 'vehdoc_ajax_upload_documents');

/**
 * AJAX: Delete Document
 */
function vehdoc_ajax_delete_document() {
    check_ajax_referer('wp_rest', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Not authenticated'));
    }

    $doc_id   = intval($_POST['document_id'] ?? 0);
    $order_id = intval($_POST['order_id'] ?? 0);

    $doc_user = get_post_meta($doc_id, '_vehdoc_document_user', true);
    if ($doc_user != get_current_user_id() && !current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied'));
    }

    $existing_docs = get_post_meta($order_id, '_vehdoc_documents', true) ?: array();
    $existing_docs = array_diff($existing_docs, array($doc_id));
    update_post_meta($order_id, '_vehdoc_documents', array_values($existing_docs));

    wp_delete_attachment($doc_id, true);

    wp_send_json_success(array('message' => 'Document deleted'));
}
add_action('wp_ajax_vehdoc_delete_document', 'vehdoc_ajax_delete_document');

/**
 * Secure Document Access
 */
function vehdoc_restrict_document_access($file) {
    if (!is_user_logged_in()) return $file;

    $upload_dir = wp_upload_dir();
    if (strpos($file, $upload_dir['basedir']) === false) return $file;

    return $file;
}
