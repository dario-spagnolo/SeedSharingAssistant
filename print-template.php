<style>
@font-face {
    font-family: 'courier';
    src: url("../fonts/courier.ttf") format('truetype');
}

body {
  font-family: 'courier';
}

table {
  width: 100%;
  font-size: 35px;
}

td {
  border-bottom: 0.05cm solid black;
  padding-top: 0.4cm;
  padding-bottom: 0.4cm;
}

p {
  font-size: 27px;
}

.copy {
  border-bottom:0.1cm solid black;
  border-top:0.1cm solid black;
}

#checksum {
  text-align:center;
  font-size:35px;
  letter-spacing: 0.2cm;
}
</style>

<p>With this share and <?=$threshold - 1?> other <?= ($threshold <= 2) ? "share" : "shares"?>, it is possible to reconstruct the original secret (seed) using the Shamir Secret Sharing algorithm implemented by Daan Sprenkels (https://github.com/dsprenkels/sss-cli).</p>
<p>This share is <?= strlen($full_share) ?> characters long, 20 characters per line, <?=count($lines)?> lines. It starts with <?=substr($full_share, 0, 4)?> and it ends with <?=substr($full_share, -4, 4)?>. It contains only numbers from 0 (zero) to 9 (nine) and capital letters from A (Apple) to F (Forest).</p>
<p>At the end of each line, a short checksum is provided to optionally check for integrity after manually entering each line.</p>

<p class="copy">Copy of this share for redundancy:
  <?=$compact_share[0]?><br />
  <?=$compact_share[1]?><br />
  <?=$compact_share[2]?>
</p>

<table>
  <tr style="text-align:center">
    <td>Line #</td>
    <td>Share</td>
    <td>Checksum</td>
  </tr>
<?php
  $line_idx = 1;
  foreach($lines as $line) {
?>
  <tr>
    <td style="text-align:center"><?= $line_idx ?></td>
    <td><?= $line["line"] ?></td>
    <td style="text-align:center"><?= $line["crc"] ?></td>
  </tr>
<?php
    $line_idx++;
  }
?>
</table>

<p>MD5 checksum of this share:</p>
<p id="checksum"><?=$full_checksum?></p>

<p class="copy">Copy of this share for redundancy:
  <?=$compact_share[0]?><br />
  <?=$compact_share[1]?><br />
  <?=$compact_share[2]?>
</p>
