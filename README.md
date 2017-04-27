This is an example application which makes use of [linkorb/lib-hl7-peri22x][].

    $ git clone https://github.com/boite/hl7-to-dossier.git
    $ cd hl7-to-dossier && composer install --no-dev

A console command,`convert:observation`, is provided which reads an HL7 message
from a file, converts it to a Peri22x dossier and writes the dossier XML file
to the current directory, along with any files embedded in the HL7 message.

    $ bin/h2d c:o path/to/some-file.hl7
    Write Dossier XML file "some-file-1.xml".
    Write Dossier Embedded PDF file "some-file-1-report-1.pdf".
    Write Dossier Embedded PDF file "some-file-1-report-2.pdf".

[linkorb/lib-hl7-peri22x]: <https://github.com/linkorb/lib-hl7-peri22x>
  "Convert HL7 Messages to Peri22x Dossiers."
