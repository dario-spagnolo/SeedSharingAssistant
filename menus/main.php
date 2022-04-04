<?php
function main_menu($message = "") {
  clear();

  if ($message != "") echo $message . "\n\n";

  custom_menu(
    "Menu",
    array(
      array(
        "label" => "Split",
        "action" => "split"
      ),
      array(
        "label" => "Combine",
        "action" => "combine"
      ),
      array(
        "label" => "Exit",
        "action" => "quit"
      )
    )
  );
}
