<?php

$input = "3 + 5 * (2 - 6)";

eval('$result = ' . $input . ';');

//echo "The result is $result";

function evaluate_expression(string $expression): int {
    $ret = 0;
    eval('$ret = ' . $expression . ';');
    return (int)$ret;
}
//$expression = "(7 - 3) * (2 + 4) / 2";
$expression = "3 + 5 * (2 - 6)";
$result = evaluate_expression($expression);
echo "Result is: " . $result;


?>