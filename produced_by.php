<?php 

// Plugin name : Produced by
// Version : 1.0.0
// Description : Add a field produced by to a photo


defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

// plugin init
add_event_handler('init', 'produced_by_init');

function produced_by_init()
{
    // Add a link "Produced by" on the menu "Actions" for each photos
    add_event_handler('loc_end_picture', 'produced_by_add_link');
}


function add_produced_by()
{
    global $template, $picture;

    // Checks if the user has permission to edit photos
    if (!is_admin_user()) {
        return;
    }

    // Displays the "Produced by" link in the "Actions" menu
    $template->assign(
        array(
            'PRODUCED_BY_LINK' => get_root_url() . 'admin.php?page=photo_modify&amp;id=' . $picture['id'] . '#produced_by',
            'PRODUCED_BY_TEXT' => l10n('Produced by'),
        )
    );

    // Add the field "Produced by" on the edit photo page
    add_event_handler('loc_end_element_set_global', 'produced_by_add_field');
}


function produced_by_add_field()
{
    global $template, $page;

    // checks if the user has permission to edit photos
    if (!is_admin_user()) {
        return;
    }

    // Checks if the user clicked on the "Produced by" link
    if (isset($page['section']) && $page['section'] == 'produced_by') {
        // Retrieves the "Produced by" value of the photo
        $produced_by = produced_by_get_value($page['element_id']);

        // Displays the "Produced by" field
        $template->set_filename('produced_by', realpath(PIWIGO_PATH . 'plugins/produced_by/template_extension.tpl'));
        $template->assign(
            array(
                'PRODUCED_BY_VALUE' => $produced_by,
            )
        );
        $template->append('element_set_global_plugins_actions', $template->parse('produced_by'));
    }
}

// Function to retrieve the "Produced by" value of the photo
function produced_by_get_value($picture_id)
{
    $query = '
        SELECT produced_by
        FROM ' . IMAGES_TABLE . '
        WHERE id = ' . $picture_id . '
    ';

    $result = pwg_query($query);
    $row = pwg_db_fetch_assoc($result);

    return $row['produced_by'];
}

// Function to update the value "Produced by" of the photo
add_event_handler('element_set_global', 'produced_by_update_value');

function produced_by_update_value($data, $element_info)
{
    if (isset($data['produced_by'])) {
        $query = '
            REPLACE INTO ' . IMAGES_TABLE . '
            SET
                id = ' . $element_info['id'] . ',
                produced_by = "' . pwg_db_real_escape_string($data['produced_by']) . '"
        ';

        pwg_query($query);
    }
}

?>