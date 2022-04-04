<?php

function is_seed_valid($seed) {
  global $bip39_words;

  $words = explode(" ", $seed);
  foreach($words as $word) {
    if(!array_search($word, $bip39_words)) {
      return false;
    }
  }

  if (count($words) != 12 and count($words) != 24) return false;

  return true;
}

function build_menu_from_formatted_shares() {
  global $split_formatted_shares;

  $menu_items = array();
  $formatted_share_idx = 0;
  foreach ($split_formatted_shares as $formatted_share) {
    $menu_items[] = array(
      "label"  => "Share #" . ($formatted_share_idx + 1) . " " . $split_formatted_shares[$formatted_share_idx]["share"],
      "action" => "view_share",
      "params"=> array(
        "share_idx" => $formatted_share_idx,
        "share" => $formatted_share,
        "formatted_lines"   => $split_formatted_shares[$formatted_share_idx]["formatted_lines"]
      )
    );
    $formatted_share_idx++;
  }

  $menu_items[] = array(
    "label"  => "Enter a new seed",
    "action" => "split",
    "params" => array(
      "reset_seed" => true
    )
  );

  $menu_items[] = array(
    "label"  => "Back to menu",
    "action" => "main_menu"
  );

  return $menu_items;
}

function compress_seed($seed) {
  global $bip39_words;

  $indexes = array();
  $words = explode(" ", $seed);
  foreach($words as $word) {
      $indexes[] = array_search($word, $bip39_words);
  }

  return join(",", $indexes) . "\n";
}
