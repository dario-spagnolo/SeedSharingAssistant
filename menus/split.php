<?php
function split($params = array()) {
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

    $indexes = compress_seed($seed);

    exec("echo -n \"$indexes\" | secret-share-split --count $count --threshold $threshold", $split_shares);

    $menu_items = array();

    foreach($split_shares as $share) {
      $share = strtoupper($share);

      $cmd = "echo -n $share | md5sum | awk '{print $1}'";
      $full_checksum = strtoupper(exec($cmd));

      $formatted_lines = array();
      $lines = str_split($share, 20);
      foreach ($lines as $line) {
        $cmd = "echo -n $line | md5sum | awk '{print $1}' | head -c4";
        $crc = strtoupper(exec($cmd));

        $line_parts = str_split($line, 2);
        $formatted_lines[] = array(
          "line" => implode(" ", $line_parts),
          "crc" => "$crc"
        );
      }

      $split_formatted_shares[] = array(
        "share" => substr($share, 0, 10) . "...",
        "full_share" => $share,
        "formatted_lines" => $formatted_lines,
        "full_checksum" => $full_checksum,
        "threshold" => $threshold
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
  echo "Line #\t" . str_pad("Share", 20 + 9, " ", STR_PAD_RIGHT) . "\tChecksum\n";

  $line_idx = 1;
  foreach($formatted_lines as $formatted_line) {
    echo $line_idx . "\t" . str_pad($formatted_line["line"], 20 + 9, " ", STR_PAD_RIGHT) . "\t" . $formatted_line["crc"] . "\n";
    $line_idx++;
  }
  echo "\n";

  custom_menu(
    "Make your choice:",
    array(
        array(
          "label" => "Print this share",
          "action" => "split_print_share",
          "params" => array(
            "share" => $share
          )
        ),
        array(
          "label" => "Return to shares",
          "action" => "split"
        )
    )
  );
}

function split_new_seed() {
  global $split_formatted_shares, $seed, $split_shares;
  clear();
}

function split_print_share($params) {
  $share = $params["share"];

  $lines = $share["formatted_lines"];
  $full_checksum = $share["full_checksum"];
  $threshold = $share["threshold"];
  $full_share = $share["full_share"];
  $compact_share = str_split($full_share, round(strlen($full_share) / 3));

  ob_start();
  include 'print-template.php';
  $html = ob_get_clean();

  $html_file = "tmp/" . uniqid() . ".html";
  $pdf_file = "tmp/" . uniqid() . ".pdf";
  file_put_contents($html_file, $html);
  exec("wkhtmltopdf $html_file $pdf_file");

  exec("lpr " . $pdf_file);

  unlink($html_file);
  unlink($pdf_file);

  echo "Print job sent.\n";
  wait_for_key("c");

  split();
}
