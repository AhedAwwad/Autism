<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\lancaster;
use File;
use DateTime;
use Storage;
use Carbon;
use App\index_term;
use App\fileee;
use App\word;
use Skyeng\Lemmatizer;
use Skyeng\Lemma;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Skyeng\Dictionary\Adjective;
use Skyeng\Dictionary\Adverb;
use Skyeng\Dictionary\Noun;
use Skyeng\Dictionary\PartOfSpeech;
use Skyeng\Dictionary\Verb;
use DB;
use TfIdfTransformer;
use Mekras\Speller\Hunspell\Hunspell;
use Mekras\Speller\Source\StringSource;
use Mekras\Speller\Aspell\Aspell;
use Mekras\Speller\Source\Source;
use Mekras\Speller\Speller;
//use Mekras\Speller\Source\FileSource;
//namespace Mekras\Speller\Examples;
//use Mekras\Speller\Aspell\Aspell;
use Mekras\Speller\Exception\PhpSpellerException;
use Mekras\Speller\Ispell\Ispell;
use Mekras\Speller\Source\FileSource;
use Mekras\Speller\Source\HtmlSource;
use Mekras\Speller\Source\IconvSource;
use Mekras\Speller\Source\XliffSource;





class uiController extends Controller
{

 private $stop_words = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");

