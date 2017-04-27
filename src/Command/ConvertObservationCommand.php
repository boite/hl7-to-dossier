<?php

namespace Boite\H2D\Command;

use SplFileObject;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Hl7v2\Encoding\Datagram;
use Hl7v2\MessageParser;
use Hl7Peri22x\Processor\ObservationProcessor;
use Hl7v2\Encoding\PositionalState;

class ConvertObservationCommand extends Command
{
    private $messageParser;
    private $observationProcessor;

    public function __construct(
        MessageParser $messageParser,
        ObservationProcessor $observationProcessor,
        $name = null
    ) {
        parent::__construct($name);

        $this->messageParser = $messageParser;
        $this->observationProcessor = $observationProcessor;
    }

    protected function configure()
    {
        $this
            ->setName('convert:observation')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Path to a file which contains an HL7 Message.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = new SplFileObject($input->getArgument('file'));

        $data = new Datagram((string) $file);
        $data->setPositionalState(new PositionalState);

        $message = $this->messageParser->parse($data);

        $i = 0;
        foreach ($message->getSegmentGroups() as $observationParts) {
            ++$i;
            $dossier = $this->observationProcessor->getDossier($observationParts);
            $filename = "{$file->getBasename('.' . $file->getExtension())}-{$i}";
            $basename = "{$filename}.xml";
            $dossier->toXmlDocument()->save($basename);
            $output->writeln("Write Dossier XML file \"{$basename}\".");
            $j = 0;
            foreach ($dossier->getEmbeddedFiles() as $embedFile) {
                ++$j;
                $embedFilename = "{$filename}-report-{$j}";
                $embedBasename = "{$embedFilename}.{$embedFile->getExtension()}";
                $embedFile->save($embedBasename);
                $uext = strtoupper($embedFile->getExtension());
                $output->writeln("Write Dossier Embedded {$uext} file \"{$embedBasename}\".");
            }
        }
    }
}
