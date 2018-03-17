<?php
namespace Affinity4\Migrate\Console\Command;

/**
 * --------------------------------------------------
 * OutputMessageTrait
 * --------------------------------------------------
 * 
 * @author Luke Watts <luke@affinity4.ie>
 * 
 * @since 0.0.1
 */
trait OutputMessageTrait
{
    /**
     * --------------------------------------------------
     * Output Message
     * --------------------------------------------------
     * 
     * Writes output message
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     *
     * @param array $lines
     * @param object $output
     */
    private function outputMessage(array $lines, $output)
    {
        $max = 0;
        foreach ($lines as $line) {
            $count = strlen($line);

            if ($max <= $count) {
                $max = $count;
            }
        }

        $rule = '';
        for ($n = 0; $n < $max; $n++) {
            $rule .= '-';
        }

        array_splice($lines, 0, 0, $rule);
        array_splice($lines, 2, 0, $rule);

        $output->writeln($lines);
    }
}