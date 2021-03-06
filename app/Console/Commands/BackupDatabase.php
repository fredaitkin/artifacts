<?php

namespace Artifacts\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup
                            {--no-stats-flag : Drop MySQL8 column-statistics flag}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $options = $this->options();

            if (isset($options['no-stats-flag'])):
                $stats_flag = '';
            else:
                $stats_flag = '--column-statistics=0';
            endif;

            $command = sprintf(
                'mysqldump -u%s -p%s --port=%s %s ' . $stats_flag . ' > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.port'),
                config('database.connections.mysql.database'),
                storage_path('backups/artifacts.sql')
            );

            exec($command);

            $this->info('The backup has been proceed successfully.');
        } catch (Exception $e) {
            $this->error('The backup process has been failed: ' . $e->getMessage());
        }
    }
}
