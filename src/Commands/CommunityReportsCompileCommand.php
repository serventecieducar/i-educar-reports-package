<?php

namespace iEducar\Community\Reports\Commands;

use Illuminate\Console\Command;

class CommunityReportsCompileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'community:reports:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile reports files';

    protected function getJasperFiles(): ?string
    {
        $reportDefaultPath = 'ieducar/modules/Reports/ReportSources';
        if (false === is_dir(base_path($reportDefaultPath))) {
            return null;
        }

        return base_path($reportDefaultPath);
    }

    /**
     * Return the JasperStarter binary file.
     *
     * @return string
     */
    protected function getJasperStarter()
    {
        return base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Compiling reports files..');

        $jasperFiles = $this->getJasperFiles();

        if ($jasperFiles === null) {
            $this->info('Report Packet not install or linked');

            return;
        }

        $jasperStarter = $this->getJasperStarter();

        if (! is_file($jasperStarter)) {
            $this->error('JasperStarter não encontrado. Instale geekcom/phpjasper no i-Educar (composer).');

            return 1;
        }

        passthru(
            'cd '.escapeshellarg($jasperFiles).'; for line in $(ls -a | sort | grep .jrxml | sed -e "s/\\.jrxml//"); do '
            .escapeshellarg($jasperStarter).' cp "$line.jrxml" -o "$line" && echo "  $line"; done'
        );
    }
}
