<?php
/* Check if EGN is valid */
/* See: http://www.grao.bg/esgraon.html */
function egn_valid($egn) {
    $EGN_WEIGHTS = array(2,4,8,5,10,9,7,3,6);

    if (strlen($egn) !== 10)
        return FALSE;

    $year = intval(substr($egn, 0, 2));
    $mon  = intval(substr($egn, 2, 2));
    $day  = intval(substr($egn, 4, 2));

    if ($mon > 40) {
        if (!checkdate($mon - 40, $day, $year + 2000))
            return FALSE;
    } else {
        if ($mon > 20) {
            if (!checkdate($mon - 20, $day, $year + 1800))
                return FALSE;
        } else {
            if (!checkdate($mon, $day, $year + 1900))
                return FALSE;
        }
    }

    $checksum = intval(substr($egn,9,1));
    $egnsum = 0;

    for ($i = 0; $i < 9; $i++)
        $egnsum += intval(substr($egn, $i, 1)) * $EGN_WEIGHTS[$i];

    $valid_checksum = $egnsum % 11;

    if ($valid_checksum === 10)
        $valid_checksum = 0;

    if ($checksum === $valid_checksum)
        return TRUE;

    return FALSE;
}
