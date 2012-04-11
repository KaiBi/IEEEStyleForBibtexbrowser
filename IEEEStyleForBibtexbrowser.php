<?php

/*
    This file is part of IEEEStyleForBibtexbrowser.

    Foobar is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with IEEEStyleForBibtexbrowser.  If not, see <http://www.gnu.org/licenses/>.
    
    You can always find the latest version at https://github.com/KaiBi/IEEEStyleForBibtexbrowser
*/

/* See the included README.md for documentation */

function IEEEStyle(&$bibentry) {
	
	/* bibtexbrowser has a small issue if the bibtex-entry itself contains a field "type"
	   (e.g. @mastersthesis could contain type = {Bachelor Thesis}).
	   This is easily fixed by setting the "Q_TYPE" configuration variable to something
	   other than "type" in your bibtexbrowser.local.php
	   
	   IEEEStyleForBibtexbrowser uses $entryType for the bibtex entry type and $type for
	   the field within an entry.
	*/
	$entryType = strtolower($bibentry->getField(Q_TYPE));
	$type = false;
	if ($bibentry->hasField('type'))
		$type = $bibentry->getField('type');
	
	$author = false;
	if ($bibentry->hasField(AUTHOR)) {
	
		// Bibtexbrowser does not support automatically abbreviating author names.
		// We have to do it ourselves.
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
			$authors[$i] = $bibentry->addHomepageLink(implode(' ', $name) . ' ' . $lastname);
		}
		
		// special formatting depending on the number of contributing authors
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
			$e = $editors[$i];
			$parts = explode(',', $e);
			$lastname = trim($parts[0]);
			$firstnames = explode(' ', trim($parts[1]));
			$name = array();
			foreach ($firstnames as $fn)
				$name[] = substr(trim($fn), 0, 1) . '.';
			$editors[$i] = $bibentry->addHomepageLink(implode(' ', $name) . ' ' . $lastname);
		}
		
		// special formatting depending on the number of authors
		if (count($editors) > 3)
			$editor = $editors[0] . ' et al., Eds.';
		else if (count($authors) > 1)
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
		$editionToShort = array (
			// extend this if you need hihgher edition numbers
			'first' => '1st',
			'second' => '2nd',
			'third' => '3rd'
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
			$entry[] = $author;
			$entry[] = '&quot;' . $title . '&quot;';
			$entry[] = '<em>' . $journal . '</em>';
			if ($volume) $entry[] = 'vol. ' . $volume;
			if ($number) $entry[] = 'no. ' . $number;
			if ($month) $entry[] = $month . ' ' . $year;
			else $entry[] = $year;
			if ($pages) $entry[] = $pages;
			break;
		case 'book':
			$entry[] = $author;
			$entry[] = "<em>$title</em>";
			if ($address) $publisher = $address . ': ' . $publisher;
			if ($edition) $entry[] = ($editionShort? $editionShort : $edition) . ' ed. ' . $publisher;
			else $entry[] = $publisher;
			$entry[] = $year;
			break;
		case 'booklet':
			break;
		case 'conference':
			break;
		case 'inbook':
			$entry[] = $author;
			$entry[] = "<em>$title</em>";
			if ($address) $publisher = $address . ': ' . $publisher;
			if ($edition) $entry[] = ($editionShort? $editionShort : $edition) . ' ed. ' . $publisher;
			else $entry[] = $publisher;
			$entry[] = $year;
			if ($chapter) $entry[] = $chapter;
			if ($pages) $entry[] = $pages;
			break;
		case 'incollection':
			$entry[] = $author;
			$entry[] = '&quot;' . $title . '&quot;';
			$entry[] = 'in <em>' . $booktitle . '</em>';
			if ($edition && $editor) $entry[] = ($editionShort? $editionShort : $edition) . ' ed. ' . $editor;
			if ($address) $entry[] = $address . ': ' . $publisher;
			else $entry[] = $publisher;
			$entry[] = $year;
			if ($pages) $entry[] = $pages;
			break;
		case 'inproceedings':
			$entry[] = $author;
			$entry[] = '&quot;' . $title . '&quot;';
			$entry[] = 'in <em>' . $booktitle . '</em>';
			if ($editor) $entry[] = $editor;
			if ($address) $entry[] = $address . ': ' . $publisher;
			else $entry[] = $publisher;
			// the year is omitted if it is already part of the booktitle
			if (!preg_match('/' . $year . '|' . '\'' . substr($year, 2) . '/', $booktitle)) $entry[] = $year;
			if ($pages) $entry[] = $pages;
			break;
		case 'manual':
			break;
		case 'mastersthesis':
			$entry[] = $author;
			$entry[] = '&quot;' . $title . '&quot;';
			if ($type) $entry[] = $type;
			$entry[] = $school;
			if ($address) $entry[] = $address;
			$entry[] = $year;
			break;
		case 'misc':
			break;
		case 'phdthesis':
			$entry[] = $author;
			$entry[] = '&quot;' . $title . '&quot;';
			if ($type) $entry[] = $type;
			$entry[] = $school;
			if ($address) $entry[] = $address;
			$entry[] = $year;
			break;
		case 'proceedings':
			break;
		case 'techreport':
			$entry[] = $author;
			$entry[] = '&quot;' . $title . '&quot;';
			if ($address) $entry[] = $institution . ': ' . $address;
			else $entry[] = $institution;
			if ($number) $entry[] = 'Rep. ' . $number;
			// the year is omitted if it is already part of the report number
			if (!$number || !preg_match('/' . $year . '|' . '\'' . substr($year, 2) . '/', $number)) $entry[] = $year;
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