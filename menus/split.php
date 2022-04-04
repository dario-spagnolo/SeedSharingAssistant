<?php
function split($params) {
  global $split_formatted_shares, $seed, $split_shares;
  clear();

  if (isset($params["reset_seed"]) && $params["reset_seed"] == true) {
    //Reset global variables
    $split_formatted_shares = array();
    $split_shares = array();
    $seed = "";
  }

  if ($seed == "") {
    $seed = readline("Your seed: ");
    if ($seed == "s") $seed = SAMPLE_SEED;
    if (!is_seed_valid($seed)) {
      $seed = "";
      main_menu("The seed you entered is invalid.");
    }

    count:
    $count = readline("Number of shares (between 2 and 10): ");
    if (!is_numeric($count)) {
      echo "'$count' is not a valid number.\n\n";
      goto count;
    } elseif ($count < 2 or $count > 10) {
      echo "I can create between 2 and 10 shares.\n\n";
      goto count;
    }

    threshold:
    $threshold = readline("Threshold (between 2 and $count): ");
    if (!is_numeric($threshold)) {
      echo "'$threshold' is not a valid number.\n\n";
      goto threshold;
    } elseif ($threshold < 2 or $threshold > $count) {
      echo "Threshold must be between 2 and $count.\n\n";
      goto threshold;
    }

    //enemy bounce riot memory shrimp village elevator joke powder infant labor inch
    $indexes = compress_seed($seed);

    exec("echo -n \"$indexes\" | secret-share-split --count $count --threshold $threshold", $split_shares);

    $menu_items = array();

    foreach($split_shares as $share) {
      $share = strtoupper($share);

      $formatted_lines = array();
      $lines = str_split($share, 20);
      foreach ($lines as $line) {
        $cmd = "echo -n $line | md5sum | awk '{print $1}' | head -c4";
        $crc = strtoupper(exec($cmd));

        $line_parts = str_split($line, 2);
        $formatted_lines[] = implode(" ", $line_parts) . "\t$crc";
      }

      $split_formatted_shares[] = array(
        "share" => substr($share, 0, 10) . "...",
        "formatted_lines" => $formatted_lines
      );
    }
  }


  clear();
  echo "Seed is: $seed\n";

  custom_menu(
    "See shares:",
    build_menu_from_formatted_shares()
  );
}

function view_share($params) {
  global $seed;

  $share = $params["share"];
  $share_idx = ($params["share_idx"] + 1);
  $formatted_lines = $params["formatted_lines"];

  clear();

  echo "Share #$share_idx\n\n";

  echo implode("\n", $formatted_lines) . "\n\n";

  wait_for_key("c");

  clear();

  echo "Seed is: $seed\n";

  custom_menu(
    "See shares:",
    build_menu_from_formatted_shares()
  );
}

function split_new_seed() {
  global $split_formatted_shares, $seed, $split_shares;
  clear();
}
