<?php

mb_internal_encoding('utf-8');
error_reporting(-1);

$productCost = 40000;    //цена товара
$firstComission = 0;    //комиссия за оформление кредита
$credit = $productCost + $firstComission;
$percentInput = 3;
$comission = 1000;    //ежемесячная комиссия
$payout = 5000;    //ежемесячная выплата
$payed = 0;

function giantPercent() {
	echo "Этот процент огромен. Не надо так!\n";
}

function priceAndCredit($cost,$cred) {
	echo "Цена товара: $cost\nСумма кредита: $cred\n\n";
}

function error() {
	echo "Ошибка";
	exit();
}

function payments($month,$credit,$payed){
	echo "$month-й месяц.\nОсталось выплатить: $credit рублей\nЗаплачено: $payed рублей\n\n";
}

function lastPay($month,$payed) {
	echo "$month-й месяц.\nКредит выплачен! Поздравляю!\nЗаплачено: $payed рублей\n\n";
}

function badCredit(){
	echo "Такой кредит лучше не брать\n";
    break;
}

if ($percentInput >= 200) {
	giantPercent();
	exit();
}

priceAndCredit($productCost,$credit);

$regexp = '/[0-9]+/u';

/* Дальше идет замена процента с "1" на "1.01" и т.д. для удобного умножения */

if ($percentInput < 10) {
    $percent = preg_replace($regexp, '1.0$0', $percentInput);
} elseif ($percentInput >= 10 && $percentInput < 100) {
    $percent = preg_replace($regexp, '1.$0', $percentInput);
} elseif ($percentInput >=100 && $percentInput < 200) {
    $regexp2 = '/^([0-9])([0-9]*)/u';
    $percent = preg_replace($regexp2, '2.$2', $percentInput);
} else {
	error();
}

for ($months = 1; $credit >= 0; $months++) {
	$credit *= $percent;
	$credit += $comission;
	if ($credit >= $payout) {
	    $credit -= $payout;
	    $credit = ceil($credit);
	    $payed += $payout;
	    payments($months,$credit,$payed);
    } else {
    	$credit -=$payout;
    	$credit = ceil($credit);
	    $payed += $payout;
	    $payed += $credit;
	    lastPay($months,$payed);
    }
    if ($credit > ($productCost * 5) or $payed > ($productCost * 5)) {
    	badCredit();
    }
}

?>