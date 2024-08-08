<?php

const ALPHABET = 'abcdefghijklmnopqrstuvwxyz234567';

// Build a lookup table for decoding.
$lookupTable = [];
for ($i = 0; $i < strlen(ALPHABET); $i++) {
  $lookupTable[ALPHABET[$i]] = $i;
}

// Add aliases for rfc4648.
$lookupTable['0'] = $lookupTable['o'];
$lookupTable['1'] = $lookupTable['i'];

/**
 * @param array $input The input array to encode.
 * @return string A Base32 string encoding the input.
 */
function encode(array $input): string {
  // How many bits will we skip from the first byte.
  $skip = 0;
  // 5 high bits, carry from one byte to the next.
  $bits = 0;

  // The output string in base32.
  $output = '';

  function encodeByte($byte, &$skip, &$bits, &$output) {
    if ($skip < 0) {
      // we have a carry from the previous byte
      $bits |= $byte >> -$skip;
    } else {
      // no carry
      $bits = ($byte << $skip) & 248;
    }

    if ($skip > 3) {
      // Not enough data to produce a character, get us another one
      $skip -= 8;
      return 1;
    }

    if ($skip < 4) {
      // produce a character
      $output .= ALPHABET[$bits >> 3];
      $skip += 5;
    }

    return 0;
  }

  for ($i = 0; $i < count($input); ) {
    $i += encodeByte($input[$i], $skip, $bits, $output);
  }

  return $output . ($skip < 0 ? ALPHABET[$bits >> 3] : '');
}

/**
 * @param string $input The base32 encoded string to decode.
 * @return array Uint8Array
 */
function decode(string $input): array {
  // how many bits we have from the previous character.
  $skip = 0;
  // current byte we're producing.
  $byte = 0;

  $output = [];
  $o = 0;

  function decodeChar($char, &$skip, &$byte, &$output, &$o) {
    global $lookupTable;

    // Consume a character from the stream, store
    // the output in this.output. As before, better
    // to use update().
    $val = $lookupTable[strtolower($char)];
    if ($val === null) {
      throw new Exception('Invalid character: ' . json_encode($char));
    }

    // move to the high bits
    $val <<= 3;
    $byte |= $val >> $skip;
    $skip += 5;

    if ($skip >= 8) {
      // We have enough bytes to produce an output
      $output[$o++] = $byte;
      $skip -= 8;

      if ($skip > 0) {
        $byte = ($val << (5 - $skip)) & 255;
      } else {
        $byte = 0;
      }
    }
  }

  foreach (str_split($input) as $c) {
    decodeChar($c, $skip, $byte, $output, $o);
  }

  return array_slice($output, 0, $o);
}
/*
// Test the functions
$input = [16, 11, 228, 153, 88, 186, 152, 235, 67, 103, 96, 39, 143, 192, 247, 43, 14, 218, 77, 128, 201, 211, 75, 75, 195, 154, 185, 42, 2];
$encoded = encode($input);
echo "Encoded: $encoded\n";

$decoded = decode($encoded);
echo "Decoded: ";
print_r($decoded);
*/