<?php namespace Brackets\AdminUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class AdminUIInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin-ui:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a brackets/admin-ui package';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $files)
    {
        $this->info('Installing package brackets/admin-ui');

        $this->call('vendor:publish', [
            '--provider' => "Brackets\\AdminUI\\AdminUIServiceProvider",
        ]);

        $this->frontendAdjustments($files);

        $this->info('Package brackets/admin-ui installed');
    }

    private function strReplaceInFile($fileName, $ifExistsRegex, $find, $replaceWith) {
        $content = File::get($fileName);
        if (preg_match($ifExistsRegex, $content)) {
            return;
        }

        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }

    private function appendIfNotExists($fileName, $ifExistsRegex, $append) {
        $content = File::get($fileName);
        if (preg_match($ifExistsRegex, $content)) {
            return;
        }

        return File::put($fileName, $content.$append);
    }

    /**
     * @param Filesystem $files
     */
    private function frontendAdjustments(Filesystem $files) {
        // webpack
        if ($this->appendIfNotExists('webpack.mix.js', '|resources/assets/admin|', "\n\n" . $files->get(__DIR__ . '/../../../install-stubs/webpack.mix.js'))) {
            $this->info('Webpack configuration updated');
        }

        //Change package.json
        $this->info('Changing package.json');
        $packageJsonFile = base_path('package.json');
        $packageJson = $files->get($packageJsonFile);
        $packageJsonContent = json_decode($packageJson, JSON_OBJECT_AS_ARRAY);
        $packageJsonContent['devDependencies']['craftable'] = '^1.0.0';
        $files->put($packageJsonFile, json_encode($packageJsonContent, JSON_PRETTY_PRINT));
        $this->info('package.json changed');
    }
}