<?php

function quit() {
  clean_tmp();
  die("\n");
}

function clear() {
  system("clear");
}

function custom_menu($title, $items) {
  echo $title . "\n";
  echo "=========" . "\n";

  $valid_choices = array();

  for ($i = 0; $i < count($items); $i++) {
    echo $i + 1 . " - " . $items[$i]["label"] . "\n";
    $valid_choices[] = ($i + 1);
  }

  echo "=========" . "\n\n";

  if (count($valid_choices) > 9) {
    $action = readline("Your choice: ");
  }else {
    echo "Your choice: ";
    exec("stty -echo");
    do {
      $action = trim(read_keypress());
    } while(!in_array($action, $valid_choices));
    exec("stty echo");
  }

  $action_cmd = $items[$action - 1]["action"];
  $action_params = isset($items[$action - 1]["params"]) ? $items[$action - 1]["params"] : null;
  $action_return = isset($items[$action - 1]["return"]) ? $items[$action - 1]["return"] : true;

  if ($action_cmd == "return") {
    return $action_return;
  }else {
    return call_user_func($action_cmd, $action_params);
  }
}

function wait_for_key($key) {
    echo "Press '$key' to continue...\n";
    exec("stty -echo");
    do {
      $read_key = trim(read_keypress());
    } while($read_key != $key);
    exec("stty echo");
}

function read_keypress() {
  $stdin = fopen('php://stdin', 'r');
  exec("stty cbreak");
  $keypress = fgetc($stdin);
  return $keypress;
}

function clean_tmp() {
  //TODO use unlink()
  exec("rm -f tmp/*");
}
