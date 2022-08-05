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

        $this->frontendAdjustments($files);

        $this->call('vendor:publish', [
            '--provider' => "Brackets\\AdminUI\\AdminUIServiceProvider",
        ]);

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
        if (File::exists(base_path('webpack.mix.js')) && $this->appendIfNotExists('webpack.mix.js', '|resources/js/admin|', "\n\n" . $files->get(__DIR__ . '/../../../install-stubs/partial-webpack.mix.js'))) {
            $this->info('Webpack configuration updated');
        }

        //Change package.json
        $this->info('Changing package.json');
        $packageJsonFile = base_path('package.json');
        $packageJson = $files->get($packageJsonFile);
        $packageJsonContent = json_decode($packageJson, JSON_OBJECT_AS_ARRAY);

        if (!File::exists('webpack.mix.js')){
            $packageJsonContent['scripts']['craftable-dev'] = 'mix';
            $packageJsonContent['scripts']['craftable-watch'] = 'mix watch';
            $packageJsonContent['scripts']['craftable-prod'] = 'mix --production';
            $packageJsonContent['devDependencies']['laravel-mix'] = '^6.0.6';
        }

        $packageJsonContent['devDependencies']['craftable'] = '^2.1.3';
        $packageJsonContent['devDependencies']['vue-loader'] = '^15.9.8';
        $packageJsonContent['devDependencies']['sass-loader'] = '^8.0.2';
        $packageJsonContent['devDependencies']['resolve-url-loader'] = '^3.1.0';
        $packageJsonContent['devDependencies']['sass'] = '^1.32.6';

        $files->put($packageJsonFile, json_encode($packageJsonContent, JSON_PRETTY_PRINT));
        $this->info('package.json changed');
    }
}