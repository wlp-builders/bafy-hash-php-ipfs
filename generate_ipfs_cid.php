<?php

function generate_ipfs_cid($data) {
    // Compute the SHA-256 hash of the data
    $sha256_hash = hash('sha256', $data, true);

    // Construct the multihash: sha2-256 (0x12), length 32 (0x20), then the hash
    $multihash = chr(0x12) . chr(0x20) . $sha256_hash;

    // Construct the CIDv1 bytes: version 1 (0x01), codec dag-pb (0x70), then the multihash
    $cid_bytes = chr(0x01) . chr(0x70) . $multihash;

    // Encode the CID bytes in base32, lowercase, without padding
    $encoded = base32_encode($cid_bytes);

    // Prepend the multibase prefix 'b' for base32
    return 'b' . strtolower(rtrim($encoded, '='));
}

// Base32 encoding function without external libraries
function base32_encode($input) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $output = '';
    $v = 0;
    $vbits = 0;

    for ($i = 0, $len = strlen($input); $i < $len; $i++) {
        $v = ($v << 8) | ord($input[$i]);
        $vbits += 8;

        while ($vbits >= 5) {
            $output .= $alphabet[($v >> ($vbits - 5)) & 0x1F];
            $vbits -= 5;
        }
    }

    if ($vbits > 0) {
        $output .= $alphabet[($v << (5 - $vbits)) & 0x1F];
    }

    return $output;
}

/*
// Example usage
$data = "Hello, IPFS!";
echo generate_ipfs_cid($data);
*/