<?php
declare(strict_types= 1);
$a = 10;
$b = 10;
$c = 10;
echo "a = {$a} \n";
echo "b = {$b} \n";
echo "c = {$c} \n";
if ($a < $b + $c && $b < $a + $c && $c < $b + $a) {
    echo "Lze sestrojit\n";
} else {
    echo "Nelze sestrojit";
    return 0;
}
if ($a == $b && $b == $c) {
    echo "Je rovnostranný\n\n";
} elseif ($a == $c) {
    echo "je rovnoramenný\n\n";
} elseif ($a == $b) {
    echo "je rovnoramenný\n\n";
} elseif ($b == $c) {
    echo "je rovnoramenný\n\n";
} else {
    echo "je obecný\n\n";
}
$s = ($a + $b + $c) / 2;
echo "{$s}\n";
$S = sqrt($s*($s - $a)*($s - $b)*($s - $c));
echo "{$S}";
?>