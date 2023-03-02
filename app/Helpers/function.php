<?php

/**
 * Generate Avatar
 *
 * @param $name = String
 * @param $type = ['male', 'female', 'human', 'identicon', 'initials', 'bottts', 'avataaars', 'jdenticon', 'gridy', 'micah']
 */
function getAvatar($name, $type = 'initials')
{
    if ($type == 'custom') {
        $avatar = asset($name);
    } else {
        $avatar = 'https://avatars.dicebear.com/api/'.$type.'/'.$name.'.svg';
    }

    return $avatar;
}