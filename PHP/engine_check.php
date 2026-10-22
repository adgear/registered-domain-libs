<?php

function requireFixedPhpVersion () {
    /*
     * Due to recent CVE's:
     * - RCE via OOM corruption, CVE-2026-109618 (GHSA-w8hw-w9fx-j79w)
     * - RCE via stale global state, CVE-2026-109619 (GHSA-6wmf-4rv5-p3rp)
     * we are requiring to have patched versions of PHP engine to run this script.
     * Yes, if you are familiar with the matter it sounds weird, but it's a corp policy we have to follow.
     */

    // Minimum patch version required for each supported PHP release line
    $minimumPatch = [
        '8.2' => '8.2.35',
        '8.3' => '8.3.36',
        '8.4' => '8.4.27',
        '8.5' => '8.5.12',
    ];

    $current = PHP_VERSION; // e.g. "8.3.12"
    $line = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION; // e.g. "8.3"

    // Reject anything below 8.2 (7.x, 6.x, 8.0, 8.1)
    if (PHP_VERSION_ID < 80200) {
        die(sprintf(
            "PHP %s is not supported. Please upgrade to one of: %s, or any later release.\n",
            $current,
            implode(', ', $minimumPatch)
        ));
    }

    // Listed line: the patch level must meet the minimum
    // Lines not in the table (8.6, 8.7, 9.x, ...) will pass through
    if (isset($minimumPatch[$line]) && version_compare($current, $minimumPatch[$line], '<')) {
        die(sprintf(
            "PHP %s or higher is required for the %s line. You are running PHP %s.\n",
            $minimumPatch[$line],
            $line,
            $current
        ));
    }
}
requireFixedPhpVersion();
