<?php

declare(strict_types=1);

namespace hypeJunction\Embed\Cli;

use Elgg\Cli\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Post-migration data integrity checks for the hypeembed plugin.
 *
 * Run with:
 *   php elgg-cli hypeembed:doctor
 */
class DoctorCommand extends Command {

    /** @var mixed */
    protected static $defaultName = 'hypeembed:doctor';

    /**
     * @return void
     */
    protected function configure(): void {
        $this->setDescription('Post-migration data integrity checks for hypeembed');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function command(InputInterface $input, OutputInterface $output): int {
        $exitCode = self::SUCCESS;

        // Count object/ckeditor_file entities
        $count_ckeditor_file = (int) elgg_get_entities([
            'type' => 'object',
            'subtype' => 'ckeditor_file',
            'count' => true,
        ]);
        $output->writeln("  object/ckeditor_file: {$count_ckeditor_file} entities");

        // Count object/embed_file entities
        $count_embed_file = (int) elgg_get_entities([
            'type' => 'object',
            'subtype' => 'embed_file',
            'count' => true,
        ]);
        $output->writeln("  object/embed_file: {$count_embed_file} entities");

        // Count object/embed_code entities
        $count_embed_code = (int) elgg_get_entities([
            'type' => 'object',
            'subtype' => 'embed_code',
            'count' => true,
        ]);
        $output->writeln("  object/embed_code: {$count_embed_code} entities");

        // Verify upgrades completed
        // TODO: check pending Elgg\Upgrade\Batch scripts for this plugin
        // Example: query elgg_entities for type='object' subtype='upgrade' with status != 'completed'

        // Orphan relationship check
        // TODO: check for relationships referencing non-existent entities owned by this plugin

        // Plugin-specific config invariants
        // TODO: verify expected plugin settings are set and valid

        if ($exitCode === self::SUCCESS) {
            $output->writeln('<info>hypeembed:doctor complete — no issues found</info>');
        } else {
            $output->writeln('<error>hypeembed:doctor found issues — review output above</error>');
        }

        return $exitCode;
    }
}