  public function multiExplode($delimiters, $string)
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }
  
  public static function stem($word) {

    $exceptions = array(
      'skis' => 'ski',
      'skies' => 'sky',
      'dying' => 'die',
      'lying' => 'lie',
      'tying' => 'tie',
      'idly' => 'idl',
      'gently' => 'gentl',
      'ugly' => 'ugli',
      'early' => 'earli',
      'only' => 'onli',
      'singly' => 'singl',
      'sky' => 'sky',
      'news' => 'news',
      'howe' => 'howe',
      'atlas' => 'atlas',
      'cosmos' => 'cosmos',
      'bias' => 'bias',
      'andes' => 'andes',
    );

    // Process exceptions.
    if (isset($exceptions[$word])) {
      $word = $exceptions[$word];
    }
    elseif (strlen($word) > 2) {
      // Only execute algorithm on words that are longer than two letters.
      $word = self::prepare($word);
      $word = self::step0($word);
      $word = self::step1a($word);
      $word = self::step1b($word);
      $word = self::step1c($word);
      $word = self::step2($word);
      $word = self::step3($word);
      $word = self::step4($word);
      $word = self::step5($word);
    }
    return strtolower($word);
  }

  /**
   * Set initial y, or y after a vowel, to Y.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The prepared word.
   */
  protected static function prepare($word) {
    $inc = 0;
    if (strpos($word, "'") === 0) {
      $word = substr($word, 1);
    }
    while ($inc <= strlen($word)) {
      if (substr($word, $inc, 1) === 'y' && ($inc == 0 || self::isVowel($inc - 1, $word))) {
        $word = substr_replace($word, 'Y', $inc, 1);
      }
      $inc++;
    }
    return $word;
  }

  /**
   * Search for the longest among the "s" suffixes and removes it.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function step0($word) {
    $found = FALSE;
    $checks = array("'s'", "'s", "'");
    foreach ($checks as $check) {
      if (!$found && self::hasEnding($word, $check)) {
        $word = self::removeEnding($word, $check);
        $found = TRUE;
      }
    }
    return $word;
  }

  /**
   * Handles various suffixes, of which the longest is replaced.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function step1a($word) {
    $found = FALSE;
    if (self::hasEnding($word, 'sses')) {
      $word = self::removeEnding($word, 'sses') . 'ss';
      $found = TRUE;
    }
    $checks = array('ied', 'ies');
    foreach ($checks as $check) {
      if (!$found && self::hasEnding($word, $check)) {
        // @todo: check order here.
        $length = strlen($word);
        $word = self::removeEnding($word, $check);
        if ($length > 4) {
          $word .= 'i';
        }
        else {
          $word .= 'ie';
        }
        $found = TRUE;
      }
    }
    if (self::hasEnding($word, 'us') || self::hasEnding($word, 'ss')) {
      $found = TRUE;
    }
    // Delete if preceding word part has a vowel not immediately before the s.
    if (!$found && self::hasEnding($word, 's') && self::containsVowel(substr($word, 0, -2))) {
      $word = self::removeEnding($word, 's');
    }
    return $word;
  }

  /**
   * Handles various suffixes, of which the longest is replaced.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function step1b($word) {
    $exceptions = array(
      'inning',
      'outing',
      'canning',
      'herring',
      'earring',
      'proceed',
      'exceed',
      'succeed',
    );
    if (in_array($word, $exceptions)) {
      return $word;
    }
    $checks = array('eedly', 'eed');
    foreach ($checks as $check) {
      if (self::hasEnding($word, $check)) {
        if (self::r($word, 1) !== strlen($word)) {
          $word = self::removeEnding($word, $check) . 'ee';
        }
        return $word;
      }
    }
    $checks = array('ingly', 'edly', 'ing', 'ed');
    $second_endings = array('at', 'bl', 'iz');
    foreach ($checks as $check) {
      // If the ending is present and the previous part contains a vowel.
      if (self::hasEnding($word, $check) && self::containsVowel(substr($word, 0, -strlen($check)))) {
        $word = self::removeEnding($word, $check);
        foreach ($second_endings as $ending) {
          if (self::hasEnding($word, $ending)) {
            return $word . 'e';
          }
        }
        // If the word ends with a double, remove the last letter.
        $double_removed = self::removeDoubles($word);
        if ($double_removed != $word) {
          $word = $double_removed;
        }
        elseif (self::isShort($word)) {
          // If the word is short, add e (so hop -> hope).
          $word .= 'e';
        }
        return $word;
      }
    }
    return $word;
  }

  /**
   * Replaces suffix y or Y with i if after non-vowel not @ word begin.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function step1c($word) {
    if ((self::hasEnding($word, 'y') || self::hasEnding($word, 'Y')) && strlen($word) > 2 && !(self::isVowel(strlen($word) - 2, $word))) {
      $word = self::removeEnding($word, 'y');
      $word .= 'i';
    }
    return $word;
  }

  /**
   * Implements step 2 of the Porter2 algorithm.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function step2($word) {
    $checks = array(
      "ization" => "ize",
      "iveness" => "ive",
      "fulness" => "ful",
      "ational" => "ate",
      "ousness" => "ous",
      "biliti" => "ble",
      "tional" => "tion",
      "lessli" => "less",
      "fulli" => "ful",
      "entli" => "ent",
      "ation" => "ate",
      "aliti" => "al",
      "iviti" => "ive",
      "ousli" => "ous",
      "alism" => "al",
      "abli" => "able",
      "anci" => "ance",
      "alli" => "al",
      "izer" => "ize",
      "enci" => "ence",
      "ator" => "ate",
      "bli" => "ble",
      "ogi" => "og",
    );
    foreach ($checks as $find => $replace) {
      if (self::hasEnding($word, $find)) {
        if (self::inR1($word, $find)) {
          $word = self::removeEnding($word, $find) . $replace;
        }
        return $word;
      }
    }
    if (self::hasEnding($word, 'li')) {
      if (strlen($word) > 4 && self::validLi(self::charAt(-3, $word))) {
        $word = self::removeEnding($word, 'li');
      }
    }
    return $word;
  }

  /**
   * Implements step 3 of the Porter2 algorithm.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function step3($word) {
    $checks = array(
      'ational' => 'ate',
      'tional' => 'tion',
      'alize' => 'al',
      'icate' => 'ic',
      'iciti' => 'ic',
      'ical' => 'ic',
      'ness' => '',
      'ful' => '',
    );
    foreach ($checks as $find => $replace) {
      if (self::hasEnding($word, $find)) {
        if (self::inR1($word, $find)) {
          $word = self::removeEnding($word, $find) . $replace;
        }
        return $word;
      }
    }
    if (self::hasEnding($word, 'ative')) {
      if (self::inR2($word, 'ative')) {
        $word = self::removeEnding($word, 'ative');
      }
    }
    return $word;
  }

  /**
   * Implements step 4 of the Porter2 algorithm.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function step4($word) {
    $checks = array(
      'ement',
      'ment',
      'ance',
      'ence',
      'able',
      'ible',
      'ant',
      'ent',
      'ion',
      'ism',
      'ate',
      'iti',
      'ous',
      'ive',
      'ize',
      'al',
      'er',
      'ic',
    );
    foreach ($checks as $check) {
      // Among the suffixes, if found and in R2, delete.
      if (self::hasEnding($word, $check)) {
        if (self::inR2($word, $check)) {
          if ($check !== 'ion' || in_array(self::charAt(-4, $word), array('s', 't'))) {
            $word = self::removeEnding($word, $check);
          }
        }
        return $word;
      }
    }
    return $word;
  }

  /**
   * Implements step 5 of the Porter2 algorithm.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function step5($word) {
    if (self::hasEnding($word, 'e')) {
      // Delete if in R2, or in R1 and not preceded by a short syllable.
      if (self::inR2($word, 'e') || (self::inR1($word, 'e') && !self::isShortSyllable($word, strlen($word) - 3))) {
        $word = self::removeEnding($word, 'e');
      }
      return $word;
    }
    if (self::hasEnding($word, 'l')) {
      // Delete if in R2 and preceded by l.
      if (self::inR2($word, 'l') && self::charAt(-2, $word) == 'l') {
        $word = self::removeEnding($word, 'l');
      }
    }
    return $word;
  }

  /**
   * Removes certain double consonants from the word's end.
   *
   * @param string $word
   *   The word to stem.
   *
   * @return string $word
   *   The modified word.
   */
  protected static function removeDoubles($word) {
    $doubles = array('bb', 'dd', 'ff', 'gg', 'mm', 'nn', 'pp', 'rr', 'tt');
    foreach ($doubles as $double) {
      if (substr($word, -2) == $double) {
        $word = substr($word, 0, -1);
        break;
      }
    }
    return $word;
  }

  /**
   * Checks whether a character is a vowel.
   *
   * @param int $position
   *   The character's position.
   * @param string $word
   *   The word in which to check.
   * @param string[] $additional
   *   (optional) Additional characters that should count as vowels.
   *
   * @return bool
   *   TRUE if the character is a vowel, FALSE otherwise.
   */
  protected static function isVowel($position, $word, array $additional = array()) {
    $vowels = array_merge(array('a', 'e', 'i', 'o', 'u', 'y'), $additional);
    return in_array(self::charAt($position, $word), $vowels);
  }

  /**
   * Retrieves the character at the given position.
   *
   * @param int $position
   *   The 0-based index of the character. If a negative number is given, the
   *   position is counted from the end of the string.
   * @param string $word
   *   The word from which to retrieve the character.
   *
   * @return string
   *   The character at the given position, or an empty string if the given
   *   position was illegal.
   */
  protected static function charAt($position, $word) {
    $length = strlen($word);
    if (abs($position) >= $length) {
      return '';
    }
    if ($position < 0) {
      $position += $length;
    }
    return $word[$position];
  }

  /**
   * Determines whether the word ends in a "vowel-consonant" suffix.
   *
   * Unless the word is only two characters long, it also checks that the
   * third-last character is neither "w", "x" nor "Y".
   *
   * @param int|null $position
   *   (optional) If given, do not check the end of the word, but the character
   *   at the given position, and the next one.
   *
   * @return bool
   *   TRUE if the word has the described suffix, FALSE otherwise.
   */
  protected static function isShortSyllable($word, $position = NULL) {
    if ($position === NULL) {
      $position = strlen($word) - 2;
    }
    // A vowel at the beginning of the word followed by a non-vowel.
    if ($position === 0) {
      return self::isVowel(0, $word) && !self::isVowel(1, $word);
    }
    // Vowel followed by non-vowel other than w, x, Y and preceded by
    // non-vowel.
    $additional = array('w', 'x', 'Y');
    return !self::isVowel($position - 1, $word) && self::isVowel($position, $word) && !self::isVowel($position + 1, $word, $additional);
  }

  /**
   * Determines whether the word is short.
   *
   * A word is called short if it ends in a short syllable and if R1 is null.
   *
   * @return bool
   *   TRUE if the word is short, FALSE otherwise.
   */
  protected static function isShort($word) {
    return self::isShortSyllable($word) && self::r($word, 1) == strlen($word);
  }

  /**
   * Determines the start of a certain "R" region.
   *
   * R is a region after the first non-vowel following a vowel, or end of word.
   *
   * @param int $type
   *   (optional) 1 or 2. If 2, then calculate the R after the R1.
   *
   * @return int
   *   The R position.
   */
  protected static function r($word, $type = 1) {
    $inc = 1;
    if ($type === 2) {
      $inc = self::r($word, 1);
    }
    elseif (strlen($word) > 5) {
      $prefix_5 = substr($word, 0, 5);
      if ($prefix_5 === 'gener' || $prefix_5 === 'arsen') {
        return 5;
      }
      if (strlen($word) > 5 && substr($word, 0, 6) === 'commun') {
        return 6;
      }
    }

    while ($inc <= strlen($word)) {
      if (!self::isVowel($inc, $word) && self::isVowel($inc - 1, $word)) {
        $position = $inc;
        break;
      }
      $inc++;
    }
    if (!isset($position)) {
      $position = strlen($word);
    }
    else {
      // We add one, as this is the position AFTER the first non-vowel.
      $position++;
    }
    return $position;
  }

  /**
   * Checks whether the given string is contained in R1.
   *
   * @param string $string
   *   The string.
   *
   * @return bool
   *   TRUE if the string is in R1, FALSE otherwise.
   */
  protected static function inR1($word, $string) {
    $r1 = substr($word, self::r($word, 1));
    return strpos($r1, $string) !== FALSE;
  }

  /**
   * Checks whether the given string is contained in R2.
   *
   * @param string $string
   *   The string.
   *
   * @return bool
   *   TRUE if the string is in R2, FALSE otherwise.
   */
  protected static function inR2($word, $string) {
    $r2 = substr($word, self::r($word, 2));
    return strpos($r2, $string) !== FALSE;
  }

  /**
   * Checks whether the word ends with the given string.
   *
   * @param string $string
   *   The string.
   *
   * @return bool
   *   TRUE if the word ends with the given string, FALSE otherwise.
   */
  protected static function hasEnding($word, $string) {
    $length = strlen($string);
    if ($length > strlen($word)) {
      return FALSE;
    }
    return (substr_compare($word, $string, -1 * $length, $length) === 0);
  }

  /**
   * Removes a given string from the end of the current word.
   *
   * Does not check whether the ending is actually there.
   *
   * @param string $string
   *   The ending to remove.
   */
  protected static function removeEnding($word, $string) {
    return substr($word, 0, -strlen($string));
  }

  /**
   * Checks whether the given string contains a vowel.
   *
   * @param string $string
   *   The string to check.
   *
   * @return bool
   *   TRUE if the string contains a vowel, FALSE otherwise.
   */
  protected static function containsVowel($string) {
    $inc = 0;
    $return = FALSE;
    while ($inc < strlen($string)) {
      if (self::isVowel($inc, $string)) {
        $return = TRUE;
        break;
      }
      $inc++;
    }
    return $return;
  }

  /**
   * Checks whether the given string is a valid -li prefix.
   *
   * @param string $string
   *   The string to check.
   *
   * @return bool
   *   TRUE if the given string is a valid -li prefix, FALSE otherwise.
   */
  protected static function validLi($string) {
    return in_array($string, array(
      'c',
      'd',
      'e',
      'g',
      'h',
      'k',
      'm',
      'n',
      'r',
      't',
    ));
  }





