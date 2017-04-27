<?php

namespace Boite\H2D\Command;

use Hl7v2\MessageParserBuilder;
use Hl7Peri22x\Document\DocumentFactory;
use Hl7Peri22x\Dossier\DossierFactory;
use Hl7Peri22x\Processor\ObservationProcessor;
use Mimey\MimeTypes;
use Peri22x\Resource\ResourceFactory;
use Peri22x\Section\SectionFactory;
use Peri22x\Value\ValueFactory;

class CommandPlugin
{
    public static function buildCommands()
    {
        $commands = [];

        $commands[] = new ConvertObservationCommand(
            (new MessageParserBuilder())->build(),
            new ObservationProcessor(
                new DossierFactory(new DocumentFactory(new MimeTypes)),
                new ResourceFactory,
                new SectionFactory(new ValueFactory)
            )
        );

        return $commands;
    }
}
