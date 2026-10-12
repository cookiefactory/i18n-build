<?php

    namespace Tholabs\I18nBuild\Writer;

    use Iterator;
    use Tholabs\I18nBuild\Writer\Nodes\KeyDefinitionNodeWriter;
    use Tholabs\I18nBuild\Writer\Nodes\PackageDefinitionNodeWriter;

    final class NodeWriters {

        /**
         * @return Iterator|NodeWritable[]
         */
        static function getDefault () : Iterator {
            yield new PackageDefinitionNodeWriter();
            yield new KeyDefinitionNodeWriter();
        }

    }