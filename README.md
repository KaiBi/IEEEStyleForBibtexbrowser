IEEETransactionsStyle
=====================

IEEETransactionsStyle is a custom style script for [bibtexbrowser][1],
a PHP-based generator for citations from bibtex files. It tries to
adhere to the [IEEE style guidelines][2] as close as possible.


Limitations (read: to do)
-------------------------

*   The entry types _booklet_, _conference_, _manual_, _proceedings_,
    _unpublished_ are completely ignored.
*   Electronic and online resources have not been considered at all.
*   Special name suffixes are not handled properly.
    "Lastname, Firstname Jr." will become "J. F. Lastname"
*   The conversion of edition numbers from long to abbreviated form works
    only up to 3 (e.g. "First" will become "1st", but "Twentysecond" will
    not be changed)
*   The entry types _inbook_ is a little unclear.
    Use _incollection_ instead if possible.
*   The online sources of dissertations and theses (if available) are not
    given directly in the citation. Bibtexbrowser usually appends a link
    to the entries by itself.
*   If a required field is missing in the bibtex source file it will be
    assumed as empty string and produce a weird output.


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


Required and Optional Bibtex Fields
-----------------------------------

The style depends on the presence of various fields in the bibtex entries.
The following paragraph lists the required fields that are recognized by
the style. Additional fields have no influence on the citation, but
will of course still be in the bibtex entry during export. The fields
_url_, _doi_ and _comment_ are always possible.

__@article__
_Required:_ author, title, journal, year
_Optional:_ volume, number, pages, month

__@book__
_Required:_ author, editor, title, publisher, year
_Optional:_ address, edition

__@inbook__
_Required:_ author, title, chapter, pages, publisher, year
_Optional:_ address, edition

__@incollection__
_Required:_ author, title, booktitle, year, publisher
_Optional:_ address, edition, editor, pages

__@inproceedings__
_Required:_ author, title, booktitle, year, publisher
_Optional:_ address, editor, pages

__@mastersthesis__
_Required:_ author, title, school, year
_Optional:_ type, address

__@phdthesis__
_Required:_ author, title, school, year
_Optional:_ type, address

__@techreport__
_Required:_ author, title, institution, year
_Optional:_ number, address


License and Copyright
---------------------

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