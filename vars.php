<?php
$seed = "";
$split_formatted_shares = array();
$split_shares = array();

$combine_shares = array();

$terminal = array(
  "cols" => exec("tput cols"),
  "lines" => exec("tput lines")
);

//Sample seed for testing purposes (don't use it!)
define("SAMPLE_SEED", "horror plastic snake wagon twist destroy glove frost jeans frozen virus spot");

//Parse BIP39 word list
$bip39_words = file("bip39-words.txt", FILE_IGNORE_NEW_LINES);
