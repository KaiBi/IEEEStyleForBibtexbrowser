<?php

// This is version 2013-01-22

/*
    This file is part of IEEEStyleForBibtexbrowser.

    IEEEStyleForBibtexbrowser is free software: you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    IEEEStyleForBibtexbrowser is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with IEEEStyleForBibtexbrowser. If not, see <http://www.gnu.org/licenses/>.
    
    You can always find the latest version at https://github.com/KaiBi/IEEEStyleForBibtexbrowser
*/

/* See the included README.md for documentation */

function IEEEStyle(&$bibentry) {
	
	if (!defined('IEEEStyle_SkipVersionCheck'))
		define('IEEEStyle_SkipVersionCheck', false);
	if (!defined('IEEEStyle_FullAuthorList'))
		define('IEEEStyle_FullAuthorList', false);
	if (!defined('IEEEStyle_FullEditorList'))
		define('IEEEStyle_FullEditorList', false);
	
	// check for supported bibtexbrowser-version
//	if (BIBTEXBROWSER !== 'v20121205' and IEEEStyleSkipVersionCheck !== true) {
//		die('This version of IEEEStyleForBibtexbrowser has not been tested with bibtexbrowser ' . BIBTEXBROWSER . '. You may add the following line to your bibtexbrowser-configuration to ignore this warning: ' . "\n" . '@define(\'IEEEStyle_SkipVersionCheck\', true);');
//	}
	
	/*
	   IEEEStyleForBibtexbrowser uses $entryType for the bibtex entry type and $type for
	   the field within an entry.
	*/
	$entryType = strtolower($bibentry->getField(Q_INNER_TYPE));
	$type = false;
	if ($bibentry->hasField(Q_TYPE))
		$type = $bibentry->getField(Q_INNER_TYPE);
	
	$author = false;
	if ($bibentry->hasField(Q_AUTHOR)) {
	
		// abbreviate author names
		$authors = $bibentry->getRawAuthors();
		for ($i = 0; $i < count($authors); $i++) {
			$a = $authors[$i];
			// check author format; "Firstname Lastname" or "Lastname, Firstname"
			if (strpos($a, ',') === false) {
				$parts = explode(' ', $a);
				$lastname = trim(array_pop($parts));
				$firstnames = $parts;
			} else {
				$parts = explode(',', $a);
				$lastname = trim($parts[0]);
				$firstnames = explode(' ', trim($parts[1]));
			}
			$name = array();
			foreach ($firstnames as $fn)
				$name[] = substr(trim($fn), 0, 1) . '.';
			// do not forget the author links if available
			if (BIBTEXBROWSER_AUTHOR_LINKS=='homepage') {
				$authors[$i] = $bibentry->addHomepageLink(implode(' ', $name) . ' ' . $lastname);
			}
			if (BIBTEXBROWSER_AUTHOR_LINKS=='resultpage') {
				$authors[$i] = $bibentry->addAuthorPageLink(implode(' ', $name) . ' ' . $lastname);
			}
		}
		
		// special formatting depending on the number of contributing authors
		if (IEEEStyle_FullAuthorList === true and count($authors) > 3)
			$author = implode(', ', $authors);
		else
			if (count($authors) > 3)
				$author = $authors[0] . ' et al.';
			else if (count($authors) > 2)
				$author = $authors[0] . ', ' . $authors[1] . ' and ' . $authors[2];
			else if (count($authors) > 1)
				$author = $authors[0] . ', ' . $authors[1];
			else
				$author = $authors[0];
	}

	$title = false;
	if ($bibentry->hasField(TITLE)) {
		$title = $bibentry->getTitle();
		$title = '<span class="bibtitle">' . $title . '</span>';
	}
	if ($title && $bibentry->hasField('url'))
		$title = '<a class="bibtitlelink"' . (BIBTEXBROWSER_BIB_IN_NEW_WINDOW? ' target="_blank" ' : '') . ' href="' . $bibentry->getField("url") . '">' . $title . '</a>';
	
	$publisher = false;
	if ($bibentry->hasField('publisher'))
		// we do not want the 'special' logic from the 'getPublisher'-method
		$publisher = $bibentry->getField('publisher');

	$address = false;
	if ($bibentry->hasField('address'))
		$address = $bibentry->getField('address');

	$editor = false;
	if ($bibentry->hasField(EDITOR)) {
		$editors = $bibentry->getEditors();
		for ($i = 0; $i < count($editors); $i++) {
			$a = $editors[$i];
			// check author format; "Firstname Lastname" or "Lastname, Firstname"
			if (strpos($a, ',') === false) {
				$parts = explode(' ', $a);
				$lastname = trim(array_pop($parts));
				$firstnames = $parts;
			} else {
				$parts = explode(',', $a);
				$lastname = trim($parts[0]);
				$firstnames = explode(' ', trim($parts[1]));
			}
			$name = array();
			foreach ($firstnames as $fn)
				$name[] = substr(trim($fn), 0, 1) . '.';
			// do not forget the author links if available
			if (BIBTEXBROWSER_AUTHOR_LINKS=='homepage') {
				$editors[$i] = $bibentry->addHomepageLink(implode(' ', $name) . ' ' . $lastname);
			}
			if (BIBTEXBROWSER_AUTHOR_LINKS=='resultpage') {
				$editors[$i] = $bibentry->addAuthorPageLink(implode(' ', $name) . ' ' . $lastname);
			}
		}
		
		// special formatting depending on the number of authors
		if (IEEEStyle_FullEditorList === true and count($editors) > 3)
			$editor = implode(', ', $editors) . ', Eds.';
		else
			if (count($editors) > 3)
				$editor = $editors[0] . ' et al., Eds.';
			else if (count($editors) > 1)
				$editor = implode(', ', $editors) . ', Eds.';
			else
				$editor = $editors[0] . ', Ed.';
	}
	
	$booktitle = false;
	if ($bibentry->hasField(BOOKTITLE))
		$booktitle = $bibentry->getField(BOOKTITLE);
	
	$school = false;
	if ($bibentry->hasField(SCHOOL))
		$school = $bibentry->getField(SCHOOL);
	
	$year = false;
	if ($bibentry->hasField(YEAR))
		$year = $bibentry->getField(YEAR);
	
	$edition = false;
	$editionShort = false;
	if ($bibentry->hasField('edition')) {
		$edition = $bibentry->getField('edition');
		// extend this if you need hihgher edition numbers
		$editionToShort = array (
			'first' => '1st',
			'second' => '2nd',
			'third' => '3rd',
			'fourth' => '4th',
			'fifth' => '5th'
		);
		// convert textual edition ordinals to numeric
		if (array_key_exists(strtolower($edition), $editionToShort))
			$editionShort = $editionToShort[strtolower($edition)];
	}
	
	$comment = false;
	if ($bibentry->hasField('comment'))
		$comment = $bibentry->getField('comment');
	
	$pages = false;
	if ($bibentry->hasField('pages'))
		$pages = 'pp. ' . str_replace('--', '-', $bibentry->getField('pages'));
	
	$journal = false;
	if ($bibentry->hasField('journal'))
		$journal = $bibentry->getField('journal');
	
	$volume = false;
	if ($bibentry->hasField('volume'))
		$volume = $bibentry->getField('volume');
	
	$number = false;
	if ($bibentry->hasField('number'))
		$number = $bibentry->getField('number');
	
	$month = false;
	if ($bibentry->hasField('month'))
		$month = $bibentry->getField('month');
	
	$chapter = false;
	if ($bibentry->hasField('chapter'))
		$chapter = 'ch. ' . $bibentry->getField('chapter');
	
	$institution = false;
	if ($bibentry->hasField('institution'))
		$institution = $bibentry->getField('institution');
	
	$entry = array();
	$result = '';
	
	// redundancies are left on purpose to improve changeability
	switch ($entryType) {
		case 'article':
			if ($author) $entry[] = $author;
			if ($title) $entry[] = '&quot;' . $title . '&quot;';
			if ($journal) $entry[] = '<em>' . $journal . '</em>';
			if ($volume) $entry[] = 'vol. ' . $volume;
			if ($number) $entry[] = 'no. ' . $number;
			if ($month && $year) $entry[] = $month . ' ' . $year;
			else if ($year) $entry[] = $year;
			if ($pages) $entry[] = $pages;
			break;
		case 'book':
			if ($author) $entry[] = $author;
			if ($title) $entry[] = '<em>' . $title . '</em>';
			if ($address && $publisher) $publisher = $address . ': ' . $publisher;
			if ($edition && $publisher) $entry[] = ($editionShort? $editionShort : $edition) . ' ed. ' . $publisher;
			else if ($publisher) $entry[] = $publisher;
			if ($year) $entry[] = $year;
			break;
		case 'booklet':
			break;
		case 'conference':
			break;
		case 'inbook':
			if ($author) $entry[] = $author;
			if ($title) $entry[] = '<em>' . $title . '</em>';
			if ($address && $publisher) $publisher = $address . ': ' . $publisher;
			if ($edition && $publisher) $entry[] = ($editionShort? $editionShort : $edition) . ' ed. ' . $publisher;
			else if ($publisher) $entry[] = $publisher;
			if ($year) $entry[] = $year;
			if ($chapter) $entry[] = 'ch. ' . $chapter;
			if ($pages) $entry[] = $pages;
			break;
		case 'incollection':
			if ($author) $entry[] = $author;
			if ($title) $entry[] = '&quot;' . $title . '&quot;';
			if ($booktitle) $entry[] = 'in <em>' . $booktitle . '</em>';
			if ($edition && $editor) $entry[] = ($editionShort? $editionShort : $edition) . ' ed. ' . $editor;
			else if ($editor) $entry[] = $editor;
			if ($address && $publisher) $entry[] = $address . ': ' . $publisher;
			else if ($publisher) $entry[] = $publisher;
			if ($year) $entry[] = $year;
			if ($pages) $entry[] = $pages;
			break;
		case 'inproceedings':
			if ($author) $entry[] = $author;
			if ($title) $entry[] = '&quot;' . $title . '&quot;';
			if ($booktitle) $entry[] = 'in <em>' . $booktitle . '</em>';
			if ($editor) $entry[] = $editor;
			if ($address && $publisher) $entry[] = $address . ': ' . $publisher;
			else if ($publisher) $entry[] = $publisher;
			// the year is omitted if it is already part of the booktitle
			if ($year && !preg_match('/' . $year . '|' . '\'' . substr($year, 2) . '/', $booktitle)) $entry[] = $year;
			if ($pages) $entry[] = $pages;
			break;
		case 'manual':
			break;
		case 'mastersthesis':
			if ($author) $entry[] = $author;
			if ($title) $entry[] = '&quot;' . $title . '&quot;';
			if ($type) $entry[] = $type;
			else $entry[] = 'Master\'s thesis';
			if ($school) $entry[] = $school;
			if ($address) $entry[] = $address;
			if ($year) $entry[] = $year;
			break;
		case 'misc':
			break;
		case 'phdthesis':
			if ($author) $entry[] = $author;
			if ($title) $entry[] = '&quot;' . $title . '&quot;';
			if ($type) $entry[] = $type;
			else $entry[] = 'Ph.D. dissertation';
			if ($school) $entry[] = $school;
			if ($address) $entry[] = $address;
			if ($year) $entry[] = $year;
			break;
		case 'proceedings':
			if ($editor) $entry[] = $editor;
			if ($title) $entry[] = '<em>' . $title . '</em>';
			if ($address && $publisher) $publisher = $address . ': ' . $publisher;
			else if ($publisher) $entry[] = $publisher;
			// the year is omitted if it is already part of the title
			if ($year && !preg_match('/' . $year . '|' . '\'' . substr($year, 2) . '/', $title)) $entry[] = $year;
			break;
		case 'techreport':
			if ($author) $entry[] = $author;
			if ($title) $entry[] = '&quot;' . $title . '&quot;';
			if ($address && $institution) $entry[] = $institution . ': ' . $address;
			else if ($institution) $entry[] = $institution;
			if ($number) $entry[] = 'Rep. ' . $number;
			// the year is omitted if it is already part of the report number
			if ($year && (!$number || !preg_match('/' . $year . '|' . '\'' . substr($year, 2) . '/', $number))) $entry[] = $year;
			break;
		case 'unpublished':
			break;
		default:
			break;
	}
	
	$result = implode(', ', $entry);
	if ($comment) $result .= ' (' . $comment .')';
		$result .= '.';
	return $result . "\n" . $bibentry->toCoins();
}

?>
