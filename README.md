IEEETransactionsStyle
=====================

IEEETransactionsStyle is a custom style script for [bibtexbrowser][1],
a PHP-based generator for citations from bibtex files. It tries to
adhere to the [IEEE style guidelines][2] as close as possible. You can
find an example of how this style looks at the [SEDA research group][4].


Limitations
-----------

*   The entry types _booklet_, _conference_, _manual_, _unpublished_
    are ignored.
*   Never tested with crossrefs.
*   Online resources have not been considered at all.
*   Special name suffixes are not handled properly.
    "Lastname, Firstname Jr." will become "J. F. Lastname"
*   The conversion of edition numbers from long to abbreviated works
    only up to 5 (e.g. "First" will become "1st", but "Twentysecond" will
    not be changed)
*   The entry types _inbook_ is a little unclear.
    Use _incollection_ instead if possible.
*   Year numbers are automatically omitted if they are already part of
    the proceeding title or the techreport number. This may sometimes be
    wrong. Example: In the techreport number _782A1995Z-4_ the _1995_ may
    not stand for the year number, but will be recognized as such.


Features on the Todo List
-------------------------

*   Automatic abbreviation of keywords such as "Proceedings" as
    described in [the guidelines][2]
*   Debug mode in which missing or incorrect entries are highlighted


Integration into Bibtexbrowser
------------------------------

As described on the [bibtexbrowser website][1]:

1.  Create a bibtexbrowser.local.php in the bibtexbrowser directory
2.  Copy IEEEStyleForBibtexbrowser.php to the bibtexbrowser directory
3.  Insert the following code into bibtexbrowser.local.php:

        <?php
          include('IEEEStyleForBibtexbrowser.php');
          define('BIBLIOGRAPHYSTYLE','IEEEStyle');
        ?>


A Short Introduction into Bibtex Files
--------------------------------------

This section will give you a (very) brief explanation of bibtex files and
introduce the vocabulary necessary for understanding the style guide below.

