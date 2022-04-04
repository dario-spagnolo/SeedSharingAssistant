<?php

function combine($msg = "") {
  clear();
  custom_menu(
    "Combine",
    array(
      array(
        "label" => "Enter new share",
        "action"=> "combine_new"
      ),
      array(
        "label" => "View shares",
        "action"=> "combine_view"
      ),
      array(
        "label" => "Back to main menu",
        "action"=> "main_menu"
      ),
      array(
        "label" => "Quit",
        "action"=> "quit"
      )
    )
  );
}

function combine_new() {
  clear();
  echo "Enter share:\n";

  $share = readline();

  $cmd = "echo -n $share | md5sum | awk '{print $1}' | head -c4";
  $crc = exec($cmd);

  echo "Checksum for this share is: $crc\n";

  custom_menu(
    "Is checksum correct?",
    array(
      array(
        "label" => "Yes",
        "action"=> "combine_save",
        "params"=> array(
          "share" => $share,
          "crc"   => $crc
        )
      ),
      array(
        "label" => "No",
        "action"=> "combine_new"
      ),
      array(
        "label" => "Back to main menu",
        "action"=> "main_menu"
      ),
      array(
        "label" => "Quit",
        "action"=> "quit"
      )
    )
  );
}

function combine_save($params) {
  global $combine_shares;

  $share = $params["share"];
  $crc = $params["crc"];

  $combine_shares[] = array(
    "share" => $share,
    "crc"   => $crc
  );

  combine();
}

function combine_view() {
  global $combine_shares;

  clear();

  $idx = 1;
  foreach($combine_shares as $share) {
    echo $idx . "\t" . $share["share"] . "\t" . $share["crc"] . "\n";
    $idx++;
  }

  echo "\n";
  wait_for_key("c");

  combine();
}