function correct1($input) {
$words  = DB::table('words')->distinct('word')->pluck('word')->toArray();

//$shortest = -1;

// loop through words to find the closest
foreach ($words as $word) {

    // calculate the distance between the input word,
    // and the current word
    if(soundex($input)==soundex($word))
{
  return $words;
}

    }

//echo "Input word: $input\n";
/*if ($shortest == 0) {
    echo "Exact match found: $closest\n";
} else {
    return $closest;
}*/

}



function correct2() {
  $dic = self::train('hi hello how are uou?');
  $word = "halo";
  if (array_key_exists($word, $dic)) {
    return $word;
  }
  $search_result = $dic[soundex($word)];
  foreach ($search_result as $key => &$res) {
    $dist = levenshtein($key, $word);
    // consider just distance equals to 1 (the best) or 2
    if ($dist == 1 || $dist == 2) {
      $res = $res / $dist;
    }
    // discard all the other candidates that have distances other than 1 and 2
    // from the original word
    else {
      unset($search_result[$key]);
    }
  }
  // reverse sorting of the words by frequence
  arsort($search_result);
  // return the first key of the array (which will be the word suggested)
  foreach ($search_result as $key => $res) {
    return $key;
  }
}

function train($file) {
  $contents = $file;
  // get all strings of word letters
  preg_match_all('/\w+/', $contents, $matches);
  unset($contents);
  $dictionary = array();
  foreach ($matches[0] as $word) {
    $word = strtolower($word);
    $soundex_key = soundex($word);
    if (!isset($dictionary[$soundex_key][$word])) {
      $dictionary[$soundex_key][$word] = 0;
    }
 
    $dictionary[$soundex_key][$word] += 1;
  }
  unset($matches);
  return $dictionary;
}



 function correct($input) {
$words  = DB::table('words')->distinct('word')->pluck('word')->toArray();

$shortest = -1;

// loop through words to find the closest
foreach ($words as $word) {

    // calculate the distance between the input word,
    // and the current word
    $lev = levenshtein($input, $word);

    // check for an exact match
    if ($lev == 0) {

        // closest word is this one (exact match)
        $closest = $word;
        $shortest = 0;

        // break out of the loop; we've found an exact match
        break;
    }

    // if this distance is less than the next found shortest
    // distance, OR if a next shortest word has not yet been found
    if ($lev <= $shortest || $shortest < 0) {
        // set the closest match, and shortest distance
        $closest  = $word;
        $shortest = $lev;
    }
}

//echo "Input word: $input\n";
/*if ($shortest == 0) {
    echo "Exact match found: $closest\n";
} else {
    return $closest;
}*/
return $closest;
}

//---------------------------------------- insert ---------------------------------------------