Here is an example bibtex entry for an imaginary master's thesis.

        @mastersthesis{claus.2008,
            author  = {Claus, Santa},
            title   = {The Travelling Salesman Problem Revisited},
	    school  = {Elven Academy of Applied Sciences},
	    year    = {2008},
	    month   = Dec,
	    type    = {Master's thesis},
	    address = {North Pole}
        }

For the remainder of this document the individual parts of a bibtex entry will
be called as follow:
*   The __entry type__ is the part following the _@_. In this case it is
    _mastersthesis_.
*   The __key__ is a unique identifier. No two bibtex entries may use the same
    key. In this case, the key is a combination of the first author's last name
    and the year (_claus.2008_).
*   The individual __fields__ make up the main part of a bibtex entry. They are
    essentially key-value pairs in textual form that contain the data
    associated with a piece of literature or the artifact that should be cited.
    Depending on the entry type different fields are mandatory and some are
    optional.


Mandatory and Optional Fields
-----------------------------------

The style depends on the presence of various fields in the bibtex entries.
This section lists the required fields that are recognized by
IEEETransactionsStyle. Additional fields have no influence on the citation,
but will be included in any exported bibtex entries. Please refer to the
next section for formatting conventions.

__@article__

An article in a scientific journal
_Required:_ author, title, journal, year
_Optional:_ volume, number, pages, month, url, doi, comment

__@book__

A book that has been written by the author(s) completely or in major parts
_Required:_ author, editor, title, publisher, year
_Optional:_ address, edition, url, doi, comment

__@inbook__

A part of a book without its own title (e.g. a chapter)
_Required:_ author, title, chapter, pages, publisher, year
_Optional:_ address, edition, url, doi, comment

__@incollection__

A part of a book or other collection with its own title (e.g. an essay)
_Required:_ author, title, booktitle, year, publisher
_Optional:_ address, edition, editor, pages, url, doi, comment

__@inproceedings__

A paper or contribution in the proceedings of a conference
_Required:_ author, title, booktitle, year, publisher
_Optional:_ address, editor, pages, url, doi, comment

__@mastersthesis__

A master's, bachelor's, diploma, project or other thesis except PhD theses
_Required:_ author, title, school, year
_Optional:_ type, address, url, doi, comment

__@proceedings__

Proceedings of a conference
_Required:_ editor, title, publisher, year
_Optional:_ address, url, doi, comment

__@phdthesis__

A dissertation written to acquire the title of PhD
_Required:_ author, title, school, year
_Optional:_ type, address, url, doi, comment

__@techreport__

Technical documents, reports or other publications of a university or
other institution
_Required:_ author, title, institution, year
_Optional:_ number, address, url, doi, comment


Formatting Conventions
----------------------

To completely adhere to the [IEEE style guidelines ][2] the bibtex entries
must be formatted accordingly. The following paragraph lists each field
and gives advice on what should be contained and what the content should
look like.

__address__

The publisher's or issuing institution's address in the format
_City, State/Province, Country_. State and country may be omitted if the city
is well-known. The country may be omitted if it is the US. States and provinces
should be in abbreviated form if possible. German federal states may also be
omitted.

__author__

Multiple authors are linked by the keyword _and_. IEEETransactionsStyle
understands both common formats: either _Lastname, Firstname_ or
_Firstname Lastname_. Please give the full name of all authors if available.
IEEETransactionsStyle will abbreviate them on its own for the citation.

__booktitle__

Name of the book or proceedings which the contribution is part of. Arbitrary
string value.

__chapter__

The chapter as integer value.

__comment__

Arbitrary string value. Will be appended to the citation in simple brackets.

__doi__

The Document Object Identifier (DOI) without prefix like _http://dx.doi.org/_.
Bibtexbrowser will automatically create a clickable link if the doi is set. If
you want to give the url manually use the _url_ field.

__edition__

Edition number formatted as natural text starting with a capital letter
(e.g. _First_). IEEETransactionsStyle will automatically convert the text to a
number in the citation.

__editor__

See _author_.

__institution__

Arbitrary string. If the location of the issuing institution is not part of its
name it should be appended after a comma.

__journal__

Name of the journal in abbreviated form (if available).

__month__

Abbreviated three-letter name of the month starting with a capital letter and
_without curly brackets around the field_ (see the example above).

__number__

The number of a journal or report. Should usually be an integer. In the case
of techreports a string may also be valid.

__pages__

A single number or two numbers (start and end) linked by a single or double
dash. Gives the position or range of a contribution in a larger work.

__publisher__

Name of the publisher in abbreviated form if available. Note that the location
and address of the publisher should not be part of this field, but instead be
given separately in the _address_ field.

__school__

See _institution_.

__title__

Main title of the work or contribution as arbitrary string value.

__type__

The type of the thesis. IEEETransactionsStyle will automatically insert
_Ph.D. dissertation_ or _Master's thesis_ depending on the kind of entry
if this field is left empty.

__url__

Internet address of the work or link to a location where it can be obtained
from.

__volume__

Volume number of a scientific journal as simple integer value.

__year__

Four-digit number.


Contact, License and Copyright
------------------------------

    (c) Kai Bizik <b_iz_ik@cs.uni-kl.de> (remove underscores)

    IEEETransactionsStyle is free software: you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published
    by the Free Software Foundation, either version 3 of the License,
    or (at your option) any later version.

    IEEETransactionsStyle is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with IEEETransactionsStyle. If not, see <http://www.gnu.org/licenses/>.


    The original bibtexbrowser script is (c) Martin Monperrus and also
    available under the GNU General Public License.
    See http://www.monperrus.net/martin/bibtexbrowser/#Copyright
    

[1]: http://www.monperrus.net/martin/bibtexbrowser/
[2]: https://origin.www.ieee.org/documents/ieeecitationref.pdf
[3]: http://www.library.dal.ca/Files/How_do_I/pdf/IEEE_Citation_Style_Guide.pdf
[4]: http://www.seda.cs.uni-kl.de/publications/