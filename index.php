<?php

use Sabre\VObject\Reader;

require __DIR__.'/vendor/autoload.php';


$ics = file_get_contents($argv[1]);

$calendar = Reader::read($ics);

// Step 1: Map UID → SUMMARY
$summaryByUid = [];

foreach ($calendar->VTODO as $vtodo) {
    $summaryByUid[(string)$vtodo->UID] = (string)$vtodo->SUMMARY;
}

// Step 2: Build parent → children array (using parent's SUMMARY as key)
$parentChildMap = [];

foreach ($calendar->VTODO as $vtodo) {
    $childUid = (string) $vtodo->UID;
    $childSummary = (string) $vtodo->SUMMARY;

    foreach (($vtodo->{'RELATED-TO'} ?? []) as $related) {
        if (strtoupper((string) $related['RELTYPE']) === 'PARENT') {
            $parentUid = (string) $related;
            $parentName = $summaryByUid[$parentUid] ?? $parentUid;

            if (!isset($parentChildMap[$parentName])) {
                $parentChildMap[$parentName] = [];
            }

            $parentChildMap[$parentName][] = [
                'uid' => $childUid,
                'summary' => $childSummary
            ];

            continue 2;
        }
    }

    $parentChildMap['*'][] = [
        'uid' => $childUid,
        'summary' => $childSummary
    ];
}

foreach ($parentChildMap as &$children) {
    $children = array_values(array_unique($children, SORT_REGULAR));
}

unset($children);

foreach ($parentChildMap as $parent => $children) {
    echo "$parent:\n";

    foreach ($children as $child) {
        echo "- [ ] " . $child['summary'] . "\n";
    }

    echo "\n";
}
