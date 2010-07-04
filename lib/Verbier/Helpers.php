<?php

function display_flash_notice() {
	$message = \Verbier\FlashMessage::get('notice');
	if ($message !== NULL) {
		return content_tag('p', $message, array('class' => 'flash-notice'));
	}
}

function h($string) {
	return htmlentities($string, ENT_QUOTES, 'utf-8');
}

function pretty_date($date) {
	$timestamp = strtotime($date);
	return date('d F Y', $timestamp);
}

function format_article($article) {
	$article = str_replace("\n", '</p><p>', $article);
	return '<p>'.$article.'</p>';
}

function truncate($string, $length = 30, $append = '...') {
	return strlen($string) > $length ? substr($string, 0, $length).$append : $string;
}

function time_ago_in_words($from) {
	return distance_of_time_in_words($from, time());
}

// Check out Maintainable Framework
function distance_of_time_in_words($from, $to) {
	$distanceInMinutes = round(abs($to - $from) / 60);
	
	if ($distanceInMinutes >= 0 && $distanceInMinutes <= 1) {
		return ($distanceInMinutes == 0) ? 'less than a minute' : '1 minute';
	} elseif ($distanceInMinutes >= 2 && $distanceInMinutes <= 44) {
		return "$distanceInMinutes minutes";
	} elseif ($distanceInMinutes >= 45 && $distanceInMinutes <= 89) {
		return 'about 1 hour';
	} elseif ($distanceInMinutes >= 90 && $distanceInMinutes <= 1439) {
		return 'about ' . round($distanceInMinutes / 60) . ' hours';
	} elseif ($distanceInMinutes >= 1440 && $distanceInMinutes <= 2879) {
		return '1 day';
	} elseif ($distanceInMinutes >= 2880 && $distanceInMinutes <= 43199) {
		return intval($distanceInMinutes / 1440) . ' days';
	} elseif ($distanceInMinutes >= 43200 && $distanceInMinutes <= 86399) {
		return 'about 1 month';
	} elseif ($distanceInMinutes >= 86400 && $distanceInMinutes <= 525959) {
		return round(($distanceInMinutes / 43200)) . ' months';
	} elseif ($distanceInMinutes >= 525960 && $distanceInMinutes <= 1051919) {
		return 'about 1 year';
	} else {
		return 'over ' . round($distanceInMinutes / 525600) . ' years';
	}
}

function tag($name, array $options = array()) {
	$tagOptions = $options ? tag_options($options) : '';
	return "<{$name}{$tagOptions} />";
}

function content_tag($name, $content, array $options = array()) {
	$tagOptions = $options ? tag_options($options) : '';
	return "<{$name}{$tagOptions}>{$content}</{$name}>";
}

function tag_options(array $options) {
	$preparedOptions = array();
	foreach ($options as $key => $value) {
		$preparedOptions[] = $key.'="'.$value.'"';
	}
	return ' ' . implode(' ', $preparedOptions);
}

function link_to($label, $link, array $options = array()) {
	return content_tag('a', $label, array_merge($options, array('href' => $link)));
}


function error_messages_for($object) {
	if (!is_object($object) || !$object->hasErrors()) {
		return NULL;
	}
	
	$messages = $object->getErrors();
	if (is_array($messages)) {
		$errors = array();
		foreach ($messages as $name => $value) {
			$errors[] = content_tag('li', $value);	
		}
		
		return content_tag('strong', count($errors) . ' error(s) prohibitied this from being saved') . content_tag('ul', implode("\n", $errors));
	}
	return NULL;
}

/**
 * Display a random item from an array
 *
 * @param array $array 
 * @return void
 */
function random_element(array $array) {
	return $array[array_rand($array)];
}

/**
 * Make @username, #hashtags and links clickable in tweets
 *
 * @param string $text  The text to twitterize
 * @return void
 */
function twitterize($text) {
	$text = linkify($text);
	$text = preg_replace('/#([-\w]+)/', '<a href="http://twitter.com/search?q=#$1">#$1</a>', $text);
	$text = preg_replace('/@([-\w]+)/', '<a href="http://twitter.com/$1">@$1</a>', $text);
	return $text;
}

/**
 * Find links in a string and make them clickable
 *
 * @param string $text
 * @return void
 */
function linkify($text) {
	$text = preg_replace('/(https?:\/\/[-.\/\w]+)/', '<a href="$1">$1</a>', $text);
	return $text;
}

/**
 * Display a nice sentence from a numeric array
 *
 * array('Olav', 'Jon', 'Per', 'Arne') //=> Olav, Jon, Per and Arne
 *
 * @param array $items  The items to connect
 * @param string $connector  The word that connects the last word
 * @return string  The fresh and delicious sentence
 */
function array_to_sentence($items, $connector = 'and') {
	if (is_array($items) && count($items) > 1) {
		$lastItem = array_pop($items);
		$items = implode(', ', $items);
		$items .= ' ' . $connector . ' ' . $lastItem;
	}
	return is_array($items) ? $items[0] : (string) $items;
}

/**
 * Turn a regular string into a SEO-friendly slug
 *
 * @param string $string  The string to slugify
 * @return string
 */
function slugify($string) {
	$map = array(
		'-' => '_',
		' ' => '-',
		'&' => 'and',
		
	);
	$string = strtr($string, $map);
	$string = preg_replace('/[^.-a-zA-Z0-9\s_]/i', '', $string);
	return trim(strtolower($string));
}

/**
 * Pluralize a word when $count is not 1. Useful when displaying a comment count for example
 *
 * @param int $count
 * @param string $singular  The singular version of the word
 * @param string $plural  The plural version of the word
 */
function pluralize($count, $singular, $plural) {
	$word = $count == 1 ? $singular : $plural;
	return "{$count} {$word}";
}