public function stop_w_q($query)
{
  $contents = $query;
  $pieces = array('.', ';', '>', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '\\', ']', '}', '[', '{'
        , ':', '"', '?', '<', '?', '’', '~', '}', ',', '/', '?', '‘', '÷', '×', '?', '<', '>', '?',
            '1', '2', '3', '4', '5', '6', '7', '8', '9');

$stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");
$pieces = self::multiExplode($pieces, $contents);
$array = array();
foreach($pieces as $piece) {
  $pl = strtolower($piece);
  $p1 = preg_replace('/\b('.implode('|',$stopwords).')\b/','',$pl);
  $a = self::stem($p1);
  $a1 = self::lemma($a);
  array_push($array,$a1);  
}
$tf_idf_query_array=array();
foreach ($array as $key => $value) {
  $ss=DB::select('select word from words where words.word = "'.$value.'"');
  $s1=DB::select('select word,file_id from index_terms,words where words.id = index_terms.word_id and 
  words.word = "'.$value.'" ');
   if(empty($value)) {
    unset($array[$key]);
   } 
   else 
    if (empty($ss)){
unset($array[$value]);
}  else  {
$tf_idf_query1=DB::table('words')->join('index_terms','index_terms.word_id','=','words.id')
->where('words.word','=',$value)->distinct('file_id')->pluck('idf','word')->toArray();
array_push($tf_idf_query_array,$tf_idf_query1);
}}
 return $tf_idf_query_array ;
}

function normalise_query($query) {
 $doc=self::stop_w_q($query);
 $total=0;
 $i=0;
 var_dump($doc);
        foreach ($doc  as $entry) { 
          foreach ($entry as $key=>$value) {
            
$total += $value*$value;
        }}
        $total = sqrt($total);
       /* foreach($doc as &$entry) {
                //$entry = $entry/$total;
        }*/
        return $total;
}


public function coss($query ="gold silver truck raghad")
{
$doc=self::stop_w_q($query);
$ff=array();
$cos=0;
//var_dump($doc);
foreach ($doc  as $key1 ) 
{ 
  foreach ($key1 as $v => $value) { 
// v is term and value is its idf  

$file=DB::table('index_terms')->join('words','words.id','=','index_terms.word_id')
->where('words.word','=',$v)->distinct('file_id')->pluck('file_id')->toArray();
//var_dump($value);
foreach ($file as $key=>$value ) {
  //echo $value;
  if (!in_array($value ,$ff)){
         array_push($ff, $value); 
  }
    
}
foreach ($ff as $key ) 
{
$file_tfidf = DB::select('select * from  index_terms where file_id= '.$key);//word_id
$word_query=DB::select('select * from words where word= "'.$v.'"');//id
foreach ($file_tfidf as $keyy) {
print_r($keyy->word_id);
}
}


/*$file1=DB::table('index_terms')->join('words','words.id','=','index_terms.word_id')
->where('index_terms.file_id','=',$key)->pluck('words.idf')->toArray();*/

}
}
 
//var_dump($file_tfidf);
}

public function normalise_file($id)
{

$file=DB::table('index_terms')->join('words','words.id','=','index_terms.word_id')
->where('index_terms.file_id','=',$id)->pluck('tf_idf')->toArray();
$total=0;
var_dump($file);
foreach ($file as $key=>$value1) 
 {
 $total+= $value1*$value1;
 }
 return sqrt($total);
}

public function tf($word ,$file_name)
{
  $freq_word_infile = DB::table('index_terms')->join('fileees','fileees.id', '=',
 'index_terms.file_id')->join('words','words.id', '=', 'index_terms.word_id')
  ->where([['words.word','=',$word],
  ['fileees.title','=',$file_name]])->value('freq');

  ////////////////////
 $all_words_infile= DB::table('index_terms')->join('fileees','fileees.id', '=',
 'index_terms.file_id')->join('words','words.id', '=', 'index_terms.word_id')->
 where('fileees.title','=',$file_name)->sum(value('freq'));
 /////////////////////
 $tf= $freq_word_infile;
 // /$all_words_infile;
/////////////////////
 DB::update ('update index_terms,fileees,words set index_terms.tf = '.$tf.' where 
  index_terms.file_id=fileees.id and index_terms.word_id=words.id and
  words.word = "'.$word.'"and fileees.title = "'.$file_name.'"');

return $freq_word_infile;
}


public function idf($word)
{
 $total_files_corpus =DB::select ('select *from fileees');
 //echo count($total_files_corpus )."<br>";
 $total_worditeration_corpus = DB::select ('SELECT distinct file_id FROM index_terms , words 
  WHERE words.id=index_terms.word_id and words.word ="'.$word.'"');
 //echo count($total_worditeration_corpus).'<br>' ;
 $idf =log(count($total_files_corpus)/count($total_worditeration_corpus) ,10) ;

 DB::update ('update words set words.idf = '.$idf.' where words.word = "'.$word.'"');
 return $idf;
//return $total_files_corpus;
//return log($total_files_corpus/$total_worditeration_corpus);
//return ($tf=DB::select('SELECT sum(freq) FROM index_terms ,words 
  //where words.id=index_terms.word_id and words.word= "aheda" GROUP by freq;'));

}


 public function tfidf()
 {
/*$freq_word_infile = DB::table('index_terms')->join('fileees','fileees.id', '=',
 'index_terms.file_id')->join('words','words.id', '=', 'index_terms.word_id')->where([['words.word','=',$word],
  ['fileees.title','=',$file_name]])->value('freq');*/
 /*$file= DB::table('index_terms')->join('fileees','fileees.id', '=',
 'index_terms.file_id')->join('words','words.id', '=', 'index_terms.word_id')
 ->distinct('fileees.id')->pluck('title','word')->toArray();
*/
//var_dump($f);
 $ff= DB::table('index_terms')->join('fileees','fileees.id', '=',
 'index_terms.file_id')->join('words','words.id', '=', 'index_terms.word_id')
 ->distinct('fileees.id')->pluck('title')->toArray();
 var_dump($ff);
 foreach ($ff as $f1) {
 $f= DB::table('index_terms')->join('fileees','fileees.id', '=',
 'index_terms.file_id')->join('words','words.id', '=', 'index_terms.word_id')
 ->distinct('fileees.id')->where('fileees.title','=',$f1)->pluck('word')->toArray();
 foreach ($f as $key) {
   
  $tfidf=self::idf($key)*(self::tf($key,$f1)); 
  echo $tfidf."<br>";
   //$key_id= DB::select('select words.id from words where words.word ="'.$key.'"');
  DB::update('update index_terms,fileees,words  set  index_terms.tf_idf ='.$tfidf.'
    where index_terms.word_id= words.id and  fileees.id =index_terms.file_id
    and words.word = "'.$key.'" and fileees.title = "'.$f1.'"');
  }
//echo $f1_id."<br>";
   
        }


 //DB::update('update index_terms set tf ='.self::idf($w1->word));
//echo (self::tf($f1,$f2))."<br>";



//  echo (self::idf($word));
}


public function insert_all($s="../storage/app/*.txt")
{
//$file_insert = self::read_corpus($s);
foreach (glob($s) as $file1) 
  {
self::insert_word($file1);
  }
}
/////////////////////////////////////////////////////
   public function insert_word($file_name)
{
   
    $file1=new fileee;
    $file1->title = $file_name;
    $file1->num_of_word= self::word_all_file($file_name);//count of words 
    $file1->save();
   // $countFiles = self::read_corpus($s);
     $array2    = self::read_file($file_name);
     //$word_all_file=self::word_all_file();
     $ff=DB::select('select word from words');
     foreach ($array2 as $key =>$value) {

$fff= DB::table('words')->where('words.word','=',$key)->pluck('words.id')->toArray();
 //DB::select('select words.id from words where words.word= "'.$key.'"');
       // echo   $fff[0] ;
     ///var_dump($fff); ///إذا موجودة بالداتا بس زود بالانديكس تيرمز واذا لا زود بالحقلين 
        if (count($fff)<1){
          $word        = new word;
          $word->word=$key;
          $word->save();

      $index_term=new index_term;
      $index_term->file_id= $file1->id;
      $index_term->word_id= $word->id;
      $index_term->freq=$value ;
        $index_term->save();
 //         echo $value;
     }
     else{
       $index_term=new index_term;
      $index_term->file_id= $file1->id;
      $index_term->word_id= $fff[0];
      $index_term->freq=$value ;
        $index_term->save();
     //return $array2;
   }
}}


public function word_all_file($file_name)
{
   
    $d=0;
    $contents=self::read_file($file_name);

      foreach ($contents as $key => $value)
      { if(isset($key))
          $d+=$value;
      }
return $d;
}

public function read_file($file_name)
{try
  {
   
    $contents = File::get($file_name);
    if(empty($contents))
          echo " file is empty ";
        else 
          { 
            $ind =self::countt($file_name);
            return $ind;
         }}
  catch(exception $e)
  {
    echo $e;
  }
}


//---------------------------------------- indexing ---------------------------------------------







public function lemma($wordbeforestemming)
    {  
$lemmatizer = new Lemmatizer();
$lemmas = $lemmatizer->getLemmas($wordbeforestemming);
return $lemmas{0}->lemma;
}

function validateDate($date,$format)
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

function validateAllFormatDate($date)
    {
        $date_format=array('d/m/y', // 03/08/06
            'd/m/Y', // 03/08/2006
            'j/n/y', // 3/8/06
            'j/n/Y', // 3/8/2006
            'dmy',   // 030806
            'dmY',   // 03082006
            'dMy',   // 03Aug06
            'dMY',   // 03Aug2006
            'd-M-y', // 03-Aug-06
            'd-M-Y', // 03-Aug-2006
            'jMy',   // 3Aug06
            'jMY',   // 3Aug2006
            'j-M-y', // 3-Aug-06
            'j-M-Y', // 3-Aug-2006
            'j-F-y', // 3-August-06
            'j-F-Y', // 3-August-2006
            'Ymd',   // 20060803
            'Y/m/d',  // 2006/08/03
            'Y-m-d', // 2006-08-03
            'mdy',   // 080306
            'mdY',   // 080306
            'm/d/Y', // 08/03/2006
            'M-d-y', // Aug-03-06
            'M-d-Y', // Aug-03-2006
            // 'M-y',   // Aug-06
            // 'W'  , // Monday
            // 'M-S', // feb-8th
            // 'S-M', // 8th-feb
        );

        foreach ($date_format as $format) {
            if(self::validateDate($date,$format))
                return TRUE;
        }
        return FALSE;
    }

public function stop_w($file_name)
{
  $contents = File::get($file_name);
     $all_tokens_to_stem=array();
     $all_tokens_no_stem=array();
        
//-------------------- Date -----------------------

       $pattern='((jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December|january|february|march|april|may|june|july|august|september|october|november|december)(\.|\/|-|\s)*(\d+))';
       $pattern_replace='((jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December|january|february|march|april|may|june|july|august|september|october|november|december)(\.|\/|-|\s)*(\d+))';
        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'${1}-${3}',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

       $pattern='(\(Time(,|\s|-|.|\:)(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December|january|february|march|april|may|june|july|august|september|october|november|december)(,|\s|-|.)*(\d+)\))';
       $pattern_replace='(\(Time(,|\s|-|.|\:)(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December|january|february|march|april|may|june|july|august|september|october|november|december)(,|\s|-|.)*(\d+)\))';
        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'${2}-${4}',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

      /* $pattern='(([a-zA-Z]+)(|.|-)([a-zA-Z]+)(|.|-)([a-zA-Z]*))';
       $pattern_replace='(([a-zA-Z]+)(|.|-)([a-zA-Z]+)(|.|-)([a-zA-Z]*))';
        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'${1}-${3}-${5}',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }*/
      $pattern='((\d{1,2})(\/|\-|\.|\:|\,)(\d{1,2})(\/|\-|\.|\:|\,)(\d{4}))';
      $pattern_replace='((\d{1,2})(\/|\-|\.|\:|\,)(\d{1,2})(\/|\-|\.|\:|\,)(\d{4}))';
        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'$1-$3-$5',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

$pattern='((\d{4})(\/|\-|\.|\:|\,)(\d{1,2})(\/|\-|\.|\:|\,)(\d{1,2}))';
      $pattern_replace='((\d{4})(\/|\-|\.|\:|\,)(\d{1,2})(\/|\-|\.|\:|\,)(\d{1,2}))';
        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'$1-$3-$5',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

  //-------------------- Countries -----------------------
        $pattern='((damas)|(damascus))';
        $pattern_replace='((damas)|(damascus))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'damascus',$match);
                array_push($all_tokens_no_stem,$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((abu[\s]*dhabi)|(auh)|(abo[\s]*dhabi))';
        $pattern_replace='((abu[\s]*dhabi)|(auh)|(abo[\s]*dhabi))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'Abu Dhabi',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        
        $pattern='((dubai)|(dxb))';
        $pattern_replace='((dubai)|(dxb))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'dubai',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        
        $pattern='((cairo)|(cai))';
        $pattern_replace='((cairo)|(cai))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'cairo',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        
        $pattern='((london)|(lon))';
        $pattern_replace='((london)|(lon))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'london',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        
        $pattern='((paris)|(par))';
        $pattern_replace='((paris)|(par))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'paris',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        

        $pattern='((new[\s]*york[\s]*city)|(nyc))';
-        $pattern_replace='((new[\s]*york[\s]*city)|(nyc))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'new york city',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }


        $pattern='((new[\s]*york)|(ny))';
        $pattern_replace='((new[\s]*york)|(ny))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'new york',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((germany)|(ge)|(ger)|(de)|(deu))';
        $pattern_replace='((germany)|(ge)|(ger)|(de)|(deu))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'germany',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((spain)|(es)|(esp))';
        $pattern_replace='((spain)|(es)|(esp))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'spain',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((egypt)|(eg)|(egy))';
        $pattern_replace='((egypt)|(eg)|(egy))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'egypt',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((morocco)|(ma)|(mar))';
        $pattern_replace='((morocco)|(ma)|(mar))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'morocco',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((oman)|(om)|(omn))';
        $pattern_replace='((oman)|(om)|(omn))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'oman',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((Libya)|(ly)|(lby))';
        $pattern_replace='((Libya)|(ly)|(lby))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'Libya',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((lebanon)|(lb)|(lbn))';
        $pattern_replace='((lebanon)|(lb)|(lbn))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'lebanon',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((bahrain)|(bh)|(bhr))';
        $pattern_replace='((bahrain)|(bh)|(bhr))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'bahrain',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((kuwait)|(kw)|(kwt))';
        $pattern_replace='((kuwait)|(kw)|(kwt))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'kuwait',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((jordan)|(jo)|(jor))';
        $pattern_replace='((jordan)|(jo)|(jor))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'jordan',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((palestine)|(ps)|(pse))';
        $pattern_replace='((palestine)|(ps)|(pse))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'palestine',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((iraq)|(iq)|(irq))';
        $pattern_replace='((iraq)|(iq)|(irq))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'iraq',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((syria)|(sy)|(syr))';
        $pattern_replace='((syria)|(sy)|(syr))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'syria',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((qatar)|(qa)|(qat))';
        $pattern_replace='((qatar)|(qa)|(qat))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'qatar',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((mexico)|(mx)|(mex))';
        $pattern_replace='((mexico)|(mx)|(mex))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'mexico',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }                                
        $pattern='((netherlands)|(nl)|(nld))';
        $pattern_replace='((netherlands)|(nl)|(nld))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'netherlands',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((canada)|(ca)|(can))';
        $pattern_replace='((canada)|(ca)|(can))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'canada',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((britain)|(brit))';
        $pattern_replace='((britain)|(brit))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'britain',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((united[\s]*kingdom)|(uk))';
        $pattern_replace='((united[\s]*kingdom)|(uk))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'united kingdom',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((ireland)|(ie)|(irl))';
        $pattern_replace='((ireland)|(ie)|(irl))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'ireland',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }

        $pattern='((saudi[\s]+arabia)|(sa)|(ksa))';
        $pattern_replace='((saudi[\s]+arabia)|(sa)|(ksa))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'saudi arabia',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((china)|(cn)|(chn))';
        $pattern_replace='((china)|(cn)|(chn))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'bahrain',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='((People’s[\s]*Republic[\s]*of[\s]*China)|(prc))';
        $pattern_replace='((People’s[\s]*Republic[\s]*of[\s]*China)|(prc))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'People’s Republic of China',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }
        $pattern='(((the)*[\s]*united[\s]*arab[\s]*emirates)|(uae))';
        $pattern_replace='(((the)*[\s]*united[\s]*arab[\s]*emirates)|(uae))';

        if(preg_match_all($pattern, $contents ,$matches1)){
            foreach ($matches1[0] as $match) {
                //  var_dump($match);
                $token=preg_replace($pattern_replace,'united arab emirates',$match);
                array_push($all_tokens_no_stem,$token);
                //  var_dump("phone".$token);
                $contents = str_replace($match,'',$contents);
            }
        }     
//-------------------- Date -----------------------                                                           
        $pattern='/(?P<day>January|February|March|April|May|June|July|August|September|October|November|December)\s(?P<month>[1-9]|[1-2][0-9]|3[0-1]{1})th\,(?P<year>[0-9]{4})/';

        if(preg_match_all($pattern,$contents,$matches2)){
            foreach ($matches2[0] as $match) {
                $date =date_create($match);
                $token=date_format($date,"d/m/Y");
                //var_dump($token);
                array_push($all_tokens_no_stem,$token);

                $contents = str_replace($match,'',$contents);
            }
        }

        // token text to string
        $tokens=explode(" ",$contents);

        //var_dump($tokens);

        foreach ($tokens as $token) {
          //echo "9";
            // chech string is date
            //time
            $format='H:i:s';
            $d = DateTime::createFromFormat($format,$token);
            if($d && $d->format($format) == $token){
              
                $contents=str_replace($token,'',$contents);
                array_push($all_tokens_no_stem,$token);
            }

            //date
            if (self::validateAllFormatDate($token)){
              
                //    var_dump($token);
                $contents=str_replace($token,'',$contents);
                //    var_dump($input);
                $date=date_create($token);

                $token=date_format($date,"d/m/Y");
                array_push($all_tokens_no_stem,$token);

            }else{
              echo "11";
                // call funcution tokenize
                $strings=self::tokenize($token);
                //var_dump($strings);
                if ($strings != NULL){
                    echo "$";
                    foreach ($strings as $string){
                        echo "@";
                        array_push($all_tokens_to_stem,strtolower($string));
                    } echo "#";
                }echo "&";
            }echo "%";
        }echo ")";

        return array("1"=>$all_tokens_to_stem,"2"=>$all_tokens_no_stem);
       // var_dump($all_tokens_no_stem);
    }

public function  aaa()
{
  $token = self::stop_w($file_name)["2"];
  var_dump($token);
}

public function  indexing($file_name)
{
  $tokens= self::stop_w($file_name)["1"];
  //print_r($tokens);
  $token_count = 0;
  $irregular_words = self::irr()["1"];
  $array = array();
        foreach($tokens as $token)
        { 
            $token_count++;
            foreach ($irregular_words as $key=>$irr_word)
            {
                if (is_array($irr_word))
                {
                    if (in_array($token,$irr_word))
                    {
                        $token = $key;
                    }
                }
                elseif ($token == $irr_word)
                {
                    $token = $key;
                }
            }
            $token = self::Stem($token);
            $token = self::lemma($token);
            array_push($array,$token);
}
return $array;
}

public function  indexing_no_stem()
{
  $tokens= self::stop_w($file_name)["2"];
  //var_dump($tokens);
  $token_count = 0;
  $irregular_words = self::irr()["1"];
  $array = array();
        foreach($tokens as $token)
        { 
            $token_count++;
            foreach ($irregular_words as $key=>$irr_word)
            {
                if (is_array($irr_word))
                {
                    if (in_array($token,$irr_word))
                    {
                        $token = $key;
                    }
                }
                elseif ($token == $irr_word)
                {
                    $token = $key;
                }
            }
           
            array_push($array,$token);
}
return $array;
}

public function countt($file_name)
{
$array = self::indexing($file_name);
$joinedExcerpts = implode(".\n", $array);
$sentences = preg_split('/[^\s|\pL]/umi', $joinedExcerpts, -1, PREG_SPLIT_NO_EMPTY);
$wordsSequencesCount = array();
foreach($sentences as $sentence) {
    $words = array_map('mb_strtolower',
                       preg_split('/[^\pL+]/umi', $sentence, -1, PREG_SPLIT_NO_EMPTY));
    foreach($words as $index => $word) {
        $wordsSequence = '';
        foreach(array_slice($words, $index) as $nextWord) {
                $wordsSequence .= $wordsSequence ? (' ' . $nextWord) : $nextWord;
            if( !isset($wordsSequencesCount[$wordsSequence]) ) {
                $wordsSequencesCount[$wordsSequence] = 0;
            }
            ++$wordsSequencesCount[$wordsSequence];
     }
   }
   }
$ngramsCount       = array_filter($wordsSequencesCount,function($count) { return $count > 1; });

return $wordsSequencesCount;
}


public function countt_no_stem()
{
$array = self::indexing_no_stem();
//$joinedExcerpts = implode(".\n", $array);
//$sentences = preg_split('/[^\s|\pL]/umi', $joinedExcerpts, -1, PREG_SPLIT_NO_EMPTY);
$wordsSequencesCount = array();
echo "string";
foreach($array as $sentence) {
  echo "1";
    $words = $array;
   foreach($words as $index => $word) {
    echo "2";
        $wordsSequence = '';
        foreach(array_slice($words, $index) as $nextWord) {
                $wordsSequence .= $wordsSequence ? (' ' . $nextWord) : $nextWord;
            if( !isset($wordsSequencesCount[$wordsSequence]) ) {
                $wordsSequencesCount[$wordsSequence] = 0;
            }
            ++$wordsSequencesCount[$wordsSequence];
     }
   }
   }
$ngramsCount       = array_filter($wordsSequencesCount,function($count) { return $count > 1; });

var_dump($wordsSequencesCount);
}

 public function irr()
 {

$irregular_words = array (
    "beat" => array("beat","beaten"),
    "become" => array("became","become"),
    "begin" => array("began","begun"),
    "bend" => array("bent","bent"),
    "bet" => array("bet","bet"),
    "bite" => array("bit","bitten"),
    "bleed" => array("bled","bled"),
    "blow"  => array("blew","blown"),
    "break" => array("broke","broken"),
    "bring" => array("brought","brought"),
    "build" => array("built","built"),
    "buy" => array("bought","bought"),
    "catch" => array("caught","caught"),
    "choose" => array("chose","chosen"),
    "come" => array("came","come"),
    "cost" => array("cost","cost"),
    "cut" => array("cut","cut"),
    "dig" => array("dug","dug"),
    "do" => array("did","done"),
    "draw" => array("drew","drawn"),
    "drink" => array("drank","drunk"),
    "drive" => array("drove","driven"),
    "eat" => array("ate","eaten"),
    "fall" => array("fell","fallen"),
    "feed" => array("fed","fed"),
    "feel" => array("felt","felt"),
    "fight" => array("fought","fought"),
    "find" => array("found","found"),
    "fly" => array("flew","flown"),
    "forget" => array("forgot","forgotten"),
    "forgive" => array("forgave","forgiven"),
    "freeze" => array("froze","frozen"),
    "get" => array("got","gotten"),
    "give" => array("gave","given"),
    "go" => array("went","gone"),
    "hang"  => array("hung","hung"),
    "have" => array("had","had"),
    "hear" => array("heard","heard"),
    "hide" => array("hid","hidden"),
    "hit" => array("hit","hit"),
    "hold" => array("held","held"),
    "hurt" => array("hurt","hurt"),
    "keep" => array("kept","kept"),
    "know" => array("knew","known"),
    "lay" => array("laid","laid"),
    "lead" => array("led","led"),
    "leave" => array("left","left"),
    "lend" => array("lent","lent"),
    "let" => array("let","let"),
    "lie" => array("lay","lain"),
    "light" => array("lit","lit"),
    "lose" => array("lost","lost"),
    "make" => array("made","made"),
    "mean" => array("meant","meant"),
    "meet" => array("met","met"),
    "pay" => array("paid","paid"),
    "put" => array("put","put"),
    "quit" => array("quit","quit"),
    "read" => array("read","read"),
    "ride" => array("rode","ridden"),
    "ring" => array("rang","rung"),
    "rise" => array("rose","risen"),
    "run" => array("ran","run"),
    "say" => array("said","said"),
    "see" => array("saw","seen"),
    "sell" => array("sold","sold"),
    "send" => array("sent","sent"),
    "set" => array("set","set"),
    "shake" => array("shook","shaken"),
    "shine" => array("shone","shone"),
    "shoot" => array("shot","shot"),
    "shrink" => array("shrank","shrunk"),
    "shut" => array("shut","shut"),
    "sing" => array("sang","sung"),
    "sink" => array("sank","sunk"),
    "sit" => array("sat","sat"),
    "sleep" => array("slept","slept"),
    "slide" => array("slid","slid"),
    "speak" => array("spoke","spoken"),
    "spend" => array("spent","spent"),
    "spin" => array("spun","spun"),
    "stand" => array("stood","stood"),
    "steal" => array("stole","stolen"),
    "stick" => array("stuck","stuck"),
    "sting" => array("stung","stung"),
    "swear" => array("swore","sworn"),
    "sweep" => array("swept","swept"),
    "swim" => array("swam","swum"),
    "take" => array("took","taken"),
    "teach" => array("taught","taught"),
    "tear" => array("tore","torn"),
    "tell" => array("told","told"),
    "think" => array("thought","thought"),
    "throw" => array("threw","thrown"),
    "understand" => array("understood","understood"),
    "wake" => array("woke","woken"),
    "wear" => array("wore","worn"),
    "win" => array("won","won"),
    "wind" => array("wound","wound"),
    "write" => array("wrote","written"),
    
    "calf" => "calves",
    "child" => "children",
    "deer" => "deer",
    "dwarf" => "dwarves",
    "elf" => "elves",
    "foot" => "feet",
    "goose" => "geese",
    "half" => "halves",
    "hoof" => "hooves",
    "knife" => "knives",
    "loaf" => "loaves",
    "leaf" => "leaves",
    "life" => "lives",
    "louse" => "lice",
    "man" => "men",
    "mouse" => "mice",
    "ox" => "oxen",
    "scarf" => "scarves",
    "self" => "selves",
    "sheep" => "sheep",
    "shelf" => "shelves",
    "thief" => "thieves",
    "tooth" => "teeth",
    "wife" => "wives",
    "wolf" => "wolves",
    "woman" => "women",
    "cactus" => "cacti",
    "fungus" => "fungi",
    "octopus" => "octopi",
    "focus" => "foci",
    "stimulus" => "stimuli",
    "alumnus" => "alumni",
    "nucleus" => "nuclei",
    "syllabus" => "syllabi",
    "oasis" => "oases",
    "crisis" => "crises",
    "basis" => "bases",
    "synopsis" => "synopses",
    "synthesis" => "syntheses",
    "parenthesis" => "parentheses",
    "ellipsis" => "ellipses",
    "thesis" => "theses",
    "axis" => "axes",
    "analysis" => "analyses",
    "paralysis" => "paralyses",
    "diagnosis" => "diagnoses",
    "hypothesis" => "hypotheses",
    "phenomenon" => "phenomena",
    "criterion" => "criteria",
    "bacterium" => "bacteria",
    "medium" => "media",
    "memorandum" => "memoranda",
    "datum" => "data",
    "curriculum" => "curricula",
    "addendum" => "addenda",
    "larva" => "larvae",
    "antenna" => "antennae",
    "formula" => "formulae",
    "alga" => "algae",
    "vertebra" => "vertebrae",
    "radius" => "radi",
    "index" => "indices",
    "ovum" => "ova",
    "matrix" => "matrices",
    "beau" => "beaux",
    "bus" => "busses",
    "person" => "people"

);

$no_change = array(
    "series" => "series",
    "species" => "species"
);

return array("1"=>$irregular_words,"2"=>$no_change); 
 }


    public function token_condition($sb)
    {
        if(in_array(strtolower($sb), $this->stop_words)||($sb == '')||($sb == "'")||($sb == '\n') || strlen($sb)== 1)
        {
            return false;
        }
        return true;
    }


    public function tokenize($token)
    {
        $tokens = array();
        // '/(?P<day>[January|February|March|April|May|June|July|August|September|October|November|December])\s(?P<month>[0-9]{2}[th])\s(?P<year>[0-9]{4})/'

        $sb = '';

        $arr_of_char = str_split($token);
        for ($i = 0; $i < count($arr_of_char); $i++) {
            // if(in_array($month_names,$sb))
            // {
            // if($arr_of_char[$i+1]==',' && $arr_of_char[$i+1]==' ' && preg_match("/[0-9]{1,2}th\/[0-9]{1,2}\/[0-9]{4}/", $dt)$arr_of_char[]=="")
            // }
            $prior = ($i - 1 > 0) ? $arr_of_char[$i - 1] : ' ';
            $current = $arr_of_char[$i];
            $next = ($i + 1 < count($arr_of_char)) ? $arr_of_char[$i + 1] : ' ';

            //if($sb[0] == '.') substr_replace($sb,'',0,1);
            // extract acronyms
            // this will actually extract acronyms of any length
            // once it detects this pattern a.b.c
            // it's a greedy lexer that breaks at ' '

            if (ctype_alpha($current) && '.' == $next) {
                // Pattern-1  = U.S.A   (5 chars)
                // Pattern-2  = U.S.A.  (6 chars)
                if ($i + 2 < strlen($token)) {
                    // Pattern-1
                    if ((ctype_alpha($arr_of_char[$i])
                            && '.' == $arr_of_char[$i + 1]
                            && ctype_alpha($arr_of_char[$i + 2])
                            // && '.' == $arr_of_char[$i + 3]
                            // && ctype_alpha($arr_of_char[$i + 4])
                        )||($i + 4 <strlen($token) &&'.' == $arr_of_char[$i + 3]
                            && ctype_alpha($arr_of_char[$i + 4]) )){


                        for (; $i < count($arr_of_char) && $arr_of_char[$i] != ' '; $i++) {
                            if(preg_match('/[\'^£$%&*()}{@#~?><>,|\\\[\];:#!\/"*,=_+¬-]/',$arr_of_char[$i])) break;
                            if($arr_of_char[$i] != '.' )
                                $sb.=$arr_of_char[$i];
                        }
                        // check for Pattern-2 (trailing '.')
                        if ($i + 1 < strlen($token)
                            && '.' == $arr_of_char[$i + 1]) {
                            if($sb != '.')
                                $sb.=$arr_of_char[$i++];
                        }

                        if(self::token_condition($sb)){
                            array_push($tokens,strtolower($sb));
                        }
                        $sb='';
                        continue;
                    }
                }
            }

            //var_dump($current.' '.$next);
            if (('*' == $current && '/' == $next)||('/' == $current && '*' == $next)) {
                //var_dump("yes");
                $sb='';
                $i+=2;
                continue;
            }


            if (($prior==' ' && preg_match('/[\'^£$%&*()}{@#~?><>,|\\\[\];:#!\/."*,=_+¬-]/',$current) && $next==' ' )
                ||($prior==' ' && preg_match('/[\'^£$%&*()}{@#~?><>,|\\\[\];:#!\/."*,=_+¬-]/',$current)))
            {
                $sb='';
                continue;
            }

            if (('/' == $current && '/' == $next && count($arr_of_char)== 2)) {
                $sb='';
                continue;
            }


            if(is_numeric($current) && ctype_alpha($next))
            {
                if(self::token_condition($sb)){
                    array_push($tokens,strtolower($sb));
                }
                $sb = '';
                continue;
            }

            if(is_numeric($prior) && ctype_alpha($current)){
                if(self::token_condition($sb)){
                    array_push($tokens,strtolower($sb));
                }
                $sb = $current;
                continue;
            }
            ///////////////////////////////
            if ('w' == $current && '/' == $next) {
                $sb.=$current;
                $sb.=$next;
                if(self::token_condition($sb)){ array_push($tokens,strtolower($sb));
                    //var_dump("2   ".$sb);
                }
                $sb = '';
                $i += 1;
                continue;
            }

            // extract URLs
            if ('h' == $current && 't' == $next) {
                if ($i + 7 < strlen($token) &&
                    "http://" === substr_replace($token,$i, $i + 7)){

                    for (; $i < count($arr_of_char) && $arr_of_char[$i] != ' '; $i++) {
                        $sb.=$arr_of_char[$i];
                    }

                    if(self::token_condition($sb)){
                        array_push($tokens,strtolower($sb));
                    }
                    $sb = '';
                    continue;
                }
            }
            // extract windows drive letter paths
            // c:/ or c:\
            if (ctype_alpha($current) && ':' == $next) {
                if ($i + 2 < strlen($token)
                    && ($arr_of_char[$i + 2] == '\\'
                        || $arr_of_char[$i + 2] == '/')) {
                    $sb.=$current;
                    $sb.=$next;
                    $sb.=$arr_of_char[$i + 2];
                    $i += 2;
                    continue;
                }
            }
            // keep numbers together when separated by a period
            // "4.0" should not be tokenized as { "4", ".", "0" }
            if (is_numeric($current) && '.' == $next) {
                if ($i + 2 < strlen($token) &&
                    is_numeric($arr_of_char[$i + 2])) {
                    $sb.=$current;
                    $sb.=$next;
                    $sb.=$arr_of_char[$i + 2];
                    $i += 2;
                    continue;
                }
            }
            if (is_numeric($current) && ',' == $next) {
                if ($i + 2 < strlen($token) &&
                    is_numeric($arr_of_char[$i + 2])) {
                    $sb.=$current;
                    $sb.=$next;
                    $sb.=$arr_of_char[$i + 2];
                    $i += 2;
                    continue;
                }
            }
            // keep alpha characters separated by hyphens together
            // "b-node" should not be tokenized as { "b", "-", "node" }
            if (ctype_alpha($current) && '-' == $next) {
                if ($i + 2 < strlen($token) &&
                    ctype_alpha($arr_of_char[$i + 2])){
                    $sb.=$current;
                    $sb.=$next;
                    $sb.=$arr_of_char[$i + 2];
                    $i += 2;
                    continue;
                }
            }

            // TODO: need a greedy look-ahead to
            // avoid splitting this into multiple tokens
            // "redbook@vnet.ibm.com" currently is
            // tokenized as { "redbook@vnet", ".", "ibm", ".", "com" }
            // need to greedily lex all tokens up to the space
            // once the space is found, see if the last 4 chars are '.com'
            // if so, then take the entire segment as a single token
            // don't separate tokens concatenated with an underscore
            // eg. "ws_srv01" is a single token, not { "ws", "_", "srv01" }
            if (ctype_alpha($current) && '_' == $next) {
                if ($i + 2 < strlen($token) &&
                    ctype_alpha($arr_of_char[$i + 2])) {
                    $sb.=$current;
                    $sb.=$next;
                    $i++;
                    continue;
                }
            }

            // extract twitter channels
            if (('#' == $current ||
                    '@' == $current) &&
                ' ' != $next &&
                !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $next)) {
                $sb.=$current;
                continue;
            }

            // keep tokens like tcp/ip and os/2 and system/z together
            if (' ' != $current && '/' == $next) {
                $sb.=$current;
                $sb.=$next;
                $i++;
                continue;
            }

            if (' ' == $current) {
                if(self::token_condition($sb)){
                    // if($sb[0]=' ') substr_replace($sb,'',0);
                    // var_dump("4   ".$sb);
                    array_push($tokens,strtolower($sb)); }
                $sb = '';
                continue;
            }

            // don't tokenize on <word>'s or <words>'
            // but do tokenize on '<words>
            if ('\'' == $current) {
                if (' ' == $prior ||is_numeric($prior) ||ctype_alpha($prior) || preg_match('/[\'^£$%&*()}{@#~?><>,|\\\[\];:#!."*,=_+¬-]/',$prior)) {
                    if(self::token_condition($sb)){
                        // if($sb[0]=' ') substr_replace($sb,'',0);
                        array_push($tokens,strtolower($sb));
                        // var_dump("5   ".$sb);
                        $sb='';
                    }
                }else{
                    $sb.=$current;
                }
                continue;
            }

            if(($current == '.' && $next == ' ')
                ||($current == '.' && $i <count($arr_of_char) && !ctype_alpha($next))) {
                //var_dump($current.' '.$next);
                if(self::token_condition($sb) ){ // return strtolower($sb);}//
                    //   var_dump("6   ".$sb);
                    //   if($sb[0]=' ') substr_replace($sb,'',0);
                    array_push($tokens,strtolower($sb));
                }
                $sb= '';
                continue;
            }
            if(preg_match('/[\x00-\x1F\x7F-\xFF]/',$current,$m))
            {
                $sb= '';
                continue;
            }
//    var_dump($prior.' .......  '.$current.' .........  '.$next);
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|\\\[\];:#!\/"*,=_+¬-]/',$current)) {
                // if( 1< strlen($input) || $arr_of_char[$i+1] == ' ') {$sb.= ''; continue;}
                if(  ($sb != "'") || ($sb != ".")  ) //return strtolower($sb);//
                    if(self::token_condition($sb) ){ // return strtolower($sb);}//
                        // var_dump("7   ".$sb);
                        array_push($tokens,strtolower($sb));
                    }
                $sb = '';
                continue;
            }

            $sb.=$current;
        }

        if (0 != strlen($sb)){
            if($sb!='-'){
                if(self::token_condition($sb) )
                    //return strtolower($sb);//
                {
                    // var_dump("8   ".$sb);
                    array_push($tokens,strtolower($sb));
                }
            }

        }

        return $tokens;
    }


public function index($file_name="../storage/app/test.txt")
{  $contents=File::get($file_name);
   $array = self::stop_w($file_name);

}

function getMatches($pattern, $subject) {
    $matches = array();

    if (is_array($pattern)) {
        foreach ($pattern as $p) {
            $m = getMatches($p, $subject);

            foreach ($m as $key => $match) {
                if (isset($matches[$key])) {
                    $matches[$key] = array_merge($matches[$key], $m[$key]);   
                } else {
                    $matches[$key] = $m[$key];
                }
            }
        }
    } else {
        preg_match_all($pattern, $subject, $matches);
    }

    return $matches;

}





public function ngra()
{
 mb_internal_encoding('UTF-8');
$excerpts = self::ii();
dd($excerpts) ;}}
